<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Dto\Services\AdminService\AdminDto;
use App\Dto\Services\ProjectService\AddProjectDto;
use App\Dto\Services\ProjectService\ChangeProjectReviewTypeDto;
use App\Dto\Services\ProjectService\DeleteProjectDto;
use App\Dto\Services\ProjectService\GetProjectByGitRepositoryUrlDto;
use App\Dto\Services\ProjectService\GetProjectByNameDto;
use App\Dto\Services\ProjectService\GetProjectDto;
use App\Dto\Services\ProjectService\GetProjectListDto;
use App\Dto\Services\ProjectService\ProjectDto;

interface ProjectServiceInterface
{
    public function deleteProject(DeleteProjectDto $dto): void;
    public function addProject(AddProjectDto $dto, AdminDto $adminDto): void;
    public function changeProjectReviewType(ChangeProjectReviewTypeDto $dto): void;
    public function getProjectList(GetProjectListDto $dto): array;
    public function getAllProjectList(): array;
    public function getProjectIdList(): array;
    public function getProject(GetProjectDto $dto): ProjectDto;
    public function getProjectByName(GetProjectByNameDto $dto): ProjectDto;
    public function getProjectByGitRepositoryUrl(GetProjectByGitRepositoryUrlDto $dto): null|ProjectDto;
}
