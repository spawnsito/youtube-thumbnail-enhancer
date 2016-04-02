<?php

require_once 'ImageProperty.php';

class Image
{
    const HIGH_QUALITY = 'hq';
    const MEDIUM_QUALITY = 'mq';

    const HIGH_QUALITY_WIDTH = 480;
    const HIGH_QUALITY_HEIGHT = 270;
    const HIGH_QUALITY_COPY_WIDTH = 480;
    const HIGH_QUALITY_COPY_HEIGHT = 360;
    const HIGH_QUALITY_OFFSET_TOP = 45;
    const HIGH_QUALITY_OFFSET_LEFT = 0;

    const ICON_PATH = 'Resources/play-{quality}.png';

    private $image;
    private $quality;

    public function __construct($path, $quality = self::MEDIUM_QUALITY)
    {
        $this->image = new ImageProperty();
        $this->image->createFromJpeg($path);

        $this->quality = $quality;
        if ($quality == self::HIGH_QUALITY) {
            $this->convertToHighQuality();
        }
    }

    public function obtainImage()
    {
        return $this->image->obtainCanvas();
    }

    private function convertToHighQuality()
    {
        $image = new ImageProperty();
        $image->create(self::HIGH_QUALITY_WIDTH, self::HIGH_QUALITY_HEIGHT);
        $image->copyFrom($this->image, 0, 0, self::HIGH_QUALITY_OFFSET_LEFT, self::HIGH_QUALITY_OFFSET_TOP, self::HIGH_QUALITY_COPY_WIDTH, self::HIGH_QUALITY_COPY_HEIGHT);

        $this->image = $image;
    }

    public function addPlayIcon()
    {
        $playIcon = $this->obtainPlayIcon();
        $left = $this->calculateCenter($this->image->obtainWidth(), $playIcon->obtainWidth());
        $top = $this->calculateCenter($this->image->obtainHeight(), $playIcon->obtainHeight());

        $this->image->addWaterMark($playIcon, $left, $top);
    }

    public function render($quality, $path = null)
    {
        $this->image->renderAsJpeg($quality, $path);
    }

    private function obtainPlayIcon()
    {
        $path = str_replace('{quality}', $this->quality, self::ICON_PATH);
        $playIcon = new ImageProperty();
        $playIcon->createFromPng(__DIR__ . '/' . $path);
        $playIcon->addAlphaBlending();

        return $playIcon;
    }

    private function calculateCenter($image, $watermark)
    {
        return round($image / 2) - round($watermark / 2);
    }

}