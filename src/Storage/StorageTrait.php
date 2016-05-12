<?php

namespace Purple\Anbu\Storage;

trait StorageTrait
{
    protected $uri;

    protected $time;

    protected $storage;

    protected $adapter;

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function setStorage($storage)
    {
        $this->storage = base64_encode(serialize($storage));
    }

}