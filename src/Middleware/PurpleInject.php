<?php

namespace Purple\Anbu\Middleware;

use Closure;
use Purple\Anbu\Purple;
use Symfony\Component\HttpFoundation\Request;

class PurpleInject
{

    protected $purple;

    public function __construct(Purple $purple)
    {
        $this->purple = $purple;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $purple = $this->purple;

        if (!$purple->isEnabled()) {
            return $next($request);
        }
        $purple->beforeHook();
        $response = $next($request);
        $purple->afterHook($response);
        return $response;
    }
}
