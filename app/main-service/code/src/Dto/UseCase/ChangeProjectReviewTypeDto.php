<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class ChangeProjectReviewTypeDto
{
    public function __construct(
        private int $adminId,
        private int $id,
        private \App\Enums\ReviewTypeEnum $reviewType,
    ) {}

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReviewType(): \App\Enums\ReviewTypeEnum
    {
        return $this->reviewType;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
