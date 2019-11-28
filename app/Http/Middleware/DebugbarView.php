<?php

namespace App\Http\Middleware;

use Closure;

class DebugbarView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('app.debug') && \Auth::check() && \Auth::user()->id == 1) {
            \Debugbar::enable();
        } else {
            \Debugbar::disable();
        }

        return $next($request);
    }
}
