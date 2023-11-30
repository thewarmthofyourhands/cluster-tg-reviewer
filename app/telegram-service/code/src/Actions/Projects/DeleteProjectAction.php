<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\Actions\AbstractAction;
use App\Actions\DefaultAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Services\RequestServices\TelegramRequestService\SendMessageDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Projects\DeleteProjectByNameHandler;
use App\UseCase\Projects\GetProjectNameListHandler;

readonly class DeleteProjectAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private GetProjectNameListHandler $getProjectNameListHandler,
        private DeleteProjectByNameHandler $deleteProjectByNameHandler,
        private DefaultAction $defaultAction,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $projectNameList = $this->getProjectNameListHandler->handle($dto->getTelegramUser()->getTelegramId());
        $keyboard = $this->createOneTimeKeyboard($projectNameList);
        $this->setTelegramUserActionData($dto->getTelegramUser()->getTelegramId(), 'deleteProject.deleteByName');
        $this->sendMessage(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            Please, type or select project name for delete:
            EOL,
            $keyboard,
        );
    }

    public function deleteByName(InputMessageTelegramDto $dto): void
    {
        $name = $dto->getText();
        $this->deleteProjectByNameHandler->handle($name, $dto->getTelegramUser()->getTelegramId());
        $this->resetTelegramUserActionData($dto->getTelegramUser()->getTelegramId());
        $this->telegramBotService->sendMessage(new SendMessageDto(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            Project {$name} successful deleted
            EOL,
            null,
            null,
        ));
        $this->defaultAction->getProjectList($dto);
    }
}
