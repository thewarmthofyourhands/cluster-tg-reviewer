<?php

declare(strict_types=1);

namespace App\Actions\PullRequests;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\UseCase\PullRequests\PullRequestDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\PullRequests\GetPullRequestListHandler;

readonly class GetPullRequestListAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private GetPullRequestListHandler $getPullRequestListHandler,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $pullRequestDtoList = $this->getPullRequestListHandler->handle(
            $data['projectId'],
            $dto->getTelegramUser()->getTelegramId(),
        );
        $pullRequestMessageList = array_map(
            static fn(PullRequestDto $dto) => <<<EOL
            branch: {$dto->getBranch()}
            title: {$dto->getTitle()}
            status: {$dto->getStatus()->value}
            id: {$dto->getPullRequestNumber()}
            EOL,
            $pullRequestDtoList,
        );
        $pullRequestListMessage = implode(PHP_EOL, $pullRequestMessageList);
        $message = <<<EOL
        Pull requests list:
        
        {$pullRequestListMessage}
        EOL;
        $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
        $this->sendMessage($dto->getTelegramUser()->getTelegramId(), $message);
    }
}
