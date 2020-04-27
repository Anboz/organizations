<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AuthTokenMiddleware extends BaseMiddleware
{

    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return redirect('/error', 404);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return redirect('/error', 404);
            }else{
                return redirect('/error', 404);
            }
        }
        return $next($request);
    }
}
///login
