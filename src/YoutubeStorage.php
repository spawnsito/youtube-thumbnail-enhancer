<?php


class YoutubeStorage
{
    const PATH = "http://img.youtube.com/vi/{id}/{quality}default.jpg";

    public function obtainResource($id, $quality)
    {
        return str_replace(array('{id}', '{quality}'), array($id, $quality), self::PATH);
    }
}