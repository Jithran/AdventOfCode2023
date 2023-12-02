<?php

include 'input.php';

echo '<pre>';

$replacements = [
    'one' => '1',
    'two' => '2',
    'three' => '3',
    'four' => '4',
    'five' => '5',
    'six' => '6',
    'seven' => '7',
    'eight' => '8',
    'nine' => '9',
];



$total = 0;
$aInput = explode("\n", $input);
foreach ($aInput as $index => $line) {

    $lineProcessed = strtr($line, $replacements);

    preg_match_all('/\d/', $lineProcessed, $matches);

    if (count($matches) > 0) {
        // concat the first key and last key of $matches together
        $integer = $matches[0][0] . $matches[0][count($matches[0]) - 1];
        $total += $integer;

        print_R("Index: " . $index . " \n");
        print_R('Raw Line:' . $line . "\n");
        print_R('Line:' . $lineProcessed . "\n");
        print_R($matches[0]);
        print_R($integer);
        print_R("\n----\n");
    }

}

echo "<br/><br/>Total: ";
echo $total;

