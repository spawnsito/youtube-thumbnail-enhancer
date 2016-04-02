<?php

require_once __DIR__ . '/../YoutubeThumbnail.php';
require_once __DIR__ . '/../Configuration.php';
require_once __DIR__ . '/stubs/CurlRequestStub.php';

class YoutubeThumbnailTest extends PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf('YoutubeThumbnail', new YoutubeThumbnail());
    }

    public function testCreateMediumQualityThumbnail()
    {
        $options = array('quality' => 'mq', 'inpt' => 'https://www.youtube.com/watch?v=8hRUiytcbf8');
        $configuration = new Configuration($options);

        $youtubeThumbnail = new YoutubeThumbnail($configuration);
        $youtubeThumbnail->setCurlRequest(new CurlRequestStub());

        $thumbnail = $youtubeThumbnail->create($configuration);

        $expected = __DIR__ . '/fixtures/image.jpg';
        $output = __DIR__ . '/fixtures/image_output.jpg';
        $thumbnail->render(95, $output);

        $this->assertFileEquals($expected, $output);

        unlink($output);
    }
}