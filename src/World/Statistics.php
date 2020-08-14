<?php
declare(strict_types=1);

namespace Simulation\World;

/**
 * Various statistical operations observing the situation in The World.
 * E.g. Is the particular player in The World? Is there any free space left in The World? etc.
 */
class Statistics
{
    public function isFreeSpaceFoundIn(World $world): bool
    {
        foreach ($world->getData() as $row) {
            foreach ($row as $pixelData) {
                if ($pixelData->isEmpty()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isPlayerInWorld(Player $player, World $world, ?int $specificRound, ?int $specificTake): bool
    {
        foreach ($world->getData() as $row) {
            foreach ($row as $pixelData) {
                if (null === $pixelData->getPlayer()) {
                    continue;
                }
                if (
                    ($specificRound === null || $specificRound === $pixelData->getCountRound())
                    && ($specificTake === null || $specificTake === $pixelData->getCountTake())
                    && $pixelData->getPlayer()->getId() === $player->getId()
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}
