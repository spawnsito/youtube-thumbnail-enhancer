<?php

class Image
{
    const HIGH_QUALITY = 'hq';
    const MEDIUM_QUALITY = 'mq';

    private $canvas;

    public function __construct($path, $quality = self::MEDIUM_QUALITY)
    {
        $this->canvas = imagecreatefromjpeg($path);
        if ($quality == self::HIGH_QUALITY) {
            $this->convertToHighQuality();
        }

    }

    public function obtainImage()
    {
        return $this->canvas;
    }

    private function convertToHighQuality()
    {
        $cleft = 0;
        $ctop = 45;
        $canvas = imagecreatetruecolor(480, 270);
        imagecopy($canvas, $this->canvas, 0, 0, $cleft, $ctop, 480, 360);
        $this->canvas = $canvas;
    }

}