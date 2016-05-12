<?php

namespace Purple\Anbu\Modules\Debug;

use Illuminate\Foundation\Application;
use Purple\Anbu\Modules\AbstractModule;
use Symfony\Component\HttpFoundation\Response;

class Debug extends AbstractModule
{
    /**
     * The display name of the module.
     *
     * @var string
     */
    protected $name = 'Debug';

    /**
     * The short or URL friendly name of the module.
     *
     * @var string
     */
    protected $slug = 'debug';

    /**
     * A description of the modules purpose.
     *
     * @var string
     */
    protected $description = 'Debug objects outside of runtime environment.';

    /**
     * Icon for side menu.
     *
     * @var string
     */
    protected $icon = 'eye';

    /**
     * Executed before the profiled request.
     *
     * @param Application $app
     */
    public function before(Application $app)
    {
        // Initialize debugs array.
        $this->data['debugs'] = [];
    }

    /**
     * Debug an object or value.
     *
     * @return mixed
     */
    public function debug($value)
    {
        // Start output buffer.
        ob_start();

        // Var dump value.
        var_dump($value);

        // Store output buffer in array.
        $this->data['debugs'][] = ob_get_clean();
    }

    /**
     * Executed after the profiled request.
     *
     * @param  Application  $app
     * @param  Response $response
     */
    public function after(Application $app, Response $response)
    {
        // Set badge count to number of debug entries.
        $this->badge = count($this->data['debugs']);
    }
}
