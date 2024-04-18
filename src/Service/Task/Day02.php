<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Service\DataService;

/**
 * @link https://adventofcode.com/2023/day/2
 */
readonly class Day02 implements TaskSolutionAInterface
{
    private const array AVAILABLE_CUBES = [
        'red' => 12,
        'green' => 13,
        'blue' => 14,
    ];

    public function __construct(private DataService $dataService)
    {
    }

    public function solveA(string $inputFile = 'input.txt'): int
    {
        $sum = 0;

        foreach ($this->dataService->iterateInputLines("02/{$inputFile}") as $line) {
            preg_match('/Game (\d+): (.+)/i', $line, $matches);
            $gameId = (int) $matches[1];

            $sets = explode(';', $matches[2]);
            /** @var list<array<string, int>> $sets */
            $sets = array_map(function (string $set) {
                $set = explode(',', $set);
                $set = array_map(function (string $cubes) {
                    $explodedCubes = explode(' ', trim($cubes));
                    $explodedCubes[0] = (int) $explodedCubes[0];

                    return $explodedCubes;
                }, $set);

                return array_column($set, 0, 1);
            }, $sets);

            $validGame = true;

            foreach ($sets as $set) {
                foreach ($set as $color => $count) {
                    if (self::AVAILABLE_CUBES[$color] < $count) {
                        $validGame = false;

                        break 2;
                    }
                }
            }

            if ($validGame) {
                $sum += $gameId;
            }
        }

        return $sum;
    }
}
