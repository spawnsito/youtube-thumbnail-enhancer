<?php

require_once __DIR__ . '/../YoutubeThumbnail.php';
require_once __DIR__ . '/../Configuration.php';
require_once __DIR__ . '/../YoutubeIdNotFoundException.php';
require_once __DIR__ . '/../YoutubeResourceNotFoundException.php';
require_once __DIR__ . '/stubs/CurlRequestStub.php';
require_once __DIR__ . '/stubs/FileSystemStub.php';

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
        $youtubeThumbnail->setCacheSystem(new FileSystemStub());

        $thumbnail = $youtubeThumbnail->create($configuration);

        $expected = __DIR__ . '/fixtures/image.jpg';
        $output = __DIR__ . '/fixtures/image_output.jpg';
        $thumbnail->render(95, $output);

        $this->assertFileEquals($expected, $output);

        unlink($output);
    }

    public function testIdYoutubeNotFound()
    {
        $options = array();
        $configuration = new Configuration($options);

        $youtubeThumbnail = new YoutubeThumbnail($configuration);
        $youtubeThumbnail->setCurlRequest(new CurlRequestStub());
        $youtubeThumbnail->setCacheSystem(new FileSystemStub());

        try {
            $youtubeThumbnail->create($configuration);
            $this->assertTrue(false);
        } catch (YoutubeIdNotFoundException $exception) {
            $this->assertTrue(true);
        }

    }

    public function testVideoYoutubeNotFound()
    {
        $options = array('inpt' => 'https://www.youtube.com/watch?v=8hRUiytcbf8');
        $configuration = new Configuration($options);

        $youtubeThumbnail = new YoutubeThumbnail($configuration);
        $curlRequest = new CurlRequestStub();
        $curlRequest->ping = false;

        $youtubeThumbnail->setCurlRequest($curlRequest);
        $youtubeThumbnail->setCacheSystem(new FileSystemStub());

        try {
            $youtubeThumbnail->create($configuration);
            $this->assertTrue(false);
        } catch (YoutubeResourceNotFoundException $exception) {
            $this->assertTrue(true);
        }

    }
}