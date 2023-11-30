<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Services\ChatService\GetChatByProjectIdDto;
use App\Dto\Services\CredentialService\GetCredentialByProjectIdDto;
use App\Dto\Services\DeveloperService\GetDeveloperListDto;
use App\Enums\ChatStatusEnum;
use App\Enums\ProjectStatusEnum;
use App\Interfaces\Services\ChatServiceInterface;
use App\Interfaces\Services\CredentialServiceInterface;
use App\Interfaces\Services\DeveloperServiceInterface;
use App\Interfaces\Services\ProjectStatusServiceInterface;
use DateTime;

readonly class ProjectStatusService implements ProjectStatusServiceInterface
{
    public function __construct(
        private DeveloperServiceInterface $developerService,
        private ChatServiceInterface $chatService,
        private CredentialServiceInterface $credentialService,
    ) {}

    public function getProjectStatus(int $id): ProjectStatusEnum
    {
        $developerList = $this->developerService->getDeveloperList(new GetDeveloperListDto(
            $id,
        ));

        if ([] === $developerList) {
            return ProjectStatusEnum::WITHOUT_DEVELOPERS;
        }

        $chatDto = $this->chatService->findChatByProjectId(new GetChatByProjectIdDto(
            $id,
        ));

        if (null === $chatDto) {
            return ProjectStatusEnum::WITHOUT_CHAT;
        }

        if (ChatStatusEnum::NOT_EXIST === $chatDto->getStatus()) {
            return ProjectStatusEnum::INVALID_CHAT;
        }

        $credentialDto = $this->credentialService->findCredentialByProjectId(new GetCredentialByProjectIdDto(
            $id,
        ));

        if (null === $credentialDto) {
            return ProjectStatusEnum::WITHOUT_CREDENTIAL;
        }

        if (false === $credentialDto->getIsRequestWorkable()) {
            return ProjectStatusEnum::INVALID_CREDENTIAL;
        }

        if ((new Datetime())->modify('+1 days') >= $credentialDto->getDateExpired()) {
            return ProjectStatusEnum::EXPIRED_CREDENTIAL;
        }

        return ProjectStatusEnum::READY;
    }
}
