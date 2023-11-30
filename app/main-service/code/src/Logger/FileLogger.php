<?php

declare(strict_types=1);

namespace App\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

readonly class FileLogger implements LoggerInterface
{
    use LoggerTrait;

    protected function format(string $level, string $message, array $context = []): string
    {
        return <<<EOL
        [php {$level}] {$message}
        {$this->formatContext($context)}
        
        EOL;
    }

    protected function formatContext(array $context): string
    {
        try {
            return \json_encode($context, \JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return \print_r($context, true);
        }
    }

    protected function write(string $message): void
    {
        \file_put_contents(getcwd() . '/var/log/error.log', $message, FILE_APPEND|LOCK_EX);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->write($this->format((string) $level, $message, $context));
    }
}
