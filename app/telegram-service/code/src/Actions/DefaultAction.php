<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Dto\Services\RequestServices\TelegramRequestService\InlineKeyboardDto;
use App\Dto\Services\RequestServices\TelegramRequestService\SendMessageDto;
use App\Dto\UseCase\Admins\AddAdminDto;
use App\Dto\UseCase\Projects\ProjectDto;
use App\Infrastructure\TelegramUserActionDataTransformer;
use App\Services\Telegram\TelegramBotService;
use App\UseCase\Admins\AddAdminHandler;
use App\UseCase\Admins\LoginHandler;
use App\UseCase\Projects\GetProjectListHandler;
use Psr\Log\LoggerInterface;

readonly class DefaultAction
{
    public function __construct(
        private TelegramBotService $telegramBotService,
        private GetProjectListHandler $getProjectListMessageHandler,
        private AddAdminHandler $addAdminHandler,
    ) {}

    private function normalizeText(string $text): array
    {
        $text = mb_strtolower(trim($text));

        return preg_split('/\s+/', $text);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $command = $this->normalizeText($dto->getText());
        $command = $command[0];

        if ('/start' === $command) {
            $this->addAdminHandler->handle(new AddAdminDto(
                $dto->getTelegramUser()->getUsername(),
                $dto->getTelegramUser()->getTelegramId(),
            ));
        }

        if ('/help' === $command || '/start' === $command) {
            $this->telegramBotService->sendMessage(new SendMessageDto(
                $dto->getTelegramUser()->getTelegramId(),
                <<<EOL
                Hi there! That a bot to integrate your team of development to cross review approach.
                I would really like you to find this useful.                
                In the future I plan to add implementations to slack, web service, add integrations with gitlab and bitbucket and some new review modes.
                
                If you have a bug or would like feedback, write to me @monk_case
                
                List of commands:
                /projects - list of projects
                /howtouse - How use the bot
                EOL,
                null,
                null,
            ));
            return;
        }

        if ('/howtouse' === $command) {
            $this->telegramBotService->sendMessage(new SendMessageDto(
                $dto->getTelegramUser()->getTelegramId(),
                <<<EOL
                How to use:
                1. Create github organization and repository
                2. Create fine granted token for organization with pull requests permissions
                3. Create project in telegram
                4. Set credentials, developers and chat
                5. Add a bot to the chat that will be used for notifications
                6. Check that project status would be "ready"
                EOL,
                null,
                null,
            ));
            return;
        }

        if ('/projects' === $command) {
            $this->getProjectList($dto);
        }
    }

    public function getProjectList(InputMessageTelegramDto $dto): void
    {
        $projectDtoList = $this->getProjectListMessageHandler->handle($dto->getTelegramUser()->getTelegramId());
        $projectMessageList = array_map(static function (ProjectDto $dto) {
            return sprintf(
                '%s - %s',
                $dto->getName(),
                $dto->getProjectStatus()->value,
            );
        }, $projectDtoList);
        $projectListMessage = implode(PHP_EOL, $projectMessageList);

        if ([] === $projectDtoList) {
            $inlineKeyboard = [
                (new InlineKeyboardDto(
                    'Add',
                    TelegramUserActionDataTransformer::createJsonActionDataByDto(new TelegramActionDataDto(
                        'addProject',
                        null,
                    )),
                ))->toArray(),
            ];
        } else {
            $inlineKeyboard = [
                (new InlineKeyboardDto(
                    'Add',
                    TelegramUserActionDataTransformer::createJsonActionDataByDto(new TelegramActionDataDto(
                        'addProject',
                        null,
                    )),
                ))->toArray(),
                (new InlineKeyboardDto(
                    'Select',
                    TelegramUserActionDataTransformer::createJsonActionDataByDto(new TelegramActionDataDto(
                        'selectProject',
                        null,
                    )),
                ))->toArray(),
                (new InlineKeyboardDto(
                    'Delete',
                    TelegramUserActionDataTransformer::createJsonActionDataByDto(new TelegramActionDataDto(
                        'deleteProject',
                        null,
                    )),
                ))->toArray(),
            ];
        }

        $this->telegramBotService->sendMessage(new SendMessageDto(
            $dto->getTelegramUser()->getTelegramId(),
            <<<EOL
            List of projects:
                
            {$projectListMessage}
            EOL,
            null,
            $inlineKeyboard,
        ));
    }
}
