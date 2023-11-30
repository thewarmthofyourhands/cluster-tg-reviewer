<?php

declare(strict_types=1);

namespace App\Actions\Credentials;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Credentials\GetCredentialsHandler;

readonly class GetCredentialsAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private GetCredentialsHandler $getCredentialsHandler,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $credentialsDto = $this->getCredentialsHandler->handle(
            $data['projectId'],
            $dto->getTelegramUser()->getTelegramId(),
        );

        if (null === $credentialsDto) {
            $message = <<<EOL
            No Credentials. Please add one
            EOL;
            $inlineKeyboard = $this->createInlineKeyboard([
                ['Add', new TelegramActionDataDto('addCredentials', $data)]
            ]);
        } else {
            $message = <<<EOL
            Credentials:
            
            Date expired: {$credentialsDto->getDateExpired()}
            Token: {$credentialsDto->getToken()}
            EOL;
            $inlineKeyboard = $this->createInlineKeyboard([
                ['Delete', new TelegramActionDataDto('deleteCredentials', $data)]
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
