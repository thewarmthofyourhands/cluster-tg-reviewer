<?php

declare(strict_types=1);

namespace App\EventListeners;

use App\Exceptions\Application\ApplicationException;
use App\Exceptions\Application\AuthenticateException;
use App\Exceptions\Rest\IncorrectHttpMessageContentTypeException;
use App\Exceptions\Validation\ValidatorException;
use App\Rest\RestApiResponse;
use App\Rest\RestApiResponseMessageBuilder;
use Eva\HttpKernel\Events\ExceptionEvent;
use Psr\Log\LoggerInterface;

readonly class HttpExceptionListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();

        if ($throwable instanceof ValidatorException) {
            $exceptionEvent->setResponse((new RestApiResponseMessageBuilder())
                ->addBody((new RestApiResponse(null, $throwable->getCode(), $throwable->getMessage()))->toArray())
                ->setStatusCode(422)
                ->build());
        } else if ($throwable instanceof AuthenticateException) {
            $exceptionEvent->setResponse((new RestApiResponseMessageBuilder())
                ->addBody((new RestApiResponse(null, $throwable->getCode(), $throwable->getMessage()))->toArray())
                ->setStatusCode(401)
                ->build());
        } else if ($throwable instanceof ApplicationException) {
            $exceptionEvent->setResponse((new RestApiResponseMessageBuilder())
                ->addBody((new RestApiResponse(null, $throwable->getCode(), $throwable->getMessage()))->toArray())
                ->setStatusCode(400)
                ->build());
        } else if ($throwable instanceof IncorrectHttpMessageContentTypeException) {
            $exceptionEvent->setResponse((new RestApiResponseMessageBuilder())
                ->addBody((new RestApiResponse(null, 406, $throwable->getMessage()))->toArray())
                ->setStatusCode(406)
                ->build());
        } else {
            $exceptionEvent->setResponse((new RestApiResponseMessageBuilder())
                ->addBody((new RestApiResponse(null, 500, 'Something went wrong'))->toArray())
                ->setStatusCode(500)
                ->build());
        }

        $message = sprintf(
            '[%s] error code: %s, error class: %s, message: %s, file: %s, line: %s, trace: %s',
            date('Y-m-d H:i:s'),
            $throwable->getCode(),
            get_class($throwable),
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            $throwable->getTraceAsString(),
        );
        $this->logger->error($message, [
            'requestBody' => $exceptionEvent->getRequest()->getBody(),
        ]);
    }
}
