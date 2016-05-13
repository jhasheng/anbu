<?php

namespace Purple\Anbu\Modules;

use Illuminate\Foundation\Application;
use Purple\Anbu\Modules\AbstractModule;
use Symfony\Component\HttpFoundation\Response;

class Info extends AbstractModule
{
    /**
     * The display name of the module.
     *
     * @var string
     */
    protected $name = 'Info';

    /**
     * The short or URL friendly name of the module.
     *
     * @var string
     */
    protected $slug = 'info';

    /**
     * A description of the modules purpose.
     *
     * @var string
     */
    protected $description = 'PHP environmental information.';

    /**
     * Icon for side menu.
     *
     * @var string
     */
    protected $icon = 'info-circle';
    
    protected $template = 'info';

    /**
     * Executed during the profiler request cycle.
     *
     * @return void
     */
    public function live()
    {
        // Start output buffer.
        ob_start();

        // Execute PHP info function.
        phpinfo();

        // Capture buffer contents.
        $info = ob_get_contents();

        // Clear the buffer.
        ob_end_clean();

        // We only want the body.
        $info = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $info);

        // Extract PHP version.
        preg_match('/\<h1 class=\"p\"\>PHP Version ([\d.]+)\<\/h1\>/', $info, $matches);

        // Replace the second title.
        $info = preg_replace('/\<h1 class=\"p\"\>PHP Version ([\d.]+)\<\/h1\>/', null, $info);

        // Set version into data array.
        $this->data['version'] = array_get($matches, '1');

        // Store in data array.
        $this->data['info'] =  $info;
    }

    public function before(Application $app)
    {
        // TODO: Implement before() method.
    }

    public function after(Application $app, Response $response)
    {
        // TODO: Implement after() method.
    }
}
