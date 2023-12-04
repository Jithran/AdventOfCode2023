<?php
echo '<pre>';

$input = file_get_contents('input.txt');

$aInput = explode("\r\n", $input);

$N = array_fill(0, count($aInput), 0);

$sum = 0;
foreach($aInput AS $index=> $line) {

    $N[$index] += 1;

    $score = getCardsSameNumbers($line);

    if($score > 0) {
        //$sum += pow(2, $score - 1);
        $sum += 2**($score - 1);
    }

    for ($j = 0; $j < $score; $j++) {
        if (isset($N[$index + 1 + $j])) {
            $N[$index + 1 + $j] += $N[$index];
        }
    }
}

function getCardsSameNumbers($card): int
{
    list ($game, $cards) = explode(': ', $card);

    list ($winningCard, $OwnCard) = explode(' | ', $cards);

    $winningCardNumbers = explode(' ', $winningCard);
    $ownCardNumbers = explode(' ', $OwnCard);

    $winningCardNumbers = array_filter(array_map('intval', $winningCardNumbers));
    $ownCardNumbers = array_filter(array_map('intval', $ownCardNumbers));

    // check how many numbers are the same
    $sameNumbers = array_intersect($winningCardNumbers, $ownCardNumbers);
    return count($sameNumbers);
}

echo 'Sum: ' . $sum . '<br/>';
echo 'Sum Part 2: ' . array_sum($N) . '<br/>';