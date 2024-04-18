<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Service\DataService;

/**
 * @link https://adventofcode.com/2023/day/2
 */
readonly class Day02 implements TaskSolutionAInterface, TaskSolutionBInterface
{
    public function __construct(private DataService $dataService)
    {
    }

    public function solveA(string $inputFile = 'input.txt'): int
    {
        $availableCubes = [
            'red' => 12,
            'green' => 13,
            'blue' => 14,
        ];
        $sum = 0;

        foreach ($this->dataService->iterateInputLines("02/{$inputFile}") as $line) {
            ['gameId' => $gameId, 'sets' => $sets] = $this->parseGameLine($line);
            $validGame = true;

            foreach ($sets as $set) {
                foreach ($set as $color => $count) {
                    if ($availableCubes[$color] < $count) {
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

    public function solveB(string $inputFile = 'input.txt'): int
    {
        $sum = 0;

        foreach ($this->dataService->iterateInputLines("02/{$inputFile}") as $line) {
            $minimumCubes = [
                'red' => 0,
                'green' => 0,
                'blue' => 0,
            ];

            ['gameId' => $gameId, 'sets' => $sets] = $this->parseGameLine($line);

            foreach ($sets as $set) {
                foreach ($set as $color => $count) {
                    if ($minimumCubes[$color] < $count) {
                        $minimumCubes[$color] = $count;
                    }
                }
            }

            $gamePower = $minimumCubes['red'] * $minimumCubes['green'] * $minimumCubes['blue'];
            $sum += $gamePower;
        }

        return $sum;
    }

    /**
     * @return array{
     *      gameId: int,
     *      sets: list<array<string, int>>,
     * }
     */
    private function parseGameLine(string $line): array
    {
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

        return [
            'gameId' => $gameId,
            'sets' => $sets,
        ];
    }
}
