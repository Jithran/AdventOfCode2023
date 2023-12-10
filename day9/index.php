<?php
echo '<pre>';

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);

$p1 = 0;
$p2 = 0;

foreach($lines AS $line) {
    $line = explode(' ', trim($line));
    $p1 += calculateDif($line, true);
    $p2 += calculateDif($line, false);
}

echo 'Part 1: ' . $p1 . PHP_EOL;
echo 'Part 2: ' . $p2 . PHP_EOL;

function calculateDif($line, $getPostFix = true)  {
    $valCount = array_count_values($line);
    if(isset($valCount[0]) && $valCount[0] == count($line)) {
        return 0;
    }

    $diff = [];
    for ($i = 0; $i < count($line) - 1; $i++) {
        $diff[] = $line[$i + 1] - $line[$i];
    }

    $diffRec = calculateDif($diff, $getPostFix);
    if($getPostFix) {
        return $line[count($line) - 1] + $diffRec;
    }

    return $line[0] - $diffRec;
}
