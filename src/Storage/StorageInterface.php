<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16/5/12
 * Time: 17:15
 */

namespace Purple\Anbu\Storage;


interface StorageInterface
{
    public function setUri($uri);

    public function setTime($time);

    public function setStorage($storage);

    public function getStorage();

}