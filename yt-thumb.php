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

$image = $imageObject->obtainImage();

$imageWidth = imagesx($image);
$imageHeight = imagesy($image);


// ADD THE PLAY ICON
$play_icon = $show_play_icon ? "play-" : "noplay-";
$play_icon .= $quality . ".png";
$logoImage = imagecreatefrompng($play_icon);

imagealphablending($logoImage, true);

$logoWidth = imagesx($logoImage);
$logoHeight = imagesy($logoImage);

// CENTER PLAY ICON
$left = round($imageWidth / 2) - round($logoWidth / 2);
$top = round($imageHeight / 2) - round($logoHeight / 2);


// CONVERT TO PNG SO WE CAN GET THAT PLAY BUTTON ON THERE
imagecopy($image, $logoImage, $left, $top, 0, 0, $logoWidth, $logoHeight);
imagepng($image, $filename . ".png", 9);


// MASHUP FINAL IMAGE AS A JPEG
$input = imagecreatefrompng($filename . ".png");
$output = imagecreatetruecolor($imageWidth, $imageHeight);
$white = imagecolorallocate($output, 255, 255, 255);
imagefilledrectangle($output, 0, 0, $imageWidth, $imageHeight, $white);
imagecopy($output, $input, 0, 0, 0, 0, $imageWidth, $imageHeight);

// OUTPUT TO 'i' FOLDER
imagejpeg($output, "i/" . $filename . ".jpg", 95);

// UNLINK PNG VERSION
@unlink($filename . ".png");

// REDIRECT TO NEW IMAGE
header("Location: i/" . $filename . ".jpg");
die;

?>
