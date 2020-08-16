<?php
declare(strict_types=1);

namespace Simulation\World;

class FreedomLimitCompensation
{
    public function getCompensation(): int
    {
        // At the moment we simply compensate by 1 per freedom violation case.
        return 1;
    }
}
