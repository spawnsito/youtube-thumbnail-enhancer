<?php


class CurlRequest
{
    public function ping($url)
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($handle);

        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($httpCode == 404 OR !$response) {
            return false;
        }

        curl_close($handle);
        return true;
    }
}