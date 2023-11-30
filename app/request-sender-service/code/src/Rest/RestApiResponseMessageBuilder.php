<?php

declare(strict_types=1);

namespace App\Rest;

use Eva\Http\HttpProtocolVersionEnum;
use JsonException;
use Eva\Http\Builder\JsonResponseMessageBuilder;

use function Eva\Common\Functions\json_encode;

class RestApiResponseMessageBuilder extends JsonResponseMessageBuilder
{
    protected array $headers = [
        'Content-Type' => 'application/json',
    ];
}
