<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Service\DataService;

class Day03 implements TaskSolutionAInterface, TaskSolutionBInterface
{
    /**
     * Gears list. Key is gear position "line;offset",
     * value is numbers that are adjacent to that gear
     *
     * @var array<string, int[]>
     */
    private array $gears = [];

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

    public function solveB(string $inputFile = 'input.txt'): int
    {
        // To parse schematic and log all gear locations, we first solve part A
        $this->solveA($inputFile);

        $sum = 0;

        foreach ($this->gears as $gearSet) {
            if (count($gearSet) < 2) {
                continue;
            }

            if (count($gearSet) > 2) {
                throw new \Exception('Found a single gear between more than 2 parts');
            }

            $gearRatio = $gearSet[0] * $gearSet[1];
            $sum += $gearRatio;
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
            $upperBlockPart = substr($schematic[$line - 1], $positionBefore, $textBlockLength);
            $textBlock[] = $upperBlockPart;
            $this->logGears($upperBlockPart, $line - 1, $positionBefore, $number);
        }

        $middleBlockPart = substr($schematic[$line], $positionBefore, $textBlockLength);
        $this->logGears($middleBlockPart, $line, $positionBefore, $number);
        $middleBlockPart = str_replace($number, '', $middleBlockPart);
        $textBlock[] = $middleBlockPart;

        if ($line !== count($schematic) - 1) {
            $lowerBlockPart = substr($schematic[$line + 1], $positionBefore, $textBlockLength);
            $this->logGears($lowerBlockPart, $line + 1, $positionBefore, $number);
            $textBlock[] = $lowerBlockPart;
        }

        $textBlockFlat = implode('', $textBlock);
        $textBlockFlat = trim($textBlockFlat, '.');

        return strlen($textBlockFlat) !== 0;
    }

    /**
     * @param string $text A single line of block text (1-cell adjacent text, next to a number)
     */
    private function logGears(string $text, int $line, int $blockStartPosition, string $number): void
    {
        preg_match_all('/\*/', $text, $matches, PREG_OFFSET_CAPTURE);
        $matches = $matches[0];

        foreach ($matches as $match) {
            $totalOffset = $blockStartPosition + $match[1];
            $gearLocation = "{$line};{$totalOffset}";
            $this->gears[$gearLocation] ??= [];
            $this->gears[$gearLocation][] = (int) $number;
        }
    }
}
