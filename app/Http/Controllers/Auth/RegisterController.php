<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Uuid;
use DB;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {   
        $referralcode=referral();
        $uuid = Uuid::generate(4);
        if(is_numeric($data['email'])){

            return User::create([
            'name' => $data['name'],
            /*'phone_no' =>$data['phone'],*/
            'phone_no' => $data['email'],
            'role_id'=>"2",
            'uuid'=>$uuid,
            'password' => Hash::make($data['password']),
            'referral_code' =>$referralcode,
            ]);

        }else{

            return User::create([
            'name' => $data['name'],
            /*'phone_no' =>$data['phone'],*/
            'email' => $data['email'],
            'role_id'=>"2",
            'uuid'=>$uuid,
            'password' => Hash::make($data['password']),
            'referral_code' =>$referralcode,
            ]);
        } 
        
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {   
        //echo"<pre>123"; die;
        $this->validator($request->all())->validate();
        $input=$request->all();
        
            if(is_numeric($request->get('email'))){
                $result=DB::table('users')->where('phone_no', $input['email'])->first();
                if(!empty($result)){
                    toastr()->error('Mobile Number Already Taken');
                    return redirect()->back();
                }
            }else{
                $result=DB::table('users')->where('email', $input['email'])->first();
                if(!empty($result)){
                    toastr()->error('Email Already Taken');
                    return redirect()->back();
                }
            }

        //echo"<pre>123";print_r($input); die;
        if(!empty($input['referral_register'])){
            if(strstr($input['referral_register'],"OJAAK-")){
                $split_referral= substr($input['referral_register'],6);
                $refferralcheck  = User::where('referral_code',$split_referral)->first();
                if(!empty($refferralcheck)){
                    event(new Registered($user = $this->create($request->all())));
                        $this->guard()->login($user);
                        referralpoints($user->id,$split_referral);
                        predefined_privacy_table($user->id);
                        return $this->registered($request, $user)
                                    ?: redirect($this->redirectPath());
                }else{
                    toastr()->error('Invalid Referral Code  !.');
                    return redirect()->back();   
                }
            }else{
                toastr()->error('Invalid Referral Code  !.');
                return redirect()->back();   
            }
            
        }else{
            //echo"<pre>";print_r($input); die;
            event(new Registered($user = $this->create($request->all())));
                $this->guard()->login($user);
                predefined_privacy_table($user->id);
                return $this->registered($request, $user)
                                ?: redirect($this->redirectPath());

        }
                   
    }
}
