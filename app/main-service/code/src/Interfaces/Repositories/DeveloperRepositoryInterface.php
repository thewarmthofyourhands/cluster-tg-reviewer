<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Dto\Repositories\DeveloperRepository\AddDeveloperDto;
use App\Dto\Repositories\DeveloperRepository\ChangeDeveloperStatusDto;
use App\Dto\Repositories\DeveloperRepository\DeleteDeveloperDto;
use App\Dto\Repositories\DeveloperRepository\DeveloperDto;
use App\Dto\Repositories\DeveloperRepository\GetDeveloperByIdDto;
use App\Dto\Repositories\DeveloperRepository\GetDeveloperByNicknameDto;
use App\Dto\Repositories\DeveloperRepository\GetDeveloperListDto;
use App\Dto\Repositories\DeveloperRepository\GetDeveloperWithPendingPullRequestCountListDto;

interface DeveloperRepositoryInterface
{
    public function addDeveloper(AddDeveloperDto $dto): void;
    public function changeDeveloperStatus(ChangeDeveloperStatusDto $dto): void;
    public function deleteDeveloper(DeleteDeveloperDto $dto): void;
    public function getDeveloperList(GetDeveloperListDto $dto): array;
    public function getDeveloperWithPendingPullRequestCountList(GetDeveloperWithPendingPullRequestCountListDto $dto): array;
    public function getDeveloperById(GetDeveloperByIdDto $dto): null|DeveloperDto;
    public function getDeveloperByNickname(GetDeveloperByNicknameDto $dto): null|DeveloperDto;
}
