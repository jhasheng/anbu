<?php
/**
 * Created by PhpStorm.
 * User: Krasen
 * Date: 16/5/16
 * Time: 16:58
 * Email: jhasheng@hotmail.com
 */

namespace Purple\Anbu\Modules;


use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;

class Authentication extends AbstractModule
{

    protected $login = false;

    protected $icon = 'user';

    protected $template = 'identity';

    protected $slug = 'user';

    protected $name = 'Identity';

    public function before(Application $app)
    {
        $event = $this->app['events'];

        $this->data['anth'] = [];

        $event->listen(Login::class, [$this, 'loginEventFired']);
        $event->listen(Logout::class, [$this, 'logoutEventFired']);
    }

    public function after(Application $app, Response $response)
    {
        // TODO: Implement after() method.
    }

    public function loginEventFired()
    {
        $this->data['auth'] = func_get_args();
        $this->login = true;
    }

    public function logoutEventFired()
    {
        $this->login = false;
    }
}