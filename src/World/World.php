<?php
declare(strict_types=1);

namespace Simulation\World;

use Simulation\Exception\FreedomLimit;
use Simulation\Exception\TheWorldIsFull;

/**
 * This is The World made of Pixels Players play in.
 * One Pixel may be owned by one Player only, the ownership may change over time.
 * The initial pixel of each player is kind of "base" (home?) of each player and should not change over time no matter what.
 *
 * This exact territory of The World is made in the form of rectangle filled by pixels.
 * X - represents the width of the territory "columns", starting from the left side (MIN_X) up to the right (MAX_X).
 * Y - represents the height of the territory "rows", starting from the top (MIN_Y) up to the bottom (MAX_Y).
 */
final class World
{
    private const MIN_X = 0;
    private const MAX_X = 360; //7 //14 // 20; // 3600000000 // 18 // 360

    private const MIN_Y = 0;
    private const MAX_Y = 180; //3 // 21 // 40 // 1800000000 // 30 // 180

    private WorldPerformance $worldPerformance;

    private array $data = [];

    public function __construct()
    {
        $this->worldPerformance = new WorldPerformance($this->getAmountOfPixels());
        for ($x = self::MIN_X; $x <= self::MAX_X; $x++) {
            for ($y = self::MIN_Y; $y <= self::MAX_Y; $y++) {
                $this->setPixelOneExactPixelTo($x, $y, null);
            }
        }
    }

    public function setPixelOneExactPixelTo(int $x, int $y, ?Player $player): void
    {
        $this->data[$y][$x] = new Pixel($x, $y, $player, $this->worldPerformance->getCounterRound(), $this->worldPerformance->getCounterTaken());

        if (null !== $player) {
            $this->worldPerformance->increasePixelsByPlayer($player, $this->data[$y][$x]);
        }
    }

    /**
     * @return Pixel[][]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getAmountOfPixels(): int
    {
        return (self::MAX_X - self::MIN_X + 1) * (self::MAX_Y - self::MIN_Y + 1);
    }

    public function getAmountOfX(): int
    {
        return (self::MAX_X - self::MIN_X + 1);
    }

    public function getAmountOfY(): int
    {
        return (self::MAX_Y - self::MIN_Y + 1);
    }

    public function getRandomPixelX(): int
    {
        return mt_rand(self::MIN_X, self::MAX_X);
    }

    public function getRandomPixelY(): int
    {
        return mt_rand(self::MIN_Y, self::MAX_Y);
    }

    public function setOnePixelTo(Player $player, Output $output): void
    {
        $this->worldPerformance->increaseCounterTakenAsReservation();

        try {

            if ($this->isPlayerInWorld($player)) {
                $this->setOtherThanInitialPixelOfPlayer($player);
            }

            $player->increaseRoundsPlayedInFreedom();

        } catch (TheWorldIsFull $exception) {
            $output->info(sprintf('%s [%s.%s]: THE WORLD IS FULL', $player->getId(), $this->worldPerformance->getCounterRound(), $this->worldPerformance->getCounterTaken()));
            // The World is full, we compensate to each of remaining players.
            $freedomLimitCompensation = new FreedomLimitCompensation();
            $player->increaseRoundsPlayedInFreedomCompensationBy($freedomLimitCompensation->getCompensation());
        } catch (FreedomLimit $exception) {
            $output->info(sprintf('%s [%s.%s]: FREEDOM LIMIT', $player->getId(), $this->worldPerformance->getCounterRound(), $this->worldPerformance->getCounterTaken()));
            // Player gets compensation & becomes equally happy compared to the players who got the territory
            $freedomLimitCompensation = new FreedomLimitCompensation();
            $player->increaseRoundsPlayedInFreedomCompensationBy($freedomLimitCompensation->getCompensation());
        }

        $player->increaseRoundsPlayed();
    }

    private function setInitialPixelOfPlayer(Player $player, Pixel $pixel): void
    {
        $this->worldPerformance->increaseCounterTakenAsReservation();
        $this->data[$player->getPixelInitial()->getY()][$player->getPixelInitial()->getX()] = $pixel;
    }

    private function setOtherThanInitialPixelOfPlayer(Player $player): void
    {
        $pixel = null;
        $locator = new Locator();
        $try = $player->getLatestTry();

        while (null === $pixel) {
            ++$try;
            $player->setLatestTry($try);

            $pixel = $locator->locateNextPointToTakeBasedOnInitial($player, $this, $this->worldPerformance->getCounterRound(), $this->worldPerformance->getCounterTaken(), $try);
            if (null !== $pixel) {
                $this->setPixelOneExactPixelTo($pixel->getX(), $pixel->getY(), $player);
            }

            if ($this->isItFreedomLimit($pixel, $locator, $try, $player)) {
                throw new FreedomLimit(sprintf('Limited freedom of player ID:%s', $player->getId()));
            }
        }
    }

    public function getPixelByPlayer(Player $player, int $specificRound, ?int $specificTake): ?Pixel
    {
        if (false === $this->isPlayerStillPlayingRounds($player, $specificRound)) {
            return null; // No reason to look for pixel as the player already
        }

        return $this->worldPerformance->getLastPixelTakenByEachPlayer($player);
    }

    public function getPixelByXY(int $x, int $y): Pixel
    {
        return $this->data[$y][$x];
    }

    public function isItInsideTheWorldBoundaries(int $x, int $y): bool
    {
        if ($x < self::MIN_X || $x > self::MAX_X) {
            return false;
        }
        if ($y < self::MIN_Y || $y > self::MAX_Y) {
            return false;
        }

        return true;
    }

    private function isItFreedomLimit(?Pixel $pixel, Locator $locator, int $try, Player $player): bool
    {
        return
            null === $pixel
            && $player->getRoundsPlayedInFreedom() + 1 <= $locator->getRoundConvertedFromTry($try);
    }

    public function initializeAllPlayers(PlayersUnique $players): void
    {
        foreach ($players->getData() as $player) {
            $pixel = new Pixel(
                $player->getPixelInitial()->getX(),
                $player->getPixelInitial()->getY(),
                $player,
                $this->worldPerformance->getCounterRound(),
                $this->worldPerformance->getCounterTaken(),
            );
            $this->setInitialPixelOfPlayer($player, $pixel);
            $this->worldPerformance->increasePixelsByPlayer($player, $pixel);
            $player->increaseRoundsPlayed();
            $player->increaseRoundsPlayedInFreedom();
        }
    }

    public function startNewRound(): void
    {
        $this->worldPerformance->increaseCounterRound();
    }

    public function isPlayerInWorld(Player $player): bool
    {
        return $this->worldPerformance->isPlayerInPixels($player);
    }

    public function getWorldPerformance(): WorldPerformance
    {
        return $this->worldPerformance;
    }

    public function isFreeSpaceFoundIn(): bool
    {
        return $this->worldPerformance->getCountFreePixels() > 0;
    }

    private function isPlayerStillPlayingRounds(Player $player, int $specificRound): bool
    {
        return
            $this->worldPerformance->getLastPixelTakenByEachPlayer($player)->getCountRound() === $specificRound // This is the current round the user already played at
            || $this->worldPerformance->getLastPixelTakenByEachPlayer($player)->getCountRound() + 1 === $specificRound // This is the new round the user have to play in
        ;
    }
}
