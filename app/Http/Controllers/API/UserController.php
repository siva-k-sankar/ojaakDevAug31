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
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DateTime;

use App\Setting;
use App\Redeem;
use App\Freepoints;


class UserController extends Controller
{
    public $successStatus = 200;
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login','register','sendotp','social','userinfo','forgot_password']]);

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
            //'dob' => 'required',
            //'gender' => 'required',
            //'image' => 'required',
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
            //$path = public_path('uploads/profile/original/');
            //$base64_image = $input['image'];
            //$profile_img = user_profile_img($path,$base64_image);
            $referralcode=referral(); 
            $uuid = Uuid::generate(4);
        	$postArray = [
            'name' => $request->name,
            'role_id' =>"2",
            'uuid'=>$uuid,
            //'gender' => $request->gender,
            'phone_no' => $request->mobileno,
    		//'dob' => date('Y-m-d', strtotime(str_replace('/', '-', $request->dob))),
            'status' => 2, //1=Active,2='In-Active,3=Delete'
            //'photo'=>$profile_img,          
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
            $sendotp = otp($mobile);
            $success['Otp'] =  $sendotp;
            if($sendotp) {
                return response()->json(['success'=>true,'data'=>$success], $this->successStatus);
            }else {
                return response()->json(['success'=>false,'message' => 'Failed OTP Send',]);
            }

        }
        
