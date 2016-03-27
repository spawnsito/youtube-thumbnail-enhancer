<?php


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
        $options = $this->sanatizeShowPlayIconOption($options);
        $options = $this->sanatizeQualityOption($options);
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

    private function sanatizeShowPlayIconOption($options)
    {
        if (!isset($options[self::SHOW_PLAY_ICON_KEY])) {
            return $options;
        }

        $options[self::SHOW_PLAY_ICON_KEY] = true;

        return $options;
    }

    private function sanatizeQualityOption($options)
    {
        $validValues = array('mq', 'hq');
        if (isset($options[self::QUALITY_KEY]) && in_array($options[self::QUALITY_KEY], $validValues)) {
            return $options;
        }
        
        $options[self::QUALITY_KEY] = self::DEFAULT_QUALITY;

        return $options;
    }
}