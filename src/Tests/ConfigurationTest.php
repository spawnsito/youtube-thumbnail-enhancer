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
}
