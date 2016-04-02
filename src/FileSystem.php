<?php

class FileSystem
{
    public function exists($path)
    {
        return file_exists($path);
    }
}