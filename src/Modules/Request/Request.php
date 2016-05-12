<?php

namespace Purple\Anbu\Modules\Request;

use Illuminate\Foundation\Application;
use Purple\Anbu\Modules\AbstractModule;
use Symfony\Component\HttpFoundation\Response;

class Request extends AbstractModule
{
    /**
     * The display name of the module.
     *
     * @var string
     */
    protected $name = 'Request';

    /**
     * The short or URL friendly name of the module.
     *
     * @var string
     */
    protected $slug = 'request';

    /**
     * A description of the modules purpose.
     *
     * @var string
     */
    protected $description = 'View server environmental variables.';

    /**
     * Icon for side menu.
     *
     * @var string
     */
    protected $icon = 'refresh';

    /**
     * Executed after the profiled request.
     *
     * @param  Application  $app
     * @param  Response $response
     */
    public function after(Application $app, Response $response)
    {
        $request = $app->request;
        // Dump all request data into the class data array.
        $this->data['request'] = $request->all();

        // Place environmental variables into data array.
        $this->data['server'] = $request->server();

        // Place request headers in the data array.
        $this->data['requestHeaders'] = $request->header();

        // Place response headers in the data array.
        $this->data['responseHeaders'] = $response->headers->all();
    }

    public function before(Application $app)
    {
        // TODO: Implement before() method.
    }
}
