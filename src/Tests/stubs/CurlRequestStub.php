<?php

require_once __DIR__ . '/../../CurlRequest.php';

class CurlRequestStub extends CurlRequest
{
    public $ping = true;

    public function ping($url)
    {
        return $this->ping;
    }

}