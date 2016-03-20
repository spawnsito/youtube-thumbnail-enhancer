<?php


class Configuration
{
    const DEFAULT_QUALITY = 'mq';

    private $options;

    public function __construct($options = array())
    {
        $this->options = $options;
    }

    public function obtainQuality()
    {
        return isset($this->options['quality']) ? $this->options['quality'] : static::DEFAULT_QUALITY;
    }

    public function obtainShowPlayIcon()
    {
        return isset($this->options['play']) ? true : false;
    }
}