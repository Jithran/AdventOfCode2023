<?php

include 'update_md.php';

$folders = '';
foreach ($challenges as $day => $challenge) {
    $folders .= '
    <div class="card">
        <a href="/' . $challenge['folder'] . '/index.php">
            <img src="/images/day' . $day . '.webp" />
            <div class="info">
                <h2>' . $challenge['title'] . '</h2>
                ' . (isset($challenge['description']) ? '<p>' . $challenge['description'] . '</p>' : '') . '
            </div>
        </a>
        <a class="challenge_link" href="' . $config['url_prefix'] . $day . '" target="_blank">View Challenge</a>
    </div>';
}

// select a random header image from the headers folder
$aHeaders = scandir('headers');
$randomHeader = $aHeaders[rand(2, count($aHeaders) - 1)];
$randomHeader = 'header4.webp';

// get the content of index.htm and replace the placeholder with the list of folders
$index = file_get_contents('index.htm');
$index = str_replace('<!-- HEADER -->', $randomHeader, $index);
$index = str_replace('<!-- FOLDERS -->', $folders, $index);
echo $index;