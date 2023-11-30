<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\ChatService\AddChatDto;
use App\Dto\Services\ChatService\ChatDto;
use App\Enums\Services\MessengerTypeEnum;
use App\Mappers\Dto\Services\ChatService\AddChatDtoMapper;
use App\Mappers\Dto\Services\ChatService\ChatDtoMapper;
use App\Services\RequestServices\MainServiceRequestService;

readonly class ChatService
{
    public function __construct(
        private MainServiceRequestService $mainServiceRequest,
    ) {}

    public function store(AddChatDto $dto): void
    {
        $data = $dto->toArray();
        unset($data['projectId'], $data['adminId']);
        $data['messengerType'] = MessengerTypeEnum::TELEGRAM->value;
        $this->mainServiceRequest->addChat($dto->getProjectId(), $data, $dto->getAdminId());
    }

    public function show(int $projectId, int $adminId): null|ChatDto
    {
        $data = $this->mainServiceRequest->getChat($projectId, $adminId);

        return null === $data ? null : ChatDtoMapper::convertArrayToDto($data);
    }

    public function delete(int $projectId, int $adminId): void
    {
        $this->mainServiceRequest->deleteChat($projectId, $adminId);
    }
}
