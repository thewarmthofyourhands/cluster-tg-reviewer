<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Dto\Services\CredentialService\CredentialDto;
use App\Dto\Services\GitService\GetPullRequestListDto;
use App\Dto\Services\ProjectService\ProjectDto;

interface GitServiceInterface
{
    public function getPullRequestList(GetPullRequestListDto $dto): array;
    public function credentialTest(CredentialDto $dto, ProjectDto $projectDto): bool;
}
