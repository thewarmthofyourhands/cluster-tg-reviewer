<?php

declare(strict_types=1);

namespace App\Dto\Services\RequestServices\TelegramRequestService;

final readonly class KeyboardDto
{
    public function __construct(
        private array $buttonList,
        private bool $oneTimeKeyboard,
        private bool $isPersistent,
        private bool $removeKeyboard,
    ) {}

    public function getButtonList(): array
    {
        return $this->buttonList;
    }

    public function getOneTimeKeyboard(): bool
    {
        return $this->oneTimeKeyboard;
    }

    public function getIsPersistent(): bool
    {
        return $this->isPersistent;
    }

    public function getRemoveKeyboard(): bool
    {
        return $this->removeKeyboard;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