      public function forgot_password(Request $request)
      {
            $input = $request->all();
            $rules = array(
              'email' => "required|email",
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
              $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
            } else {
              try {
                  $response = \Password::sendResetLink($request->only('email'), function (Message $message) {
                      $message->subject($this->getEmailSubject());
                  });
                  switch ($response) {
                      case \Password::RESET_LINK_SENT:
                          return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                      case \Password::INVALID_USER:
                          return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
                  }
              } catch (\Swift_TransportException $ex) {
                  $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
              } catch (Exception $ex) {
                  $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
              }
            }
            return \Response::json($arr);
    }

    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        //echo $userid;die;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);
    }


    public function redeemamount(Request $request){
        $input = $request->all();

        $rules = array(
            'sum' => 'required',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
            return \Response::json($arr);
        } else {

            $date = new DateTime('now');
            $date->modify("180 day"); 
            $date = $date->format('Y-m-d H:i:s'); 
            $now=date('Y-m-d H:i:s');  
            //$reddemid=explode(',', $input['Redeemtoken']);

            $reedemableamt = Setting::select('redeemable_amount')->where('id',1)->first();
            $pointstables = DB::table('freepoints')->where('user_id',auth()->user()->id)
                                        ->where('status','1')
                                        ->where('used','0')
                                        //->whereIn('id',$reddemid)
                                        ->where('expire_date','!=',null)
                                        ->whereDate('expire_date','>=',$now)->orderby('expire_date','asc')->get();
            $redeemamount=$input['sum'];
            if($redeemamount > auth()->user()->wallet_point){
                $arr = array("status" => 400, "message" => "Amount should be enter less than wallet amount", "data" => array());
                return \Response::json($arr);
            }  
            
            if ($redeemamount >= $reedemableamt->redeemable_amount && $redeemamount > 0){
                $proofs = DB::table('proofs')
                                    ->where('user_id',Auth::user()->id)
                                    ->where('proof',3)
                                    ->where('verified','1')
                                    ->first();

                if(!isset($proofs->verified)){   
                    $arr = array("status" => 400, "message" => "Please verify your pan card,to redeem your amount!", "data" => array());
                    return \Response::json($arr);     
                }
                if($proofs->verified == '1')
                {
                    $beneficiary = User::find(Auth::user()->id);
                    if(isset($beneficiary->phone_no) && $beneficiary->phone_no !=''){
                        $redeemWallet = redeemWallet($beneficiary->phone_no,$redeemamount);
                        if($redeemWallet['statusCode'] == 'DE_001' || $redeemWallet['statusCode'] == 'DE_101'){
                           
                           $updatefreepoint = DB::table('freepoints')->insert(['user_id' => $proofs->user_id,'description' => 'Amount Redeemed','status' => '0','used'=>'1','point'=>$redeemamount,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s"),'order_id' => generateRandomString()]);

                            $user= User::find(Auth::user()->id);
                            $userwallet=$user->wallet_point - $redeemamount;
                            $user->wallet_point=$userwallet;
                            $user->save();
                            $redeem= Redeem::create(['user_id'=>$proofs->user_id,'redeem_amt'=>$redeemamount,'mid'=>$redeemWallet['result']['mid'],'orderId'=>$redeemWallet['result']['orderId'],'paytmOrderId'=>$redeemWallet['result']['paytmOrderId'],'commissionAmount'=>$redeemWallet['result']['commissionAmount'],'tax'=>$redeemWallet['result']['tax']]);
                            $arr = array("status" => 200, "message" => "Amount is redeemed!", "data" => array());
                            return \Response::json($arr);    
                        }else{
                        $arr = array("status" => 400, "message" => "Redeem Transactions Failed!", "data" => array());
                        return \Response::json($arr); 
                        }
                    }else{
                    $arr = array("status" => 400, "message" => "Phone Number Not Found!", "data" => array());
                    return \Response::json($arr);   
                    }              
                }else{
                    $arr = array("status" => 400, "message" => "Please verify your pan card,to redeem your amount!", "data" => array());
                    return \Response::json($arr); 
                }
            }else{
                $arr = array("status" => 400, "message" => "Amount should be enter less than wallet amount", "data" => array());
                return \Response::json($arr); 
                //echo"0";exit();
            } 
        }   
        return \Response::json($arr);
    }


    public function walletpassbook(Request $request)
    {   

        $input = $request->all();
        $rules = array(
            'row' => 'required',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
            return \Response::json($arr);
        } else {

            $input = $request->all();

            $walletdatas = DB::table('freepoints')
                           ->select('freepoints.*','ads.uuid as adsuuid','ads.ads_ep_id as ads_ep_id')
                           ->leftJoin('ads','ads.id','=','freepoints.ads_id')
                           ->where('freepoints.user_id',auth()->user()->id);
                           
            if(isset($input['search']) && $input['search'] !=''){
                $searchValue = $input['search'];
                //echo "<pre>";print_r($searchValue);die;
                $walletdatas->where(function ($query) use ($searchValue) {
                    $query->orWhere("freepoints.description","like","%".$searchValue."%");
                    $query->orWhere("freepoints.point","like","%".$searchValue."%");
                    $query->orWhere("freepoints.created_at","like","%".$searchValue."%");
                    $query->orWhere("ads.ads_ep_id","like","%".$searchValue."%");
                    $query->orWhere("freepoints.order_id","like","%".$searchValue."%");
                    if(strtolower($searchValue) == 'cr' || strtolower($searchValue) == 'cre' || strtolower($searchValue) == 'cred' || strtolower($searchValue) == 'credi' || strtolower($searchValue) == 'credit'){
                        $query->orWhere("freepoints.status","like","%".'1'."%");
                    }
                    if(strtolower($searchValue) == 'de' || strtolower($searchValue) == 'deb' || strtolower($searchValue) == 'debi' || strtolower($searchValue) == 'debit'){
                        $query->orWhere("freepoints.status","like","%".'0'."%");
                    }
                    if(strtolower($searchValue) == 'ex' || strtolower($searchValue) == 'exp' || strtolower($searchValue) == 'expi' || strtolower($searchValue) == 'expir' || strtolower($searchValue) == 'expire' || strtolower($searchValue) == 'expired'){
                        $query->orWhere("freepoints.expire_date",'<',date('Y-m-d H:i:s'));
                    }
                });
                          
            }
            if(isset($input['fromdate']) && $input['fromdate'] !='' && isset($input['todate']) && $input['todate'] !=''){
                $fromdate = date('Y-m-d', strtotime($input['fromdate']));
                $todate = date('Y-m-d', strtotime($input['todate']));
                $walletdatas->whereDate('freepoints.created_at','>=', $fromdate);
                $walletdatas->whereDate('freepoints.created_at','<=', $todate);
            }
            $walletdatas->orderby('freepoints.id','desc');
            
            $data['wallet'] = $walletdatas->offset($input['row'])
                        ->limit(10)->get();

            foreach ($data['wallet'] as $wallet) {
                $point='';
                $status='';
                $ads=$wallet->description;
                if($wallet->point){
                    $point=number_format((float)$wallet->point, 2, '.', '');
                }
                if($wallet->status == '1'){
                    $status="Credited";
                }else if($wallet->status == '2'){
                    $status="Expired";
                }else{
                    $status="Debited";
                }
                if($wallet->description == 'Amount Redeemed'){
                    $status="Debited";
                }

                $row = array("title"=>$ads,"point"=>$point,"status"=>$status,"expire"=>(($wallet->expire_date != '')?date("d-M-Y",strtotime($wallet->expire_date)):"-"),"date"=>date("d-M-Y",strtotime($wallet->created_at)),"orderId"=>$wallet->order_id);
                
                $datas[] = $row;
            }

            $arr = array("status" => 200, "message" => "Wallet List", "data" => array($datas));
            return \Response::json($arr);  
        }

    }


    public function profileupdate(Request $request)
    {   
        $input = $request->all();
        // echo '<pre>';print_r($input);die;
        $rules = array(
            'name'      => 'required',
            'gender'      => 'required',
            'dob'      => 'required',
        );
        //echo date("Y-m-d",strtotime($request->dob));die;
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
            return \Response::json($arr);
        } else {

            $user = User::find(Auth::id());

            $user->name = $request->name;
            $user->dob = date("Y-m-d",strtotime($request->dob));
            $user->gender = $request->gender;
            $user->save();

            $arr = array("status" => 200, "message" => "Profile updated successfully!", "data" => array());
            return \Response::json($arr); 
        }
    }

}
