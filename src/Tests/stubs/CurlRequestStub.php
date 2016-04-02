<?php

require_once __DIR__ . '/../../CurlRequest.php';

class CurlRequestStub extends CurlRequest
{
    public function ping($url)
    {
        return true;
    }

}