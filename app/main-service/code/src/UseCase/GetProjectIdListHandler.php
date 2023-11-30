<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Interfaces\Services\ProjectServiceInterface;

readonly class GetProjectIdListHandler
{
    public function __construct(
        private ProjectServiceInterface $projectService,
    ) {}

    public function handle(): array
    {
        return $this->projectService->getProjectIdList();
    }
}
