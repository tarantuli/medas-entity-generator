<?php

declare(strict_types=1);

namespace Medas\EntityGenerator;

use Medas\Core\Attributes\{ConfigValue, Service};

#[Service]
readonly class ClassNameNormalizer
{
    public function __construct(
        #[ConfigValue(ConfigOptions\RootNamespace::class)]
        private string|null $rootNamespace,
    )
    {
    }

    public function normalize(string $className): string
    {
        // Replace a leading dot by the root namespace
        if (str_starts_with($className, '.')) {
            $className = $this->rootNamespace . substr($className, 1);
        }

        // Replace forward slashes with backward slashes
        return str_replace('/', '\\', $className);
    }
}
