<?php

namespace App\Controller\API;

use App\Traits\JsonResponseTrait;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    use JsonResponseTrait;

    /**
     * @throws ApiErrorException
     */
    #[Route('/payment', name: 'payment', methods: ['POST'])]
    public function index(ParameterBagInterface $parameterBag): JsonResponse
    {
        Stripe::setApiKey($parameterBag->get('stripeKey'));
        $session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ]);
        return $this->success([$session->url]);
    }
}
