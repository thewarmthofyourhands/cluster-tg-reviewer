<?php

declare(strict_types=1);

namespace App\UseCase\Telegram;

use App\Dto\Services\RequestServices\Telegram\ReplyMarkupDto;
use App\Dto\UseCase\Telegram\SendMessageDto;
use App\Services\RequestServices\TelegramRequestService;

readonly class SendMessageHandler
{
    public function __construct(
        private TelegramRequestService $telegramRequestService
    ) {}

    private function buildReplyMarkupKeyboardButtonList(array $buttonList): array
    {
        return array_chunk($buttonList, 3);
    }

    private function buildReplyMarkupInlineKeyboardButtonList(array $buttonList): array
    {
        $buttonList = array_map(static fn(array $button) => [
            'text' => $button['text'],
            'callback_data' => $button['callbackData'],
        ], $buttonList);
        return array_chunk($buttonList, 3);
    }

    private function buildReplyMarkupDtoFromSendMessageDto(SendMessageDto $dto): ReplyMarkupDto
    {
        $oneTimeKeyboard = false;
        $removeKeyboard = true;
        $isPersistent = false;
        $keyboard = [];
        $inlineKeyboard = [];
        $keyboardData = $dto->getKeyboard();
        $inlineKeyboardData = $dto->getInlineKeyboard();

        if (null !== $keyboardData) {
            $keyboard = $this->buildReplyMarkupKeyboardButtonList($keyboardData['buttonList']);
            $isPersistent = $keyboardData['isPersistent'] ?? false;
            $oneTimeKeyboard = $keyboardData['oneTimeKeyboard'] ?? true;
            $removeKeyboard = $keyboardData['removeKeyboard'] ?? false;
        }

        if (null !== $inlineKeyboardData) {
            $inlineKeyboard = $this->buildReplyMarkupInlineKeyboardButtonList($inlineKeyboardData);
        }

        return new ReplyMarkupDto(
            $inlineKeyboard,
            $keyboard,
            $oneTimeKeyboard,
            $isPersistent,
            $removeKeyboard,
        );
    }

    public function handle(SendMessageDto $dto): void
    {
        $replyMarkUp = $this->buildReplyMarkupDtoFromSendMessageDto($dto);
        $this->telegramRequestService->sendMessage(
            $dto->getToken(),
            new \App\Dto\Services\RequestServices\Telegram\SendMessageDto(
                $dto->getChatId(),
                $dto->getText(),
                $replyMarkUp->toArray(),
            ),
        );
    }
}
