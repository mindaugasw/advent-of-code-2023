<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Task;

use App\Constant\Enum\TaskPart;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskSolutionTest extends KernelTestCase
{
    private const string ENV_KEY_SKIP_HEAVY_TESTS = 'FAST';

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    #[DataProviderExternal(TaskSolutionTestDataProvider::class, 'solve')]
    public function testSolve(
        string $dayClass,
        TaskPart $part,
        string $inputFile,
        string|int $answer,
        bool $heavyTest = false,
    ): void
    {
        if ($heavyTest && getenv(self::ENV_KEY_SKIP_HEAVY_TESTS) !== false) {
            self::markTestSkipped();
        }

        $service = self::getContainer()->get($dayClass);

        $result = match ($part) {
            TaskPart::A => $service->solveA($inputFile),
            TaskPart::B => $service->solveB($inputFile),
        };

        self::assertSame($answer, $result);
    }
}
