<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\EmailUpdate;
use App\Mail\WorkEmailUpdate;
use Illuminate\Support\Facades\Mail;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;
use Auth;
use Hash;
use Image;

class ProfileController extends Controller
{
   	public $successStatus = 200;
   	public function __construct()
    {
    	$this->middleware('auth');
	}
	public function verifymail(Request $request)
    {   
        $input=$request->all();
        $findEmail=User::where('email', $request->mail)->first();
        if($findEmail)
        {
            return response()->json(['success'=>false,'message'=>'Email already registered'], $this->successStatus);
        }
        $strcode =Str::random(12);
        $numcode = mt_rand(100000, 999999);
        $randomcode=$strcode.$numcode;
        //echo"<pre>";print_r($input); die;
        $user = User::find(Auth::id());
        $user->temp_mail = $request->mail;
        $user->random_code = $randomcode;
        $user->save();
        $data['path']=url("/profile/mail/verify").'/'.$randomcode;
        $sendmail = Mail::to($request->mail)->send(new EmailUpdate($data));
       	if($sendmail)
        {	$success['message'] =  "Mail Send";
        	$success['user_name'] =  $user->name;
        	$success['user_id'] =  $user->id;
            return response()->json(['success'=>false,'data'=>$success], $this->successStatus);
        }
        else{
        	$success['message'] =  "Mail Not Send";
        	$success['user_name'] =  $user->name;
        	$success['user_id'] =  $user->id;
        	return response()->json(['success'=>false,'data'=>$success], $this->successStatus);
        }
    }
    public function verifyworkmail(Request $request)
    {   
        $input=$request->all();
        $findEmail=User::where('email', $request->mail)->first();
        if($findEmail)
        {
            return response()->json(['success'=>false,'message'=>'Email already registered'], $this->successStatus);
        }
        $strcode =Str::random(12);
        $numcode = mt_rand(100000, 999999);
        $randomcode=$strcode.$numcode;
        //echo"<pre>";print_r($input); die;
        $user = User::find(Auth::id());
        $user->temp_mail = $request->mail;
        $user->random_code = $randomcode;
        $user->save();
        $data['path']=url("/profile/mail/work/verify").'/'.$randomcode;
        $sendmail = Mail::to($request->mail)->send(new WorkEmailUpdate($data));
        if($sendmail)
        {	$success['message'] =  "Mail Send";
        	$success['user_name'] =  $user->name;
        	$success['user_id'] =  $user->id;
            return response()->json(['success'=>false,'data'=>$success], $this->successStatus);
        }
        else{
        	$success['message'] =  "Mail Not Send";
        	$success['user_name'] =  $user->name;
        	$success['user_id'] =  $user->id;
        	return response()->json(['success'=>false,'data'=>$success], $this->successStatus);
        }
       
    }
}
