<?php

ob_start();
$aFiles = scandir('.');
foreach ($aFiles as $file) {
    if (strpos($file, 'day') === 0) {
        $fileDisplay = ucfirst(preg_replace('/(\d+)/', ' $1', $file));
        echo '- <a href="' . $file . '/index.php">' . $fileDisplay . '</a><br/>';
    }
}
$folders = ob_get_clean();

// select a random header image from the headers folder
$aHeaders = scandir('headers');
$randomHeader = $aHeaders[rand(2, count($aHeaders) - 1)];

// get the content of index.htm and replace the placeholder with the list of folders
$index = file_get_contents('index.htm');
$index = str_replace('<!-- HEADER -->', $randomHeader, $index);
$index = str_replace('<!-- FOLDERS -->', $folders, $index);
echo $index;