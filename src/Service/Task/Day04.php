<?php

declare(strict_types=1);

namespace App\Service\Task;

use App\Service\DataService;

readonly class Day04 implements TaskSolutionAInterface,TaskSolutionBInterface
{
    public function __construct(private DataService $dataService)
    {
    }

    public function solveA(string $inputFile = 'input.txt'): int
    {
        $sum = 0;

        foreach ($this->dataService->iterateLines("04/{$inputFile}") as $line) {
            $cardData = $this->parseCard($line);
            $cardScore = $cardData['winsCount'] === 0
                ? 0
                : 2 ** ($cardData['winsCount'] - 1);

            $sum += $cardScore;
        }

        return $sum;
    }

    public function solveB(string $inputFile = 'input.txt'): int
    {
        $lines = $this->dataService->getAllLines("04/{$inputFile}");
        $cards = array_fill(1, count($lines), 1);

        foreach ($lines as $line) {
            $cardData = $this->parseCard($line);
            $currentCardCount = $cards[$cardData['cardId']];

            for ($i = 0; $i < $currentCardCount; $i++) {
                for ($j = 1; $j <= $cardData['winsCount']; $j++) {
                    $nextCardId = $cardData['cardId'] + $j;

                    if (array_key_exists($nextCardId, $cards)) {
                        $cards[$nextCardId]++;
                    }
                }
            }
        }
        
        return array_sum($cards);
    }

    /**
     * @return array{
     *      cardId: int,
     *      winningNumbers: array<string, int>,
     *      cardNumbers: array<string, int>,
     *      winsCount: int,
     * }
     */
    private function parseCard(string $line): array
    {
        preg_match_all('/Card[ ]+(\d+): ([\d ]+)\|([\d ]+)/i', $line, $matches);

        $data = [
            'cardId' => (int) $matches[1][0],
            'winningNumbers' => array_flip(array_filter(explode(' ', $matches[2][0]))),
            'cardNumbers' => array_flip(array_filter(explode(' ', $matches[3][0]))),
        ];
        $data['winsCount'] = $this->calculateCardWinsCount($data['winningNumbers'], $data['cardNumbers']);

        return $data;
    }

    /**
     * @param array<string, int> $winningNumbers
     * @param array<string, int> $cardNumbers
     */
    private function calculateCardWinsCount(array $winningNumbers, array $cardNumbers): int
    {
        $winsCount = 0;

        foreach ($cardNumbers as $cardNumber => $index) {
            if (array_key_exists($cardNumber, $winningNumbers)) {
                $winsCount++;
            }
        }

        return $winsCount;
    }
}
