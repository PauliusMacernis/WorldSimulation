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
        $this->counterRound = 0;
        $this->counterTake = 0;
        $this->amountOfPixelsInWorld = $amountOfPixelsInWorld;
        $this->countFreePixels = $amountOfPixelsInWorld;
    }

    public function increasePixelsByPlayer(Player $player): void
    {
        if(!isset($this->totalPixelsTakenByEachPlayer[$player->getId()])) {
            $this->totalPixelsTakenByEachPlayer[$player->getId()] = 0;
        }
        ++$this->totalPixelsTakenByEachPlayer[$player->getId()];
        --$this->countFreePixels;
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
