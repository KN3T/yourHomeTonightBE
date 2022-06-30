<?php

namespace App\Controller\API;

use App\Request\File\UploadImageRequest;
use App\Service\ImageService;
use App\Traits\JsonResponseTrait;
use App\Transformer\ImageTransformer;
use App\Transformer\ValidatorTransformer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ImageController extends AbstractController
{
    use JsonResponseTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/images', name: 'upload', methods: 'POST')]
    public function upload(
        Request              $request,
        ImageService         $imageService,
        ImageTransformer     $imageTransformer,
        UploadImageRequest   $uploadImageRequest,
        ValidatorInterface   $validator,
        ValidatorTransformer $validatorTransformer,
    ): JsonResponse
    {
        $files = $request->files->get('images');
        foreach ($files as $file) {
            $uploadImageRequest->addImage($file);
        }
        $errors = $validator->validate($uploadImageRequest);
        if (count($errors) > 0) {
            return $this->error($validatorTransformer->toArray($errors), Response::HTTP_BAD_REQUEST);
        }
        $result= $imageService->upload($uploadImageRequest);
        return $this->success($imageTransformer->listToArray($result), Response::HTTP_CREATED);
    }

}
