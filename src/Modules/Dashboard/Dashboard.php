<?php

namespace Purple\Anbu\Modules\Dashboard;

use Purple\Anbu\Modules\AbstractModule;
use Purple\Anbu\Purple;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;

class Dashboard extends AbstractModule
{
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
        $this->data['widgets'] = [];
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
     * @param  Module $module
     * @return View
     */
    protected function renderWidget($module)
    {
        // Resolve view component.
        $view = $this->app->make('view');

        // Add a module view namespace for this widget.
        $view->addNamespace('anbu_widget', $module->getPath());

        // Extract template name.
        $template = $module->getWidget();

        // Return rendered template view.
        return $view->make("anbu_widget::{$template}", $module->getData());
    }

    public function before(Application $app)
    {
        // TODO: Implement before() method.
    }
}
