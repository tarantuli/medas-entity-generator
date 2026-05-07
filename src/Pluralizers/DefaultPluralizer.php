<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\Pluralizers;

use Medas\Core\Attributes\Service;

#[Service]
readonly class DefaultPluralizer implements Pluralizer
{
    public function pluralize(string $string): string
    {
        if (str_ends_with($string, 'y')) {
            $string = substr($string, 0, -1) . 'ie';
        }

        return $string . 's';
    }
}
