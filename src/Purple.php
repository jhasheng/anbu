<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16/5/9
 * Time: 14:41
 */

namespace Purple\Anbu;

use Illuminate\Contracts\Foundation\Application;
use Purple\Anbu\Exceptions\InvalidModuleException;
use Purple\Anbu\Models\Storage;
use Purple\Anbu\Modules\Module;
use Purple\Anbu\Repositories\Repository;

class Purple
{
    const VERSION = '1.0.0-alpha2';

    /**
     * @var $app Application
     */
    protected $app;

    /**
     * @var $repository Repository
     */
    protected $repository;

    protected $modules = [];

    protected $enabled = true;

    protected $display = true;

    protected $defaultModules = [
        'Purple\Anbu\Modules\Dashboard\Dashboard',
        'Purple\Anbu\Modules\RoutesBrowser\RoutesBrowser',
        'Purple\Anbu\Modules\Request\Request',
        'Purple\Anbu\Modules\QueryLogger\QueryLogger',
        'Purple\Anbu\Modules\Logger\Logger',
        'Purple\Anbu\Modules\Events\Events',
        'Purple\Anbu\Modules\Debug\Debug',
        'Purple\Anbu\Modules\Timers\Timers',
        'Purple\Anbu\Modules\Info\Info',
        'Purple\Anbu\Modules\History\History',
//        'Anbu\Modules\Container\Container',
    ];
    
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * HTTP HOOK,进行HTTP分析和记录
     * @param $request
     * @param $response
     * @return mixed
     */
    public function executeAfterHook($request, $response)
    {
        if (!$this->enabled) {
            return false;
        }
        $this->executeModuleAfterHooks($request, $response);
        $this->storeModuleData();
        $type = $response->headers->get('Content-Type');
        if (strstr($type, 'text/html') && $this->display) {
//            echo view('anbu.button', compact('storage'));
        }
    }

    /**
     * 注册profiler模块
     * @param Application $app
     */
    public function registerModules(Application $app)
    {
        // Set application instance.
        $this->app = $app;
        $modules = $this->getModuleList();
        foreach ($modules as $module) {
            $this->registerModule($module);
        }
    }

    public function getModule($key)
    {
        return $this->modules[$key];
    }

    public function getModules()
    {
        return $this->modules;
    }
    
    public function disable()
    {
        $this->enabled = false;
    }

    /**
     * 获取所有模块
     * @return mixed
     */
    protected function getModuleList()
    {
        $config = $this->app->make('config');
        $modules = $config->get('anbu.modules', []);
        return array_merge($modules, $this->getDefaultModules());
    }

    /**
     * 注册profiler模块
     * @param $module
     */
    protected function registerModule($module)
    {
        $module = $this->app->make($module);
        if (!$module instanceof Module) {
            throw new InvalidModuleException;
        }
        $module->setApplication($this->app);
        $module->before();
        $this->modules[$module->getSlug()] = $module;
    }

    /**
     * 获取默认模块
     * @return array
     */
    protected function getDefaultModules()
    {
        return $this->defaultModules;
    }

    /**
     * 获取当前请求的URL
     * @return string
     */
    protected function getCurrentRequestUri()
    {
        /**
         * @var $current \Symfony\Component\Routing\Route
         */
        $current = $this->app->make('router')->current();
        $request = $this->app->make('request');
        return "{$request->method()} {$current->getPath()}";
    }

    /**
     * 分析单个模块
     * @param $request
     * @param $response
     */
    protected function executeModuleAfterHooks($request, $response)
    {
        foreach ($this->modules as $module) {
            $module->after($request, $response);
        }
    }

    /**
     * 保存profiler信息
     * @return Storage
     */
    protected function storeModuleData()
    {
        $storage = new Storage();
        $storage->uri = $this->getCurrentRequestUri();
        $storage->time = microtime(true) - LARAVEL_START;
        $storage->storage = base64_encode(serialize($this->fetchStorage()));
        $this->repository->put($storage);
        return $storage;
    }

    /**
     * 查询profiler信息
     * @return array
     */
    protected function fetchStorage()
    {
        $storage = [];
        foreach ($this->modules as $module) {
            $slug = $module->getSlug();
            $storage[$slug] = $module->getStorage();
        }
        return $storage;
    }

}