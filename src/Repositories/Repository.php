<?php

namespace Purple\Anbu\Repositories;

use Purple\Anbu\Models\Storage;

interface Repository
{
    /**
     * Get a storage model.
     *
     * @param  string $key
     * @return Storage
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
