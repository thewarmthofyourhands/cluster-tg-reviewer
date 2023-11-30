<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Dto\Services\RequestServices\TelegramRequestService\SendMessageDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Projects\GetProjectByIdHandler;
use App\UseCase\Projects\GetProjectByNameHandler;
use App\UseCase\Projects\GetProjectNameListHandler;

readonly class SelectProjectAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private GetProjectNameListHandler $getProjectNameListHandler,
        private GetProjectByNameHandler $getProjectByNameHandler,
        private GetProjectByIdHandler $getProjectByIdHandler,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $projectNameList = $this->getProjectNameListHandler->handle($dto->getTelegramUser()->getTelegramId());
        $keyboard = $this->createOneTimeKeyboard($projectNameList);
        $this->setTelegramUserActionData($dto->getTelegramUser()->getTelegramId(), 'selectProject.select');
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            Please, type or select project name for select:
            EOL,
            $keyboard,
        );
    }

    public function select(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();

        if (null === $data) {
            $name = $dto->getText();
            $projectDto = $this->getProjectByNameHandler->handle($name, $dto->getTelegramUser()->getTelegramId());
            $data = ['projectId' => $projectDto->getId()];
        } else {
            $projectDto = $this->getProjectByIdHandler->handle($data['projectId'], $dto->getTelegramUser()->getTelegramId());
        }

        $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
        $inlineKeyboard = $this->createInlineKeyboard([
            ['Credentials', new TelegramActionDataDto('getCredentials', $data)],
            ['Chat', new TelegramActionDataDto('getChat', $data)],
            ['Developers', new TelegramActionDataDto('getDevelopers', $data)],
        ]);
        $this->telegramBotService->sendMessage(new SendMessageDto(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            {$projectDto->getName()}
            
            Status: {$projectDto->getProjectStatus()->value}
            Repository url: {$projectDto->getGitRepositoryUrl()}
            Git service: {$projectDto->getGitType()->value}
            Review type: {$projectDto->getReviewType()->value}
            
            EOL,
            null,
            $inlineKeyboard,
        ));
    }
}
