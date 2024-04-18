<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Task;

use App\Constant\Enum\TaskPart;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskSolutionTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    #[DataProviderExternal(TaskSolutionTestDataProvider::class, 'solve')]
    public function testSolve(string $dayClass, TaskPart $part, string $inputFile, string|int $answer): void
    {
        $service = self::getContainer()->get($dayClass);

        $result = match ($part) {
            TaskPart::A => $service->solveA($inputFile),
            TaskPart::B => $service->solveB($inputFile),
        };

        self::assertSame($answer, $result);
    }
}
