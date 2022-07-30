<?php

namespace App\Controller\API;

use App\Repository\AddressRepository;
use App\Request\City\ListCityRequest;
use App\Traits\JsonResponseTrait;
use App\Transformer\ValidatorTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/city', name: 'city_')]
class CityController extends AbstractController
{
    use JsonResponseTrait;

    private ValidatorTransformer $validatorTransformer;
    private ValidatorInterface $validator;

    public function __construct(ValidatorTransformer $validatorTransformer, ValidatorInterface $validator)
    {
        $this->validatorTransformer = $validatorTransformer;
        $this->validator = $validator;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(
        Request $request,
        ListCityRequest $listCityRequest,
        AddressRepository $addressRepository
    ): JsonResponse {
        $filters = $request->query->all();
        $cityRequest = $listCityRequest->fromArray($filters);
        $errors = $this->validator->validate($cityRequest);
        if (count($errors) > 0) {
            $errorsTransformer = $this->validatorTransformer->toArray($errors);

            return $this->error($errorsTransformer);
        }
        $cityList = $addressRepository->list($cityRequest);

        return $this->success($cityList);
    }
}
