<?php

declare(strict_types=1);

namespace App\Actions\Chat;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Chat\GetChatHandler;

readonly class GetChatAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private GetChatHandler $getChatHandler,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $chatDto = $this->getChatHandler->handle(
            $data['projectId'],
            $dto->getTelegramUser()->getTelegramId(),
        );

        if (null === $chatDto) {
            $message = <<<EOL
            No chat, please add new
            EOL;
            $inlineKeyboard = $this->createInlineKeyboard([
                ['Add', new TelegramActionDataDto('addChat', $data)],
            ]);
        } else {
            $message = <<<EOL
            Telegram group chat id: {$chatDto->getMessengerId()}
            Status: {$chatDto->getStatus()->value}
            EOL;
            $inlineKeyboard = $this->createInlineKeyboard([
                ['Delete', new TelegramActionDataDto('deleteChat', $data)],
            ]);
        }

        $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            $message,
            null,
            $inlineKeyboard,
        );
    }
}
