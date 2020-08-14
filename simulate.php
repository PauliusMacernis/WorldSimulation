<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Simulation\Player\Pixel\PixelInitial;
use Simulation\Player\Pixel\PixelInitialRandom;
use Simulation\World\Output;
use Simulation\World\Player;
use Simulation\World\PlayersUnique;
use Simulation\World\Statistics;
use Simulation\World\World;


$world = new World();
$output = new Output();
$statistics = new Statistics();
$players = new PlayersUnique(
    new Player('A', new PixelInitial(1, 1)),
    new Player('B', new PixelInitial(1, 2)),
    new Player('C', new PixelInitialRandom($world)),
);

$output->output($world, 'Initial');

$counterRound = 0;
$counterTake = 0;
while ($statistics->isFreeSpaceFoundIn($world)) {
    ++$counterRound;
    foreach ($players->getData() as $player) {
        ++$counterTake;
        $world->setOnePixelTo($player, $statistics, $counterRound, $counterTake);
        $output->output($world, sprintf('After the pixel is taken by the player: %s', $player->getId()));
    }
}


// * @todo Tests:
// * Field: 10,3 A: 2,2 B: 2,3
// */