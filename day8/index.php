<?php
set_time_limit(0);
echo "<pre>";
$input = file_get_contents('input.txt');

$lines = explode("\r\n", $input);

$directions = array_shift($lines);
array_shift($lines);

$map = [];
foreach($lines AS $line) {
    $line = str_replace(['(', ')'], '', $line);
    [$key, $value] = explode(' = ', $line);
    [$left, $right] = explode(', ', $value);

    $map[$key] = [
        'L' => $left,
        'R' => $right
    ];
}


$key = 'AAA';

$i = 0;
while(true) {
    foreach(str_split($directions) AS $direction) {
        $i++;
        $key = $map[$key][$direction];

        if($key == 'ZZZ') {
            echo 'Found ZZZ in ' . $i . ' steps';
            break 2;
        }
    }
}

// get all keys in the map ending with Z
$keys = array_filter(array_keys($map), function($key) {
    return substr($key, -1) == 'Z';
});

// loop through all keys simultaneously and find the iteration where all simulations end at the same time with a key ending with Z
$iterations = 0;
$instances = [];
foreach($keys AS $key) {
    $instances[] = new Instance($key, $map, 0);
}

while(true) {
    $iterations++;
    foreach(str_split($directions) AS $direction) {
        $currentKeysPostFix = [];

        foreach ($instances as $instance) {
            $currentKeysPostFix[] = $instance->next($direction);
        }

        if($iterations % 100000 == 0) {
            echo 'Iteration ' . $iterations . PHP_EOL;
        }

        if(count(array_unique($currentKeysPostFix)) == 1 && $currentKeysPostFix[0] == 'Z') {
            echo 'Found **Z in ' . $iterations . ' steps for all instances';
            exit;
        }
    }
}

class Instance
{
    public $key;
    public $map;

    public function __construct($key, $map)
    {
        $this->key = $key;
        $this->map = $map;
    }

    public function next($dir)
    {
        $this->key = $this->map[$this->key][$dir];

        // return last char of key string
        return substr($this->key, -1);
    }

}