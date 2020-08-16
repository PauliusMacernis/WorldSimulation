<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Simulation\Player\Pixel\PixelInitial;
use Simulation\World\Output;
use Simulation\World\Player;
use Simulation\World\PlayersUnique;
use Simulation\World\Statistics;
use Simulation\World\World;


$world = new World();
$output = new Output();
$statistics = new Statistics();
$players = new PlayersUnique(
    new Player('A', new PixelInitial(1, 5)),

    new Player('B', new PixelInitial(1, 4)),
    new Player('C', new PixelInitial(2, 5)),
    new Player('D', new PixelInitial(1, 6)),
    new Player('E', new PixelInitial(0, 5)),

    new Player('F', new PixelInitial(2, 4)),
    new Player('G', new PixelInitial(2, 6)),
    new Player('H', new PixelInitial(0, 6)),
    new Player('I', new PixelInitial(0, 4)),

//    new Player('A', new PixelInitialRandom($world)),
//    new Player('B', new PixelInitialRandom($world)),
//    new Player('C', new PixelInitialRandom($world)),
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
