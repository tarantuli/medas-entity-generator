<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\ConsoleCommands;

use Medas\Console\Commands\{BaseConsoleCommand, CommandInput, ConsoleCommandGroup, Option, Range};
use Medas\Core\Attributes\Service;
use Medas\EntityGenerator\{EntityClassGenerator, FileNameFinder};

#[Service]
readonly class CreateEntityFile extends BaseConsoleCommand
{
    public function __construct(
        private EntityClassGenerator    $entityClassGenerator,
        private EntityGeneratorCommands $group,
        private FileNameFinder          $fileNameFinder,
    )
    {
    }

    public function group(): ConsoleCommandGroup
    {
        return $this->group;
    }

    public function name(): string
    {
        return 'create-entity-file';
    }

    public function aliases(): array
    {
        return ['c.entity'];
    }

    public function description(): string
    {
        return 'Creates an entity file for the given fully qualified class name';
    }

    public function allowedArgumentCount(): Range
    {
        return new Range(1);
    }

    public function options(): array
    {
        return [new Option('auto-id')];
    }

    public function process(CommandInput $input): void
    {
        $className = $input->getArgument(1);
        $code = $this->entityClassGenerator->generate($className, !$input->hasOption('auto-id'));
        $fileName = $this->fileNameFinder->find($className);

        $this->fileNameFinder->writeToFile($code, $fileName);
    }
}
