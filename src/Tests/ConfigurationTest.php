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
        $configuration = new Configuration($options);
        $this->assertEquals('hq', $configuration->obtainQuality());
    }
    
    public function testCreateWithShowPlayOption()
    {
        $options = array('play' => true);
        $configuration = new Configuration($options);
        $this->assertEquals(true, $configuration->obtainShowPlayIcon());
    }
}
