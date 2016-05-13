<?php
/**
 * Created by PhpStorm.
 * User: Krasen
 * Date: 16/5/12
 * Time: 18:01
 * Email: jhasheng@hotmail.com
 */

namespace Purple\Anbu\Storage;



use Purple\Anbu\Exception\InvalidAdapterException;

class Storage
{
    protected $storage;

    protected static $apdater = [
        'mysql' => \Purple\Anbu\Storage\Adapter\MySQL::class,
    ];

    public function __construct($adapter)
    {
        if (array_key_exists($adapter, self::$apdater)) {
            $className = self::$apdater[$adapter];
            $this->storage = new $className;
        } else {
            throw new InvalidAdapterException;
        }
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->storage, $name], $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        $name = self::$apdater['mysql'] . '::' . $name;
        return call_user_func_array($name, $arguments);
    }

}