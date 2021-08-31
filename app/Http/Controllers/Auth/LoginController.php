<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User;
use DB;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;
    
    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {//print_r('dfvdfv');die;
        $this->middleware('guest')->except('logout');
    }
    protected function credentials(Request $request)
    { 
        $remember_me = $request->has('remember') ? true : false;
        
        if(is_numeric($request->get('email'))){
            return ['phone_no'=>$request->get('email'),'password'=>$request->get('password')];
        }
        return $request->only($this->username(), 'password',$remember_me);
    }
    protected function authenticated(Request $request, $user)
    { 
        
        if($user->hasRoleSlug() == 'admin'){
            $this->redirectTo = route('admin.dashboard');
        }elseif($user->hasRoleSlug() == 'customer'){
            $this->redirectTo = route('welcome');
        }else{
            $this->redirectTo = route('admin.dashboard');
        }
    }
    public function logout(Request $request)
    {   
        $input=$request->all();
        $this->performLogout($request);
        if(isset($input['formlogout'])){
          //return redirect()->route('welcome'); 
            return back();
        }
        //return redirect()->route('welcome');
        return 0;
    }
    
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {   
        $input=$request->all();
        if(is_numeric($request->get('email'))){
            $result=DB::table('users')->where('phone_no', $input['email'])->first();
        }else{
            $result=DB::table('users')->where('email', $input['email'])->first();
        }
        $mail=DB::table('settings')->first();
        //echo '<pre>';print_r( $mail );die;
        if(!empty($mail)){
            $msg=$mail->email;
            if(!empty($result)){
                if($result->status == '0'){
                    //return view('auth.login')->with('deactivemessage',$msg);
                    return response()->json([
                    'error' => "Account deactived"
                    ], 200);
                }
                if($result->status == '2'){
                    /*return view('auth.login')->with('blockedmessage',$msg);*/
                    return response()->json([
                    'error' => "Account Blocked"
                    ], 200);
                }
                if($result->status == '3'){
                    /*return view('auth.login')->with('blockedmessage',$msg);*/
                    return response()->json([
                    'error' => "Account Blocked"
                    ], 200);// for sub Admin 
                }        
            }
        }
        
        
        //echo"<pre>123";print_r($result); die;

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    } 
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        if ($request->ajax()) {
            $this->guard()->user()->last_activity = Carbon::now();
            $this->guard()->user()->save();
            return response()->json($this->guard()->user(), 200);
        }
        
        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'error' => Lang::get('auth.failed')
            ], 200);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => Lang::get('auth.failed'),
            ]);
    }  


}
