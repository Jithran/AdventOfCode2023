<?php
echo '<pre>';

/*echo '<pre>'.print_r(remapHandToSortable('JJ3JJ'), true).'</pre>';
echo '<pre>'.print_r(remapHandToSortable('11234'), true).'</pre>';
echo '<pre>'.print_r(remapHandToSortable('11223'), true).'</pre>';
echo '<pre>'.print_r(remapHandToSortable('11134'), true).'</pre>';
echo '<pre>'.print_r(remapHandToSortable('11122'), true).'</pre>';
echo '<pre>'.print_r(remapHandToSortable('11112'), true).'</pre>';
echo '<pre>'.print_r(remapHandToSortable('11111'), true).'</pre>';

exit;*/

$input = file_get_contents('input.txt');
$lines = explode("\r\n", $input);

$hands = [];
foreach ($lines as $line) {
    [$hand, $bid] = explode(' ', $line);
    $hands[] = [
        'hand' => $hand,
        'bid' => (int)$bid,
        'score' => remapHandToSortable($hand)
    ];
}

usort($hands, function ($a, $b) {
    return strcmp($a['score'], $b['score']);
});

$total = 0;
foreach ($hands as $rank => $play) {
    //echo $play['hand'] . ' ' . $play['bid'] . PHP_EOL;
    $total += ($rank + 1) * $play['bid'];
}

echo $total;


/*$hands = [];

foreach($lines AS $line) {
    [$hand, $bid] = explode(' ', $line);

    $hand = str_replace(['T','J,','Q','K','A'], ['A','B','C','D','E'] , $hand);

    $hands[] = [
        'hand' => $hand,
        'bid' => $bid
    ];
}*/


function remapHandToSortable($hand)
{
    $classification = getBestHand($hand);
    $handCharacters = str_split($hand);
    $mappedCharacters = [];
    $replacementLetters = ["T" => "A", "J" => "1", "Q" => "C", "K" => "D", "A" => "E"];

    foreach ($handCharacters as $card) {
        if (isset($replacementLetters[$card])) {
            $mappedCharacters[] = $replacementLetters[$card];
        } else {
            $mappedCharacters[] = $card;
        }
    }

    // Return an array with the classification and the mapped characters
    return $classification . implode('', $mappedCharacters);
}

function getBestHand(&$hand)
{
    $possibilities = array_map('identifyHandType', replacements($hand));
    return max($possibilities);
}


function identifyHandType($hand)
{
    $handCharacters = str_split($hand);

    // Count the occurrences of each card in the hand
    $cardCounts = array_count_values($handCharacters);

    // Standard hand type is high card
    $handType = 0;

    // Check for five of a kind
    if (in_array(5, $cardCounts)) {
        $handType = 6;
    }
    // Check for four of a kind
    else if (in_array(4, $cardCounts)) {
        $handType = 5;
    }
    // Check for three of a kind or full house
    else if (in_array(3, $cardCounts)) {
        // If there's also a pair, it's a full house
        if (in_array(2, $cardCounts)) {
            $handType = 4;
        } else {
            // Otherwise, it's just three of a kind
            $handType = 3;
        }
    }
    // Check for two pair
    else if (count(array_filter($cardCounts, function ($val) use ($hand) {
            return $val == 2;
        })) == 2) {
        $handType = 2;


    }
    // Check for one pair
    else if (in_array(2, $cardCounts)) {
        $handType = 1;
    }

    return $handType;
}

function replacements($hand)
{
    if (empty($hand)) {
        return [''];
    }

    $results = [];
    $chars = $hand[0];
    if($hand[0] === "J") {
        $chars = "23456789TQKA";
    }
    foreach (str_split($chars) as $char) {
        foreach (replacements(substr($hand, 1)) as $y) {
            $results[] = $char . $y;
        }
    }
    return $results;
}


