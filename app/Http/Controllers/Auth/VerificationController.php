<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
//use Illuminate\Foundation\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\User;
class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function verify(Request $request)
    {   
        $input=$request->all();
        $user=User::find($request->route('id'));
        if(!empty($user)){
            //Auth::guard()->login($user);
            if ($request->route('id') != $user->id) {
                throw new AuthorizationException;
            }

            if ($user->hasVerifiedEmail()) {
                toastr()->warning('Already Verified!');
                return redirect($this->redirectPath());
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                addpoint($user->id,'new_reg_point');
                referralpoints($user->id,$user->referral_register);
            }
            //Auth::logout();
            toastr()->success('Email Verified Successfully!');
            return redirect($this->redirectPath());
            //return redirect($this->redirectPath())->with('verified', true);
        }else{
            toastr()->warning('Invaild Verification!');
            return redirect();
        }
    }
    
    


    /*public function verify(Request $request)
    {   
        $input=$request->all();
        echo"<pre>";print_r($request->route('id'));die;
        if ($request->route('id') != $request->user()->getKey()) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            addpoint($request->route('id'),'mail_point');
        }

        return redirect($this->redirectPath())->with('verified', true);
    }*/
    
    /*public function verify(Request $request) { 
        if (! $this->guard()->onceUsingId($request->route('id'))) { 
            throw new AuthorizationException; 
        } 
        $user = $this->guard()->user(); 
        if ($user->hasVerifiedEmail()) { 
            return redirect($this->redirectPath()); 
        } 
        if ($user->markEmailAsVerified()) { 
            event(new Verified($user));
            addpoint($request->route('id'),'mail_point'); 
        } 
        return redirect($this->redirectPath())->with('verified', true);
    }*/
}
