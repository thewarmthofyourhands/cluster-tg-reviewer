<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Dto\Services\RequestServices\TelegramRequestService\InlineKeyboardDto;
use App\Dto\Services\RequestServices\TelegramRequestService\KeyboardDto;
use App\Dto\Services\RequestServices\TelegramRequestService\SendMessageDto;
use App\Infrastructure\TelegramUserActionDataTransformer;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;

readonly abstract class AbstractAction
{
    public function __construct(
        protected TelegramBotService $telegramBotService,
        protected TelegramUserService $telegramUserService,
    ) {}

    protected function sendMessage(
        int $telegramUserId,
        string $text,
        null|array $keyboard = null,
        null|array $inlineKeyboard = null,
    ): void {
        $this->telegramBotService->sendMessage(new SendMessageDto(
            $telegramUserId,
            $text,
            $keyboard,
            $inlineKeyboard,
        ));
    }

    protected function createOneTimeKeyboard(array $buttonList): array
    {
        $buttonList = array_map(static function(string|array $button) {
            if (is_array($button)) {
                return $button;
            }

            return ['text' => $button];
        }, $buttonList);
        return (new KeyboardDto(
            $buttonList,
            true,
            false,
            false,
        ))->toArray();
    }

    protected function createInlineKeyboard(array $buttonList): array
    {
        $inlineKeyboard = [];
        foreach ($buttonList as $button) {
            [$buttonName, $telegramActionData] = $button;
            assert(is_string($buttonName));
            assert($telegramActionData instanceof TelegramActionDataDto);
            $inlineKeyboard[] = (new InlineKeyboardDto(
                $buttonName,
                TelegramUserActionDataTransformer::createJsonActionDataByDto($telegramActionData),
            ))->toArray();
        }

        return $inlineKeyboard;
    }

    protected function resetTelegramUserActionData(int $telegramUserId): void
    {
        $this->setTelegramUserActionData($telegramUserId, 'default');
    }

    protected function setTelegramUserActionData(int $telegramUserId, string $action, null|array $data = null): void
    {
        $this->telegramUserService->editData(
            $telegramUserId,
            TelegramUserActionDataTransformer::createJsonActionDataByDto(new TelegramActionDataDto(
                $action,
                $data,
            ))
        );
    }
}
