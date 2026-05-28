# medas-entity-generator

Part of the [Medas framework](https://github.com/tarantuli/medas-core).

## Description

Scaffolds entity and collection class files from the command line. Given a fully qualified class name, it generates a ready-to-use PHP file and writes it to the correct PSR-4 path derived from the project's Composer autoload configuration.

Two generators are provided:

| Generator                  | Output                                                                                                   |
|----------------------------|----------------------------------------------------------------------------------------------------------|
| `EntityClassGenerator`     | An `#[Entity]` class implementing `HasId` with either a `Uuid` or `int` id, using the `Timestamps` trait |
| `CollectionClassGenerator` | A typed `RecordCollection` subclass annotated with `#[EntityCollection]`                                 |

Store names are derived from the entity's short class name by pluralising it and converting to snake_case (via `SnakeCaseNames`).

`FileNameFinder` resolves the target file path by reading the PSR-4 prefix map from `vendor/autoload.php` at runtime, so it works for any namespace registered in `composer.json`. Class names may use forward slashes instead of backslashes, and a leading dot is expanded to the configured `root-namespace`.

`FileWriter` creates any missing parent directories before writing. If the target file already exists, it prints a warning and skips writing.

## Configuration options

| Option                            | Default | Description                                                            |
|-----------------------------------|---------|------------------------------------------------------------------------|
| `entity-generator.root-namespace` | none    | Namespace prefix substituted for a leading dot in class name arguments |

## Usage

### Package developer context

Register the package and use the generators programmatically:

```php
use Medas\EntityGenerator\EntityGeneratorPackage;

EntityGeneratorPackage::instance();
```

**Generating entity class code:**

```php
use Medas\EntityGenerator\EntityClassGenerator;
use Medas\Core\Attributes\Service;

#[Service]
readonly class MyScaffolder
{
    public function __construct(
        private EntityClassGenerator $entityClassGenerator,
    ) {}

    public function scaffold(): void
    {
        // UUID-based entity (default)
        $code = $this->entityClassGenerator->generate('MyApp\\Entities\\Invoice');

        // Auto-increment int id
        $code = $this->entityClassGenerator->generate('MyApp\\Entities\\Invoice', useUuid: false);

        echo $code;
    }
}
```

A UUID entity for `MyApp\Entities\Invoice` produces:

```php
<?php

declare(strict_types=1);

namespace MyApp\Entities;

use Medas\Core\Interfaces\{Uuid, HasId};
use Medas\EntityManager\Attributes\{Entity, Id};
use Medas\EntityManager\Traits\Timestamps;

#[Entity(store: 'invoices')]
class Invoice implements HasId
{
    use Timestamps;

    #[Id]
    public Uuid $id;

    public function id(): Uuid
    {
        return $this->id;
    }
}
```

**Generating collection class code:**

```php
use Medas\EntityGenerator\CollectionClassGenerator;

$code = $collectionClassGenerator->generate('MyApp\\Entities\\Invoice');
```

Produces:

```php
<?php

declare(strict_types=1);

namespace MyApp\Entities;

use Medas\EntityManager\Attributes\EntityCollection;
use Medas\StorageManager\Entities\RecordCollection;

/**
 * @extends RecordCollection<Invoice>
 */
#[EntityCollection(Invoice::class)]
class InvoiceCollection extends RecordCollection
{
}
```

**Resolving the target file path:**

```php
use Medas\EntityGenerator\FileNameFinder;

// Returns e.g., /var/www/src/Entities/Invoice.php
$path = $fileNameFinder->find('MyApp\\Entities\\Invoice');
```

**Writing a generated file:**

```php
use Medas\EntityGenerator\FileWriter;

$fileWriter->writeToFile($code, $path);
// Prints: created entity file /var/www/src/Entities/Invoice.php
// If the file already exists: file /var/www/src/Entities/Invoice.php already exists
```

### Backend user context

**Creating an entity file:**

```bash
# Full namespace with backslashes
php bin/console entity-generator:create-entity-file "MyApp\Entities\Invoice"

# Using forward slashes (equivalent)
php bin/console entity-generator:create-entity-file MyApp/Entities/Invoice

# With a configured root namespace (entity-generator.root-namespace = MyApp\Entities)
php bin/console entity-generator:create-entity-file .Invoice

# With an auto-increment integer id instead of UUID
php bin/console entity-generator:create-entity-file MyApp/Entities/Invoice --auto-id

# Short alias
php bin/console c.entity MyApp/Entities/Invoice
```

**Creating a collection file:**

```bash
php bin/console entity-generator:create-collection-file MyApp/Entities/Invoice
# Writes InvoiceCollection to the path resolved for MyApp\Entities\InvoiceCollection

# Short alias
php bin/console c.collection MyApp/Entities/Invoice
```

**Configuring the root namespace** — set this once in your project's YAML config so you can use the dot shorthand:

```yaml
entity-generator:
  root-namespace: MyApp\Entities
```

Then `c.entity ./Order` expands to `MyApp\Entities\Order`.
