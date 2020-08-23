<?php
declare(strict_types=1);

namespace Simulation\World;

/**
 * Optimizes actions in the World.
 */
class WorldPerformance
{
    /**
     * Array keys - player IDs, values - counts of pixels taken in total
     * @var int[]
     */
    private array $totalPixelsTakenByEachPlayer;

    /**
     * Array keys - player IDs, values - Pixel info
     * @var Pixel[]
     */
    private array $lastPixelTakenByEachPlayer;

    /**
     * Defines the round (aka. cycle, iteration, etc.) the world is at.
     * The round increases after ALL players did the move once.
     */
    private int $counterRound;

    /**
     * Defines the total count of actions already performed by Players in the world.
     */
    private int $counterTake;
    private int $amountOfPixelsInWorld;
    private int $countFreePixels;

    public function __construct(int $amountOfPixelsInWorld)
    {
        $this->totalPixelsTakenByEachPlayer = [];
        $this->lastPixelTakenByEachPlayer = [];
        $this->counterRound = 0;
        $this->counterTake = 0;
        $this->amountOfPixelsInWorld = $amountOfPixelsInWorld;
        $this->countFreePixels = $amountOfPixelsInWorld;
    }

    public function increasePixelsByPlayer(Player $player, Pixel $pixel): void
    {
        if(!isset($this->totalPixelsTakenByEachPlayer[$player->getId()])) {
            $this->totalPixelsTakenByEachPlayer[$player->getId()] = 0;
        }
        ++$this->totalPixelsTakenByEachPlayer[$player->getId()];
        --$this->countFreePixels;

        if(null !== $pixel) {
            $this->setLastPixelTakenByEachPlayer($player, $pixel);
        }
    }

    private function setLastPixelTakenByEachPlayer(Player $player, Pixel $pixel) {
        if(!isset($this->lastPixelTakenByEachPlayer[$player->getId()])) {
            $this->lastPixelTakenByEachPlayer[$player->getId()] = null;
        }
        $this->lastPixelTakenByEachPlayer[$player->getId()] = $pixel;
    }

    public function getLastPixelTakenByEachPlayer(Player $player): Pixel
    {
        return $this->lastPixelTakenByEachPlayer[$player->getId()];
    }

    public function isPlayerInPixels(Player $player): bool
    {
        return isset($this->totalPixelsTakenByEachPlayer[$player->getId()]);
    }

    public function getCounterRound(): int
    {
        return $this->counterRound;
    }

    public function increaseCounterRound(): void
    {
        ++$this->counterRound;
    }

    public function getCounterTaken(): int
    {
        return $this->counterTake;
    }

    public function increaseCounterTakenAsReservation(): void
    {
        ++$this->counterTake;
    }

    public function getCountFreePixels(): int
    {
        return $this->countFreePixels;
    }
}
