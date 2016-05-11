<?php

namespace Purple\Anbu;

use App\Http\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Purple\Anbu\Command\ClearCommand;
use Purple\Anbu\Middleware\PurpleInject;
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
        $this->registerMiddleware(PurpleInject::class);
        $this->registerRoutes();
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register()
    {
        //默认配置文件
        $this->mergeConfigFrom(__DIR__ . '/../config/anbu.php', 'anbu');
        /**
         * @var $config \Illuminate\Config\Repository
         */
        $config     = $this->app['config'];
        $repo       = $config->get('anbu.repository', self::DEFAULT_REPO);
        $repository = $this->app->make($repo);
        $this->app->bind(Repository::class, $repo);
        /**
         * @var $purple Purple
         */
        $this->app->singleton(Purple::class, function ($app) use ($repository) {
            $purple = new Purple($repository);
            $purple->registerModules($app);
            return $purple;
        });

        $this->app['command.purple.clear'] = $this->app->share(function () use ($repository) {
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
        $route->middleware('purple', PurpleInject::class);
        $route->get('anbu/{storage?}/{module?}', [
            'as'         => 'anbu.show',
            'middleware' => 'purple:1',
            'uses'       => 'Purple\Anbu\Controller\ProfilerController@index'
        ]);
    }

    /**
     * Register the Debugbar Middleware
     *
     * @param  string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        /**
         * @var $kernel Kernel
         */
        $kernel = $this->app['Illuminate\Contracts\Http\Kernel'];
        $kernel->pushMiddleware($middleware);
    }

    protected function publishFiles()
    {
        //发布视图文件
        $this->publishes([
            __DIR__ . '/Resources/views/anbu' => base_path('resources/views/anbu'),
        ], 'view');
        //发布配置文件
        $this->publishes([
            __DIR__ . '/../config/anbu.php' => base_path('config/anbu.php'),
        ], 'config');
        //静态资源发布
        $this->publishes([
            __DIR__ . '/../resources' => base_path('public/anbu')
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
