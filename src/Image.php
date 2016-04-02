<?php

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

    const ICON_FILENAME = 'Resources/play-{quality}.png';

    private $canvas;
    private $quality;

    public function __construct($path, $quality = self::MEDIUM_QUALITY)
    {
        $this->canvas = imagecreatefromjpeg($path);
        $this->quality = $quality;
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
        $canvas = imagecreatetruecolor(self::HIGH_QUALITY_WIDTH, self::HIGH_QUALITY_HEIGHT);
        imagecopy($canvas, $this->canvas, 0, 0, self::HIGH_QUALITY_OFFSET_LEFT, self::HIGH_QUALITY_OFFSET_TOP, self::HIGH_QUALITY_COPY_WIDTH, self::HIGH_QUALITY_COPY_HEIGHT);
        $this->canvas = $canvas;
    }

    public function addPlayIcon()
    {
        $imageWidth = imagesx($this->canvas);
        $imageHeight = imagesy($this->canvas);

        $play_icon = str_replace('{quality}', $this->quality, self::ICON_FILENAME);

        $logoImage = imagecreatefrompng(__DIR__ . '/' . $play_icon);
        imagealphablending($logoImage, true);

        $logoWidth = imagesx($logoImage);
        $logoHeight = imagesy($logoImage);

        $left = round($imageWidth / 2) - round($logoWidth / 2);
        $top = round($imageHeight / 2) - round($logoHeight / 2);

        $copyFilename = 'copy_png.png';
        imagecopy($this->canvas, $logoImage, $left, $top, 0, 0, $logoWidth, $logoHeight);
        imagepng($this->canvas, $copyFilename, 9);

        $input = imagecreatefrompng($copyFilename);
        $output = imagecreatetruecolor($imageWidth, $imageHeight);
        $white = imagecolorallocate($output, 255, 255, 255);
        imagefilledrectangle($output, 0, 0, $imageWidth, $imageHeight, $white);
        imagecopy($output, $input, 0, 0, 0, 0, $imageWidth, $imageHeight);
        $this->canvas = $output;

        @unlink($copyFilename);
    }

}