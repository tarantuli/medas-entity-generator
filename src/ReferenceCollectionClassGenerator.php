<?php

declare(strict_types=1);

namespace Medas\EntityGenerator;

use Medas\Core\Attributes\Service;

#[Service]
readonly class ReferenceCollectionClassGenerator
{
    private const string PHP_TEMPLATE
        = <<<'PHP'
<?php

declare(strict_types=1);

namespace {{namespace}};

use Medas\Core\Collections\ReferenceCollection;

/**
 * @extends ReferenceCollection<{{shortClassName}}>
 */
class {{shortClassName}}BackReferences extends ReferenceCollection
{
}

PHP;

    public function __construct(
        private ClassNameNormalizer $classNameNormalizer,
    )
    {
    }

    public function generate(string $className): string
    {
        $className = $this->classNameNormalizer->normalize($className);
        [$namespace, $shortClassName] = $this->splitClassName($className);

        $replacements = [
            '{{namespace}}' => $namespace,
            '{{shortClassName}}' => $shortClassName,
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            self::PHP_TEMPLATE
        );
    }

    private function splitClassName(string $className): array
    {
        $pos = strrpos($className, '\\');

        if ($pos === false) {
            throw new Exceptions\ClassHasNoNamespace($className);
        }

        return [
            substr($className, 0, $pos),
            substr($className, $pos + 1),
        ];
    }
}
