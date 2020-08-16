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
    private const MAX_X = 7; //7 //14 // 20; // 3600000000

    private const MIN_Y = 0;
    private const MAX_Y = 11; //3 // 21 // 40 // 1800000000;

    private array $data = [];

    public function __construct()
    {
        for ($x = self::MIN_X; $x <= self::MAX_X; $x++) {
            for ($y = self::MIN_Y; $y <= self::MAX_Y; $y++) {
                $this->setPixelOneExactPixelTo($x, $y, null, null, null);
            }
        }
    }

    public function setPixelOneExactPixelTo(int $x, int $y, ?Player $player, ?int $counterRound, ?int $counterTake): void
    {
        $this->data[$y][$x] = new Pixel($x, $y, $player, $counterRound, $counterTake);
    }

    /**
     * @return Pixel[][]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getRandomPixelX(): int
    {
        return mt_rand(self::MIN_X, self::MAX_X);
    }

    public function getRandomPixelY(): int
    {
        return mt_rand(self::MIN_Y, self::MAX_Y);
    }

    public function setOnePixelTo(Player $player, Statistics $statistics, int $counterRound, int $counterTake, Output $output): void
    {
        try {
            if (false === $statistics->isPlayerInWorld($player, $this, null, null)) {
                $this->setInitialPixelOfPlayer($player, $counterRound, $counterTake);
            } else {
                $this->setOtherThanInitialPixelOfPlayer($player, $statistics, $counterRound, $counterTake);
            }

            $player->increaseRoundsPlayedInFreedom();

        } catch (TheWorldIsFull $exception) {
            $output->info(sprintf('%s [%s.%s]: THE WORLD IS FULL', $player->getId(), $counterRound, $counterTake));
            // The World is full, we compensate to each of remaining players.
            $freedomLimitCompensation = new FreedomLimitCompensation();
            $player->increaseRoundsPlayedInFreedomCompensationBy($freedomLimitCompensation->getCompensation());
        } catch (FreedomLimit $exception) {
            $output->info(sprintf('%s [%s.%s]: FREEDOM LIMIT', $player->getId(), $counterRound, $counterTake));
            // Player gets compensation & becomes equally happy compared to the players who got the territory
            $freedomLimitCompensation = new FreedomLimitCompensation();
            $player->increaseRoundsPlayedInFreedomCompensationBy($freedomLimitCompensation->getCompensation());
        }

        $player->increaseRoundsPlayed();
    }

    private function setInitialPixelOfPlayer(Player $player, int $counterRound, int $counterTake): void
    {
        $this->data[$player->getPixelInitial()->getY()][$player->getPixelInitial()->getX()] = new Pixel(
            $player->getPixelInitial()->getX(),
            $player->getPixelInitial()->getY(),
            $player,
            $counterRound,
            $counterTake
        );
    }

    private function setOtherThanInitialPixelOfPlayer(Player $player, Statistics $statistics, int $counterRound, int $counterTake): void
    {
        $pixel = null;
        $locator = new Locator();
        $try = 0;

        while (null === $pixel) {
            ++$try;

            $pixel = $locator->locateNextPointToTakeBasedOnInitial($player, $this, $statistics, $counterRound, $counterTake, $try);
            if (null !== $pixel) {
                $this->setPixelOneExactPixelTo($pixel->getX(), $pixel->getY(), $player, $counterRound, $counterTake);
            }

            if ($this->isItFreedomLimit($pixel, $locator, $counterTake, $try, $player)) {
                throw new FreedomLimit(sprintf('Limited freedom of player ID:%s', $player->getId()));
            }
        }
    }

    public function getPixelByPlayer(Player $player, ?int $specificRound, ?int $specificTake): ?Pixel
    {
        foreach ($this->data as $row) {
            foreach ($row as $pixelData) {
                if (null === $pixelData->getPlayer()) {
                    continue;
                }
                if (
                    ($specificRound === null || $specificRound === $pixelData->getCountRound())
                    && ($specificTake === null || $specificTake === $pixelData->getCountTake())
                    && $pixelData->getPlayer()->getId() === $player->getId()
                ) {
                    return $pixelData;
                }
            }
        }

        return null;
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

    private function isItFreedomLimit(?Pixel $pixel, Locator $locator, int $counterTake, int $try, Player $player): bool
    {
        return
            null === $pixel
            && $player->getRoundsPlayedInFreedom() + 1 <= $locator->getRoundConvertedFromTry($try);
    }
}
