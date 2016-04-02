<?php

class FileSystemStub extends FileSystem
{
    public $exists = false;

    public function exists($path)
    {
        return $this->exists;
    }

}