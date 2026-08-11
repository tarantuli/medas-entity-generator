<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\ConfigOptions;

use Medas\Core\{Attributes\Service, Interfaces\ConfigGroup, Interfaces\ConfigOption};

#[Service]
readonly class UseAutoIds implements ConfigOption
{
    public function __construct(
        private EntityGeneratorGroup $group,
    )
    {
    }

    public function group(): ConfigGroup
    {
        return $this->group;
    }

    public function name(): string
    {
        return 'use-auto-ids';
    }

    public function description(): string
    {
        return 'Whether to use auto-incrementing ids instead of uuids by default';
    }

    public function hasDefault(): bool
    {
        return true;
    }

    public function default(): false
    {
        return false;
    }
}
