<?php

include 'challengeData.php';

// get content from readme.example

$readme = file_get_contents('readme.example');


// create a html list of the challenges
$challengeList = '';
foreach ($challenges as $day => $challenge) {
    $challengeList .= '- ['.$challenge['title'].']('.$config['url_prefix'].$day.')'.PHP_EOL;
}


// write the readme.md file
$readme = str_replace('<!-- CHALLENGES -->', $challengeList, $readme);
file_put_contents('README.md', $readme);