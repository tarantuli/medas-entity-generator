<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\ConfigOptions;

use Medas\Core\{Attributes\Service, Interfaces\ConfigGroup};

#[Service]
readonly class EntityGeneratorGroup implements ConfigGroup
{
    public function parent(): ConfigGroup|null
    {
        return null;
    }

    public function name(): string
    {
        return 'entity-generator';
    }
}
