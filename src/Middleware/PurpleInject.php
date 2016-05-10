<?php

namespace Purple\Anbu\Middleware;

use Closure;
use Purple\Anbu\Purple;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  integer $disabled
     * @return mixed
     */
    public function handle($request, Closure $next, $disabled = 0)
    {
        $response = $next($request);
        $purple = $this->purple;
        if (intval($disabled)) $purple->disable();
        $purple->executeAfterHook($request, $response);
        return $response;
    }
}
