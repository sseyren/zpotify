<?php

namespace App\Http\Middleware;

use Closure;
use \App\Http\Controllers\APIBaseController as APIBaseController;
use JWTAuth;

class GetUserFromToken extends APIBaseController
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
        $user = JWTAuth::parseToken()->authenticate();
        return $user;
        //return $next($request);
    }
}
