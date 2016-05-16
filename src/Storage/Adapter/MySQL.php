<?php

namespace Purple\Anbu\Storage\Adapter;

use Illuminate\Database\Eloquent\Model;
use Purple\Anbu\Storage\StorageInterface;
use Purple\Anbu\Storage\StorageTrait;

class MySQL extends Model implements StorageInterface
{
    use StorageTrait;
    /**
     * Database table name.
     *
     * @var string
     */
    public $table = 'anbu';

    /**
     * Retrieve the unserialized data.
     *
     * @return array
     */

    public function setUri($uri)
    {
        $this->setAttribute('uri', $uri);
    }

    public function setTime($time)
    {
        $this->setAttribute('time', $time);
    }

    public function setStorage($storage)
    {
        $this->setAttribute('storage', base64_encode(serialize($storage)));
    }

    public function getData()
    {
        return unserialize(base64_decode($this->getAttribute('storage')));
    }
}
