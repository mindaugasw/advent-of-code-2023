<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Task;

use App\Constant\Enum\TaskPart;
use App\Service\Task\Day01;
use App\Service\Task\Day02;

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
            '02-A example' => [
                'dayClass' => Day02::class,
                'part' => TaskPart::A,
                'inputFile' => 'example.txt',
                'answer' => 8,
            ],
            '02-A' => [
                'dayClass' => Day02::class,
                'part' => TaskPart::A,
                'inputFile' => 'input.txt',
                'answer' => 2317,
            ],
            '02-B example' => [
                'dayClass' => Day02::class,
                'part' => TaskPart::B,
                'inputFile' => 'example.txt',
                'answer' => 2286,
            ],
            '02-B' => [
                'dayClass' => Day02::class,
                'part' => TaskPart::B,
                'inputFile' => 'input.txt',
                'answer' => 74804,
            ],
        ];
    }
}
