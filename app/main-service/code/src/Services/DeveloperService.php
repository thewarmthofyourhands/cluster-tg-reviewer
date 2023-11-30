<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\DeveloperService\AddDeveloperDto;
use App\Dto\Services\DeveloperService\ChangeDeveloperStatusDto;
use App\Dto\Services\DeveloperService\DeleteDeveloperDto;
use App\Dto\Services\DeveloperService\DeveloperDto;
use App\Dto\Services\DeveloperService\DeveloperWithPendingPullRequestCountDto;
use App\Dto\Services\DeveloperService\DeveloperWithPullRequestCountDto;
use App\Dto\Services\DeveloperService\GetDeveloperByIdDto;
use App\Dto\Services\DeveloperService\GetDeveloperByNicknameDto;
use App\Dto\Services\DeveloperService\GetDeveloperListDto;
use App\Dto\Services\DeveloperService\GetDeveloperWithPendingPullRequestCountListDto;
use App\Dto\Services\ProjectService\GetProjectDto;
use App\Interfaces\Repositories\DeveloperRepositoryInterface;
use App\Interfaces\Services\DeveloperServiceInterface;
use App\Interfaces\Services\ProjectServiceInterface;

readonly class DeveloperService implements DeveloperServiceInterface
{
    public function __construct(
        private DeveloperRepositoryInterface $developerRepository,
    ) {}

    public function addDeveloper(AddDeveloperDto $dto): void
    {
        $this->developerRepository->addDeveloper(new \App\Dto\Repositories\DeveloperRepository\AddDeveloperDto(
            $dto->getProjectId(),
            $dto->getNickname(),
            $dto->getIsAdmin(),
            $dto->getStatus(),
        ));
    }

    public function changeDeveloperStatus(ChangeDeveloperStatusDto $dto): void
    {
        $this->developerRepository->changeDeveloperStatus(new \App\Dto\Repositories\DeveloperRepository\ChangeDeveloperStatusDto(
            $dto->getProjectId(),
            $dto->getId(),
            $dto->getStatus(),
        ));
    }

    public function deleteDeveloper(DeleteDeveloperDto $dto): void
    {
        $this->developerRepository->deleteDeveloper(new \App\Dto\Repositories\DeveloperRepository\DeleteDeveloperDto(
            $dto->getProjectId(),
            $dto->getId(),
        ));
    }

    public function getDeveloperList(GetDeveloperListDto $dto): array
    {
        $res = $this->developerRepository->getDeveloperList(new \App\Dto\Repositories\DeveloperRepository\GetDeveloperListDto(
            $dto->getProjectId(),
        ));

        return array_map(static fn(\App\Dto\Repositories\DeveloperRepository\DeveloperDto $dto) => new DeveloperDto(
            $dto->getId(),
            $dto->getProjectId(),
            $dto->getNickname(),
            $dto->getIsAdmin(),
            $dto->getStatus(),
        ), $res);
    }

    public function getDeveloperWithPendingPullRequestCountList(GetDeveloperWithPendingPullRequestCountListDto $dto): array
    {
        $res = $this->developerRepository->getDeveloperWithPendingPullRequestCountList(new \App\Dto\Repositories\DeveloperRepository\GetDeveloperWithPendingPullRequestCountListDto(
            $dto->getProjectId(),
        ));

        return array_map(static fn(\App\Dto\Repositories\DeveloperRepository\DeveloperWithPendingPullRequestCountDto $dto) =>
        new DeveloperWithPendingPullRequestCountDto(
            $dto->getId(),
            $dto->getProjectId(),
            $dto->getNickname(),
            $dto->getIsAdmin(),
            $dto->getStatus(),
            $dto->getPullRequestCount(),
        ), $res);
    }

    public function getDeveloperById(GetDeveloperByIdDto $dto): null|DeveloperDto
    {
        $res = $this->developerRepository->getDeveloperById(new \App\Dto\Repositories\DeveloperRepository\GetDeveloperByIdDto(
            $dto->getProjectId(),
            $dto->getId(),
        ));
        if (null === $res) {
            return null;
        }

        return new DeveloperDto(
            $res->getId(),
            $res->getProjectId(),
            $res->getNickname(),
            $res->getIsAdmin(),
            $res->getStatus(),
        );
    }

    public function getDeveloperByNickname(GetDeveloperByNicknameDto $dto): null|DeveloperDto
    {
        $res = $this->developerRepository->getDeveloperByNickname(new \App\Dto\Repositories\DeveloperRepository\GetDeveloperByNicknameDto(
            $dto->getProjectId(),
            $dto->getNickname(),
        ));
        if (null === $res) {
            return null;
        }

        return new DeveloperDto(
            $res->getId(),
            $res->getProjectId(),
            $res->getNickname(),
            $res->getIsAdmin(),
            $res->getStatus(),
        );
    }
}

