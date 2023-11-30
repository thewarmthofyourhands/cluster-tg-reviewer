<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Dto\Services\RequestServices\TelegramRequestService\SendMessageDto;
use App\Services\RequestServices\TelegramRequestService;
use App\Services\TransactionService;
use Throwable;

use function Eva\Common\Functions\json_decode;
use function Eva\Common\Functions\json_encode;

class TelegramBotService
{
    public function __construct(
        private readonly TransactionService $transactionService,
        private readonly TelegramRequestService $telegramRequestService,
        private readonly TelegramTsService $telegramTsService,
    ) {}

    public function fetchUpdateList(): null|array
    {
        try {
            $this->transactionService->beginTransaction();
            $ts = $this->telegramTsService->show();

            if (null === $ts) {
                $this->telegramTsService->store();
                $updatesResponse = $this->telegramRequestService->getUpdates();
            } else {
                $updatesResponse = $this->telegramRequestService->getUpdates((int) $ts['value']);
            }

            $updateList = $updatesResponse['result'];

            if ($updateList === []) {
                $this->transactionService->commit();

                return null;
            }

            $newTs = (end($updateList)['update_id'] + 1);
            $this->telegramTsService->edit($newTs);
            $this->transactionService->commit();

            return $updateList;
        } catch (Throwable $e) {
            $this->transactionService->rollback();

            if (str_contains($e->getMessage(), 'Lock wait timeout exceeded;')) {
                return null;
            }

            throw $e;
        }
    }
//
//    public static function createInlineKeyboard(array $inlineKeyboardDtoList = []): array
//    {
//        $inlineKeyboardList = array_map(fn(InlineKeyboardDto $dto) => $dto->toArray(), $inlineKeyboardDtoList);
//
//        return array_chunk($inlineKeyboardList, 4);
//    }
//
//    public static function createKeyboard(array $keyboardList = []): array
//    {
//        return array_chunk($keyboardList, 4);
//    }

    public function sendMessage(SendMessageDto $sendMessageDto): void
    {
        $this->telegramRequestService->sendMessage($sendMessageDto);
    }
}
