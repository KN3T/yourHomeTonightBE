<?php

namespace App\EventListener;

use App\Constants\ExceptionMessageConstants;
use App\Traits\JsonResponseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Throwable;

class ExceptionListener
{
    use JsonResponseTrait;

    public const DEFAULT_ERROR_MESSAGE = 'Something wrong';
    private const DEV_ENV = 'dev';
    private string $environment;

    public function __construct(KernelInterface $kernel)
    {
        $this->environment = $kernel->getEnvironment();
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        [$statusCode, $message] = $this->getStatusCodeAndMessage($exception);
        $response = $this->error($message, $statusCode);

        $event->setResponse($response);
    }

    private function isDevEnvironment(): bool
    {
        return $this->environment === static::DEV_ENV;
    }

    private function getStatusCodeAndMessage(Throwable $exception): array
    {
        $exceptionClass = get_class($exception);
        $message = '';
        switch ($exceptionClass) {
            case HttpExceptionInterface::class:
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                break;
            case AccessDeniedHttpException::class:
                $message = ExceptionMessageConstants::ACCESS_DENIED;
                $statusCode = Response::HTTP_FORBIDDEN;
                break;
            case NotFoundHttpException::class:
                $statusCode = Response::HTTP_NOT_FOUND;
                break;
            case ValidatorException::class:
                $statusCode = Response::HTTP_BAD_REQUEST;
                $message = ExceptionMessageConstants::BAD_REQUEST;
                break;
            case UnauthorizedHttpException::class:
                $message = ExceptionMessageConstants::UNAUTHORIZED;
                $statusCode = Response::HTTP_UNAUTHORIZED;
                break;
            default:
                $statusCode = Response::HTTP_BAD_REQUEST;
        }
        if (empty($message)) {
            $message = $this->isDevEnvironment() ? $exception->getMessage() : static::DEFAULT_ERROR_MESSAGE;
        }

        return [$statusCode, $message];
    }
}
