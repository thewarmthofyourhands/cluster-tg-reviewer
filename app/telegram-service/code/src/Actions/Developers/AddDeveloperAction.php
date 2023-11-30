<?php

declare(strict_types=1);

namespace App\Actions\Developers;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Dto\UseCase\Developers\AddDeveloperDto;
use App\Enums\UseCase\Developers\DeveloperStatusEnum;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Developers\AddDeveloperHandler;

readonly class AddDeveloperAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private AddDeveloperHandler $addDeveloperHandler,
        private GetDeveloperListAction $getDeveloperListAction,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $message = <<<EOL
        Please type telegram nickname of developer
        For example: @eve_example_user_nickname
        EOL;
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'addDeveloper.setNickname',
            $data,
        );
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            $message,
        );
    }

    public function setNickname(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $nickname = $dto->getText();
        $nickname = str_replace('@', '', $nickname);
        $data['nickname'] = $nickname;
        $message = <<<EOL
        Please choose developer status
        EOL;
        $keyboard = $this->createOneTimeKeyboard([
            DeveloperStatusEnum::READY->value,
            DeveloperStatusEnum::STOP->value,
        ]);
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'addDeveloper.setStatus',
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
        $data['status'] = $status;
        $this->addDeveloperHandler->handle(new AddDeveloperDto(
            $dto->getTelegramUser()->getTelegramId(),
            $data['projectId'],
            $data['nickname'],
            false,
            $data['status'],
        ));
        $message = <<<EOL
        Successful added
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
