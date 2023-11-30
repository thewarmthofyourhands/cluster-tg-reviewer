<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Dto\UseCase\ChangeProjectReviewTypeDto;
use App\Interfaces\Services\AdminServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;

readonly class ChangeProjectReviewTypeHandler
{
    public function __construct(
        private AdminServiceInterface $adminService,
        private ProjectServiceInterface $projectService,
    ) {}

    public function handle(ChangeProjectReviewTypeDto $dto): void
    {
        $this->adminService->auth($dto->getAdminId());
        $this->projectService->changeProjectReviewType(new \App\Dto\Services\ProjectService\ChangeProjectReviewTypeDto(
            $dto->getAdminId(),
            $dto->getId(),
            $dto->getReviewType(),
        ));
    }
}
