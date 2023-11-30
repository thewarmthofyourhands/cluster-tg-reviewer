<?php

declare(strict_types=1);

namespace App\Actions\Chat;

use App\Actions\AbstractAction;
use App\Actions\Projects\SelectProjectAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Dto\UseCase\Chat\AddChatDto;
use App\Enums\UseCase\MessengerTypeEnum;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Chat\AddChatHandler;

readonly class AddChatAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private AddChatHandler $addChatHandler,
        private SelectProjectAction $selectProjectAction,

    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $message = <<<EOL
        Please share chat id
        EOL;
        $keyboard = $this->createOneTimeKeyboard([
            ['text' => 'Share chat', 'request_chat' => ['request_id' => 122133333]],
        ]);
        $this->setTelegramUserActionData(
            $dto->getTelegramUser()->getTelegramId(),
            'addChat.setMessengerId',
            $data,
        );
        $this->sendMessage($dto->getTelegramUser()->getTelegramId(), $message, $keyboard);
    }

    public function setMessengerId(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $messengerId = (int) $dto->getText();
        $this->addChatHandler->handle(new AddChatDto(
            $dto->getTelegramUser()->getTelegramId(),
            $data['projectId'],
            $messengerId,
        ));
        $message = <<<EOL
        Chat successful shared
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
