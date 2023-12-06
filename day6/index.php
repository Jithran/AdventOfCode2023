<?php
echo '<pre>';

$input = file_get_contents('input.txt');

$lines = explode("\r\n", $input);
$times = array_values(array_filter(array_map('intval', explode(' ', trim(array_shift($lines))))));
$dist = array_values(array_filter(array_map('intval', explode(' ', trim(array_shift($lines))))));

$p2T = implode('', $times);
$p2D = implode('', $dist);

// Part 1
$p1 = 1;
foreach ($times as $index => $time) {
    $p1 *= $ans = overTime($times[$index], $dist[$index]);
    //echo 'Time: ' . $times[$index] . ' - Dist: ' . $dist[$index] . ' - Ans: ' . $ans . PHP_EOL;
}

echo 'Part 1: ' . $p1 . PHP_EOL;
echo 'Part 2: ' . overTime($p2T, $p2D) . PHP_EOL;



function overTime($time, $dist): int
{
    $ans = 0;
    for ($i = 0; $i <= $time; $i++) {
        $dt = $i * ($time - $i);
        if ($dt > $dist) {
            $ans++;
            //echo 'Time: ' . $time . ' - Dist: ' . $dist . ' - $dt: ' . $dt . ' - i: ' . $i . PHP_EOL;
        }
    }
    return $ans;
}