<?php

namespace App\Controller\API;

use App\Entity\Booking;
use App\Entity\User;
use App\Event\PurchaseBookingEvent;
use App\Repository\BookingRepository;
use App\Request\Booking\CreateBookingRequest;
use App\Service\BookingService;
use App\Service\StripePaymentService;
use App\Traits\JsonResponseTrait;
use App\Transformer\BookingTransformer;
use App\Transformer\ValidatorTransformer;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingController extends AbstractController
{
    use JsonResponseTrait;

    private ParameterBagInterface $parameterBag;
    private ValidatorTransformer $validatorTransformer;
    private ValidatorInterface $validator;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        ParameterBagInterface $parameterBag,
        ValidatorTransformer $validatorTransformer,
        ValidatorInterface $validator,
        EventDispatcherInterface $dispatcher
    ) {
        $this->parameterBag = $parameterBag;
        $this->validator = $validator;
        $this->validatorTransformer = $validatorTransformer;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws ApiErrorException
     */
    #[Route('/bookings', name: 'booking', methods: ['POST'])]
    public function createBooking(
        Request $request,
        CreateBookingRequest $createBookingRequest,
        BookingService $bookingService,
        StripePaymentService $stripePaymentService
    ): JsonResponse {
        $request = json_decode($request->getContent(), true);
        $bookingRequest = $createBookingRequest->fromArray($request);
        $errors = $this->validator->validate($bookingRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors));
        }

        $booking = $bookingService->createBooking($createBookingRequest);
        $paymentUrl = $stripePaymentService->makePayment($booking);

        return $this->success([$paymentUrl]);
    }

    #[Route('/bookings/{id}/repay', name: 'repay_booking', methods: ['POST'])]
    public function repay(
        Booking $booking,
        StripePaymentService $stripePaymentService,
    ): JsonResponse {
        if (Booking::CANCELLED == $booking->getStatus()) {
            return $this->error('Booking has been cancelled');
        }
        $paymentUrl = $stripePaymentService->makePayment($booking);

        return $this->success([$paymentUrl]);
    }

    #[Route('/bookings/{id}', name: 'booking_detail', methods: ['GET'])]
    public function detail(
        Booking $booking,
        BookingService $bookingService,
        BookingTransformer $bookingTransformer,
    ): JsonResponse {
        $booking = $bookingTransformer->toArray($booking);

        return $this->success($booking);
    }

    #[Route('/payment/check', name: 'check-payment', methods: ['POST'])]
    public function checkPayment(
        Request $request,
        BookingRepository $bookingRepository,
        BookingTransformer $bookingTransformer
    ): JsonResponse {
        $request = json_decode($request->getContent(), true);
        $sessionPayment = $request['session'];
        $bookingId = $request['bookingId'];
        $booking = $bookingRepository->find($bookingId);
        $stripe = new StripeClient($this->parameterBag->get('stripeKey'));
        $result = $stripe->checkout->sessions->retrieve($sessionPayment);
        if ('paid' === $result->payment_status) {
            $booking->setStatus(Booking::SUCCESS)
                ->setPurchasedAt(new \DateTime('now'))
                ->setUpdatedAt(new \DateTime('now'));
            $bookingRepository->save($booking);

            $event = new PurchaseBookingEvent($booking);
            $this->dispatcher->dispatch($event, PurchaseBookingEvent::SENDMAIL);

            return $this->success($this->getCheckoutInfo($result, $bookingTransformer, $booking));
        }

        return $this->error('Payment failed');
    }

    #[Route('/bookings/{id}/done', name: 'booking_done', methods: ['POST'])]
    public function done(
        Booking $booking,
        BookingService $bookingService,
        BookingTransformer $bookingTransformer
    ): JsonResponse {
        if (!$this->checkUserPrivilege($booking)) {
            return $this->error('You are not allowed to do this');
        }
        $bookingService->setBookingDone($booking);
        $booking = $bookingTransformer->toArray($booking);

        return $this->success($booking);
    }

    private function getCheckoutInfo(
        StripeSession $session,
        BookingTransformer $bookingTransformer,
        Booking $booking
    ): array {
        $paymentInfo = $session->customer_details->toArray();
        $bookingResult = $bookingTransformer->toArray($booking);

        return [
            'paymentInfo' => [
                'billingName' => $paymentInfo['name'],
                'purchasedAt' => $booking->getPurchasedAt(),
            ],
            'booking' => $bookingResult,
        ];
    }

    private function checkUserPrivilege(Booking $booking): bool
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if ($user === $booking->getRoom()->getHotel()->getUser() || $user->isAdmin()) {
            return true;
        }
        return false;
    }
}
