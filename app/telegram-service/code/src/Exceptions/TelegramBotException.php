<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use function Eva\Common\Functions\json_encode;

class TelegramBotException extends \Exception
{
    private readonly string $tgUpdate;

    public function __construct(array $tgUpdate, string $message = "", string|int $code = 0, ?Throwable $previous = null)
    {
        $this->tgUpdate = json_encode($tgUpdate);
        if (is_string($code)) {
            $message .= ' [code] = '. $code;
            $code = 0;
        }
        parent::__construct($message, $code, $previous);
    }

    public function getTgUpdate(): string
    {
        return $this->tgUpdate;
    }
}
