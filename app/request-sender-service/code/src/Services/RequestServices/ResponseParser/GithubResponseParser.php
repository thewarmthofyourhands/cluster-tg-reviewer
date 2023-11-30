<?php

declare(strict_types=1);

namespace App\Services\RequestServices\ResponseParser;

use Eva\Http\Parser\ResponseParserInterface;
use JsonException;
use Eva\Http\Message\ResponseInterface;

use function Eva\Common\Functions\json_decode;

class GithubResponseParser implements ResponseParserInterface
{
    /**
     * @throws JsonException
     */
    public static function parseBody(ResponseInterface $response): null|array
    {
        $body = $response->getBody();

        return $body === '' ? null : json_decode($body, true);
    }

    public static function parseLinkHeader(ResponseInterface $response): null|array
    {
        if (false === array_key_exists('Link', $response->getHeaders())) {
            return null;
        }

        $linkHeaderValue = $response->getHeaders()['Link'];
        $linkValueList = explode(', ', $linkHeaderValue);
        $linkValueList = str_replace(['<', '>', 'rel="', '"'], '', $linkValueList);
        $rawLinkData = array_map(static fn(string $el) => explode('; ', $el), $linkValueList);
        $linkData = [];

        foreach ($rawLinkData as $linkElement) {
            [$url, $rel] = $linkElement;
            $rel = trim($rel);
            $url = trim($url);
            $relList = ['last', 'prev', 'first', 'next'];

            if (in_array($rel, $relList, true)) {
                $linkData[$rel] = $url;
            }
        }

        return $linkData;
    }
}
