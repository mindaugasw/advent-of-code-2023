<?php

declare(strict_types=1);

namespace App\Command;

use App\Constant\Enum\TaskPart;
use App\Service\Task\TaskSolutionAInterface;
use App\Service\Task\TaskSolutionBInterface;
use App\Service\Task\TaskSolutionInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:task')]
class TaskCommand extends Command
{
    private const string ARGUMENT_DAY = 'day';
    private const string ARGUMENT_PART = 'part';

    private readonly SymfonyStyle $io;
    private readonly int $taskNumber;
    private readonly TaskPart $taskPart;

    public function __construct(
        /** @var iterable<TaskSolutionInterface> */
        private readonly iterable $taskSolutions,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                self::ARGUMENT_DAY,
                InputArgument::REQUIRED,
                'Day or task number which to solve',
            )
            ->addArgument(
                self::ARGUMENT_PART,
                InputArgument::OPTIONAL,
                'Task part to solve: [A|B]. If not provided, will solve both',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->validateInput($input);
        $solutionService = $this->findTaskSolutionService($this->taskNumber);

        $this->solveTaskPart($solutionService, TaskSolutionAInterface::class, 'solveA', TaskPart::A);
        $this->solveTaskPart($solutionService, TaskSolutionBInterface::class, 'solveB', TaskPart::B);

        return Command::SUCCESS;
    }

    private function validateInput(InputInterface $input): void
    {
        $dayArgument = $input->getArgument(self::ARGUMENT_DAY);
        $this->taskNumber = (int) $dayArgument;

        if ($dayArgument !== (string) $this->taskNumber) {
            throw new InvalidArgumentException("Invalid task number argument \"{$dayArgument}\"");
        }

        $part = strtoupper($input->getArgument(self::ARGUMENT_PART) ?? '');

        $this->taskPart = match ($part) {
            'A' => TaskPart::A,
            'B' => TaskPart::B,
            '' => TaskPart::Both,
            default => throw new InvalidArgumentException("Invalid task part argument \"$part\""),
        };
    }

    private function findTaskSolutionService(int $taskNumber): TaskSolutionAInterface|TaskSolutionBInterface {
        $targetClassName = sprintf('Day%02d', $taskNumber);

        foreach ($this->taskSolutions as $solutionService) {
            if (str_ends_with($solutionService::class, $targetClassName)) {
                return $solutionService;
            }
        }

        throw new InvalidArgumentException("Solution for task \"{$taskNumber}\" does not exist");
    }

    private function solveTaskPart(
        TaskSolutionInterface $solutionService,
        string $solutionInterface,
        string $method,
        TaskPart $part,
    ): void {
        if ($this->taskPart !== $part && $this->taskPart !== TaskPart::Both) {
            $this->io->text("<comment>Skipping {$this->taskNumber}-{$part->name}</comment>");

            return;
        }

        if ($solutionService instanceof $solutionInterface) {
            $this->io->text("<info>Solving {$this->taskNumber}-{$part->name} ...</info>");

            $result = $solutionService->$method();

            $this->io->text((string) $result);

            return;
        }

        $this->io->text("<comment>Solution for {$this->taskNumber}-{$part->name} does not exist</comment>");
    }
}
