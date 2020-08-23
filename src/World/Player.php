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
    private int $latestTry;
    private int $roundsPlayed;
    private int $roundsPlayedInFreedom;
    private int $roundsPlayedInFreedomCompensation;

    public function __construct(?string $id, PixelInitial $pixelInitial)
    {
        $this->id = $id;
        $this->pixelInitial = $pixelInitial;
        $this->latestTry = 0;
        $this->roundsPlayed = 0;
        $this->roundsPlayedInFreedom = 0;
        $this->roundsPlayedInFreedomCompensation = 0;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPixelInitial(): PixelInitial
    {
        return $this->pixelInitial;
    }

    public function getLatestTry(): int
    {
        return $this->latestTry;
    }

    public function setLatestTry(int $latestTry): void
    {
        $this->latestTry = $latestTry;
    }

    public function getRoundsPlayed(): int
    {
        return $this->roundsPlayed;
    }

    public function increaseRoundsPlayed(): void
    {
        ++$this->roundsPlayed;
    }

    public function getRoundsPlayedInFreedom(): int
    {
        return $this->roundsPlayedInFreedom;
    }

    public function increaseRoundsPlayedInFreedom(): void
    {
        ++$this->roundsPlayedInFreedom;
    }

    public function increaseRoundsPlayedInFreedomCompensationBy(int $amount): void
    {
        $this->roundsPlayedInFreedomCompensation += $amount;
    }

    public function getRoundsPlayedInFreedomCompensation(): int
    {
        return $this->roundsPlayedInFreedomCompensation;
    }
}
