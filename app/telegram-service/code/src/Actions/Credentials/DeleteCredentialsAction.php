<?php

declare(strict_types=1);

namespace App\Actions\Credentials;

use App\Actions\AbstractAction;
use App\Actions\Projects\SelectProjectAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Credentials\DeleteCredentialsHandler;

readonly class DeleteCredentialsAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private DeleteCredentialsHandler $deleteCredentialsHandler,
        private SelectProjectAction $selectProjectAction,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $this->deleteCredentialsHandler->handle(
            $data['projectId'],
            $dto->getTelegramUser()->getTelegramId(),
        );
        $message = <<<EOL
        Successful deleted
        EOL;
        $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
        $this->sendMessage($dto->getTelegramUser()->getTelegramId(), $message);
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
