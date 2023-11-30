<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class ChatDto
{
    public function __construct(
        private int $projectId,
        private int $id,
        private int $messengerId,
        private \App\Enums\MessengerTypeEnum $messengerType,
        private \App\Enums\ChatStatusEnum $status,
    ) {}

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMessengerId(): int
    {
        return $this->messengerId;
    }

    public function getMessengerType(): \App\Enums\MessengerTypeEnum
    {
        return $this->messengerType;
    }

    public function getStatus(): \App\Enums\ChatStatusEnum
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
