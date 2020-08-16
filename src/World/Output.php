<?php
declare(strict_types=1);

namespace Simulation\World;

/**
 * Outputs data to CLI in a human readable format.
 */
class Output
{
    private const FREE_TERRITORY_TITLE = '.';

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
        echo sprintf("\nIn total (aka. GDP?): %s", $gdp);
        echo "\n--------------------------------------------------------------------------------\n";
    }

    public function info(string $text): void
    {
        echo "\n";
        echo "\n--------------------------------------------------------------------------------";
        echo "\n" . $text;
    }
}
