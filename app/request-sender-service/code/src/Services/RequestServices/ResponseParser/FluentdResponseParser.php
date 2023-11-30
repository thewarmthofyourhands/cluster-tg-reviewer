<?php

declare(strict_types=1);

namespace App\Services\RequestServices\ResponseParser;

use Eva\Http\Parser\ResponseParserInterface;
use JsonException;
use Eva\Http\Message\ResponseInterface;

use function Eva\Common\Functions\json_decode;

class FluentdResponseParser implements ResponseParserInterface
{
    /**
     * @throws JsonException
     */
    public static function parseBody(ResponseInterface $response): null|array
    {
        $body = $response->getBody();

        return $body === '' || null === $body ? null : json_decode($body, true);
    }
}
