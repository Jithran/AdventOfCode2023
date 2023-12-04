<?php
echo '<pre>';

$input = file_get_contents('input.txt');
$aInput = explode("\r\n", $input);

$CardCount = array_fill(0, count($aInput), 1);
$sum = 0;

foreach ($aInput as $i => $line) {
    $score = getCardsSameNumbers($line);

    if ($score > 0) {
        //$sum += pow(2, $score - 1);
        $sum += 2 ** ($score - 1);
    }

    for ($j = 0; $j < $score; $j++) {
        // check if the next card exists and we don't go the non-existing cards
        if (isset($CardCount[$i + 1 + $j])) {
            // add the current card count to the next cards where we will get a clone from
            $CardCount[$i + 1 + $j] += $CardCount[$i];
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
echo 'Sum Part 2: ' . array_sum($CardCount) . '<br/>';