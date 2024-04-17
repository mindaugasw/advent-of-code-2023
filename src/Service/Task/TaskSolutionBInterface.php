<?php

declare(strict_types=1);

namespace App\Service\Task;

interface TaskSolutionBInterface extends TaskSolutionInterface
{
    public function solveB(): string|int;
}
