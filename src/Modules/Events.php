<?php

namespace Purple\Anbu\Modules;

use Illuminate\Foundation\Application;
use Purple\Anbu\Modules\AbstractModule;
use Symfony\Component\HttpFoundation\Response;

class Events extends AbstractModule
{
    protected $end = false;

    protected $template = 'events';
    /**
     * The display name of the module.
     *
     * @var string
     */
    protected $name = 'Events';

    /**
     * The short or URL friendly name of the module.
     *
     * @var string
     */
    protected $slug = 'events';

    /**
     * A description of the modules purpose.
     *
     * @var string
     */
    protected $description = 'List of fired events.';

    /**
     * Icon for side menu.
     *
     * @var string
     */
    protected $icon = 'exclamation-circle';

    /**
     * Executed before the profiled request.
     *
     * @param Application $app
     */
    public function before(Application $app)
    {
        // Initialize array.
        $this->data['events'] = [];

        /**
         * @var $event \Illuminate\Events\Dispatcher
         */
        $event = $this->app['events'];

        // Bind handler for all events.
        $event->listen('*', [$this, 'eventFired']);
    }

    /**
     * Log fired events.
     *
     * @return void
     */
    public function eventFired()
    {
        if ($this->end) return;
        // Get the events system.
        /**
         * @var $event \Illuminate\Events\Dispatcher
         */
        $event = $this->app['events'];
        // Add the event to the data array.
        $this->data['events'][] = [
            $event->firing(),
            microtime(true) - LARAVEL_START
        ];
    }

    /**
     * Executed after the profiled request.
     *
     * @param  Application  $app
     * @param  Response $response
     */
    public function after(Application $app, Response $response)
    {
        $this->badge = count($this->data['events']);
        $this->end = true;
    }
}
