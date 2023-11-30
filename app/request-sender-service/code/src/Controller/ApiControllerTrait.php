<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\AuthenticateException;
use App\Exceptions\Rest\IncorrectHttpMessageContentTypeException;
use App\Exceptions\Validation\ValidatorException;
use App\Validation\Schema\Rest\HttpContentTypeHeaderSchema;
use App\Validation\Schema\Rest\HttpAuthorizationHeaderSchema;
use App\Validation\Validator;
use Eva\Http\Message\RequestInterface;
use JsonException;

trait ApiControllerTrait
{
    /**
     * @throws IncorrectHttpMessageContentTypeException
     * @throws JsonException
     */
    protected function validateHttpContentTypeHeader(Validator $validator, RequestInterface $request): void
    {
        try {
            $validator->validate($request->getHeaders(), HttpContentTypeHeaderSchema::SCHEMA);
        } catch (ValidatorException $validatorException) {
            throw new IncorrectHttpMessageContentTypeException();
        }
    }

    /**
     * @throws AuthenticateException
     * @throws JsonException
     */
    protected function validateHttpAuthorizationHeader(Validator $validator, RequestInterface $request): void
    {
        try {
            $validator->validate($request->getHeaders(), HttpAuthorizationHeaderSchema::SCHEMA);
        } catch (ValidatorException $validatorException) {
            throw new AuthenticateException(ApplicationErrorCodeEnum::REQUIRED_AUTHORIZATION_HEADER);
        }
    }

    /**
     * @throws JsonException
     * @throws IncorrectHttpMessageContentTypeException
     */
    protected function validateHttpMessageFormat(Validator $validator, RequestInterface $request): void
    {
        $this->validateHttpContentTypeHeader($validator, $request);
    }

    /**
     * @throws AuthenticateException
     * @throws JsonException
     * @throws IncorrectHttpMessageContentTypeException
     */
    protected function validateAuthHttpMessageFormat(Validator $validator, RequestInterface $request): void
    {
        $this->validateHttpContentTypeHeader($validator, $request);
        $this->validateHttpAuthorizationHeader($validator, $request);
    }
}
