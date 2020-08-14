<?php
declare(strict_types=1);

namespace Simulation\World;

use Simulation\Exception\UniquePlayersListViolation;

/**
 * The list of Players that acts on the surface of The World.
 * This list is unique in the sense of initial pixels of players.
 * In other words, this list assures the unique initial place in The World for each of players.
 */
class PlayersUnique
{
    /** @var Player[] */
    private array $data;

    public function __construct(Player ...$players)
    {
        $this->data = [];
        $yxRegistry = [];

        foreach ($players as $player) {
            if (isset($yxRegistry[$player->getPixelInitial()->getY()][$player->getPixelInitial()->getX()])) {
                throw new UniquePlayersListViolation(sprintf('The list of players must be unique in the sense of the initial pixels. Initial pixel collision detected. X:%s Y:%s , players: Old:%s vs. New:%s', $player->getPixelInitial()->getX(), $player->getPixelInitial()->getY(), $yxRegistry[$player->getPixelInitial()->getY()][$player->getPixelInitial()->getX()]->getId(), $player->getId()));
            }
            $yxRegistry[$player->getPixelInitial()->getY()][$player->getPixelInitial()->getX()] = $player;
            $this->data[] = $player;
        }
        unset($yxRegistry);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
