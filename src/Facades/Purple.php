<?php

namespace Purple\Anbu\Facades;

use App;

class Purple
{
    /**
     * Proxy static method calls to module instances.
     *
     * @param  string $method
     * @param  mixed $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        // Resolve profiler from container.
        $profiler = App::make(\Purple\Anbu\Purple::class);
        // Return the module instance by method name.
        return $profiler->getModule($method);
    }
}