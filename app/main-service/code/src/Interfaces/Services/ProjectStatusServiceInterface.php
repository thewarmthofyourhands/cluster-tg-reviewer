<?php

declare(strict_types=1);

namespace App\Interfaces\Services;

use App\Enums\ProjectStatusEnum;

interface ProjectStatusServiceInterface
{
    public function getProjectStatus(int $id): ProjectStatusEnum;
}
