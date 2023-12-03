<?php

include 'update_md.php';

$folders = '';
foreach($challenges as $day => $challenge) {
    $folders .= '<a href="'.$challenge['folder'].'">'.$challenge['title'].'</a><br/>';
}

// select a random header image from the headers folder
$aHeaders = scandir('headers');
$randomHeader = $aHeaders[rand(2, count($aHeaders) - 1)];

// get the content of index.htm and replace the placeholder with the list of folders
$index = file_get_contents('index.htm');
$index = str_replace('<!-- HEADER -->', $randomHeader, $index);
$index = str_replace('<!-- FOLDERS -->', $folders, $index);
echo $index;