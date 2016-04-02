<?php

class FileSystemStub extends FileSystem
{
    public $exists = false;
    public $filename = null;

    public function exists($path)
    {
        return $this->exists;
    }

    public function obtain($filename)
    {
        if (!$this->filename) {
            return parent::obtain($filename);
        } else {
            return $this->filename;
        }
    }
}