<?php
 namespace App\Http\Controllers;
 use Illuminate\Http\Request;
 use Validator,Redirect,Response,File;
 use Socialite;
 use App\User;
 use App\Setting;
 use Carbon\Carbon;
 use DB;
 use Auth;
 use Toastr;
 use Illuminate\Support\Str;
 use Uuid;
use Cache;

 class SocialController extends Controller
    {
        public function redirect($provider)
        {
            return Socialite::driver($provider)->redirect();
        }
        public function callback($provider)
        {
            $getInfo = Socialite::driver($provider)->user(); 
            //echo '<pre>';print_r( $getInfo );die;
            $userid=Auth::id();
            if(empty(Auth::id())){
                $result=User::where('email', $getInfo->email)->first();
                //print_r($result);die;
                if(!empty($result)){
                    $result->google_id=$getInfo->id;
                    $result->save();
                    if($result->status == '1'){
                        auth()->login($result); 
                        return redirect('/');
                    }else{
                        $mail=DB::table('settings')->first();
                        //echo '<pre>';print_r( $mail );die;
                        $msg=$mail->email;
                        if($result->status == '0'){
                            toastr()->error("Account deactived");
                        }
                        if($result->status == '2'){
                            toastr()->error("Account Blocked");
                        }
                        if($result->status == '3'){
                            toastr()->error("Account Blocked");
                        }
                        
                        return redirect('/');
                        //return view('auth.login')->with('blockedmessage',$msg);
                       
                    }
                    
                }
                else{
                    $user = $this->createUser($getInfo,$provider); 
                    auth()->login($user); 
                    return redirect()->to('/');
                }    
            }else{
                $providers=ucwords($provider);
                if($provider=="google")
                {   
                    $user = User::where('google_id', $getInfo->id)->first();
                    if (!$user) {
                        $user =$this->updateUser($getInfo,$provider,$userid); 
                        toastr()->success("$providers A/C Updated");
                        return redirect()->to('/profile');
                    }else{
                        toastr()->error("$providers Id Already Exists !");
                        return redirect()->to('/profile');    
                    }  
                }
                if($provider=="facebook")
                {   
                    $user = User::where('facebook_id', $getInfo->id)->first();
                    if (!$user) {
                        $user =$this->updateUser($getInfo,$provider,$userid); 
                        toastr()->success("$providers A/C Updated.");
                        return redirect()->to('/profile');
                    }else{
                        toastr()->error("$providers Id Already Exists !");
                        return redirect()->to('/profile');    
                    }  
                }
            }   
        }
        public function createUser($getInfo,$provider)
        {
            $referralcode=referral();
            $uuid = Uuid::generate(4); 
            $path = public_path('uploads/profile/original/');
            $base64_image = base64_encode(file_get_contents($getInfo->avatar_original));
            $profile_img = user_profile_img($path,$base64_image);
            if($provider=="facebook")
            {   
                $user = User::where('facebook_id', $getInfo->id)->first();
                if (!$user) {
                    $user = User::create([
                    'name'     => $getInfo->name,
                    'role_id'     => "2",
                    'email'    => $getInfo->email,
                    'email_verified_at' => date("Y-m-d H:i:s"),
                    'referral_code' => $referralcode,
                    'uuid'=>$uuid,
                    'facebook_id' => $getInfo->id,
                    'photo'=> $profile_img,
                    'account_register_reference'=> '4',
                    ]);
                    $userdata=$user;
                    addpoint($user->id,'new_reg_point');
                    predefined_privacy_table($userdata->id);
                }
            }
            if($provider=="google")
            {  
                $user = User::where('google_id', $getInfo->id)->first();
                if (!$user) {
                    $user = User::create([
                    'name'     => $getInfo->name,
                    'role_id'     => "2",
                    'email'    => $getInfo->email,
                    'google_id' => $getInfo->id,
                    'uuid'=>$uuid,
                    'referral_code' => $referralcode,
                    'email_verified_at' => date("Y-m-d H:i:s"),
                    'photo'=> $profile_img,
                    'account_register_reference'=> '3',
                    ]);
                    $userdata=$user;
                    addpoint($user->id,'new_reg_point');
                    predefined_privacy_table($userdata->id);
                }
                    
            }
            return $user;
        }
        public function updateUser($getInfo,$provider,$userid)
        {
            if($provider=="google")
            {       
                
                $user = User::find(Auth::id());
                $user->google_id = $getInfo->id;
                $user->save();
                addpoint($user->id,'social_reg');
                return $user;
            }
            if($provider=="facebook")
            {   
                $user = User::find(Auth::id());
                $user->facebook_id = $getInfo->id;
                $user->save();
                addpoint($user->id,'social_reg');
                return $user;
            }
            
        }
}