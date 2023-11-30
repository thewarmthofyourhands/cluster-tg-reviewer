<?php

declare(strict_types=1);

namespace App\Actions\Credentials;

use App\Actions\AbstractAction;
use App\Actions\Projects\SelectProjectAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Dto\UseCase\Credentials\AddCredentialDto;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Credentials\AddCredentialsHandler;

readonly class AddCredentialsAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private AddCredentialsHandler $addCredentialsHandler,
        private SelectProjectAction $selectProjectAction,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $message = <<<EOL
        Please type your api token with permissions for pull requests
        EOL;
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'addCredentials.setToken',
            $data,
        );
        $this->sendMessage($dto->getTelegramUser()->getTelegramId(), $message);
    }

    public function setToken(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $token = $dto->getText();
        $data['token'] = $token;
        $message = <<<EOL
        Please type date when token will expired.
        Format: yyyy-mm-dd
        EOL;
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'addCredentials.setDateExpired',
            $data,
        );
        $this->sendMessage($dto->getTelegramUser()->getTelegramId(), $message);
    }

    public function setDateExpired(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $dateExpired = $dto->getText();

        if (1 !== preg_match('/^20[0-9][0-9]-[0-1][0-9]-[0-3][0-9]$/', $dateExpired)) {
            throw new ApplicationException(ApplicationErrorCodeEnum::INVALID_CREDENTIAL_DATE_EXPIRED);
        }

        $data['dateExpired'] = $dateExpired;
        $this->addCredentialsHandler->handle(new AddCredentialDto(
            $dto->getTelegramUser()->getTelegramId(),
            $data['projectId'],
            $data['token'],
            $data['dateExpired'],
        ));
        $message = <<<EOL
        Successful added
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
