<?php

require_once 'OptionSanatizer.php';

class Configuration
{
    const QUALITY_KEY = 'quality';
    const SHOW_PLAY_ICON_KEY = 'play';

    const DEFAULT_QUALITY = 'mq';
    const DEFAULT_SHOW_PLAY_ICON = false;

    private $options;

    private $defaults = array(
        self::QUALITY_KEY => self::DEFAULT_QUALITY,
        self::SHOW_PLAY_ICON_KEY => self::DEFAULT_SHOW_PLAY_ICON,
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
}