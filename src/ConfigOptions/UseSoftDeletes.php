<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\ConfigOptions;

use Medas\Core\{Attributes\Service, Interfaces\ConfigGroup, Interfaces\ConfigOption};

#[Service]
readonly class UseSoftDeletes implements ConfigOption
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
        return 'use-soft-deletes';
    }

    public function description(): string
    {
        return 'Whether to use soft deletes by default';
    }

    public function hasDefault(): bool
    {
        return true;
    }

    public function default(): true
    {
        return true;
    }
}
