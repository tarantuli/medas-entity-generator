<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\Pluralizers;

interface Pluralizer
{
    public function pluralize(string $string): string;
}
