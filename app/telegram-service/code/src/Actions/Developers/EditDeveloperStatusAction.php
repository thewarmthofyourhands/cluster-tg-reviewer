<?php

declare(strict_types=1);

namespace App\Actions\Developers;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Dto\UseCase\Developers\ChangeDeveloperStatusDto;
use App\Enums\UseCase\Developers\DeveloperStatusEnum;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Developers\ChangeDeveloperStatusHandler;
use App\UseCase\Developers\GetDeveloperByNameHandler;
use App\UseCase\Developers\GetDeveloperNameListHandler;

readonly class EditDeveloperStatusAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private ChangeDeveloperStatusHandler $changeDeveloperStatusHandler,
        private GetDeveloperNameListHandler $getDeveloperNameListHandler,
        private GetDeveloperByNameHandler $getDeveloperByNameHandler,
        private GetDeveloperListAction $getDeveloperListAction,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $developerNameList = $this->getDeveloperNameListHandler->handle(
            $data['projectId'],
            $dto->getTelegramUser()->getTelegramId(),
        );
        $message = <<<EOL
        Please choose developer
        EOL;
        $keyboard = $this->createOneTimeKeyboard($developerNameList);
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'editDeveloperStatus.setDeveloperNickname',
            $data,
        );
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            $message,
            $keyboard,
        );
    }

    public function setDeveloperNickname(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $developerNickname = $dto->getText();
        $developerDto = $this->getDeveloperByNameHandler->handle(
            $data['projectId'],
            $developerNickname,
            $dto->getTelegramUser()->getTelegramId(),
        );
        $data['id'] = $developerDto->getId();
        $message = <<<EOL
        Please choose status for developer
        EOL;
        $keyboard = $this->createOneTimeKeyboard([
            DeveloperStatusEnum::READY->value,
            DeveloperStatusEnum::STOP->value,
        ]);
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'editDeveloperStatus.setStatus',
            $data,
        );
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            $message,
            $keyboard,
        );
    }

    public function setStatus(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $status = $dto->getText();
        $status = DeveloperStatusEnum::from($status);
        $this->changeDeveloperStatusHandler->handle(new ChangeDeveloperStatusDto(
            $dto->getTelegramUser()->getTelegramId(),
            $data['projectId'],
            $data['id'],
            $status,
        ));
        $message = <<<EOL
        Successful changed
        EOL;
        $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            $message
        );
        $getDeveloperListAction = $this->getDeveloperListAction;
        $getDeveloperListAction(new InputMessageTelegramDto(
            $dto->getChatId(),
            $dto->getChatType(),
            $dto->getTelegramUser(),
            '',
            new TelegramActionDataDto('getDevelopers', ['projectId' => $data['projectId']]),
        ));
    }
}
