<?php

namespace App\Service;

use App\Entity\Booking;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StripePaymentService
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function makePayment(Booking $booking): ?string
    {
        Stripe::setApiKey($this->parameterBag->get('stripeKey'));
        $session = Session::create($this->getPaymentInfo($booking));

        return $session->url;
    }

    private function getPaymentInfo(Booking $booking): array
    {
        return [
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Room '.$booking->getRoom()->getNumber(),
                        ],
                        'unit_amount' => $booking->getTotal() * 100,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->parameterBag->get('appUrl').'/checkoutVerify/bookId='.$booking->getId().'&?sessionId={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->parameterBag->get('appUrl').'/checkoutVerify/bookId='.$booking->getId().'&?sessionId={CHECKOUT_SESSION_ID}',
        ];
    }
}
