<?php

declare(strict_types=1);

namespace App\Actions\Developers;

use App\Actions\AbstractAction;
use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Dto\UseCase\Developers\DeveloperDto;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\Developers\GetDeveloperListHandler;

readonly class GetDeveloperListAction extends AbstractAction
{
    public function __construct(
        TelegramBotService $telegramBotService,
        TelegramUserService $telegramUserService,
        private GetDeveloperListHandler $getDeveloperListHandler,
    ) {
        parent::__construct($telegramBotService, $telegramUserService);
    }

    public function __invoke(InputMessageTelegramDto $dto): void
    {
        $data = $dto->getData()->getData();
        $developerDtoList = $this->getDeveloperListHandler->handle(
            $data['projectId'],
            $dto->getTelegramUser()->getTelegramId(),
        );

        if ([] === $developerDtoList) {
            $message = <<<EOL
            No developers, please add new
            EOL;
            $inlineKeyboard = $this->createInlineKeyboard([
                ['Add', new TelegramActionDataDto('addDeveloper', $data)],
            ]);
        } else {
            $developerMessageList = array_map(static function(DeveloperDto $dto) {
                return <<<EOL
                @{$dto->getNickname()} - {$dto->getStatus()->value}
                EOL;
            }, $developerDtoList);
            $developerListMessage = implode(PHP_EOL, $developerMessageList);
            $message = <<<EOL
            Developer list:
            
            {$developerListMessage}
            EOL;
            $backData = ['projectId' => $data['projectId']];
            $inlineKeyboard = $this->createInlineKeyboard([
                ['Add', new TelegramActionDataDto('addDeveloper', $data)],
                ['Delete', new TelegramActionDataDto('deleteDeveloper', $data)],
                ['Change status', new TelegramActionDataDto('editDeveloperStatus', $data)],
                ['Back', new TelegramActionDataDto('selectProject.select', $backData)],
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
