<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Service\DataService;

class Day03 implements TaskSolutionAInterface
{
    public function __construct(private DataService $dataService)
    {
    }

    public function solveA(string $inputFile = 'input.txt'): int
    {
        $schematic = $this->dataService->getAllLines("03/{$inputFile}");
        $sum = 0;
        for ($i = 0; $i < count($schematic); $i++) {
            preg_match_all('/\d+/i', $schematic[$i], $matchedNumbers, PREG_OFFSET_CAPTURE);
            $matchedNumbers = $matchedNumbers[0];

            foreach ($matchedNumbers as $numberData) {
                $isPartNumber = $this->containsAdjacentSymbol($schematic, $numberData[0], $i, $numberData[1]);

                if ($isPartNumber) {
                    $sum += (int) $numberData[0];
                }
            }
        }

        return $sum;
    }

    /**
     * Calculates if a given number contains any adjacent symbol, including diagonally
     *
     * @param string[] $schematic
     * @param int $line Number position: zero-based line index in the schematic
     * @param int $position Number position: zero-based offset on the line
     */
    private function containsAdjacentSymbol(array $schematic, string $number, int $line, int $position): bool
    {
        $positionBefore = max(0, $position - 1);
        $positionAfter = min(strlen($schematic[0]), $position + strlen($number) + 1);
        $textBlockLength = $positionAfter - $positionBefore;
        $textBlock = [];

        if ($line !== 0) {
            $textBlock[] = substr($schematic[$line - 1], $positionBefore, $textBlockLength);
        }

        $middleBlockPart = substr($schematic[$line], $positionBefore, $textBlockLength);
        $middleBlockPart = str_replace($number, '', $middleBlockPart);
        $textBlock[] = $middleBlockPart;

        if ($line !== count($schematic) - 1) {
            $textBlock[] = substr($schematic[$line + 1], $positionBefore, $textBlockLength);
        }

        $textBlockFlat = implode('', $textBlock);
        $textBlockFlat = trim($textBlockFlat, '.');

        return strlen($textBlockFlat) !== 0;
    }
}
