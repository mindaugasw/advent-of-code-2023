<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Service\DataService;

/**
 * @link https://adventofcode.com/2023/day/1
 */
readonly class Day01 implements TaskSolutionAInterface
{
    public function __construct(private DataService $dataService)
    {
    }

    public function solveA(): int
    {
        $sum = 0;

        foreach ($this->dataService->iterateInputLines('01/input.txt') as $line) {
            preg_match('/^([a-z]*)(\d)/i', $line, $firstMatches);
            $firstNumber = $firstMatches[2];

            preg_match('/(\d)([a-z]*)$/i', $line, $lastMatches);
            $lastNumber = $lastMatches[1];

            $number = (int) ($firstNumber . $lastNumber);
            $sum += $number;
        }

        return $sum;
    }
}
