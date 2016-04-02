<?php

class FileSystem
{
    const CURRENT_PATH = './';

    protected $path;

    public function __construct($path = self::CURRENT_PATH)
    {
        $this->path = $path;
    }

    public function exists($path)
    {
        return file_exists($path);
    }

    public function obtain($filename)
    {
        return sprintf('%s/%s', $this->path, $filename);
    }

    public function obtainPath()
    {
        return $this->path;
    }
}