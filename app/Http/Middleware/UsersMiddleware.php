<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UsersMiddleware
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
        /*if(Auth::check())
        {
            if(Auth::user()->role_id == 2){
                //return redirect()->route('welcome');
                return $next($request);
            }else{
                auth()->logout();
                return redirect()->route('welcome');
            }
            
        }else{
            return $next($request);        
        }*/
        if(Auth::check())
        {
            if(Auth::user()->role_id == 2){
                return $next($request);
            }else{
                return back();
                //return redirect()->route('login');
            }
            
        }else{
            return $next($request);        
        }
    }
}
