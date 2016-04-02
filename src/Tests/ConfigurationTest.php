<?php

require_once __DIR__ . '/../Configuration.php';

class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf('Configuration', new Configuration());
    }

    public function testCreateWithDefaultQuality()
    {
        $configuration = new Configuration();
        $this->assertEquals('mq', $configuration->obtainQuality());
    }

    public function testCreateWithQualityOption()
    {
        $options = array('quality' => 'hq');
        $configuration = $this->createConfiguration($options);
        $this->assertEquals('hq', $configuration->obtainQuality());
    }

    public function testIsNormalQuality()
    {
        $configuration = new Configuration();
        $this->assertTrue($configuration->isNormalQuality());

        $options = array('quality' => 'hq');
        $configuration = new Configuration($options);
        $this->assertFalse($configuration->isNormalQuality());
    }

    public function testSanitizeQualityOption()
    {
        $options = array('quality' => 'void');
        $configuration = $this->createConfiguration($options);
        $this->assertEquals('mq', $configuration->obtainQuality());
    }

    public function testCreateWithShowPlayOption()
    {
        $options = array('play' => true);
        $configuration = $this->createConfiguration($options);
        $this->assertEquals(true, $configuration->obtainShowPlayIcon());
    }

    public function testInputOption()
    {
        $options = array('inpt' => 'https://www.youtube.com/watch?v=8hRUiytcbf8');
        $configuration = $this->createConfiguration($options);

        $this->assertEquals('https://www.youtube.com/watch?v=8hRUiytcbf8', $configuration->obtainInput());
    }

    public function testObtainYoutubeId()
    {
        $expectedId = '8hRUiytcbf8';
        $options = array('inpt' => 'https://www.youtube.com/watch?v=8hRUiytcbf8');
        $configuration = $this->createConfiguration($options);

        $this->assertEquals($expectedId, $configuration->obtainYoutubeId());

        $options = array('inpt' => 'http://www.youtube.com/watch?v=8hRUiytcbf8');
        $configuration = $this->createConfiguration($options);

        $this->assertEquals($expectedId, $configuration->obtainYoutubeId());

        $options = array('inpt' => 'youtube.com/watch?v=8hRUiytcbf8');
        $configuration = $this->createConfiguration($options);

        $this->assertEquals($expectedId, $configuration->obtainYoutubeId());

        $options = array('inpt' => 'www.youtube.com/watch?v=8hRUiytcbf8');
        $configuration = $this->createConfiguration($options);

        $this->assertEquals($expectedId, $configuration->obtainYoutubeId());

        $options = array('inpt' => 'http://youtu.be/8hRUiytcbf8');
        $configuration = $this->createConfiguration($options);

        $this->assertEquals($expectedId, $configuration->obtainYoutubeId());

        $options = array('inpt' => '8hRUiytcbf8');
        $configuration = $this->createConfiguration($options);

        $this->assertEquals($expectedId, $configuration->obtainYoutubeId());
    }

    public function testYoutubeIdNotFoundReturnInput()
    {
        $options = array('inpt' => 'http://google.es');
        $configuration = $this->createConfiguration($options);

        $this->assertEquals($options['inpt'], $configuration->obtainYoutubeId());
    }

    public function testYoutubeIdIsEmpty()
    {
        $options = array();
        $configuration = $this->createConfiguration($options);

        $this->assertEquals('', $configuration->obtainYoutubeId());
    }

    public function testObtainRefresh()
    {
        $options = array('refresh' => true);
        $configuration = $this->createConfiguration($options);
        $this->assertTrue($configuration->obtainRefresh());

        $configuration = $this->createConfiguration();
        $this->assertFalse($configuration->obtainRefresh());
    }

    /**
     * @return Configuration
     */
    private function createConfiguration($options = array())
    {
        return new Configuration($options);
    }
}
