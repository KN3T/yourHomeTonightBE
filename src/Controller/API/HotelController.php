<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hotels', name: 'hotel_')]
class HotelController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(): Response
    {
        //
    }
}
