<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Uuid;
use Image;
use Auth;
use App\Following;
use Carbon\Carbon;
use App\Customer_manage_user;

class FollowersController extends Controller{
   
    public function follow(Request $request){
        $input=$request->all();
        $user = auth()->user();
        $uuid=Uuid::generate(4);
        $follow = DB::table('users')
        ->where('uuid',$input['id'])
        ->first();
        if(!empty($follow)){

            if($user->id != $follow->id){
                $record = DB::table('followers')
                ->where('user_id',$user->id)
                ->where('following',$follow->id)
                ->first();
                if(empty($record)){
                    $following = Following::firstOrCreate(['user_id'=>$user->id,'following'=>$follow->id],['uuid'=>$uuid,'user_id'=>$user->id,'following'=>$follow->id]);
                    return 1;die;
                }else{
                    $unfollow = DB::table('followers')
                    ->where('user_id',$user->id)
                    ->where('following',$follow->id)
                    ->delete();
                    return 0;die;                       
                }
            }
        }
    }

    public function blockuser(Request $request){
        $input=$request->all();
        $users = DB::table('users')->where('uuid',$input['uuuid'])->first();
        if(!empty($users) && $input['statuschanges'] == 'Block'){
            $following = Customer_manage_user::firstOrCreate(['user_id'=>auth()->user()->id,'block_user_id'=>$users->id],['user_id'=>auth()->user()->id,'block_user_id'=>$users->id]); 
                    echo "1";die;
        }else{
            $following = Customer_manage_user::where(['user_id'=>auth()->user()->id,'block_user_id'=>$users->id])->delete();
                    echo "2";die;  
        }
        return 1;

    }

}
