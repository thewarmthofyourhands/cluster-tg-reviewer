<?php

declare(strict_types=1);

namespace App\Actions\Developers;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Developers\DeleteDeveloperByNameHandler;
use App\UseCase\Developers\GetDeveloperNameListForDeletingHandler;

readonly class DeleteDeveloperAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private DeleteDeveloperByNameHandler $deleteDeveloperByNameHandler,
        private GetDeveloperNameListForDeletingHandler $getDeveloperNameListForDeletingHandler,
        private GetDeveloperListAction $getDeveloperListAction,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $message = <<<EOL
        Please choose developer to delete
        EOL;
        $developerNicknameList = $this->getDeveloperNameListForDeletingHandler->handle(
            $data['projectId'],
            $dto->getTelegramUser()->getTelegramId(),
        );
        $keyboard = $this->createOneTimeKeyboard($developerNicknameList);
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'deleteDeveloper.setNickname',
            $data,
        );
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            $message,
            $keyboard,
        );
    }

    public function setNickname(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $developerNickname = $dto->getText();
        $data['developerNickname'] = $developerNickname;
        $message = <<<EOL
        Do you really would like to delete {$developerNickname}?
        EOL;
        $keyboard = $this->createOneTimeKeyboard(['Yes', 'No']);
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'deleteDeveloper.confirmDeletion',
            $data,
        );
        $this->sendMessage($dto->getTelegramUser()->getTelegramId(), $message, $keyboard);
    }

    public function confirmDeletion(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $confirm = $dto->getText();

        if ('No' === $confirm) {
            $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
            $message = <<<EOL
            Deletion canceled
            EOL;
            $this->sendMessage(
                $dto->getTelegramUser()->getTelegramId(),
                $message,
            );
            $getDeveloperListAction = $this->getDeveloperListAction;
            $getDeveloperListAction(new InputMessageTelegramDto(
                $dto->getChatId(),
                $dto->getChatType(),
                $dto->getTelegramUser(),
                '',
                new TelegramActionDataDto('getDevelopers', ['projectId' => $data['projectId']]),
            ));
            return;
        }

        $developerNickname = $data['developerNickname'];
        $this->deleteDeveloperByNameHandler->handle(
            $data['projectId'],
            $developerNickname,
            $dto->getTelegramUser()->getTelegramId(),
        );
        $message = <<<EOL
        Successful deleted
        EOL;
        $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            $message,
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
