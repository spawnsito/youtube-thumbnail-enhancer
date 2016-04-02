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
}