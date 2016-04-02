<?php

require_once __DIR__ . '/src/Configuration.php';
require_once __DIR__ . '/src/YoutubeThumbnail.php';

$configuration = new Configuration($_REQUEST);
$youtubeThumbnail = new YoutubeThumbnail();

try {
    $output = $youtubeThumbnail->create($configuration);

    header("Location: " . str_replace(__DIR__, '', $output));
} catch (YoutubeIdNotFoundException $exception) {
    header("Status: 404 Not Found");
    die("YouTube ID not found");
} catch (YoutubeResourceNotFoundException $exception) {
    header("Status: 404 Not Found");
    die("No YouTube video found or YouTube timed out. Try again soon.");
}