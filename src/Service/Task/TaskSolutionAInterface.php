<?php

declare(strict_types=1);

namespace App\Service\Task;

interface TaskSolutionAInterface extends TaskSolutionInterface
{
    public function solveA(): string|int;
}
