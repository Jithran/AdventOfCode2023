<?php
echo '<pre>';

//include 'control_input.php';
include 'input.php';


$aGames = explode("\n", $input);

$gameOutput = [];

// normalize the input
foreach ($aGames as $game) {
    // each game has this format: Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green
    // we want this output: [1] => Array ( [red] => 4 [green] => 2 [blue] => 3 ) where the values of the colors are the maximum amount of that color

    list($gameName, $gameData) = explode(':', $game);
    $gameIndex = trim(str_replace('Game', '', $gameName));

    $aRounds = explode(';', $gameData);

    // loop through each round
    foreach ($aRounds as $round) {

        $aColors = explode(',', $round);

        foreach ($aColors as $color) {
            list($amount, $color) = explode(' ', trim($color));

            if (!isset($gameOutput[$gameIndex][$color])) {
                $gameOutput[$gameIndex][$color] = 0;
            }

            if ($amount > $gameOutput[$gameIndex][$color]) {
                $gameOutput[$gameIndex][$color] = $amount;
            }
        }
    }
}

$gameIndexSum = 0;
foreach ($gameOutput as $gameIndex => $game) {

    foreach ($game as $color => $amount) {
        if ($amount > $check[$color]) {
            echo 'Game ' . $gameIndex . ' is invalid<br/>';
            continue 2;
        }
    }

    echo 'Game ' . $gameIndex . ' is valid<br/>';
    $gameIndexSum += $gameIndex;
}


echo '<br/><br/>Game index sum: ' . $gameIndexSum . '<br/><br/>';
//echo '<pre>' . print_r($gameOutput, true) . '</pre>';

$GamePowerOutput = [];
foreach ($gameOutput as $gameIndex => $game) {
    // calculate the power of each color
    $power = null;
    foreach ($game as $color => $amount) {
        if ($power === null) {
            $power = $amount;
        } else {
            $power *= $amount;
        }

        $GamePowerOutput[$gameIndex] = $power;

    }
}

//echo '<pre>'.print_r($GamePowerOutput, true).'</pre>';

// sum the power of each game
$gamePowerSum = 0;
foreach ($GamePowerOutput as $gameIndex => $power) {
    $gamePowerSum += $power;
}
echo '<br/><br/>Game power sum for part 2: ' . $gamePowerSum . '<br/><br/>';
exit;