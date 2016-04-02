<?php

require_once __DIR__ . '/../YoutubeThumbnail.php';
require_once __DIR__ . '/../Configuration.php';
require_once __DIR__ . '/../YoutubeIdNotFoundException.php';
require_once __DIR__ . '/../YoutubeResourceNotFoundException.php';
require_once __DIR__ . '/stubs/CurlRequestStub.php';
require_once __DIR__ . '/stubs/FileSystemStub.php';
require_once __DIR__ . '/stubs/YoutubeStorageStub.php';

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

        $fileSystem = new FileSystemStub(__DIR__ . '/output/');
        $youtubeThumbnail->setCacheSystem($fileSystem);
        $youtubeThumbnail->setYoutubeStorage(new YoutubeStorageStub());

        $output = $youtubeThumbnail->create($configuration);
        $expected = __DIR__ . '/fixtures/image.jpg';

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

    public function testObtainFromCache()
    {
        $options = array('inpt' => 'https://www.youtube.com/watch?v=8hRUiytcbf8');
        $configuration = new Configuration($options);

        $youtubeThumbnail = new YoutubeThumbnail($configuration);
        $youtubeThumbnail->setCurlRequest(new CurlRequestStub());

        $fileSystem = new FileSystemStub(__DIR__ . '/fixtures');
        $fileSystem->exists = true;
        $fileSystem->filename = __DIR__ . '/fixtures/image.jpg';
        $youtubeThumbnail->setCacheSystem($fileSystem);

        $expected = __DIR__ . '/fixtures/image.jpg';
        $output = $youtubeThumbnail->create($configuration);

        $this->assertEquals($expected, $output);
    }

    public function testCreateHighQualityThumbnail()
    {
        $options = array('quality' => 'hq', 'inpt' => 'https://www.youtube.com/watch?v=8hRUiytcbf8');
        $configuration = new Configuration($options);

        $youtubeThumbnail = new YoutubeThumbnail($configuration);
        $youtubeThumbnail->setCurlRequest(new CurlRequestStub());

        $fileSystem = new FileSystemStub(__DIR__ . '/output/');
        $youtubeThumbnail->setCacheSystem($fileSystem);

        $output = $youtubeThumbnail->create($configuration);
        $this->assertFileExists($output);

        unlink($output);
    }

    public function testCreateHighQualityWithWatermarkThumbnail()
    {
        $options = array('quality' => 'hq', 'inpt' => 'https://www.youtube.com/watch?v=8hRUiytcbf8', 'play' => true);
        $configuration = new Configuration($options);

        $youtubeThumbnail = new YoutubeThumbnail($configuration);
        $youtubeThumbnail->setCurlRequest(new CurlRequestStub());

        $fileSystem = new FileSystemStub(__DIR__ . '/fixtures/');
        $youtubeThumbnail->setCacheSystem($fileSystem);

        $output = $youtubeThumbnail->create($configuration);
        $this->assertFileExists($output);

        unlink($output);
    }

    public function testCreateMediumQualityWithWatermarkThumbnail()
    {
        $options = array('quality' => 'mq', 'inpt' => 'https://www.youtube.com/watch?v=8hRUiytcbf8', 'play' => true);
        $configuration = new Configuration($options);

        $youtubeThumbnail = new YoutubeThumbnail($configuration);
        $youtubeThumbnail->setCurlRequest(new CurlRequestStub());

        $fileSystem = new FileSystemStub(__DIR__ . '/output/');
        $youtubeThumbnail->setCacheSystem($fileSystem);

        $output = $youtubeThumbnail->create($configuration);
        $this->assertFileExists($output);

        unlink($output);
    }


}