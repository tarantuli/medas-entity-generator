<?php

declare(strict_types=1);

namespace Medas\EntityGenerator\ConsoleCommands;

use Medas\Console\Commands\{Argument, BaseConsoleCommand, CommandInput, ConsoleCommandGroup};
use Medas\Core\Attributes\Service;
use Medas\EntityGenerator\{FileNameFinder, FileWriter, ReferenceCollectionClassGenerator};

#[Service]
readonly class CreateReferenceCollectionFile extends BaseConsoleCommand
{
    public function __construct(
        private EntityGeneratorCommands           $group,
        private FileNameFinder                    $fileNameFinder,
        private FileWriter                        $fileWriter,
        private ReferenceCollectionClassGenerator $referenceCollectionClassGenerator,
    )
    {
    }

    public function group(): ConsoleCommandGroup
    {
        return $this->group;
    }

    public function name(): string
    {
        return 'create-reference-collection-file';
    }

    public function aliases(): array
    {
        return ['c.reference-collection'];
    }

    public function description(): string
    {
        return 'Creates a reference collection file for the given fully qualified class name';
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
        $code = $this->referenceCollectionClassGenerator->generate($className);
        $fileName = $this->fileNameFinder->find($className . 'BackReferences');

        $this->fileWriter->writeToFile($code, $fileName);
    }
}
