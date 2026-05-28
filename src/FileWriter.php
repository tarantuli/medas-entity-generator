<?php

declare(strict_types=1);

namespace Medas\EntityGenerator;

use Medas\Console\{Formats\SafeColor, Printer, Text};
use Medas\Core\Attributes\Service;
use Medas\FileSystem\DirectoryCreator;

#[Service]
readonly class FileWriter
{
    public function __construct(
        private DirectoryCreator $directoryCreator,
        private Printer|null     $printer,
    )
    {
    }

    public function writeToFile(string $code, string $fileName): void
    {
        $this->directoryCreator->create(dirname($fileName));

        if (file_exists($fileName)) {
            $this->printer?->printLine(new Text('file ' . $fileName . ' already exists', SafeColor::Gray));
        }
        elseif (file_put_contents($fileName, $code)) {
            $this->printer?->printLine(
                new Text('created entity file '),
                new Text($fileName, SafeColor::LightYellow)
            );
        }
        elseif ($this->printer) {
            $this->printer->printLine(new Text('could not creat entity file ' . $fileName, SafeColor::Red));
        }
    }
}
