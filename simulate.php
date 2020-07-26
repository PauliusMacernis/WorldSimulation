<?php

const PLAYER_PERCENT_SEPARATOR = '-';
const PLAYER_NULL_TITLE = '.';
const PLAYER_NULL_POWER = null;

const MIN_Y = 1;
const MAX_Y = 3;// 22;
const MIN_X = 1;
const MAX_X = 10; //15;

// Directions: Must start with 1 and be +1 with the next one. DIRECTION_OVERFLOW is the last and unsupported direction.
const UP = 1;
const RIGHT = 2;
const DOWN = 3;
const LEFT = 4;
const DIRECTION_OVERFLOW = 5;


//echo "test";

$values = [];


/**
 * @return int
 */
function getRandX(): int
{
    return mt_rand(MIN_X, MAX_X);
}

function getRandY(): int
{
    return mt_rand(MIN_Y, MAX_Y);
}

function prepareMatrix(array $values)
{
    for ($x = MIN_X; $x <= MAX_X; $x++) {
        for ($y = MIN_Y; $y <= MAX_Y; $y++) {
            $values[$y][$x] = PLAYER_NULL_TITLE; // in percents?
        }
    }
    return $values;
}

/**
 * @param array $values
 */
function outputMatrix(array $values)
{
    foreach ($values as $xKey => $row) {
        //echo "\n" . str_pad($xValue,10,".", STR_PAD_LEFT);
        echo "\n";
        foreach ($row as $column => $value) {
            echo str_pad($value, 6, " ", STR_PAD_LEFT);
        }
    }
}



function isItPlayer(string $playerName, string $someonesName) {

//    var_dump($playerName);
//    var_dump($someonesName);

    return $playerName === $someonesName;
}

function findPlayerInEnvironment(array $environment, string $playerName) {
    //reset($environment); // just in case.. for some reason, the last exception has been thrown once..

    foreach($environment as $yPosition => $yValues) {
        //reset($yValues);
        foreach($yValues as $xPosition => $someonesName) {
            if(isItPlayer($playerName, $someonesName)) {
                return [$xPosition, $yPosition];
            }
        }
    }
    throw new RuntimeException(sprintf('There must be the player with the name "%s"', $playerName));
}

function getEmpoweredMatrixValue(array $environment, string $playerName) {
    list($xOfPlayer, $yOfPlayer) = findPlayerInEnvironment($environment, $playerName);

//    var_dump($xOfPlayer);
//    var_dump($yOfPlayer);

    // On the level #1 - player must collect:
    //  - 4 x1 points (north/east/south/west, if not possible all - at least anywhere in the solid way)
    //  - 4x 0.5 points (NE, SE, SW, NW)
    //  -- this will make "a solid circle"
    $newTerritorySolidity = 100; // percents

    list($xNewTerritory, $yNewTerritory) = findNextPointToOccupy($environment, $xOfPlayer, $yOfPlayer, UP);


    $environment[$yNewTerritory][$xNewTerritory] = packPlayerAndPowerInfo($playerName, $newTerritorySolidity);

    return $environment;
}

/**
 * @param $playerName
 * @param $newTerritorySolidity
 * @return string
 */
function packPlayerAndPowerInfo($playerName, $newTerritorySolidity)
{
    return $playerName . PLAYER_PERCENT_SEPARATOR . $newTerritorySolidity;
}

/**
 * @param $environment
 * @return false|string[]
 */
function unpackPlayerAndPowerInfo($environment)
{
    $playerAndPower = explode(PLAYER_PERCENT_SEPARATOR, $environment);
    if(count($playerAndPower) > 2) {
        throw new RuntimeException(sprintf('Array consists of too many fragments: %s', count($playerAndPower)));
    }

    var_dump($playerAndPower);

    $player = $playerAndPower[0] ?? PLAYER_NULL_TITLE;
    $power = $playerAndPower[1] ?? PLAYER_NULL_POWER; // initial point

    return [$player, $power];
}

function getPlayerAndPowerAlreadyInThePoint(array $environment, int $xNewTerritory, int $yNewTerritory): array
{
    var_dump("_______START:" . __METHOD__ . "___________");
    outputMatrix($environment);
    var_dump($xNewTerritory);
    var_dump($yNewTerritory);
    var_dump("_______END:" . __METHOD__ . "___________");


    if(PLAYER_NULL_TITLE === $environment[$yNewTerritory][$xNewTerritory]) {
        return [PLAYER_NULL_TITLE, null];
    }

    list($player, $power) = unpackPlayerAndPowerInfo($environment[$yNewTerritory][$xNewTerritory]);

    var_dump("_______START2:" . __METHOD__ . "___________");
    var_dump($environment[$yNewTerritory][$xNewTerritory]);
    var_dump(sprintf('Player: "%s", Power: "%s"', $player, $power));
    var_dump("_______END2:" . __METHOD__ . "___________");


//
//
//    $player = $playerAndPower[0];
//    $weight = $playerAndPower[1] ?? null; // initial point

    return [$player, $power];

}



