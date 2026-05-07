<?php

declare(strict_types=1);

namespace Medas\EntityGeneratorTest\MockUps;

use Medas\EntityManager\Attributes\{Entity, Id, IsUnique};

#[Entity('mock_entities')]
class MockEntity
{
    #[Id]
    private int $id;

    #[IsUnique]
    public string $name;

    public function id(): int
    {
        return $this->id;
    }
}
