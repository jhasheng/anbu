<?php

namespace Purple\Anbu\Modules;

use ReflectionClass;
use Illuminate\Foundation\Application;

abstract class Module
{
    /**
     * Menu URL format.
     */
    const URL = 'anbu/%s/%s';

    /**
     * The display name of the module.
     *
     * @var string
     */
    protected $name;

    /**
     * The short or URL friendly name of the module.
     *
     * @var string
     */
    protected $slug;

    /**
     * A description of the modules purpose.
     *
     * @var string
     */
    protected $description = 'No description present.';

    /**
     * Icon for side menu.
     *
     * @var string
     */
    protected $icon = 'cubes';

    /**
     * Get the template view for this module.
     *
     * @var string
     */
    protected $template = 'default';

    /**
     * Dashboard widget template.
     *
     * @var string
     */
    protected $widget = 'widget';

    /**
     * Count to show on menu.
     *
     * @var integer
     */
    protected $badge = 0;

    /**
     * Show this module in the side menu.
     *
     * @var boolean
     */
    protected $inMenu = true;

    /**
     * Does this module have a dashboard widget?
     *
     * @var boolean
     */
    protected $hasWidget = false;

    /**
     * An array of data that will be global to the profiler.
     *
     * @var array
     */
    protected $global = [];

    /**
     * An array of data for the rendering of this module.
     *
     * @var array
     */
    protected $data = [];

    /**
     * An array of accessible assets.
     *
     * @var array
     */
    protected $assets = [];

    /**
     * Laravel application instance for the current request.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Executed before the profiled request.
     *
     * @return void
     */
    public function before()
    {
        // Called during service provider registration.
    }

    /**
     * Executed after the profiled request.
     *
     * @param  Symfony/Component/HttpFoundation/Request  $response
     * @param  Symfony/Component/HttpFoundation/Response $response
     * @return void
     */
    public function after($request, $response)
    {
        // Called after the framework request cycle.
    }

    /**
     * Executed during the profiler request cycle.
     *
     * @return void
     */
    public function live()
    {
        // Called in the profiler request.
    }

    /**
     * Get the display name for this module.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param  integer $count
     * @return void
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
            'data'          => $this->data,
            'global'        => $this->global,
            'badge'         => $this->badge
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
            'title'     => $this->name,
            'slug'      => $this->slug,
            'url'       => url(sprintf(self::URL, $key, $this->slug)),
            'icon'      => $this->icon,
            'badge'     => $this->badge,
        ];
    }
}
