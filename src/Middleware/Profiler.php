<?php
namespace Anbu\Middleware;

use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Symfony\Component\HttpFoundation\Request;
use Anbu\Profiler as AnbuProfiler;

class Profiler
{
    protected $app;

    protected $profiler;

    public function __construct(Application $app, AnbuProfiler $profiler)
    {
        $this->app = $app;
        $this->profiler  = $profiler;
    }

    public function handle($request, \Closure $next, $disable = null)
    {
        try {
            /** 
             * @var \Illuminate\Http\Response $response 
             */
            $response = $next($request);
        } catch (\Exception $e) {
            $response = $this->handleException($request, $e);
        }
        if ('disable' === $disable) $this->disable();
        $this->profiler->executeAfterHook($request, $response);
        return $response;
    }
    
    public function hide()
    {
        $this->profiler->hide();
    }
    
    public function disable()
    {
        $this->profiler->disable();
    }

    protected function handleException($passable, \Exception $e)
    {
        if (! $this->container->bound(ExceptionHandler::class) || ! $passable instanceof Request) {
            throw $e;
        }
        $handler = $this->container->make(ExceptionHandler::class);
        $handler->report($e);
        return $handler->render($passable, $e);
    }
}