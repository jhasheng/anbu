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
use Purple\Anbu\Repositories\Repository;

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

        /**
         * @var $purple \Purple\Anbu\Purple
         */
        $purple = $this->app->make(Purple::class);

        $this->app->instance(Purple::class, $purple);
        $purple->registerHook();

        $this->registerRoutes();
    }

    protected function registerCommands()
    {
        $this->app['command.purple.clear'] = $this->app->share(function (Repository $repository) {
            return new ClearCommand($repository);
        });

        $this->commands('command.purple.clear');
        $this->installTable();
    }

    protected function registerRoutes()
    {
        /**
         * @var $route Router
         */
        $route = $this->app['router'];
        $route->get('anbu/{storage?}/{module?}', [
            'as'         => 'anbu.show',
//            'middleware' => 'purple:1',
            'uses'       => 'Purple\Anbu\Controller\ProfilerController@index'
        ]);
    }

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

    protected function installTable()
    {
        $schema = $this->app['db']->connection()->getSchemaBuilder();
        if (!$schema->hasTable('anbu')) {
            $schema->create('anbu', function (Blueprint $table) {
                $table->increments('id');
                $table->string('uri')->nullable();
                $table->float('time')->nullable();
                $table->longText('storage');
                $table->timestamps();
            });
        }
    }
}
