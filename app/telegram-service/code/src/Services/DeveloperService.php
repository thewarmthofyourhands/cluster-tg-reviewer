<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\DeveloperService\AddDeveloperDto;
use App\Dto\Services\DeveloperService\ChangeDeveloperStatusDto;
use App\Dto\Services\DeveloperService\DeveloperDto;
use App\Mappers\Dto\Services\DeveloperService\AddDeveloperDtoMapper;
use App\Mappers\Dto\Services\DeveloperService\DeveloperDtoMapper;
use App\Services\RequestServices\MainServiceRequestService;

readonly class DeveloperService
{
    public function __construct(
        private MainServiceRequestService $mainServiceRequest,
    ) {}

    public function store(AddDeveloperDto $dto): void
    {
        $data = AddDeveloperDtoMapper::convertDtoToArray($dto);
        unset($data['projectId']);
        unset($data['adminId']);
        $this->mainServiceRequest->addDeveloper($dto->getProjectId(), $data, $dto->getAdminId());
    }

    public function index(int $projectId, int $adminId): array
    {
        $list = $this->mainServiceRequest->getDeveloperList($projectId, $adminId);

        return DeveloperDtoMapper::convertArrayListToDtoList($list);
    }

    public function show(int $projectId, int $id, int $adminId): DeveloperDto
    {
        $data = $this->mainServiceRequest->getDeveloperById($projectId, $id, $adminId);

        return DeveloperDtoMapper::convertArrayToDto($data);
    }

    public function editStatus(ChangeDeveloperStatusDto $dto): void
    {
        $this->mainServiceRequest->editDeveloperStatus(
            $dto->getProjectId(),
            $dto->getId(),
            $dto->getStatus()->value,
            $dto->getAdminId(),
        );
    }

    public function delete(int $projectId, int $id, int $adminId): void
    {
        $this->mainServiceRequest->deleteDeveloper($projectId, $id, $adminId);
    }
}
