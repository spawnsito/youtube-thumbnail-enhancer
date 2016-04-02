<?php

require 'src/Configuration.php';
require 'src/Image.php';

function isThereResponseFromYoutube($youtubeId)
{
    $handle = curl_init("https://www.youtube.com/watch/?v=" . $youtubeId);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($handle);

    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    if ($httpCode == 404 OR !$response) {
        return false;
    }

    curl_close($handle);
    return true;
}

$configuration = new Configuration($_REQUEST);
// PARAMETERS
$is_url = false;
$quality = $configuration->obtainQuality();
$inpt = $configuration->obtainInput();
$show_play_icon = $configuration->obtainShowPlayIcon();
$play_btn_file_name = ($show_play_icon) ? "-play" : "";

$youtubeId = $configuration->obtainYoutubeId();

// FILENAME
$filename = ($quality == "mq") ? $youtubeId . "-mq" : $youtubeId;
$filename .= $play_btn_file_name;


// IF EXISTS, GO
if (file_exists("i/" . $filename . ".jpg") && !$configuration->obtainRefresh()) {
    header("Location: i/" . $filename . ".jpg");
    die;
}

// IF NOT ID THROW AN ERROR
if (!$youtubeId) {
    header("Status: 404 Not Found");
    die("YouTube ID not found");
}

if (!isThereResponseFromYoutube($youtubeId)) {
    header("Status: 404 Not Found");
    die("No YouTube video found or YouTube timed out. Try again soon.");
}

$imagePath = "http://img.youtube.com/vi/" . $youtubeId . "/" . $quality . "default.jpg";
$imageObject = new Image($imagePath, $quality);
if ($configuration->obtainShowPlayIcon()) {
    $imageObject->addPlayIcon();
}
$imageObject->render(95, __DIR__ . '/i/'. $filename . '.jpg');
header("Location: i/" . $filename . ".jpg");
die;