<?php

class Image
{
    private $canvas;

    public function __construct($path, $quality = 'mq')
    {
        $this->canvas = imagecreatefromjpeg($path);
        if ($quality == 'hq') {
            $cleft = 0;
            $ctop = 45;
            $canvas = imagecreatetruecolor(480, 270);
            imagecopy($canvas, $this->canvas, 0, 0, $cleft, $ctop, 480, 360);
            $this->canvas = $canvas;
        }

    }

    public function obtainImage()
    {
        return $this->canvas;
    }

}