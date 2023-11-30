<?php

declare(strict_types=1);

namespace App\UseCase\PullRequests;

use App\Services\PullRequestService;

readonly class BindDeveloperToPullRequestHandler
{
    public function __construct(
        private PullRequestService $pullRequestService,
    ) {}

    public function handle(): void
    {
    }
}
