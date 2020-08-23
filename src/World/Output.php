<?php
declare(strict_types=1);

namespace Simulation\World;

/**
 * Outputs data to CLI in a human readable format.
 */
class Output
{
    private const FREE_TERRITORY_TITLE = '.';

    /** @var array Array containing keys for a stream name (e.g. "info") and 1 or 0 to say it is off or on. All values are on by default. */
    private array $streamsOnOff;

    public function __construct($streamsOnOff)
    {
        $this->streamsOnOff = $streamsOnOff;
    }

    public function outputWorld(World $world, string $header): void
    {
        echo "\n--------------------------------------------------------------------------------";
        echo "\n" . $header;
        echo "\n--------------------------------------------------------------------------------";
        foreach ($world->getData() as $row) {

            echo "\n";

            foreach ($row as $pixel) {
                if (null === $pixel->getPlayer()) {
                    $label = self::FREE_TERRITORY_TITLE;
                } else {
                    $label = sprintf('%s:%s.%s',
                        $pixel->getPlayer()->getId(),
                        $pixel->getCountRound(),
                        $pixel->getCountTake(),
                    );

//                    $label = sprintf('%s-%s',
//                        $pixel->getPlayer()->getId(),
//                        $pixel->getCountTake(),
//                    );
//
//                    $label = sprintf('%s',
//                        $pixel->getPlayer()->getId(),
//                    );
                }
                echo str_pad($label, 10, " ", STR_PAD_RIGHT);
            }
        }
        echo "\n--------------------------------------------------------------------------------\n";
    }

    public function outputPlayersCompensations(PlayersUnique $players, string $header): void
    {
        echo "\n--------------------------------------------------------------------------------";
        echo "\n" . $header;
        echo "\n--------------------------------------------------------------------------------";
        $gdp = 0;
        foreach ($players->getData() as $player) {
            echo "\n";
            echo str_pad(sprintf('%-2s: %-4s', $player->getId(), $player->getRoundsPlayedInFreedomCompensation()), 10, " ", STR_PAD_LEFT);
            $gdp += $player->getRoundsPlayedInFreedomCompensation();
        }
        echo "\n--------------------------------------------------------------------------------";
        echo sprintf("\nIn total: %s", $gdp);
        echo "\n--------------------------------------------------------------------------------\n";
    }

    public function info(string $text): void
    {
        if(false === $this->isStreamOn()) {
            return;
        }
        echo "\n";
        echo "\n--------------------------------------------------------------------------------";
        echo "\n" . $text;
    }

    public function performance(float $timeStart, World $world, string $message): void
    {
        $amountOfPixels = $world->getAmountOfPixels();

        echo "\n\n--------------------------------------------------------------------------------";
        echo "\nThe world of " . $amountOfPixels . " pixels. [X:" . $world->getAmountOfX() . "][Y:" . $world->getAmountOfY() . "]";
        echo "\n" . $message;
        echo "\n--------------------------------------------------------------------------------";
        echo "\nMemory peak usage: " . round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB';
        echo "\nDuration: " . round(microtime(true) - $timeStart, 2) . ' sec.';
        echo "\n--------------------------------------------------------------------------------";
        echo "\nMemory peak usage per px: " . round(memory_get_peak_usage() / $amountOfPixels,) . ' B';
        echo "\nDuration per px: " . round((microtime(true) - $timeStart) / $amountOfPixels, 4) . ' sec.';
        echo "\n--------------------------------------------------------------------------------\n";
        var_dump($world->getWorldPerformance());
    }

    /**
     * @return bool
     */
    private function isStreamOn(): bool
    {
        return !(isset($this->streamsOnOff['info']) && $this->streamsOnOff['info'] === 0);
    }
}
