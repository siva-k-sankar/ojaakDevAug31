<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Setting;
use Auth;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $footer=Setting::first();
        View::share('Footer_setting', $footer);

        view()->composer('*', function($view) {

            if(Auth::check()){
                    $findchats=DB::table('chats')
                    ->orWhere(function($query) {
                        $query->where('chats.user_1','=',Auth::user()->id);
                        $query->orwhere('chats.user_2','=',Auth::user()->id);
                    })
                    ->get()->toArray();

                    if(!empty($findchats)){
                        $unread_msg_count_id = array();
                        foreach ($findchats as $key => $chatid) {
                        
                            if($chatid->user_1 == Auth::user()->id && $chatid->user_1_read_status=='0'){
                                $unread_msg_count_id[]=$chatid->id;
                                
                            }
                            if($chatid->user_2 == Auth::user()->id && $chatid->user_2_read_status=='0'){
                                $unread_msg_count_id[]=$chatid->id;
                            }
                        }
                        $chatUnreadCount = count($unread_msg_count_id);
                        $view->with('chatUnreadCount', $chatUnreadCount);
                    }
            }else{
                $view->with('chatUnreadCount', "0");
            }
        });
    }
}
