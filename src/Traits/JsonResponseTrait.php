<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponseTrait
{
    public function success(array $data, int $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        $dataResponse = [
            'status' => 'success',
            'data' => $data,
        ];

        return new JsonResponse($dataResponse, $status, $headers);
    }

    public function error(string|array $message, int $status = Response::HTTP_BAD_REQUEST, array $headers = []): JsonResponse
    {
        $dataResponse = [
            'status' => 'error',
            'message' => $message,
        ];

        return new JsonResponse($dataResponse, $status, $headers);
    }
}
