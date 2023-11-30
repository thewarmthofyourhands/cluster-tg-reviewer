<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Dto\Repositories\ChatRepository\AddChatDto;
use App\Dto\Repositories\ChatRepository\ChatDto;
use App\Dto\Repositories\ChatRepository\DeleteChatDto;
use App\Dto\Repositories\ChatRepository\GetChatByIdDto;
use App\Dto\Repositories\ChatRepository\GetChatByProjectIdDto;
use App\Enums\ChatStatusEnum;

interface ChatRepositoryInterface
{
    public function addChat(AddChatDto $dto): void;
    public function deleteChat(DeleteChatDto $dto): void;
    public function changeChatStatus(int $projectId, ChatStatusEnum $chatStatusEnum): void;
    public function getChatById(GetChatByIdDto $dto): ChatDto;
    public function getChatByProjectId(GetChatByProjectIdDto $dto): ChatDto;
    public function findChatByProjectId(GetChatByProjectIdDto $dto): null|ChatDto;
}
