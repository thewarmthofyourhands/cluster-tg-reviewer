<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Dto\Services\DeveloperService\GetDeveloperWithPendingPullRequestCountListDto;
use App\Dto\Services\DeveloperService\AddDeveloperDto;
use App\Dto\Services\DeveloperService\ChangeDeveloperStatusDto;
use App\Dto\Services\DeveloperService\DeleteDeveloperDto;
use App\Dto\Services\DeveloperService\DeveloperDto;
use App\Dto\Services\DeveloperService\GetDeveloperByIdDto;
use App\Dto\Services\DeveloperService\GetDeveloperByNicknameDto;
use App\Dto\Services\DeveloperService\GetDeveloperListDto;

interface DeveloperServiceInterface
{
    public function addDeveloper(AddDeveloperDto $dto): void;
    public function changeDeveloperStatus(ChangeDeveloperStatusDto $dto): void;
    public function deleteDeveloper(DeleteDeveloperDto $dto): void;
    public function getDeveloperList(GetDeveloperListDto $dto): array;
    public function getDeveloperWithPendingPullRequestCountList(GetDeveloperWithPendingPullRequestCountListDto $dto): array;
    public function getDeveloperById(GetDeveloperByIdDto $dto): null|DeveloperDto;
    public function getDeveloperByNickname(GetDeveloperByNicknameDto $dto): null|DeveloperDto;
}
