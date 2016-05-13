<?php

namespace Purple\Anbu\Services;

use Purple\Anbu\Purple;
use Purple\Anbu\Storage\StorageInterface;

class ModuleHydrator
{
    /**
     * Profiler instance.
     * @var Purple
     */
    protected $purple;

    /**
     * Inject the anbu purple.
     * @param Purple $purple
     */
    public function __construct(Purple $purple)
    {
        $this->purple = $purple;
    }

    /**
     * Hydrate modules with data from storage record.
     * @param  StorageInterface $storage
     * @return void
     */
    public function hydrate(StorageInterface $storage)
    {
        $modules = $storage->getStorage();
        foreach ($modules as $slug => $module) {
            $m = $this->purple->getModule($slug);
            // Set module data from storage.
            $m->setData(isset($module['data']) ? $module['data'] : []);
            $m->setGlobal(isset($module['global']) ? $module['global'] : []);
            $m->setBadge(isset($module['badge']) ? $module['badge'] : []);
//            $m->setData(array_get($module, 'data'));
//            $m->setGlobal(array_get($module, 'global'));
//            $m->setBadge(array_get($module, 'badge'));
        }
    }
}
