<?php
declare(strict_types=1);

namespace Simulation\Player\Pixel;

/**
 * The initial pixel of a Player.
 * Kind of "the base" (the home?) of each player.
 */
class PixelInitial
{
    protected int $x;
    protected int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}
