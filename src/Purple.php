<?php
/**
 * Created by PhpStorm.
 * User: Krasen
 * Date: 16/5/12
 * Time: 18:01
 * Email: jhasheng@hotmail.com
 */

namespace Purple\Anbu;

use Purple\Anbu\Modules\ModuleInterface;
use Purple\Anbu\Repositories\DatabaseRepository;
use Purple\Anbu\Repositories\Repository;
use Purple\Anbu\Storage\Storage;
use Purple\Anbu\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Foundation\Application;

class Purple
{
    const VERSION = '1.1.0-stable';

    /**
     * @var $app \Illuminate\Foundation\Application
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
        \Purple\Anbu\Modules\Dashboard::class,
        \Purple\Anbu\Modules\RoutesBrowser::class,
        \Purple\Anbu\Modules\Request::class,
        \Purple\Anbu\Modules\History::class,
        \Purple\Anbu\Modules\Info::class,
        \Purple\Anbu\Modules\Timers::class,
        \Purple\Anbu\Modules\Debug::class,
        \Purple\Anbu\Modules\Events::class,
        \Purple\Anbu\Modules\Logger::class,
        \Purple\Anbu\Modules\QueryLogger::class,
//        'Anbu\Modules\Container',
    ];

    public function __construct(Application $app, DatabaseRepository $repository)
    {
        $this->app        = $app;
        $this->repository = $repository;
        foreach ($this->defaultModules as $module) {
            array_push($this->modules, $app->make($module, [$this->repository]));
        }
    }

    public function getModule($key)
    {
        $module = array_filter($this->modules, function (ModuleInterface $val) use ($key) {
            if ($val->getSlug() === $key) return $val;
            return false;
        });
        return $module ? array_pop($module) : null;
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function registerHook()
    {
        foreach ($this->modules as $module) {
            $module->register($this->app);
        }
    }

    public function beforeHook()
    {
        foreach ($this->modules as $module) {
            $module->before($this->app);
        }
    }

    public function afterHook(Response $response)
    {
        /**
         * @var $module ModuleInterface
         */
        foreach ($this->modules as $module) {
            $module->after($this->app, $response);
        }
        $this->endHook();
        $this->displayButton($response, 0);
    }

    /**
     * @return bool
     */
    public function isAnbuRequest()
    {
        return $this->app['request']->segment(1) == 'anbu';
    }
    
    public function inConsole()
    {
        return $this->app->runningInConsole();
    }

    protected function endHook()
    {
        $adapter = $this->app['config']->get('anbu.adapter', 'mysql');
        /**
         * @var $storage StorageInterface
         */
        $storage = new Storage($adapter);
        $storage->setUri($this->getCurrentRequestUri());
        $storage->setTime(microtime(true) - LARAVEL_START);
        /**
         * @var $module ModuleInterface
         */
        $result = [];
        foreach ($this->modules as $module) {
            $result[$module->getSlug()] = $module->getData();
        }
        $storage->setStorage($result);
        $this->repository->put($storage);
    }

    protected function displayButton(Response $response, $id)
    {
        $header = $response->headers;
        if (strstr($header->get('Content-Type'), 'text/html')) {
            $response->setContent($response->getContent() . $this->renderButtonHtml($id));
        }
    }

    protected function renderButtonHtml($id)
    {
        return view('anbu.button', compact('id'))->render();
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
        $current = $this->app['router']->current();
        $request = $this->app['request'];
        return "{$request->method()} {$current->getPath()}";
    }
}