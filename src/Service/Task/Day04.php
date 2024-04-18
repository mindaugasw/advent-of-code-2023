<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Service\DataService;

readonly class Day04 implements TaskSolutionAInterface
{
    public function __construct(private DataService $dataService)
    {
    }

    public function solveA(string $inputFile = 'input.txt'): int
    {
        $sum = 0;

        foreach ($this->dataService->iterateLines("04/{$inputFile}") as $line) {
            $cardData = $this->parseCard($line);
            $cardScore = $this->calculateCardScore(...$cardData);
            $sum += $cardScore;
        }

        return $sum;
    }

    /**
     * @return array{
     *      winningNumbers: array<string, int>,
     *      cardNumbers: array<string, int>,
     * }
     */
    private function parseCard(string $line): array
    {
        preg_match_all('/Card[ ]+(\d+): ([\d ]+)\|([\d ]+)/i', $line, $matches);

        return [
            'winningNumbers' => array_flip(array_filter(explode(' ', $matches[2][0]))),
            'cardNumbers' => array_flip(array_filter(explode(' ', $matches[3][0]))),
        ];
    }

    /**
     * @param array<string, int> $winningNumbers
     * @param array<string, int> $cardNumbers
     */
    private function calculateCardScore(array $winningNumbers, array $cardNumbers): int
    {
        $winsCount = 0;

        foreach ($cardNumbers as $cardNumber => $index) {
            if (array_key_exists($cardNumber, $winningNumbers)) {
                $winsCount++;
            }
        }

        if ($winsCount === 0) {
            return 0;
        }

        return 2 ** ($winsCount - 1);
    }
}
