<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Dto\Infrastructure\TelegramActionDataDto;

use function Eva\Common\Functions\json_decode;
use function Eva\Common\Functions\json_encode;

readonly class TelegramUserActionDataTransformer
{
    private static function matchFromShortyActionNaming(string $action): string
    {
        return match ($action) {
            'aP' => 'addProject',
            'dP' => 'deleteProject',
            'sP' => 'selectProject',
            'gC' => 'getCredentials',
            'aC' => 'addCredentials',
            'dC' => 'deleteCredentials',
            'gCh' => 'getChat',
            'aCh' => 'addChat',
            'dCh' => 'deleteChat',
            'gD' => 'getDevelopers',
            'aD' => 'addDeveloper',
            'dD' => 'deleteDeveloper',
            'eDS' => 'editDeveloperStatus',
            'chDS' => 'changeDeveloperStatus',
            default => $action,
        };
    }

    private static function matchToShortyActionNaming(string $action): string
    {
        return match ($action) {
            'addProject' => 'aP',
            'deleteProject' => 'dP',
            'selectProject' => 'sP',
            'getCredentials' => 'gC',
            'addCredentials' => 'aC',
            'deleteCredentials' => 'dC',
            'getChat' => 'gCh',
            'addChat' => 'aCh',
            'deleteChat' => 'dCh',
            'getDevelopers' => 'gD',
            'addDeveloper' => 'aD',
            'deleteDeveloper' => 'dD',
            'editDeveloperStatus' => 'eDS',
            'changeDeveloperStatus' => 'chDS',
            default => $action,
        };
    }

    public static function createJsonActionDataByDto(TelegramActionDataDto $dto): string
    {
        $data = $dto->toArray();
        $data['a'] = self::matchToShortyActionNaming($data['action']);
        $data['d'] = $data['data'];
        unset($data['action'], $data['data']);

        return json_encode($data);
    }

    public static function createActionDataByJson(string $json): TelegramActionDataDto
    {
        $data = json_decode($json, true);
        if (false === array_key_exists('action', $data)) {
            $data['action'] = $data['a'];
            unset($data['a']);
        }
        if (false === array_key_exists('data', $data)) {
            $data['data'] = $data['d'];
            unset($data['d']);
        }
        $data['action'] = self::matchFromShortyActionNaming($data['action']);

        return new TelegramActionDataDto(...$data);
    }
}
