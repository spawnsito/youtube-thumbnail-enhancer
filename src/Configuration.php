<?php

require_once 'OptionSanatizer.php';

class Configuration
{
    const QUALITY_KEY = 'quality';
    const SHOW_PLAY_ICON_KEY = 'play';
    const INPUT_KEY = 'inpt';
    const REFRESH_KEY = 'refresh';

    const HIGH_QUALITY = 'hq';
    const LOW_QUALITY = 'mq';

    const DEFAULT_QUALITY = self::LOW_QUALITY;
    const DEFAULT_SHOW_PLAY_ICON = false;

    const ID_PATTERN = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';

    private $options;

    private $defaults = array(
        self::QUALITY_KEY => self::DEFAULT_QUALITY,
        self::SHOW_PLAY_ICON_KEY => self::DEFAULT_SHOW_PLAY_ICON,
        self::INPUT_KEY => '',
    );

    public function __construct($options = array())
    {
        $sanitizer = new OptionSanatizer($options);
        $options = $sanitizer->sanatize($options);
        
        $this->options = array_replace($this->defaults, $options);
    }

    public function obtainQuality()
    {
        return $this->options[self::QUALITY_KEY];
    }

    public function obtainShowPlayIcon()
    {
        return $this->options[self::SHOW_PLAY_ICON_KEY];
    }

    public function obtainInput()
    {
        return trim($this->options[self::INPUT_KEY]);
    }

    public function obtainYoutubeId()
    {
        if ($this->isUrl()) {
            return $this->obtainYouTubeIdFromURL();
        } else {
            return $this->obtainInput();
        }
    }

    public function obtainRefresh()
    {
        return isset($this->options[self::REFRESH_KEY]);
    }

    private function isUrl()
    {
        $input = $this->obtainInput();
        $urlValues = array('youtube.', 'youtu.be', 'https://');

        if (substr($input, 0, 4) == "www." || in_array(substr($input, 0, 8),  $urlValues)  ||  substr($input, 0, 7) == "http://") {
            return true;
        }

        return false;
    }

    private function obtainYouTubeIdFromURL()
    {
        preg_match(self::ID_PATTERN, $this->obtainInput(), $matches);

        if (!isset($matches[1])) {
            return $this->obtainInput();
        }

        return $matches[1];
    }

}