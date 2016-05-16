<?php
/**
 * Created by PhpStorm.
 * User: Krasen
 * Date: 16/5/12
 * Time: 18:01
 * Email: jhasheng@hotmail.com
 */

namespace Purple\Anbu\ServiceProvider;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Purple\Anbu\Command\ClearCommand;
use Purple\Anbu\Purple;
use Purple\Anbu\Repositories\DatabaseRepository;

class PurpleServiceProvider extends ServiceProvider
{

    const DEFAULT_REPO = 'Purple\Anbu\Repositories\DatabaseRepository';

    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishFiles();
    }

    public function register()
    {
        /**
         * @var $db \Illuminate\Database\Connection;
         */
        $db = $this->app['db'];
        $db->enableQueryLog();

        $config = $this->app['config'];

        $adapter     = $config->get('anbu.adapter', 'mysql');
        $storageName = $config->get('anbu.storage_name', 'anbu');
        $routePrefix = $config->get('anbu.route_prefix', 'anbu');

        /**
         * @var $purple \Purple\Anbu\Purple
         */
        $purple = $this->app->make(Purple::class);

        $this->app->instance(Purple::class, $purple);
        $purple->registerHook();

        if ('mysql' === $adapter) $this->installTable($storageName);
        $this->registerRoutes($routePrefix);
        $this->registerCommands();
    }

    /**
     * command注入
     */
    protected function registerCommands()
    {
        $repository                        = $this->app->make(DatabaseRepository::class);
        $this->app['command.purple.clear'] = $this->app->share(function () use ($repository) {
            return new ClearCommand($repository);
        });

        $this->commands('command.purple.clear');
    }

    /**
     * 路由注册
     * @param $prefix String 路由前缀
     */
    protected function registerRoutes($prefix)
    {
        /**
         * @var $route Router
         */
        $route = $this->app['router'];
        $route->get('{storage?}/{module?}', [
            'prefix' => $prefix,
            'as'     => 'anbu.show',
            'uses'   => 'Purple\Anbu\Controller\ProfilerController@index'
        ]);
    }

    /**
     * 发布资源文件
     */
    protected function publishFiles()
    {
        //发布视图文件
        $this->publishes([
            __DIR__ . '/../Resources/views/anbu' => base_path('resources/views/anbu'),
        ], 'view');
        //发布配置文件
        $this->publishes([
            __DIR__ . '/../../config/anbu.php' => base_path('config/anbu.php'),
        ], 'config');
        //静态资源发布
        $this->publishes([
            __DIR__ . '/../../resources' => base_path('public/anbu')
        ], 'asset');
    }

    /**
     * 初始化表结构
     * @param $tableName String 表名
     */
    protected function installTable($tableName)
    {
        $schema = $this->app['db']->connection()->getSchemaBuilder();
        if (!$schema->hasTable($tableName)) {
            $schema->create($tableName, function (Blueprint $table) {
                $table->increments('id');
                $table->string('uri')->nullable();
                $table->float('time')->nullable();
                $table->longText('storage');
                $table->timestamps();
            });
        }
    }
}
