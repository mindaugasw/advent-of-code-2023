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

    public function iterateLines(string $inputFile): \Generator
    {
        $file = new \SplFileObject("{$this->dataDirectory}/{$inputFile}");

        while (!$file->eof()) {
            $line = trim($file->fgets());

            if (empty($line)) {
                continue;
            }

            yield $line;
        }
    }

    /**
     * @return string[]
     */
    public function getAllLines(string $inputFile): array
    {
        return iterator_to_array($this->iterateLines($inputFile));
    }
}
