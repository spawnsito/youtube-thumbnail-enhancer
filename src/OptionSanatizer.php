<?php

require_once 'Configuration.php';

class OptionSanatizer
{
    
    public function sanatize($options)
    {
        $options = $this->sanatizeShowPlayIconOption($options);
        $options = $this->sanatizeQualityOption($options);

        return $options;
    }

    private function sanatizeShowPlayIconOption($options)
    {
        if (!isset($options[Configuration::SHOW_PLAY_ICON_KEY])) {
            return $options;
        }

        $options[Configuration::SHOW_PLAY_ICON_KEY] = true;

        return $options;
    }

    private function sanatizeQualityOption($options)
    {
        $validValues = array(Configuration::LOW_QUALITY, Configuration::HIGH_QUALITY);
        if (isset($options[Configuration::QUALITY_KEY]) && in_array($options[Configuration::QUALITY_KEY], $validValues)) {
            return $options;
        }

        $options[Configuration::QUALITY_KEY] = Configuration::DEFAULT_QUALITY;

        return $options;
    }
}