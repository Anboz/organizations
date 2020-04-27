<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        $request->session()->put('column', 'id');
        $request->session()->put('order', 'asc');
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
