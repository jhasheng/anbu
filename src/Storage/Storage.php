<?php
/**
 * Created by PhpStorm.
 * User: Krasen
 * Date: 16/5/12
 * Time: 18:01
 * Email: jhasheng@hotmail.com
 */

namespace Purple\Anbu\Storage;


class Storage
{
    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->storage, $name], $arguments);
    }
}