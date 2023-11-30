<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Dto\Services\ChatService\AddChatDto;
use App\Dto\Services\ChatService\ChatDto;
use App\Dto\Services\ChatService\DeleteChatDto;
use App\Dto\Services\ChatService\GetChatByIdDto;
use App\Dto\Services\ChatService\GetChatByProjectIdDto;
use App\Enums\ChatStatusEnum;

interface ChatServiceInterface
{
    public function addChat(AddChatDto $dto): void;
    public function deleteChat(DeleteChatDto $dto): void;
    public function changeChatStatus(int $projectId, ChatStatusEnum $chatStatusEnum): void;
    public function getChatById(GetChatByIdDto $dto): ChatDto;
    public function getChatByProjectId(GetChatByProjectIdDto $dto): ChatDto;
    public function findChatByProjectId(GetChatByProjectIdDto $dto): null|ChatDto;
}
