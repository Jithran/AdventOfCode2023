<?php
echo '<pre>';

$input = file_get_contents('input.txt');

$rowLength = strlen(explode("\r\n", $input)[0]);

// make a long string from the input without newlines
$inputString = str_replace("\r\n", '', $input);

$totalSum = 0;
$currentNumberBuffer = null;
$currentBufferIsPartNumber = false;
$currentBufferIsToAstrix = false;
$astrixBuffer = [];

// loop trough each character in the string
for ($i = 0; $i < strlen($inputString); $i++) {
    $character = $inputString[$i];

    if(is_numeric($character)) {
        // check if it is the first number of a line
        if($i % $rowLength == 0) {
            $currentBufferIsPartNumber = false;
            $currentNumberBuffer = null;
        }

        // if the character is a number, add it to the current number buffer
        $currentNumberBuffer .= $character;

        // check if the current character is adjacent to a special character (left, right, up, down or diagonal).
        $checkFor['up'] = $i - $rowLength;
        $checkFor['down'] = $i + $rowLength;
        $checkFor['left'] = $i - 1;
        $checkFor['right'] = $i + 1;
        $checkFor['leftUp'] = $i - $rowLength - 1;
        $checkFor['rightUp'] = $i - $rowLength + 1;
        $checkFor['leftDown'] = $i + $rowLength - 1;
        $checkFor['rightDown'] = $i + $rowLength + 1;


        foreach($checkFor as $direction => $index) {
            if(checkCharacterIndex($index)) {
                if(isSpecialCharacter($inputString[$index])) {
                    $currentBufferIsPartNumber = true;
                }
                if($inputString[$index] == '*') {
                    $currentBufferIsToAstrix = $index;
                }
            }
        }

    } else {
        // if the character is not a number, check if the current buffer is a number
        if($currentBufferIsPartNumber) {
            // if it is, add the number to the total sum
            $totalSum += (int)$currentNumberBuffer;
        }

        if($currentBufferIsToAstrix) {
            $astrixBuffer[$currentBufferIsToAstrix][] = (int)$currentNumberBuffer;
        }

        // print number and if it is a part number
        if(!empty($currentNumberBuffer)) {
            //echo $currentNumberBuffer . ' ' . ($currentBufferIsPartNumber ? 'true' : 'false') . '<br/>';
        }

        $currentNumberBuffer = null;
        $currentBufferIsPartNumber = false;
        $currentBufferIsToAstrix = false;
    }
}

function isSpecialCharacter($character): bool
{
    // check if the given character is a special character (not a number or a .)
    return !is_numeric($character) && $character != '.';
}

function checkCharacterIndex($int) : bool {
    // check if the given index is a valid index in the input string
    global $inputString;
    return isset($inputString[$int]);
}

echo '<br/><br/>String length: ' . $rowLength . '<br/>';
echo '<pre>'.print_r($inputString, true).'</pre>';
//echo '<pre>'.print_r($input, true).'</pre>';
//echo '<pre>'.print_r($astrixBuffer, true).'</pre>';

echo '<br/><br/>Total sum: ' . $totalSum . '<br/><br/>';

echo '<br/><br/>Part 2: <br/><br/>';
$astrixSum = 0;
foreach($astrixBuffer as $index => $numbers) {
    if(count($numbers) == 2) {
        $astrixSum += $numbers[0] * $numbers[1];
    }
}
echo '<br/><br/>Astrix sum: ' . $astrixSum . '<br/><br/>';

exit;