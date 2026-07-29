<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\ConsoleCommands;

use Medas\Console\Commands\{
    Argument,
    BaseConsoleCommand,
    CommandInput,
    ConsoleCommandGroup,
    Option
};
use Medas\Core\Attributes\Service;
use Medas\EntityGenerator\{EntityClassGenerator, FileNameFinder, FileWriter};

#[Service]
readonly class CreateEntityFile extends BaseConsoleCommand
{
    public function __construct(
        private EntityClassGenerator    $entityClassGenerator,
        private EntityGeneratorCommands $group,
        private FileNameFinder          $fileNameFinder,
        private FileWriter              $fileWriter,
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

    public function options(): array
    {
        return [new Option('auto-id')];
    }

    public function arguments(): array
    {
        return [
            Argument::required('className'),
        ];
    }

    public function process(CommandInput $input): void
    {
        $className = $input->getArgument('className');
        $code = $this->entityClassGenerator->generate($className, !$input->hasOption('auto-id'));
        $fileName = $this->fileNameFinder->find($className);

        $this->fileWriter->writeToFile($code, $fileName);
    }
}
