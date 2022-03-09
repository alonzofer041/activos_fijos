<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;

use Closure;

class SetCongreso
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
        URL::defaults(['congreso' => 'novatic2018']);
        return $next($request);
    }
}