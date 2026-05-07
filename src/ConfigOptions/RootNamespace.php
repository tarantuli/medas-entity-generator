<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\ConfigOptions;

use Medas\Core\{Attributes\Service, Interfaces\ConfigGroup, Interfaces\ConfigOption};

#[Service]
readonly class RootNamespace implements ConfigOption
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
        return 'root-namespace';
    }

    public function description(): string
    {
        return 'The root namespace to use when the entity name starts with a dot (eg. ./Projects/Project)';
    }

    public function hasDefault(): bool
    {
        return false;
    }

    public function default(): null
    {
        return null;
    }
}
