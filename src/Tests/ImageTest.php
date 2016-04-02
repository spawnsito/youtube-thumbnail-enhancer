<?php

require_once '../Image.php';

class ImageTest extends PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf('Image', new Image('fixtures/image.jpg'));
    }

    public function testObtainImage()
    {
        $path = __DIR__ . '/fixtures/image.jpg';
        $image = new Image($path);

        $this->assertInternalType('resource', $image->obtainImage());
    }

    public function testCreateImageWithHighQuality()
    {
        $path = __DIR__ . '/fixtures/image.jpg';
        $image = new Image($path, 'hq');

        $this->assertEquals(480, imagesx($image->obtainImage()));
        $this->assertEquals(270, imagesy($image->obtainImage()));
    }

    public function testCreateImageWithMediumQuality()
    {
        $path = __DIR__ . '/fixtures/image.jpg';
        $image = new Image($path);

        $this->assertEquals(320, imagesx($image->obtainImage()));
        $this->assertEquals(180, imagesy($image->obtainImage()));
    }

    public function testAddPlayIcon()
    {
        $path = __DIR__ . '/fixtures/image-hq-no-processed.jpg';
        $image = new Image($path, 'hq');
        $image->addPlayIcon();
        
        $outputPath = __DIR__ . '/fixtures/image_create_play_icon.jpg';
        imagejpeg($image->obtainImage(), $outputPath, 95);

        $expectedPath = __DIR__ . '/fixtures/image-hq-play.jpg';
        $this->assertFileEquals($expectedPath, $outputPath);

        unlink($outputPath);
    }


}