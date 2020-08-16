<?php
declare(strict_types=1);

namespace Simulation\World;

use Simulation\Exception\PixelInitialIsMissing;
use Simulation\Exception\TheWorldIsFull;

final class Locator
{
    // Relative directions to be used when locating for the pixel from the pixel.
    private const UP = 0;
    private const RIGHT = 1;
    private const DOWN = 2;
    private const LEFT = 3;
    private const DIRECTIONS_COUNT_IN_TOTAL = 4; // UP(0), RIGHT(1), DOWN(2), LEFT(3) , - 4 directions in total

    private const ROUNDS_START_FROM_NUMBER = 1; // The number value of the first round
    private const TRY_STARTS_FROM_NUMBER = 1; // The number value of the first try


    /**
     * There are 4 ways to go from each pixel: UP, RIGHT, DOWN, LEFT. Then we have to switch to another pixel as a "base for lookup from" point.
     * Where we are at the time depends on the $try value. $try increases each time we need to switch for another direction or another "base for lookup from" point (in case all 4 directions tried).
     * Therefore...
     *
     */
    public function locateNextPointToTakeBasedOnInitial(Player $player, World $world, Statistics $statistics, int $counterRound, int $counterTake, int $try): ?Pixel
    {
        if (false === $statistics->isPlayerInWorld($player, $world, null, null)) {
            throw new PixelInitialIsMissing(sprintf('The World is missing initial pixel (aka. home) of the player ID: %s', $player->getId()));
        }

        if (false === $statistics->isFreeSpaceFoundIn($world)) {
            throw new TheWorldIsFull('The World is full. it is useless to go for locating another free spot.');
        }

        $directionForNow = $this->getDirectionConvertedFromTry($try);
        $roundForNow = $this->getRoundConvertedFromTry($try);

        $pixelAsABaseForTryingNow = $world->getPixelByPlayer($player, $roundForNow, null);

        if (null === $pixelAsABaseForTryingNow) {
            return null;
            //throw new LogicException(sprintf('Code flow error. Cannot find pixel for player ID:%s and round: %s, try: %s. It must be here according to the flow.', $player->getId(), $roundForNow, $try));
        }

        $xNewTerritory = $pixelAsABaseForTryingNow->getX();
        $yNewTerritory = $pixelAsABaseForTryingNow->getY();

        switch ($directionForNow) {
            case self::UP:
                --$yNewTerritory;
                break;
            case self::RIGHT:
                ++$xNewTerritory;
                break;
            case self::DOWN:
                ++$yNewTerritory;
                break;
            case self::LEFT:
                --$xNewTerritory;
                break;
        }

        if (false === $world->isItInsideTheWorldBoundaries($xNewTerritory, $yNewTerritory)) {
            return null;
        }

        $pixel = $world->getPixelByXY($xNewTerritory, $yNewTerritory);
        if (false === $pixel->isEmpty()) {
            return null;
        }

        return $pixel;
    }

    public function getRoundConvertedFromTry(int $try): int
    {
        return (int)floor($try / self::DIRECTIONS_COUNT_IN_TOTAL) + self::ROUNDS_START_FROM_NUMBER;
    }

    private function getDirectionConvertedFromTry(int $try): int
    {
        return ($try - self::TRY_STARTS_FROM_NUMBER) % self::DIRECTIONS_COUNT_IN_TOTAL;
    }
}
