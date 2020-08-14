<?php
declare(strict_types=1);

namespace Simulation\Exception;

use RuntimeException;

/**
 * Exception to be thrown in case Player do not have initial pixel (aka. "home pixel") set in The World.
 */
class PixelInitialIsMissing extends RuntimeException
{

}
