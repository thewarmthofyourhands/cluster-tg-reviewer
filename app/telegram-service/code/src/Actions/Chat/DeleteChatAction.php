<?php

declare(strict_types=1);

namespace App\Actions\Chat;

use App\Actions\AbstractAction;
use App\Actions\Projects\SelectProjectAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Chat\DeleteChatHandler;

readonly class DeleteChatAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private DeleteChatHandler $deleteChatHandler,
        private SelectProjectAction $selectProjectAction,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $this->deleteChatHandler->handle(
            $data['projectId'],
            $dto->getTelegramUser()->getTelegramId(),
        );
        $selectProjectAction = $this->selectProjectAction;
        $selectProjectAction->select(new InputMessageTelegramDto(
            $dto->getChatId(),
            $dto->getChatType(),
            $dto->getTelegramUser(),
            '',
            new TelegramActionDataDto('selectProject.select', ['projectId' => $data['projectId']]),
        ));
    }
}
