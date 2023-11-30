<?php

declare(strict_types=1);

namespace App\Validation;

use App\Exceptions\Validation\ValidatorException;
use JsonException;
use JsonSchema\Validator as JsonValidator;

use stdClass;
use function Eva\Common\Functions\json_decode;
use function Eva\Common\Functions\json_encode;

class Validator
{
    private readonly JsonValidator $validator;
    private const SCHEMA_DIR = '/src/Validation/Schema/';

    public function __construct()
    {
        $this->validator = new JsonValidator();
    }

    /**
     * @throws ValidatorException
     */
    public function validateByJsonSchemaPath(array $data, string $jsonSchemaPath): void
    {
        $this->validator->validate($data, [
            '$ref' => 'file://' . realpath(static::SCHEMA_DIR . $jsonSchemaPath),
        ]);

        if (false === $this->validator->isValid()) {
            $errorList = $this->validator->getErrors();
            $this->validator->reset();
            throw new ValidatorException($errorList);
        }

        $this->validator->reset();
    }

    /**
     * @throws ValidatorException
     * @throws JsonException
     */
    public function validate(stdClass|array $data, array $validationData): void
    {
        if (is_array($data)) {
            $data = json_decode(json_encode($data), false);
        }

        $this->validator->validate($data, $validationData);

        if (false === $this->validator->isValid()) {
            $errorList = $this->validator->getErrors();
            $this->validator->reset();
            throw new ValidatorException($errorList);
        }

        $this->validator->reset();
    }
}
