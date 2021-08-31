<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
//use Closure;
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
        if (! $request->expectsJson()) {
            //return route('login');
            //return route('welcome');
            //return route('invalidLoginError');
            if(Auth::check())
            {   
                if(Auth::user()->role_id == 2){
                    return $next($request);
                }else{
                    //auth()->logout();
                    return redirect()->route('admin.dashboard');
                }
                
            }else{
                //return $next($request);
                return route('welcome');        
            }
        }
    }
}
