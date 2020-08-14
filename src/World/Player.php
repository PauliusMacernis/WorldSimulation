<?php
declare(strict_types=1);

namespace Simulation\World;


use Simulation\Player\Pixel\PixelInitial;

/**
 * The one who owns or not a Pixel in The World.
 */
class Player
{
    /** @var string|null Null in case Player is not set. String otherwise. */
    private ?string $id;
    private PixelInitial $pixelInitial;

    public function __construct(?string $id, PixelInitial $pixelInitial)
    {
        $this->id = $id;
        $this->pixelInitial = $pixelInitial;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPixelInitial(): PixelInitial
    {
        return $this->pixelInitial;
    }


}
