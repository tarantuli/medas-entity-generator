<?php

declare(strict_types=1);

namespace Medas\EntityGeneratorTest\Functional;

use Medas\EntityGenerator\EntityClassGenerator;
use Medas\EntityGeneratorTest\MockUps\MockEntity;
use PHPUnit\Framework\TestCase;

class EntityClassGeneratorTest extends TestCase
{
    const string MOCK_ENTITY_EXPECTED_CLASS_CONTENT
        = <<<'PHP'
<?php

declare(strict_types=1);

namespace Medas\EntityGeneratorTest\MockUps;

use Medas\Core\Interfaces\{Uuid, HasId};
use Medas\EntityManager\Attributes\{Entity, Id};
use Medas\EntityManager\Traits\Timestamps;

#[Entity(store: 'mock_entities')]
class MockEntity implements HasId
{
    use Timestamps;

    #[Id]
    public Uuid $id;

    public function id(): Uuid
    {
        return $this->id;
    }
}

PHP;

    public function testSimpleGeneration(): void
    {
        $content = service(EntityClassGenerator::class)->generate(MockEntity::class);
        $expected = self::MOCK_ENTITY_EXPECTED_CLASS_CONTENT;

        self::assertEquals($expected, $content);
    }

    public function testForwardSlashes(): void
    {
        $content = service(EntityClassGenerator::class)->generate(str_replace('\\', '/', MockEntity::class));
        $expected = self::MOCK_ENTITY_EXPECTED_CLASS_CONTENT;

        self::assertEquals($expected, $content);
    }

    public function testRootNamespace(): void
    {
        $content = service(EntityClassGenerator::class)->generate('./MockUps/MockEntity');
        $expected = self::MOCK_ENTITY_EXPECTED_CLASS_CONTENT;

        self::assertEquals($expected, $content);
    }
}
