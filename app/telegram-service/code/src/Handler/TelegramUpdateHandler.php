<?php

declare(strict_types=1);

namespace App\Handler;

use App\Dto\Infrastructure\InputMessageTelegramDto;
use App\Dto\Infrastructure\TelegramActionDataDto;
use App\Dto\Services\RequestServices\TelegramRequestService\SendMessageDto;
use App\Exceptions\Application\ApplicationException;
use App\Exceptions\TelegramBotException;
use App\Infrastructure\TelegramRouter;
use App\Infrastructure\TelegramUserActionDataTransformer;
use App\Services\Telegram\TelegramBotService;
use App\Services\Telegram\TelegramUserService;
use App\UseCase\GetTelegramUserHandler;
use App\UseCase\GetUpdateListHandler;
use Eva\DependencyInjection\ContainerInterface;

use function Eva\Common\Functions\json_decode;
use function Eva\Common\Functions\json_encode;

readonly class TelegramUpdateHandler
{
    public function __construct(
        private GetUpdateListHandler $getUpdateListHandler,
        private GetTelegramUserHandler $getTelegramUserHandler,
        private TelegramBotService $telegramBotService,
        private TelegramUserService $telegramUserService,
        private TelegramRouter $telegramRouter,
        private ContainerInterface $container,
        private string $botNickname,
    ) {}

    private function buildInputByCallbackQueryUpdate(array $update): InputMessageTelegramDto
    {
        $text = null;
        $chatId = (int) $update['callback_query']['message']['chat']['id'];
        $chatType = $update['callback_query']['message']['chat']['type'];
        $telegramUserDto = $this->getTelegramUserHandler->handle(
            (int) $update['callback_query']['from']['id'],
            $update['callback_query']['from']['username'],
        );
        $data = $update['callback_query']['data'];
        $data = TelegramUserActionDataTransformer::createActionDataByJson($data);

        return new InputMessageTelegramDto(
            $chatId,
            $chatType,
            $telegramUserDto,
            $text,
            $data,
        );
    }

    private function buildInputByMessageUpdate(array $update): InputMessageTelegramDto
    {
        //example of group migration to supergroup {"update_id":335870049,"message":{"message_id":1,"from":{"id":1087968824,"is_bot":true,"first_name":"Group","username":"GroupAnonymousBot"},"sender_chat":{"id":-1002086227621,"title":"tttt","type":"supergroup"},"chat":{"id":-1002086227621,"title":"tttt","type":"supergroup"},"date":1699219244,"migrate_from_chat_id":-4076578895}}
        if (isset($update['message']['chat_shared'])) {
            $update['message']['text'] = (string) $update['message']['chat_shared']['chat_id'];
        }
        $chatId = (int) $update['message']['chat']['id'];
        $chatType = $update['message']['chat']['type'];
        $text = $update['message']['text'];
        $text = str_replace('@'.$this->botNickname, '', $text);
        $telegramUserDto = $this->getTelegramUserHandler->handle(
            (int) $update['message']['from']['id'],
            $update['message']['from']['username'],
        );
        $data = $telegramUserDto->getData();
        $data = TelegramUserActionDataTransformer::createActionDataByJson($data);

        return new InputMessageTelegramDto(
            $chatId,
            $chatType,
            $telegramUserDto,
            $text,
            $data,
        );
    }

    public function handle(): void
    {
        $updateList = $this->getUpdateListHandler->handle();

        foreach ($updateList as $update) {
            try {
                if (isset($update['callback_query'])) {
                    $inputMessageTelegramDto = $this->buildInputByCallbackQueryUpdate($update);
                }

                if (isset($update['message'])) {
                    $inputMessageTelegramDto = $this->buildInputByMessageUpdate($update);
                }

                if (
                    false === isset($inputMessageTelegramDto) ||
                    'private' !== $inputMessageTelegramDto->getChatType()
                    || $inputMessageTelegramDto->getChatId() !== $inputMessageTelegramDto->getTelegramUser()->getTelegramId()
                ) {
                    continue;
                }

                [$handlerClass, $method] = $this->telegramRouter->findRoute($inputMessageTelegramDto);

                if (null === $method) {
                    $this->container->get($handlerClass)($inputMessageTelegramDto);
                    continue;
                }

                $this->container->get($handlerClass)->{$method}($inputMessageTelegramDto);
            } catch (ApplicationException $e) {
                //reset
                $this->telegramUserService->editData(
                    $inputMessageTelegramDto->getTelegramUser()->getTelegramId(),
                    TelegramUserActionDataTransformer::createJsonActionDataByDto(new TelegramActionDataDto(
                        'default',
                        null,
                    ))
                );
                $this->telegramBotService->sendMessage(new SendMessageDto(
                    $inputMessageTelegramDto->getTelegramUser()->getTelegramId(),
                    $e->getMessage(),
                    null,
                    null,
                ));
                throw $e;
            } catch (\Throwable $e) {
                throw new TelegramBotException($update, $e->getMessage(), $e->getCode(), $e);
            }
        }
    }
}
