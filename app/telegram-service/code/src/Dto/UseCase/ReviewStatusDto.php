<?php

declare(strict_types=1);

namespace App\Dto\UseCase;

final readonly class ReviewStatusDto
{
    public function __construct(
        private string $projectName,
        private string $developerNickname,
        private string $title,
        private string $branch,
        private string $url,
        private string $pastHours,
    ) {}

    public function getProjectName(): string
    {
        return $this->projectName;
    }

    public function getDeveloperNickname(): string
    {
        return $this->developerNickname;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBranch(): string
    {
        return $this->branch;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPastHours(): string
    {
        return $this->pastHours;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
