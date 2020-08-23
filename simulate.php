<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Simulation\Player\Pixel\PixelInitial;
use Simulation\Player\Pixel\PixelInitialRandom;
use Simulation\World\Output;
use Simulation\World\Player;
use Simulation\World\PlayersUnique;
use Simulation\World\World;

$timeStart = microtime(true);

$output = new Output();
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
//    new Player('D', new PixelInitialRandom($world)),
);
$world = new World();

$world->startNewRound();
$world->initializeAllPlayers($players);
$output->outputWorld($world, 'Initial');

while ($world->isFreeSpaceFoundIn()) {
    $world->startNewRound();
    foreach ($players->getData() as $player) {
        $world->setOnePixelTo($player, $output);
//         $output->outputWorld($world, sprintf('After the pixel is taken by the player: %s', $player->getId()));
//         echo "\n" . $world->getWorldPerformance()->getCounterRound() . ' / ' . $world->getWorldPerformance()->getCounterTaken();
    }

//    if($world->getWorldPerformance()->getCounterRound() % 100 === 0) {
//        $output->performance($timeStart, $world, 'Round: ' . $world->getWorldPerformance()->getCounterRound());
//    }

//    if($world->getWorldPerformance()->getCounterRound() === 5000) {
//        die();
//    }
}

$output->performance($timeStart, $world, 'Performance summary at the end.');
$output->outputWorld($world, 'The Complete World:');
$output->outputPlayersCompensations($players, 'Player : Compensation');
