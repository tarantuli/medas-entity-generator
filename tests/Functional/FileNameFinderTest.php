<?php

declare(strict_types=1);

namespace Medas\EntityGeneratorTest\Functional;

use Medas\EntityGenerator\FileNameFinder;
use PHPUnit\Framework\TestCase;

class FileNameFinderTest extends TestCase
{
    public function testCorrectFileNameGeneration(): void
    {
        $fileName = service(FileNameFinder::class)->find('Medas\EntityGeneratorTest\MockUps\EntityName');

        self::assertStringContainsString('MockUps\EntityName', $fileName);
    }
}
