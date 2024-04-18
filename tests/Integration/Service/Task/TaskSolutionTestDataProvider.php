<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Task;

use App\Constant\Enum\TaskPart;
use App\Service\Task\Day01;

class TaskSolutionTestDataProvider
{
    public static function solve(): array
    {
        return [
            '01-A example' => [
                'dayClass' => Day01::class,
                'part' => TaskPart::A,
                'inputFile' => 'exampleA.txt',
                'answer' => 142,
            ],
            '01-A' => [
                'dayClass' => Day01::class,
                'part' => TaskPart::A,
                'inputFile' => 'input.txt',
                'answer' => 54927,
            ],
            '01-B example' => [
                'dayClass' => Day01::class,
                'part' => TaskPart::B,
                'inputFile' => 'exampleB.txt',
                'answer' => 281,
            ],
            '01-B' => [
                'dayClass' => Day01::class,
                'part' => TaskPart::B,
                'inputFile' => 'input.txt',
                'answer' => 54581,
            ],
        ];
    }
}
