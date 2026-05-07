<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\NameConverters;

interface NameConverter
{
    public function convert(string $name): string;
}
