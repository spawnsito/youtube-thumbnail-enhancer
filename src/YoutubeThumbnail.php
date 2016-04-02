<?php

require_once __DIR__ . '/Configuration.php';
require_once __DIR__ . '/CurlRequest.php';
require_once __DIR__ . '/Image.php';

class YoutubeThumbnail
{
    private $curlRequest;

    public function getCurlRequest()
    {
        if (!$this->curlRequest) {
            $this->curlRequest = new CurlRequest();
        }

        return $this->curlRequest;
    }

    public function setCurlRequest(CurlRequest $curlRequest)
    {
        $this->curlRequest = $curlRequest;
    }

    public function create(Configuration $configuration)
    {
        $quality = $configuration->obtainQuality();
        $show_play_icon = $configuration->obtainShowPlayIcon();
        $play_btn_file_name = ($show_play_icon) ? "-play" : "";

        $youtubeId = $configuration->obtainYoutubeId();

        $filename = ($quality == "mq") ? $youtubeId . "-mq" : $youtubeId;
        $filename .= $play_btn_file_name;

        if (file_exists("i/" . $filename . ".jpg") && !$configuration->obtainRefresh()) {
            header("Location: i/" . $filename . ".jpg");
            die;
        }

        if (!$youtubeId) {
            header("Status: 404 Not Found");
            die("YouTube ID not found");
        }

        if (!$this->isThereResponseFromYoutube($youtubeId)) {
            header("Status: 404 Not Found");
            die("No YouTube video found or YouTube timed out. Try again soon.");
        }

        $imagePath = "http://img.youtube.com/vi/" . $youtubeId . "/" . $quality . "default.jpg";
        $imageObject = new Image($imagePath, $quality);
        if ($configuration->obtainShowPlayIcon()) {
            $imageObject->addPlayIcon();
        }

        return $imageObject;
    }

    private function isThereResponseFromYoutube($youtubeId)
    {
        return $this->getCurlRequest()->ping("https://www.youtube.com/watch/?v=" . $youtubeId);
    }

}