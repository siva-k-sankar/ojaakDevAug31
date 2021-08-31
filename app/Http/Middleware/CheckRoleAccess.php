<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Auth;

use Closure;

class CheckRoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next ,$page, $action)
    {
        if(!empty(Auth::user()->id))
        {
            $response = DB::table("role_accesses")->where("page_id",$page)->where($action,"1")->where("role_id",Auth::user()->role_id)->count();
            //echo '<pre>';print_r( $response );die;
            if($response >= 1)
            {
                return $next($request);
            }
            else{
                /*if($page == '1'){
                    Auth::logout();
                }*/
                return redirect('admin/dashboard')->with('status', 'Sorry, you are not authorised to view / access this page!');
            }
        }else{
            return $next($request);
        }
    }
}
