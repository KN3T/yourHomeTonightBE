<?php

namespace App\Controller\API;

use App\Request\CreateHotelRequest;
use App\Service\HotelService;
use App\Traits\JsonResponseTrait;
use App\Transformer\HotelTransformer;
use App\Transformer\ValidatorTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/hotels', name: 'hotel_')]
class HotelController extends AbstractController
{
    use JsonResponseTrait;

    private ValidatorTransformer $validatorTransformer;
    private ValidatorInterface $validator;

    public function __construct(ValidatorTransformer $validatorTransformer, ValidatorInterface $validator)
    {
        $this->validatorTransformer = $validatorTransformer;
        $this->validator = $validator;
    }

    #[Route('/', name: 'list')]
    public function index()
    {
    }

    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        CreateHotelRequest $createHotelRequest,
        Request            $request,
        HotelService       $hotelService,
        HotelTransformer   $hotelTransformer,
        Security           $security,
    ) {
        $array = json_decode($request->getContent(), true);
        $createHotelRequest->fromArray($array);
        $errors = $this->validator->validate($createHotelRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors), Response::HTTP_BAD_REQUEST);
        }
        $hotel = $hotelService->create($createHotelRequest, $security);
        $result = $hotelTransformer->toArray($hotel);
    }
}
