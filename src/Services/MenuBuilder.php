<?php

namespace Purple\Anbu\Services;

class MenuBuilder
{
    /**
     * Main menu array.
     * @var array
     */
    protected $menu = [];

    /**
     * Build a menu from a module array.
     * @param  array  $modules
     * @param  string $key
     * @return array
     */
    public function build(array $modules, $key)
    {
        foreach ($modules as $module) {
            if ($module->inMenu()) {
                $this->menu[] = $module->getMenuItem($key);
            }
        }
        return $this->menu;
    }
}
