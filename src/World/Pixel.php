<?php
declare(strict_types=1);

namespace Simulation\World;

/**
 * The World is made of parts called pixels.
 * Each pixel may have a player assigned to the pixel, also some other attributes defining the status of the player in that particular spot.
 */
class Pixel
{
    protected int $x;
    protected int $y;
    protected ?Player $player;
    private ?int $countRound;     // Increases each time ALL players take one pixel each
    private ?int $countTake;      // Increases each time ONE player takes the pixel


    public function __construct(int $x, int $y, ?Player $player, ?int $countRound, ?int $countTake)
    {
        $this->x = $x;
        $this->y = $y;
        $this->player = $player;
        $this->countRound = $countRound;
        $this->countTake = $countTake;
    }

    public function isEmpty(): bool
    {
        return $this->player === null;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function getCountRound(): ?int
    {
        return $this->countRound;
    }

    public function getCountTake(): ?int
    {
        return $this->countTake;
    }
}
