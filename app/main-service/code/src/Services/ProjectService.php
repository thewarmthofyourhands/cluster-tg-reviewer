<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\DeveloperService\AddDeveloperDto;
use App\Dto\Services\AdminService\AdminDto;
use App\Dto\Services\ProjectService\AddProjectDto;
use App\Dto\Services\ProjectService\ChangeProjectReviewTypeDto;
use App\Dto\Services\ProjectService\DeleteProjectDto;
use App\Dto\Services\ProjectService\GetProjectByGitRepositoryUrlDto;
use App\Dto\Services\ProjectService\GetProjectByNameDto;
use App\Dto\Services\ProjectService\GetProjectDto;
use App\Dto\Services\ProjectService\GetProjectListDto;
use App\Dto\Services\ProjectService\ProjectDto;
use App\Enums\DeveloperStatusEnum;
use App\Enums\ProjectStatusEnum;
use App\Exceptions\Application\ApplicationErrorCodeEnum;
use App\Exceptions\Application\ApplicationException;
use App\Interfaces\Repositories\ProjectRepositoryInterface;
use App\Interfaces\Services\DeveloperServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;
use App\Interfaces\Services\ProjectStatusServiceInterface;
use Throwable;

readonly class ProjectService implements ProjectServiceInterface
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private DeveloperServiceInterface $developerService,
        private ProjectStatusServiceInterface $projectStatusService,
        private TransactionService $transactionService,
    ) {}

    private function getStatusById(int $id): ProjectStatusEnum
    {
        return $this->projectStatusService->getProjectStatus($id);
    }

    public function deleteProject(DeleteProjectDto $dto): void
    {
        $this->projectRepository->deleteProject(new \App\Dto\Repositories\ProjectRepository\DeleteProjectDto(
            $dto->getAdminId(),
            $dto->getId(),
        ));
    }

    /**
     * @throws Throwable
     */
    public function addProject(AddProjectDto $dto, AdminDto $adminDto): void
    {
        $this->transactionService->beginTransaction();

        try {
            $id = $this->projectRepository->addProject(new \App\Dto\Repositories\ProjectRepository\AddProjectDto(
                $dto->getAdminId(),
                $dto->getName(),
                $dto->getGitRepositoryUrl(),
                $dto->getGitType(),
                $dto->getReviewType(),
            ));
            $this->developerService->addDeveloper(new AddDeveloperDto(
                $id,
                $adminDto->getNickname(),
                true,
                DeveloperStatusEnum::READY,
            ));
            $this->transactionService->commit();
        } catch (Throwable $e) {
            $this->transactionService->rollback();
            throw $e;
        }
    }

    public function changeProjectReviewType(ChangeProjectReviewTypeDto $dto): void
    {
        $this->projectRepository->changeProjectReviewType(new \App\Dto\Repositories\ProjectRepository\ChangeProjectReviewTypeDto(
            $dto->getAdminId(),
            $dto->getId(),
            $dto->getReviewType(),
        ));
    }

    public function getProjectList(GetProjectListDto $dto): array
    {
        $res = $this->projectRepository->getProjectList(new \App\Dto\Repositories\ProjectRepository\GetProjectListDto(
            $dto->getAdminId(),
        ));

        return array_map(fn(\App\Dto\Repositories\ProjectRepository\ProjectDto $dto) => new ProjectDto(
            $dto->getId(),
            $dto->getAdminId(),
            $dto->getName(),
            $this->getStatusById($dto->getId()),
            $dto->getGitRepositoryUrl(),
            $dto->getGitType(),
            $dto->getReviewType(),
        ), $res);
    }

    public function getProject(GetProjectDto $dto): ProjectDto
    {
        $res = $this->projectRepository->getProject(new \App\Dto\Repositories\ProjectRepository\GetProjectDto(
            $dto->getId(),
        ));

        if (null === $res) {
            throw new ApplicationException(ApplicationErrorCodeEnum::ENTITY_NOT_FOUND);
        }

        return new ProjectDto(
            $res->getId(),
            $res->getAdminId(),
            $res->getName(),
            $this->getStatusById($res->getId()),
            $res->getGitRepositoryUrl(),
            $res->getGitType(),
            $res->getReviewType(),
        );
    }

    public function getProjectByName(GetProjectByNameDto $dto): ProjectDto
    {
        $res = $this->projectRepository->getProjectByName(new \App\Dto\Repositories\ProjectRepository\GetProjectByNameDto(
            $dto->getAdminId(),
            $dto->getName(),
        ));

        if (null === $res) {
            throw new ApplicationException(ApplicationErrorCodeEnum::ENTITY_NOT_FOUND);
        }

        return new ProjectDto(
            $res->getId(),
            $res->getAdminId(),
            $res->getName(),
            $this->getStatusById($res->getId()),
            $res->getGitRepositoryUrl(),
            $res->getGitType(),
            $res->getReviewType(),
        );
    }

    public function getProjectByGitRepositoryUrl(GetProjectByGitRepositoryUrlDto $dto): null|ProjectDto
    {
        $res = $this->projectRepository->getProjectByGitRepositoryUrl(new \App\Dto\Repositories\ProjectRepository\GetProjectByGitRepositoryUrlDto(
            $dto->getAdminId(),
            $dto->getGitRepositoryUrl(),
        ));
        if (null === $res) {
            return null;
        }

        return new ProjectDto(
            $res->getId(),
            $res->getAdminId(),
            $res->getName(),
            $this->getStatusById($res->getId()),
            $res->getGitRepositoryUrl(),
            $res->getGitType(),
            $res->getReviewType(),
        );
    }

    public function getAllProjectList(): array
    {
        $res = $this->projectRepository->getAllProjectList();

        return  array_map(fn(\App\Dto\Repositories\ProjectRepository\ProjectDto $dto) => new ProjectDto(
            $dto->getId(),
            $dto->getAdminId(),
            $dto->getName(),
            $this->getStatusById($dto->getId()),
            $dto->getGitRepositoryUrl(),
            $dto->getGitType(),
            $dto->getReviewType(),
        ), $res);
    }

    public function getProjectIdList(): array
    {
        return $this->projectRepository->getProjectIdList();
    }
}
