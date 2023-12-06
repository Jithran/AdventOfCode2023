<?php
echo '<pre>';
// set max execution time to 5 minutes
ini_set('max_execution_time', 0);

$input = file_get_contents('input.txt');

// Part 1

$seeds = explode("\r\n\r\n", $input)[0];
$steps = array_slice(explode("\r\n\r\n", $input), 1);

$seeds = array_slice(explode(" ", $seeds), 1);

//echo '<pre>'.print_r($seeds, true).'</pre>';
// fill location with the max int
$location = PHP_INT_MAX;
foreach ($seeds as $seed) {
    $curLocation = seedToLocation($seed, $steps);
    if ($curLocation < $location) {
        $location = $curLocation;
    }
}

// get the lowest value from locations
echo 'Part 1: ' . $location . PHP_EOL;

// Part 2

if(!empty($_GET['debug'])) {
    $location = PHP_INT_MAX;
    echo '<pre>' . print_r($seeds, true) . '</pre>';

    $iterations = 0;
    while (count($seeds) > 0) {
        echo 'handeling iteration: ' . $iterations++ . PHP_EOL;

        $start = array_shift($seeds);
        $count = array_shift($seeds);
        $cur_percentage = null;
        for ($i = $start; $i <= $start + $count; $i++) {
            // calculate the percentage of the run and echo it
            $percentage = round(($i - $start) / $count * 100, 2);

            // check if $percentage doesn't have decimals
            if (strpos($percentage, '.') === false && $cur_percentage !== $percentage) {
                $cur_percentage = $percentage;
                echo date('H:i:s') . ' - Handled: ' . $percentage . "% - for iteration " . $iterations . " with " . (count($seeds) / 2) . ' Iterations left ' . PHP_EOL;
            }
            $curLocation = seedToLocation($i, $steps);
            if ($curLocation < $location) {
                $location = $curLocation;
            }
        }
    }

// get the lowest value from locations
    echo 'Part 2: ' . $location . PHP_EOL;
}


function seedToLocation($seed, $steps)
{
    foreach ($steps as $step) {
        $maps = array_slice(explode("\r\n", $step), 1);

        foreach ($maps as $map) {
            if ($map == '') {
                continue;
            }
            list($to, $from, $len) = explode(" ", $map);

            if ($seed >= $from && $seed <= $from + $len) {
                //echo 'FROM: '.$seed . '- ';
                $seed = $to + ($seed - $from);
                //echo 'TO: '.$seed . PHP_EOL;
                break;
            }
        }
    }

    return $seed;
}
