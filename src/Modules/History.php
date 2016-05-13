<?php

namespace Purple\Anbu\Modules;

use Illuminate\Foundation\Application;
use Purple\Anbu\Modules\AbstractModule;
use Purple\Anbu\Repositories\Repository;
use Symfony\Component\HttpFoundation\Response;

class History extends AbstractModule
{
    protected $template = 'history';
    /**
     * Storage repository instance.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * The display name of the module.
     *
     * @var string
     */
    protected $name = 'History';

    /**
     * The short or URL friendly name of the module.
     *
     * @var string
     */
    protected $slug = 'history';

    /**
     * A description of the modules purpose.
     *
     * @var string
     */
    protected $description = 'Browse previous requests to the application.';

    /**
     * Icon for side menu.
     *
     * @var string
     */
    protected $icon = 'history';

    /**
     * Inject the storage repository.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Executed after the profiled request.
     *
     * @param  Application  $app
     * @param  Response $response
     */
    public function after(Application $app, Response $response)
    {
        // Bind the current URI to global data.
        $this->global['uri'] = $this->getCurrentRequestUri();
    }

    /**
     * Executed during the profiler request cycle.
     *
     * @return void
     */
    public function live()
    {
        // Bind all requests to data array.
        $history = $this->repository->all();
        // Get pagination component.
        // Create paginator.
        $this->data['history'] = $history;
        // Set badge to count of storage records.
        $this->badge = count($this->data['history']);
    }

    /**
     * Get the URI for the current request.
     *
     * @return string
     */
    protected function getCurrentRequestUri()
    {
        // Get the routing component.
        $current = $this->app['router']->current();
        // Get the current request.
        $request = $this->app['request'];
        // Return the current request.
        return "{$request->method()} {$current->getPath()}";
    }

    public function before(Application $app)
    {
        // TODO: Implement before() method.
    }
}
