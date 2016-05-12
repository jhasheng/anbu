<?php

namespace Purple\Anbu\Modules;

use ReflectionClass;
use Illuminate\Foundation\Application;

abstract class AbstractModule implements ModuleInterface
{
    const URL = 'anbu/%s/%s';
    protected $name;
    protected $slug;
    protected $description = 'No description present.';
    protected $icon        = 'cubes';
    protected $template    = 'default';
    protected $widget      = 'widget';
    protected $badge       = 0;
    protected $inMenu      = true;
    protected $hasWidget   = false;
    protected $global      = [];
    protected $data        = [];
    protected $assets      = [];
    protected $app;
    protected $version     = '0.0.0';

    public function live()
    {
        // Called in the profiler request.
    }

    public function getName()
    {
        return $this->name;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function register(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the URL friendly name for this module.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get the description for this module.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the icon for this module.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Get the template view for this module.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Get the dashboard widget template view for this module.
     *
     * @return string
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * Set the badge count for this module.
     *
     * @param  integer $badge
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
    }

    /**
     * Get the badge count for this module.
     *
     * @return integer
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Show this module in the menu?
     *
     * @return bool
     */
    public function inMenu()
    {
        return $this->inMenu;
    }

    /**
     * Does this module have a widget?
     *
     * @return boolean
     */
    public function hasWidget()
    {
        return $this->hasWidget;
    }

    /**
     * Set the data array.
     *
     * @param  array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Access the modules template data array.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the global data array.
     *
     * @param  array $global
     * @return void
     */
    public function setGlobal(array $global)
    {
        $this->global = $global;
    }

    /**
     * Get global data array.
     *
     * @return array
     */
    public function getGlobal()
    {
        return $this->global;
    }

    /**
     * Get an array of accessible assets for this module.
     *
     * @return array
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * Get the path to the module directory.
     *
     * @return string
     */
    public function getPath()
    {
        // Create reflection class.
        $reflection = new ReflectionClass($this);

        // Get directory of class.
        return dirname($reflection->getFileName());
    }

    /**
     * Set the application instance for the module.
     *
     * @param Application $app
     */
    public function setApplication(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Retrieve a storable representation of this modules data.
     *
     * @return array
     */
    public function getStorage()
    {
        return [
            'data'   => $this->data,
            'global' => $this->global,
            'badge'  => $this->badge
        ];
    }

    /**
     * Build a menu item for this module.
     *
     * @param  string $key
     * @return array
     */
    public function getMenuItem($key)
    {
        return [
            'title' => $this->name,
            'slug'  => $this->slug,
            'url'   => url(sprintf(self::URL, $key, $this->slug)),
            'icon'  => $this->icon,
            'badge' => $this->badge,
        ];
    }
}
