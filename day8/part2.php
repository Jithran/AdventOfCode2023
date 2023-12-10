<?php

$lines = file('input.txt', FILE_IGNORE_NEW_LINES);

$steps = array_shift($lines);
array_shift($lines); // Skip the unused line

$network = [];

foreach ($lines as $line) {
    [$pos, $targets] = explode(" = ", $line);
    $network[$pos] = explode(", ", trim($targets, "()"));
}

$positions = array_filter(array_keys($network), function($key) {
    return str_ends_with($key, "A");
});

$cycles = [];

foreach ($positions as $current) {
    $cycle = [];
    $currentSteps = $steps;
    $stepCount = 0;
    $firstZ = null;

    do {
        while ($stepCount == 0 || !str_ends_with($current, "Z")) {
            $stepCount++;
            $current = $network[$current][$currentSteps[0] == "L" ? 0 : 1];
            $currentSteps = substr($currentSteps, 1) . $currentSteps[0];
        }

        $cycle[] = $stepCount;

        if (is_null($firstZ)) {
            $firstZ = $current;
            $stepCount = 0;
        }
    } while ($current != $firstZ);

    $cycles[] = $cycle;
}

$nums = array_map(function($cycle) {
    return $cycle[0];
}, $cycles);

$lcmValue = array_shift($nums);

foreach ($nums as $num) {
    $lcmValue = lcm($lcmValue, $num);
}

echo $lcmValue;

/***********************************************************************************************************************
 * Math Functions, so we don't have to recompile PHP with GMP
 **********************************************************************************************************************/

function gcd($a, $b) {
    return $b ? gcd($b, $a % $b) : $a;
}

function lcm($a, $b) {
    return ($a * $b) / gcd($a, $b);
}