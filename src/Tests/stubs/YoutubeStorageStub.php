<?php

class YoutubeStorageStub extends YoutubeStorage
{
    public function obtainResource($id, $quality)
    {
        return realpath(sprintf('%s/../fixtures/%s-%s.jpg', __DIR__, $id, $quality));
    }
}