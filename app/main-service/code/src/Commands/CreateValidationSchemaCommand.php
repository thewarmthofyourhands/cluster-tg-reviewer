<?php

declare(strict_types=1);

namespace App\Commands;

use App\Validation\JsonSchemaToObjectTransformer;
use Eva\Console\ArgvInput;
use InvalidArgumentException;

class CreateValidationSchemaCommand
{
    public function execute(ArgvInput $argvInput): void
    {
        if (false === isset($argvInput->getOptions()['sourcePath'])) {
            throw new InvalidArgumentException('sourcePath is required option');
        }

        $sourcePath = $argvInput->getOptions()['sourcePath'];
        $baseNamespace = $argvInput->getOptions()['baseNamespace'] ?? 'App\\Validation\\Schema';
        $baseDir = $argvInput->getOptions()['baseDir'] ?? 'src/Validation/Schema';
        $creator = new JsonSchemaToObjectTransformer($sourcePath, $baseNamespace, $baseDir);
        $creator->transform();
    }
}
