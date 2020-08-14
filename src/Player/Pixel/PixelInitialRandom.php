<?php
declare(strict_types=1);

namespace Simulation\Player\Pixel;

use Simulation\World\World;

/**
 * {@see PixelInitial}
 * A random pixel made for testing, various random simulations, etc.
 */
class PixelInitialRandom extends PixelInitial
{
    public function __construct(World $world)
    {
        $this->x = $world->getRandomPixelX();
        $this->y = $world->getRandomPixelY();
    }
}
