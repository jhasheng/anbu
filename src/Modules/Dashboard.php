<?php

namespace Purple\Anbu\Modules;

use Purple\Anbu\Purple;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;

class Dashboard extends AbstractModule
{
    protected $template = 'dashboard';
    /**
     * The display name of the module.
     *
     * @var string
     */
    protected $name = 'Dashboard';

    /**
     * The short or URL friendly name of the module.
     *
     * @var string
     */
    protected $slug = 'dashboard';

    /**
     * A description of the modules purpose.
     *
     * @var string
     */
    protected $description = 'Welcome to Anbu. Enjoy your stay!';

    /**
     * Icon for side menu.
     *
     * @var string
     */
    protected $icon = 'dashboard';

    /**
     * Executed after the profiled request.
     *
     * @param  Application  $app
     * @param  Response $response
     */
    public function after(Application $app, Response $response)
    {
        // Bind Laravel version information.
        $this->global['version'] = Application::VERSION;
    }

    /**
     * Executed during the profiler request cycle.
     *
     * @return void
     */
    public function live()
    {
        /**
         * @var $module \Purple\Anbu\Modules\ModuleInterface
         */
        $profiler = $this->app->make(Purple::class);
        foreach ($profiler->getModules() as $module) {
            if ($module->hasWidget()) {
                $widget = new \stdClass();
                $widget->name   = $module->getName();
                $widget->badge  = $module->getBadge();
                $widget->view   = $this->renderWidget($module);

                $this->data['widgets'][] = $widget;
            }
        }
    }

    /**
     * Render a widget view.
     *
     * @param  ModuleInterface $module
     * @return View
     */
    protected function renderWidget(ModuleInterface $module)
    {
        return view('anbu.widget.widget', $module->getData())->render();
    }

    public function before(Application $app)
    {
        $this->data['widgets'] = [];
    }
}
