<?php

namespace Purple\Anbu\Repositories;

use Purple\Anbu\Storage\Storage;
use Purple\Anbu\Storage\StorageInterface;

interface Repository
{
    /**
     * Get a storage model.
     *
     * @param  string $key
     * @return StorageInterface
     */
    public function get($key = null);

    /**
     * Store a storage model.
     *
     * @param  Storage $storage
     * @return void
     */
    public function put(Storage $storage);

    /**
     * Clear all storage records.
     *
     * @return void
     */
    public function clear();

    /**
     * Get an array of all storage models.
     *
     * @return array
     */
    public function all();
}
