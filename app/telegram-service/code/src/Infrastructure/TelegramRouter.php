<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Dto\Infrastructure\InputMessageTelegramDto;

class TelegramRouter
{

    protected array $routes = [];

    public function findRoute(InputMessageTelegramDto $inputMessageTelegramDto): array
    {
        $action = $inputMessageTelegramDto->getData()->getAction();
        $actionData = explode('.', $action);
        $mainAction = $actionData[0];
        $secondaryAction = $actionData[1] ?? null;

        foreach ($this->routes as $route => $routeConfig) {
            if ($mainAction === $routeConfig['command']) {
                return [$routeConfig['handler'], $secondaryAction];
            }
        }

        throw new \RuntimeException('Unknown telegram action');
    }

    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }
}
