<?php

namespace Purple\Anbu\Modules;

use Illuminate\Foundation\Application;
use Purple\Anbu\Modules\AbstractModule;
use Symfony\Component\HttpFoundation\Response;

class Timers extends AbstractModule
{
    protected $template = 'timer';
    /**
     * The display name of the module.
     *
     * @var string
     */
    protected $name = 'Timers';

    /**
     * The short or URL friendly name of the module.
     *
     * @var string
     */
    protected $slug = 'timers';

    /**
     * A description of the modules purpose.
     *
     * @var string
     */
    protected $description = 'Custom timers, useful for application profiling.';

    /**
     * Icon for side menu.
     *
     * @var string
     */
    protected $icon = 'clock-o';

    /**
     * Array of microsecond start times for timers.
     *
     * @var array
     */
    protected $startTimes = [];

    /**
     * Does this module have a dashboard widget?
     *
     * @var boolean
     */
    protected $hasWidget = false;
    

    /**
     * Start a profile timer.
     *
     * @param  mixed $key
     * @return void
     */
    public function start($key)
    {
        // Add start microtime to start times array.
        $this->startTimes[$key] = microtime(true);
    }

    /**
     * End a profile timer.
     *
     * @param  mixed  $key
     * @param  string $comment
     */
    public function end($key, $comment = null)
    {
        $end = microtime(true);
        $start = $this->startTimes[$key];
        if (!$start) {
            return;
        }

        $duration = $end - $start;
        $this->data['times'][] = compact('key', 'start', 'end', 'duration', 'comment');
    }

    /**
     * Executed after the profiled request.
     *
     * @param  Application  $app
     * @param  Response $response
     */
    public function after(Application $app, Response $response)
    {
        $this->badge = count($this->data['times']);
    }

    public function before(Application $app)
    {
        $this->data['times'] = [];
    }
}
