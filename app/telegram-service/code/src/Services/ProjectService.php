<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\ProjectService\AddProjectDto;
use App\Dto\Services\ProjectService\ProjectDto;
use App\Enums\Services\ProjectService\ReviewTypeEnum;
use App\Mappers\Dto\Services\ProjectService\AddProjectDtoMapper;
use App\Mappers\Dto\Services\ProjectService\ProjectDtoMapper;
use App\Services\RequestServices\MainServiceRequestService;

readonly class ProjectService
{
    public function __construct(
        private MainServiceRequestService $mainServiceRequest,
    ) {}

    public function store(AddProjectDto $dto): void
    {
        $data = AddProjectDtoMapper::convertDtoToArray($dto);
        unset($data['telegramUserId']);
        $this->mainServiceRequest->addProject($data, $dto->getAdminId());
    }

    public function index(int $adminId): array
    {
        $projectList = $this->mainServiceRequest->getProjectList($adminId);

        return ProjectDtoMapper::convertListToDtoList($projectList);
    }

    public function show(int $id, int $adminId): ProjectDto
    {
        $project = $this->mainServiceRequest->getProjectById($id, $adminId);

        return ProjectDtoMapper::convertArrayToDto($project);
    }

    public function editReviewType(int $id, ReviewTypeEnum $reviewTypeEnum, int $adminId): void
    {
        $this->mainServiceRequest->editProjectReviewType($id, $reviewTypeEnum, $adminId);
    }

    public function delete(int $id, int $adminId): void
    {
        $this->mainServiceRequest->deleteProject($id, $adminId);
    }
}
