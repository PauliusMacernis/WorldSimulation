<?php
declare(strict_types=1);

namespace Simulation\World;

use LogicException;
use Simulation\Exception\PixelInitialIsMissing;
use Simulation\Exception\TheWorldIsFull;

final class Locator
{
    // Directions to be used when locating for the
    private const UP = 0;
    private const RIGHT = 1;
    private const DOWN = 2;
    private const LEFT = 3;
    private const DIRECTIONS_COUNT_IN_TOTAL = 4; // UP(0), RIGHT(1), DOWN(2), LEFT(3) , - 4 directions in total

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

        $directionForNow = $try % self::DIRECTIONS_COUNT_IN_TOTAL;
        $round = (int)floor($try / self::DIRECTIONS_COUNT_IN_TOTAL) + 1; // Rounds start from 1, not from 0. @TODO: Refactor to start from 0?
        $pixelAsABaseForTryingNow = $world->getPixelByPlayer($player, $round, null);
        if (null === $pixelAsABaseForTryingNow) {
            throw new LogicException(sprintf('Code flow error. Cannot find pixel for player ID:%s and round: %s. It must be here according to the flow.', $player->getId(), $round));
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
}
