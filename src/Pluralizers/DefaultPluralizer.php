<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\Pluralizers;

use Medas\Core\Attributes\Service;

#[Service]
readonly class DefaultPluralizer implements Pluralizer
{
    private const array ES_SUFFIXES = ['s', 'ss', 'sh', 'ch', 'x', 'z'];

    private const array F_TO_VES_EXCEPTIONS = [
        'roof',
        'belief',
        'chef',
        'chief',
        'cliff',
        'cuff',
        'gulf',
    ];

    private const array O_ES_EXCEPTIONS = [
        'photo',
        'piano',
        'halo',
        'logo',
        'memo',
        'video',
        'studio',
        'zoo',
    ];

    private const array IRREGULARS = [
        'man' => 'men',
        'woman' => 'women',
        'child' => 'children',
        'person' => 'people',
        'tooth' => 'teeth',
        'foot' => 'feet',
        'mouse' => 'mice',
        'goose' => 'geese',
    ];

    private const array UNCHANGED = [
        'sheep',
        'fish',
        'series',
        'species',
        'deer',
        'aircraft',
    ];

    public function pluralize(string $string): string
    {
        $lower = strtolower($string);

        if (in_array($lower, self::UNCHANGED, true)) {
            return $string;
        }

        if (array_key_exists($lower, self::IRREGULARS)) {
            return $this->matchCase($string, self::IRREGULARS[$lower]);
        }

        if (str_ends_with($string, 'quiz')) {
            return $string . 'zes';
        }

        if (str_ends_with($string, 'y') && !$this->endsWithVowelThenY($string)) {
            return substr($string, 0, -1) . 'ies';
        }

        if (str_ends_with($lower, 'fe') && !in_array(substr($lower, 0, -2), self::F_TO_VES_EXCEPTIONS, true)) {
            return substr($string, 0, -2) . 'ves';
        }

        if (str_ends_with($lower, 'f') && !in_array($lower, self::F_TO_VES_EXCEPTIONS, true)) {
            return substr($string, 0, -1) . 'ves';
        }

        if (str_ends_with($lower, 'o')
                && !in_array($lower, self::O_ES_EXCEPTIONS, true)
                && !$this->endsWithVowelThenO($string)) {
            return $string . 'es';
        }

        if (array_any(self::ES_SUFFIXES, fn($suffix) => str_ends_with($string, $suffix))) {
            return $string . 'es';
        }

        return $string . 's';
    }

    private function matchCase(string $original, string $replacement): string
    {
        if ($original === strtoupper($original)) {
            return strtoupper($replacement);
        }

        if ($original[0] === strtoupper($original[0])) {
            return ucfirst($replacement);
        }

        return $replacement;
    }

    private function endsWithVowelThenY(string $string): bool
    {
        $beforeY = substr($string, -2, 1);

        return in_array(strtolower($beforeY), ['a', 'e', 'i', 'o', 'u'], true);
    }

    private function endsWithVowelThenO(string $string): bool
    {
        $beforeO = substr($string, -2, 1);

        return in_array(strtolower($beforeO), ['a', 'e', 'i', 'o', 'u'], true);
    }
}
