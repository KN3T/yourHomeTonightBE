<?php

namespace App\Controller\API;

use App\Entity\Booking;
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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingController extends AbstractController
{
    use JsonResponseTrait;

    private ParameterBagInterface $parameterBag;
    private ValidatorTransformer $validatorTransformer;
    private ValidatorInterface $validator;

    public function __construct(
        ParameterBagInterface $parameterBag,
        ValidatorTransformer  $validatorTransformer,
        ValidatorInterface    $validator
    ) {
        $this->parameterBag = $parameterBag;
        $this->validator = $validator;
        $this->validatorTransformer = $validatorTransformer;
    }

    /**
     * @throws ApiErrorException
     */
    #[Route('/booking', name: 'booking', methods: ['POST'])]
    public function index(
        Request              $request,
        CreateBookingRequest $createBookingRequest,
        BookingService       $bookingService,
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

    #[Route('/payment/check', name: 'check-payment', methods: ['POST'])]
    public function checkPayment(
        Request            $request,
        BookingRepository  $bookingRepository,
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
            return $this->success($this->getCheckoutInfo($result, $bookingTransformer, $booking));
        }
        $booking->setStatus(Booking::CANCELLED)->setUpdatedAt(new \DateTime('now'));
        return $this->error('Payment failed');
    }

    private function getCheckoutInfo(StripeSession $session, BookingTransformer $bookingTransformer, Booking $booking): array
    {
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
}
