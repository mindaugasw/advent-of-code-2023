<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class DataService
{
    public function __construct(
        #[Autowire('%app.data_directory%')]
        private string $dataDirectory
    ) {
    }

    public function iterateInputLines(string $inputFile): \Generator
    {
        $file = new \SplFileObject("{$this->dataDirectory}/{$inputFile}");

        while (!$file->eof()) {
            $line = $file->fgets();

            if (empty($line)) {
                continue;
            }

            yield $line;
        }
    }
}
