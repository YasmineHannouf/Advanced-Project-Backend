<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request)
    {
        return $request->expectsJson()?null : route('api/login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($jwt = $request->cookie('Authorisation')) {
            $request->headers->set('Authorization', 'Bearer ' . $jwt);
            $request->cookie('Authorisation','Bearer' . $jwt);
        }
        
    
    
        $this->authenticate($request, $guards);
    
        return $next($request);
    }
    
}
