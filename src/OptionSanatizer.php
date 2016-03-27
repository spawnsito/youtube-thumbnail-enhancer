<?php

require_once 'Configuration.php';

class OptionSanatizer
{
    const HIGH_QUALITY = 'hq';
    const LOW_QUALITY = 'hq';

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
        $validValues = array(self::LOW_QUALITY, self::HIGH_QUALITY);
        if (isset($options[Configuration::QUALITY_KEY]) && in_array($options[Configuration::QUALITY_KEY], $validValues)) {
            return $options;
        }

        $options[Configuration::QUALITY_KEY] = Configuration::DEFAULT_QUALITY;

        return $options;
    }
}