<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Dto\Repositories\ProjectRepository\AddProjectDto;
use App\Dto\Repositories\ProjectRepository\ChangeProjectReviewTypeDto;
use App\Dto\Repositories\ProjectRepository\DeleteProjectDto;
use App\Dto\Repositories\ProjectRepository\GetProjectByGitRepositoryUrlDto;
use App\Dto\Repositories\ProjectRepository\GetProjectByNameDto;
use App\Dto\Repositories\ProjectRepository\GetProjectDto;
use App\Dto\Repositories\ProjectRepository\GetProjectListDto;
use App\Dto\Repositories\ProjectRepository\ProjectDto;

interface ProjectRepositoryInterface
{
    public function deleteProject(DeleteProjectDto $dto): void;
    public function addProject(AddProjectDto $dto): int;
    public function changeProjectReviewType(ChangeProjectReviewTypeDto $dto): void;
    public function getProjectList(GetProjectListDto $dto): array;
    public function getAllProjectList(): array;
    public function getProjectIdList(): array;
    public function getProject(GetProjectDto $dto): null|ProjectDto;
    public function getProjectByName(GetProjectByNameDto $dto): null|ProjectDto;
    public function getProjectByGitRepositoryUrl(GetProjectByGitRepositoryUrlDto $dto): null|ProjectDto;
}
