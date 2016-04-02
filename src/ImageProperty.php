<?php


class ImageProperty
{
    private $canvas;

    public function createFromJpeg($filename)
    {
        $this->canvas = imagecreatefromjpeg($filename);
    }

    public function create($width, $height)
    {
        $this->canvas = imagecreatetruecolor($width, $height);
    }

    public function copyFrom(ImageProperty $image, $offsetX, $offetY, $sourceOffsetX, $sourceOffsetY, $width, $height)
    {
        imagecopy($this->canvas, $image->obtainCanvas(), $offsetX, $offetY, $sourceOffsetX, $sourceOffsetY, $width, $height);
    }

    public function createFromPng($path)
    {
        $this->canvas = imagecreatefrompng($path);
    }

    public function obtainWidth()
    {
        return imagesx($this->canvas);
    }

    public function obtainHeight()
    {
        return imagesy($this->canvas);
    }

    public function obtainCanvas()
    {
        return $this->canvas;
    }

    public function addAlphaBlending()
    {
        imagealphablending($this->canvas, true);
    }

    public function addWaterMark(ImageProperty $watermark, $left, $top)
    {
        $this->copyFrom($watermark, $left, $top, 0, 0, $watermark->obtainWidth(), $watermark->obtainHeight());
    }

    public function renderAsJpeg($quality, $path = null)
    {
        imagejpeg($this->canvas, $path, $quality);
    }
}