/**
 * @param $xOfPlayer
 * @param $yOfPlayer
 * @return array
 */
function findNextPointToOccupy(array $environment, int $xOfPlayer, int $yOfPlayer, int $desiredDirection)
{
// Spread to North by one solid (100%) step. North+1

    list($xNewTerritory, $yNewTerritory, $newDirection) = findNextPointToOccupyAtLevel1($environment, $desiredDirection, $xOfPlayer, $yOfPlayer);

    list($playerOfThePoint, $playerPowerInThePoint) = getPlayerAndPowerAlreadyInThePoint($environment, $xNewTerritory, $yNewTerritory);
    if($playerOfThePoint !== PLAYER_NULL_TITLE && $playerPowerInThePoint === PLAYER_NULL_POWER) {
        list($xNewTerritory, $yNewTerritory, $newDirection) = findNextPointToOccupy($environment, $xOfPlayer, $yOfPlayer, ++$newDirection);
        //throw new RuntimeException(printf('Getting into initial spot of player "%s" is not allowed. Taking the spot player got initially is not allowed. ', $playerOfThePoint));
    }
    // @TODO: Hitting the initial point of other player.
    if($playerOfThePoint !== PLAYER_NULL_TITLE) {
        throw new RangeException(sprintf("The place [x:%s, y:%s] is already occupied by %s. Power level: %s", $xNewTerritory, $yNewTerritory, $playerOfThePoint, $playerPowerInThePoint));
    }


//    $xNewTerritory = $xOfPlayer;
//    $yNewTerritory = $yOfPlayer - 1;
//    // .. deal with Overflow on X
//    if ($xNewTerritory < MIN_X || $xNewTerritory > MAX_X) {
//        throw new OverflowException("OVERFLOW - X");
//    }
//    if ($yNewTerritory < MIN_Y || $yNewTerritory > MAX_Y) {
//        throw new OverflowException("OVERFLOW - Y");
//    }

    // TODO: Is the place occupied by other player?


    return array($xNewTerritory, $yNewTerritory);
}

/**
 * Level1 is UP, RIGHT, DOWN, or LEFT.
 *
 * @param $desiredDirection
 * @param $xOfPlayer
 * @param $yOfPlayer
 * @return array|int[]
 */
function findNextPointToOccupyAtLevel1(array $environment, int $desiredDirection, int $xOfPlayer, int $yOfPlayer)
{
    $xNewTerritory = $xOfPlayer;
    $yNewTerritory = $yOfPlayer;

    switch ($desiredDirection) {
        case UP:
            --$yNewTerritory;
            break;
        case RIGHT:
            ++$xNewTerritory;
            break;
        case DOWN:
            ++$yNewTerritory;
            break;
        case LEFT:
            --$xNewTerritory;
            break;
        case DIRECTION_OVERFLOW:
            throw new OverflowException("--- DIRECTION OVERFLOW ---");
            break;
    }

    if ($xNewTerritory < MIN_X || $xNewTerritory > MAX_X) {
        list($xNewTerritory, $yNewTerritory) = findNextPointToOccupy($environment, $xOfPlayer, $yOfPlayer, $desiredDirection+1);
    }
    if ($yNewTerritory < MIN_Y || $yNewTerritory > MAX_Y) {
        list($xNewTerritory, $yNewTerritory) = findNextPointToOccupy($environment, $xOfPlayer, $yOfPlayer, $desiredDirection+1);
    }

    return array($xNewTerritory, $yNewTerritory, $desiredDirection);
}

// ----------------



$matrix = prepareMatrix($values);
echo "\n-------- INIT -------------\n";
outputMatrix($matrix);

echo "\n-------- RESULT -------------\n";


// Set A
$xA = 2; //getRandX();
$yA = 2; //getRandY();
$matrix[$yA][$xA] = "A";
var_dump(sprintf('A [%s, %s]', $xA, $yA) );

// Set B
$xB = 2; //getRandX();
$yB = 3; //getRandY();
$matrix[$yB][$xB] = "B";
var_dump(sprintf('B [%s, %s]', $xB, $yB) );

if($xA === $xB && $yA === $yB) {
    throw new RuntimeException('There cannot be two points in the same spot!');
}

$matrix = getEmpoweredMatrixValue($matrix, 'A');
$matrix = getEmpoweredMatrixValue($matrix, 'B');


outputMatrix($matrix);

echo "\n";

/**
 * @todo Tests:
 * Field: 10,3 A: 2,2 B: 2,3
 */