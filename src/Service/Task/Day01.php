<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Service\DataService;

/**
 * @link https://adventofcode.com/2023/day/1
 */
readonly class Day01 implements TaskSolutionAInterface, TaskSolutionBInterface
{
    private const array NUMBER_MAP = [
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5,
        'six' => 6,
        'seven' => 7,
        'eight' => 8,
        'nine' => 9,
    ];
    private const array REPLACE_MAP = [
        'one' => 'o1e',
        'two' => 't2o',
        'three' => 't3e',
        'four' => 'f4r',
        'five' => 'f5e',
        'six' => 's6x',
        'seven' => 's7n',
        'eight' => 'e8t',
        'nine' => 'n9e',
    ];

    public function __construct(private DataService $dataService)
    {
    }

    public function solveA(string $inputFile = 'input.txt'): int
    {
        $sum = 0;

        foreach ($this->dataService->iterateLines("01/{$inputFile}") as $line) {
            preg_match('/^([a-z]*)(\d)/i', $line, $firstMatches);
            $firstNumber = $firstMatches[2];

            preg_match('/(\d)([a-z]*)$/i', $line, $lastMatches);
            $lastNumber = $lastMatches[1];

            $number = (int) ($firstNumber . $lastNumber);
            $sum += $number;
        }

        return $sum;
    }

    public function solveB(string $inputFile = 'input.txt'): int
    {
        $sum = 0;
        $pattern = '/\d|' . implode('|', array_keys(self::NUMBER_MAP)) . '/i';

        foreach ($this->dataService->iterateLines("01/{$inputFile}") as $line) {
            // Fix for overlapping numbers (e.g. oneight). By default, regex would match only the first one.
            // So we replace it with o1eight, to allow matching both 1 and 8
            $lineFixed = str_replace(array_keys(self::REPLACE_MAP), array_values(self::REPLACE_MAP), $line);

            preg_match_all($pattern, $lineFixed, $matches, PREG_OFFSET_CAPTURE);
            $matches = $matches[0];

            $firstDigit = $this->parseNumber($matches[0][0]);
            $lastDigit = $this->parseNumber($matches[count($matches) - 1][0]);

            $number = (int) ($firstDigit . $lastDigit);
            $sum += $number;
        }

        return $sum;
    }

    private function parseNumber(string $text): int
    {
        if (array_key_exists($text, self::NUMBER_MAP)) {
            return self::NUMBER_MAP[$text];
        }

        return (int) $text;
    }
}
