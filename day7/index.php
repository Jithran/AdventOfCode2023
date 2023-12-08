<?php
echo '<pre>';

$input = file_get_contents('input.txt');

$lines = explode("\r\n", $input);

// Part 1

// lines are in the format xxxxx yyy where xxxxx is the hand and yyy is the score

$hands = [];
foreach($lines AS $line) {
    list ($hand, $score) = explode(' ', $line);
    $hands[] = [
        'hand' => $hand,
        'score' => $score
    ];
}


// sort the lines with a custom function
/*uasort($hands, function($a, $b) {
    $aPoints = getPoints($a);
    $bPoints = getPoints($b);

    if($aPoints == $bPoints) {
        return 0;
    }

    return ($aPoints < $bPoints) ? -1 : 1;
});*/

// calculate the points for each hand
foreach($hands AS &$hand) {
    getPoints($hand);
}

// sort the hands by [sc]
uasort($hands, function($a, $b) {
    if($a['sc'] == $b['sc']) {
        return 0;
    }

    return ($a['sc'] < $b['sc']) ? -1 : 1;
});

$hands = array_values($hands);


$totalScore = 0;
for($i = 0; $i < count($hands); $i++) {
    $totalScore += ($i + 1) * $hands[$i]['score'];

}

echo 'Part 1: ' . $totalScore . PHP_EOL;

// expected output:
// 32T3K 1
// KTJJT 2
// KK677 3
// T55J5 4
// QQQJA 5


function getPoints(&$aHand) {
    $hand = str_split($aHand['hand']);

    $cardWeight = [
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        'T' => 10,
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => 14
    ];

    $originalOrder = $hand;

    // 1. Five of a kind
    // 2. Four of a kind
    // 3. Full house
    // 4. Three of a kind
    // 5. Two pair
    // 6. One pair
    // 7. High card

    // first we need to sort the hand
    sort($hand);

    /*$jCount = 0;
    foreach($hand AS $card) {
        if($card == 'J') {
            $jCount++;
        }
    }*/

    // check if we have a five of a kind
    if($hand[0] == $hand[4]) {
        $points = 7e12;
    }
    // check if we have a four of a kind
    elseif($hand[0] == $hand[3] || $hand[1] == $hand[4]) {
        $points = 6e12;
        /*if(in_array('J', $hand)) {
            $points += 1e12;
        }*/
    }
    // check if we have a full house
    elseif(($hand[0] == $hand[2] && $hand[3] == $hand[4]) || ($hand[0] == $hand[1] && $hand[2] == $hand[4])) {
        $points = 5e12;

        /*if(in_array('J', $hand)) {
            $points += 1e12;
        }*/
    }
    // check if we have a three of a kind
    elseif($hand[0] == $hand[2] || $hand[1] == $hand[3] || $hand[2] == $hand[4]) {
        $points = 4e12;
        /*if(in_array('J', $hand)) {
            $points += 1e12;
        }*/
    }
    // check if we have a two pair
    elseif(($hand[0] == $hand[1] && $hand[2] == $hand[3]) || ($hand[0] == $hand[1] && $hand[3] == $hand[4]) || ($hand[1] == $hand[2] && $hand[3] == $hand[4])) {
        $points = 3e12;
        /*if(in_array('J', $hand)) {
            $points += 1e12;
        }*/
    }
    // check if we have a one pair
    elseif($hand[0] == $hand[1] || $hand[1] == $hand[2] || $hand[2] == $hand[3] || $hand[3] == $hand[4]) {
        $points = 2e12;
        /*if(in_array('J', $hand)) {
            $points += 2e12;
        }*/
    }
    // check if we have a high card
    else {
        $points = 1e12;
        /*if(in_array('J', $hand)) {
            if($jCount > 1) {
                echo '<pre>'.print_r($jCount, true).'</pre>';
                exit;
                $points += 2e12;
            } else {
                $points += 1e12;
            }
        }*/
    }


    /*if($jCount > 1) {
        $points += 1e12 * ($jCount-1);
    }*/

    // now we need to calculate the points for the hand
    // in the orignal order, add the index of the card times the char value of the card
    foreach($originalOrder AS $index => &$card) {
        $cardPoints = (pow(count($originalOrder) - $index, 12) * $cardWeight[$card]);
        $points += $cardPoints;

        $card = [$card, $cardPoints, count($originalOrder) - $index];
    }

    $aHand['sc'] = $points;
    $aHand['po'] = $originalOrder;


    return $points;
}

// 251442168 is too high

// 250577259 correct