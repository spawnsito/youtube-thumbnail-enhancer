<?php

require_once __DIR__ . '/Configuration.php';
require_once __DIR__ . '/CurlRequest.php';
require_once __DIR__ . '/Image.php';
require_once __DIR__ . '/FileSystem.php';
require_once __DIR__ . '/YoutubeIdNotFoundException.php';
require_once __DIR__ . '/YoutubeResourceNotFoundException.php';
require_once __DIR__ . '/YoutubeStorage.php';

class YoutubeThumbnail
{
    const RESOURCE_PATH = '/../i/';

    private $curlRequest;
    private $cacheSystem;
    private $youtubeStorage;

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
    
    public function getCacheSystem()
    {
        if (!$this->cacheSystem) {
            $this->cacheSystem = new FileSystem(realpath(__DIR__ . self::RESOURCE_PATH));
        }

        return $this->cacheSystem;
    }
    
    public function setCacheSystem($cacheSystem)
    {
        $this->cacheSystem = $cacheSystem;
    }

    public function getYoutubeStorage()
    {
        if (!$this->youtubeStorage) {
            $this->youtubeStorage = new YoutubeStorage();
        }

        return $this->youtubeStorage;
    }

    public function setYoutubeStorage($youtubeStorage)
    {
        $this->youtubeStorage = $youtubeStorage;
    }

    public function create(Configuration $configuration)
    {
        $quality = $configuration->obtainQuality();
        $show_play_icon = $configuration->obtainShowPlayIcon();
        $play_btn_file_name = ($show_play_icon) ? "-play" : "";
        $youtubeId = $configuration->obtainYoutubeId();

        $filename = ($quality == "mq") ? $youtubeId . "-mq" : $youtubeId;
        $filename .= $play_btn_file_name;

        $filename = $filename . ".jpg";
        if ($this->getCacheSystem()->exists($filename) && !$configuration->obtainRefresh()) {
            return $this->getCacheSystem()->obtain($filename);
        }

        if (!$youtubeId) {
            throw new YoutubeIdNotFoundException();
        }

        if (!$this->isThereResponseFromYoutube($youtubeId)) {
            throw new YoutubeResourceNotFoundException();
        }

        $imagePath = $this->obtainPathYoutubeThumbnails($youtubeId, $quality);
        $imageObject = new Image($imagePath, $quality);
        if ($configuration->obtainShowPlayIcon()) {
            $imageObject->addPlayIcon();
        }

        $path = $this->getCacheSystem()->obtainPath() . $filename;
        $imageObject->render(95, $path);

        return $path;
    }

    private function isThereResponseFromYoutube($youtubeId)
    {
        return $this->getCurlRequest()->ping("https://www.youtube.com/watch/?v=" . $youtubeId);
    }

    private function obtainPathYoutubeThumbnails($youtubeId, $quality)
    {
        return $this->getYoutubeStorage()->obtainResource($youtubeId, $quality);
    }

}