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
        \Purple\Anbu\Modules\Events::class,
        \Purple\Anbu\Modules\Logger::class,
        \Purple\Anbu\Modules\QueryLogger::class,
        \Purple\Anbu\Modules\Debug::class,
        \Purple\Anbu\Modules\Timers::class,
        \Purple\Anbu\Modules\Info::class,
        \Purple\Anbu\Modules\History::class,
//        \Purple\Anbu\Modules\Authentication::class,
//        \Purple\Anbu\Modules\Container::class,
    ];

    public function __construct(Application $app, DatabaseRepository $repository)
    {
        $this->app        = $app;
        $this->repository = $repository;
        foreach ($this->defaultModules as $module) {
            array_push($this->modules, $app->make($module, [$this->repository]));
        }
    }

    /**
     * 获取指定模块
     * @param $key
     * @return mixed|null
     */
    public function getModule($key)
    {
        $module = array_filter($this->modules, function (ModuleInterface $val) use ($key) {
            if ($val->getSlug() === $key) return $val;
            return false;
        });
        return $module ? array_pop($module) : null;
    }

    /**
     * 获取所有模块信息
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * 注册启用的模块
     */
    public function registerHook()
    {
        foreach ($this->modules as $module) {
            $module->register($this->app);
        }
    }

    /**
     * 初始化模块，传入application对象
     */
    public function beforeHook()
    {
        foreach ($this->modules as $module) {
            $module->before($this->app);
        }
    }

    /**
     * 结束模块调用，传入Response对象
     * @param Response $response
     */
    public function afterHook(Response $response)
    {
        /**
         * @var $module ModuleInterface
         */
        foreach ($this->modules as $module) {
            $module->after($this->app, $response);
        }
        $result = $this->endHook();
        $this->displayButton($response, $result);
    }

    /**
     * 是否为profiler请求
     * @return bool
     */
    public function isAnbuRequest()
    {
        return $this->app['request']->segment(1) == 'anbu';
    }

    /**
     * 是否为CLI请求
     * @return bool
     */
    public function inConsole()
    {
        return $this->app->runningInConsole();
    }

    /**
     * 结束模块调用逻辑
     * @return StorageInterface
     */
    protected function endHook()
    {
        //获取存储方式
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
        //组装模块收集到的数据，填充至storage对象
        foreach ($this->modules as $module) {
            $result[$module->getSlug()] = $module->getStorage();
        }
        $storage->setStorage($result);
        $this->repository->put($storage);
        return $storage;
    }

    /**
     * 显示profiler入口到请求页面
     * @param Response $response
     * @param $storage
     */
    protected function displayButton(Response $response, $storage)
    {
        $header = $response->headers;
        if (strstr($header->get('Content-Type'), 'text/html')) {
            $response->setContent($response->getContent() . $this->renderButtonHtml($storage));
        }
    }

    /**
     * 渲染入口按钮
     * @param $storage
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    protected function renderButtonHtml($storage)
    {
        return view('anbu.button', compact('storage'))->render();
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