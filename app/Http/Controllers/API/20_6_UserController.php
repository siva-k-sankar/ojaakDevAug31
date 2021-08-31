<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Edujugon\PushNotification\PushNotification;
use Validator;
use App\User;
use Image;
use DB;
use Illuminate\Support\Str;
use Uuid;
class UserController extends Controller
{
    public $successStatus = 200;
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login','register','sendotp','social','userinfo']]);

    }
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        $input = $request->all();

        if(Auth::attempt(['email' => $input['email'], 'password' => $input['password']])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('Ojaak')->accessToken;
            $success['user_name'] =  $user->name;
        	$success['user_id'] =  $user->id;
        	 return response()->json(['success' => $success], $this->successStatus);
        }else if(Auth::attempt(['phone_no' => $input['email'], 'password' => $input['password']])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('Ojaak')->accessToken;
            $success['user_name'] =  $user->name;
            $success['user_id'] =  $user->id;
            return response()->json(['success' => $success], $this->successStatus);
        }else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => '',
            'password' => 'required|min:6',
            'mobileno' => '',
            //'otp' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'image' => 'required',
        ]);
        $input = $request->all();
        //echo"<pre>";print_r($input); die;
        if ($validator->fails()) {     
            $message = $validator->errors();
            return response()->json(['success' => false, 'messages' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);      
        }else{
            if($input['email'] != ''){
                $userdata = User::where('email',$input['email'])->first();
                if(!empty($userdata)){
                    return response()->json(['success'=>false,'message'=>'Email already registered'], $this->successStatus);
                }
            }
            if($input['mobileno'] != ''){
                $userdata = User::where('phone_no',$input['mobileno'])->first();
                if(!empty($userdata)){
                    return response()->json(['success'=>false,'message'=>'Mobile number already registered'], $this->successStatus);
                }
            }
            $path = public_path('uploads/profile/original/');
            $base64_image = $input['image'];
            $profile_img = user_profile_img($path,$base64_image);
            $referralcode=referral(); 
            $uuid = Uuid::generate(4);
        	$postArray = [
            'name' => $request->name,
            'role_id' =>"2",
            'uuid'=>$uuid,
            'gender' => $request->gender,
            'phone_no' => $request->mobileno,
    		'dob' => date('Y-m-d', strtotime(str_replace('/', '-', $request->dob))),
            'status' => 2, //1=Active,2='In-Active,3=Delete'
            'photo'=>$profile_img,          
            'email'     => $request->email,
            'referral_code' => $referralcode,
            'created_at'=>date("Y-m-d H:i:s"),
            'password'  => bcrypt($request->password),
            ];
            $user = User::create($postArray);
            
            $success['token'] =  $user->createToken('ojaak')->accessToken;
        	$success['user_name'] =  $user->name;
        	$success['user_id'] =  $user->id;
            $success['mobile_valid'] =  (($user->phone_verified_at !='')?1:0);
            $success['email_valid'] =  (($user->email_verified_at !='')?1:0);
            if($user) {
                return response()->json(['success'=>true,'data'=>$success], $this->successStatus);
   			} else {
                return response()->json([
                  'success'      =>false,
                  'message'      => 'Registration failed, please try again.',
                ]);
            }
        }
	 }
     /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function userinfo()
    {   if (Auth::check()) {
          $data = Auth::user();
          $data['image_path'] =url('uploads/profile/original/');
          return response()->json(['success' => true,'data' => $data ], $this->successStatus);
        } else {
                return response()->json(['success'=>false,'message'=>'Please Login '], 422); 
        }
    }

    public function verifyotp(Request $request)
    {   
        $input = $request->all();
        if (Auth::check()) {
           if(env('SMS_OTP')==$input['otp']){
                $user = Auth::user();
                $userdata = User::find($user->id);
                $userdata->phone_verified_at =date("Y-m-d H:i:s");
                $userdata->save();
                $success['user_name'] =  $userdata->name;
                $success['user_id'] =  $userdata->id;
                $success['mobile_valid'] =  (($userdata->phone_verified_at !='')?1:0);
                $success['email_valid'] =  (($userdata->email_verified_at !='')?1:0);
                return response()->json(['success'=>true,'message'=>'Verified Mobile Number','data' => $success], $this->successStatus); 
            } else {
                return response()->json(['success'=>true,'message'=>'Not Valid OTP '], $this->successStatus); 
            }
        }    
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function logoutApi()
    { 
        if (Auth::check()) {
           Auth::user()->AauthAcessToken()->delete();
            return response()->json(['success'=>true,'message'=>'Logged out successfully'], $this->successStatus);
        }
    }
     /**
       * Social Login And Register
       *
       * @return \Illuminate\Http\Response
       */
       public function social(Request $request)
       {
            $input = $request->all();
            if($input['provider'] == 'google'){
            	$userdata = User::where('google_id',$input['provider_id'])->first();
        	}elseif($input['provider'] == 'facebook'){
            	$userdata = User::where('facebook_id',$input['provider_id'])->first();
        	}
            //echo"<pre>";print_r($userdata); die;
			if(!empty($userdata)){
		            $userdata['token'] =  $userdata->createToken('ojaak')->accessToken;
		        	$userdata['mobile_valid'] =  (($userdata->phone_verified_at !='')?1:0);
		        	$userdata['email_valid'] =  (($userdata->email_verified_at !='')?1:0);
                return response()->json(['success'=>true,'data'=>$userdata], $this->successStatus);
			}

            // Validations
            $rules = [
                    'name' => 'required',
		            'email' => '',
		            'mobileno' => '',
		            'dob' => 'required',
		            'gender' => 'required',
		            'provider' => 'required',
		            'provider_id' => 'required',
            ];
              $validator = Validator::make($request->all(), $rules);
              if ($validator->fails()) {
                // Validation failed
                return response()->json([
                  'message' => $validator->messages(),
                ]);
              }else {

              		if($input['email'] != ''){
              			$userdata = User::where('email',$input['email'])->first();
            			if(!empty($userdata)){
		                	return response()->json(['success'=>false,'message'=>'Email already registered'], $this->successStatus);
            			}
          			}
          			if($input['mobileno'] != ''){
              			$userdata = User::where('phone_no',$input['mobileno'])->first();
            			if(!empty($userdata)){
		                	return response()->json(['success'=>false,'message'=>'Mobile number already registered'], $this->successStatus);
            			}
          			}

                    $path = public_path('uploads/profile/original/');
                    $base64_image = $input['image'];
                    $profile_img = user_profile_img($path,$base64_image);
                    $referralcode=referral();
                    $uuid = Uuid::generate(4);
                    $social = $input['provider'].'_id';
		            $postArray = [
		            'name' => $request->name,
		            'role_id' =>"2",
                    'uuid'=>$uuid,
		            'gender' => $request->gender,
		            'phone_no' => $request->mobileno,
		    		'dob' => date('Y-m-d', strtotime(str_replace('/', '-', $request->dob))),
		            'status' => 2, //1=Active,2='In-Active,3=Delete'
		            'photo'=>$profile_img,          
		            'email'     => $request->email,
                    'referral_code' => $referralcode,
		             $social    => $request->provider_id,
		            'created_at'=>date("Y-m-d H:i:s"),
		            'password'  => bcrypt($request->password),
		            ];
		            $user = User::create($postArray);
		            $success['token'] =  $user->createToken('ojaak')->accessToken;
		        	$success['user_name'] =  $user->name;
		        	$success['user_id'] =  $user->id;
		        	$success['mobile_valid'] =  (($user->phone_verified_at !='')?1:0);
		        	$success['email_valid'] =  (($user->email_verified_at !='')?1:0);
		            if($user) {
		                return response()->json(['success'=>true,'data'=>$success], $this->successStatus);
		   			} else {
		                return response()->json([
		                  'success'      =>false,
		                  'message'      => 'Registration failed, please try again.',
		                ]);
		            }

              }

        }
        public function sendotp(Request $request){
            $validator = Validator::make($request->all(), [
                'mobileno' => ['required', 'digits:10'],
            ]);
            if ($validator->fails()) {
                //return response()->json(['error'=>$validator->errors()], 401);      
                $message = $validator->errors();
                return response()->json(['success' => false, 'messages' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);      
            }
            $input = $request->all();
            $mobile=$input['mobileno'];
            $sendotp =otp($mobile);
            $success['Otp'] =  $sendotp;
            if($sendotp) {
                return response()->json(['success'=>true,'data'=>$success], $this->successStatus);
            }else {
                return response()->json(['success'      =>false,'message' => 'Failed OTP Send',]);
            }

        }
        

}
