<?php

declare(strict_types=1);

namespace App\Services\RequestServices\ResponseParser;

use Eva\Http\Parser\ResponseParserInterface;
use JsonException;
use Eva\Http\Message\ResponseInterface;

use function Eva\Common\Functions\json_decode;

class TelegramResponseParser implements ResponseParserInterface
{
    /**
     * @throws JsonException
     */
    public static function parseBody(ResponseInterface $response): array
    {
        $body = $response->getBody();
        $encodedBody = json_decode($body, true);

        return $encodedBody['data'];
    }

    /**
     * @throws JsonException
     */
    public static function parseApplicationCode(ResponseInterface $response): int
    {
        $body = $response->getBody();
        $encodedBody = json_decode($body, true);

        return $encodedBody['code'];
    }
}
