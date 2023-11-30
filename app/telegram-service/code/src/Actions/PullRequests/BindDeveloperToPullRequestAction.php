<?php

declare(strict_types=1);

namespace App\Actions\PullRequests;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;

readonly class BindDeveloperToPullRequestAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,

    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $message = <<<EOL
        Please choose developer to bind pull request
        EOL;
        $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
        $this->sendMessage($dto->getTelegramUser()->getTelegramId(),$message);
    }
}
