<?php
echo '<pre>';

$input = file('input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// find our starting point by pinpointing the only S in the input
$path = null;
foreach ($input as $y => $line) {
    $x = strpos($line, 'S');
    if ($x !== false) {
        $path[] = ['x'=>$x, 'y'=>$y];
        break;
    }
}

$pipes = [
    '|' => ['up', 'down'],
    '-' => ['right', 'left'],
    'F' => ['right', 'down'],
    'J' => ['up', 'left'],
    'L' => ['up', 'right'],
    '7' => ['down', 'left'],
    '.' => [],
];

$directions = ['up', 'right', 'down', 'left'];


// check one of the 4 directions for a connecting pipe so we can continue from there with a recursive loop

// check up
foreach($directions AS $dir) {
    $result = checkConnection($path[0]['x'], $path[0]['y'], $dir, $input, $pipes);
    if($result) {
        $path[0]['dir'] = $dir;
        break;
    }
}

while(true) {
    $result = checkConnection($path[count($path)-1]['x'], $path[count($path)-1]['y'], $path[count($path)-1]['dir'], $input, $pipes);
    if($result == 'END') {
        break;
    }
    if($result) {
        $path[] = $result;
    }
}

function checkConnection($x, $y, $dir, $input, $pipes)
{
    switch ($dir) {
        case 'up':
            $y--;
            break;
        case 'down':
            $y++;
            break;
        case 'left':
            $x--;
            break;
        case 'right':
            $x++;
            break;
    }

    // switch direction to the oposite direction, because we input the direction we came from and we need to check for the reverse direction
    switch ($dir) {
        case 'up':
            $dir = 'down';
            break;
        case 'down':
            $dir = 'up';
            break;
        case 'left':
            $dir = 'right';
            break;
        case 'right':
            $dir = 'left';
            break;
    }

    if (!isset($input[$y][$x])) {
        return false;
    }

    $char = $input[$y][$x];
    if($char == 'S') {
        return 'END';
    }
    if (in_array($dir, $pipes[$char])) {
        // return the other direction
        unset ($pipes[$char][array_search($dir, $pipes[$char])]);
        $pipes[$char] = array_values($pipes[$char]);
        return ['x' => $x, 'y' => $y, 'dir' => $pipes[$char][0]];
    }

    return false;
}

echo '<pre>' . print_r($path, true) . '</pre>';

echo 'Answer: ' . (count($path) /2) . PHP_EOL;
exit;
