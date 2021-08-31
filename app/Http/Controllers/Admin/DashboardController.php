<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Role;
use App\User;
use Hash;
use DB;
use App\Setting;
use Auth;
use Validator;
class DashboardController extends Controller
{
    public function index(Request $request)
    {   $user=User::where('role_id',2)->get();
        $user_count=$user->count();
        $unverifieduser=User::where('role_id',2)->Where(function ($query){
                        $query->orWhere("email_verified_at",NULL);
                        $query->orWhere("phone_verified_at",NULL);
                    })->get();
        $userunverified_count=$unverifieduser->count();
        $unverifiedAds = DB::table("ads")->where('status','0')->count();


        $userProfilePhotoCount = DB::table('users')->where('users.status',1)->where("users.role_id",'2')->where("users.photo_temp","!=",null)->where("users.photo_status",'0')->count();

        $proofsCount = DB::table('proofs')
                        ->leftjoin('users','users.id','=','proofs.user_id')
                        ->select('proofs.user_id','users.name','proofs.proof','proofs.uuid','users.uuid as useruuid')->orderBy('proofs.user_id', 'desc')->orderBy('proofs.created_at', 'desc')->where('users.status',1)->where('proofs.verified','0')->count();


        $enquirysCount = DB::table('enquiry')->select('enquiry.*')->whereIn('enquiry.status',array("0"))->count();

        //echo "<pre>";print_r( $enquirysCount);die;
        return view('back.dashboard',compact('user_count','userunverified_count','unverifiedAds','userProfilePhotoCount','proofsCount','enquirysCount'));
    }

    public function getunverifieddetails(Request $request)
    {   
        $unverifieduser=User::where('role_id',2)->Where(function ($query){
                        $query->orWhere("email_verified_at",NULL);
                        $query->orWhere("phone_verified_at",NULL);
                    })->get();
        $userunverified_count=$unverifieduser->count();
        $unverifiedAds = DB::table("ads")->where('status','0')->count();



        $userProfilePhotoCount = DB::table('users')->where('users.status',1)->where("users.role_id",'2')->where("users.photo_temp","!=",null)->where("users.photo_status",'0')->count();
        $proofsCount = DB::table('proofs')
                        ->leftjoin('users','users.id','=','proofs.user_id')
                        ->select('proofs.user_id','users.name','proofs.proof','proofs.uuid','users.uuid as useruuid')->orderBy('proofs.user_id', 'desc')->orderBy('proofs.created_at', 'desc')->where('users.status',1)->where('proofs.verified','0')->count();
        $enquirysCount = DB::table('enquiry')->select('enquiry.*')->whereIn('enquiry.status',array("0"))->count();


        echo $userunverified_count."@@@".$unverifiedAds."@@@".$userProfilePhotoCount."@@@".$proofsCount."@@@".$enquirysCount;die;
    }

    public function log(Request $request)
    {
        return view('back.log');

    }
    public function icon(Request $request)
    {
        return view('back.icon');

    }
    public function setting()
    {
        $settings = Setting::first();
        //echo "<pre>";print_r( $settings);die;
        return view('back.settings.setting',compact('settings'));
    }
    public function settingstore(Request $request)
    {  
    	$input=$request->all();
        $request->validate([
            'appname'      => 'required',
            'email'     => 'required',
            'phone'     => 'required',
            'address'   => 'required',
            'footer'    => 'required',
            'facebook'  => 'required|url',
            'instagram'   => 'required|url',
            'linkedin'  => 'required|url',
            'twitter'  => 'required|url',
        ]);

        Setting::updateOrCreate(
            [ 'id'       => 1 ],
            [
                'appname'     => $request->appname,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'address'  => $request->address,
                'footer'   => $request->footer,
                'facebook' => $request->facebook,
                'instagram'  => $request->instagram,
                'linkedin' => $request->linkedin,
                'twitter'  => $request->twitter,
              
            ]
        );

        $settings = Setting::first();
        toastr()->success('Updated successfully!');
       	return back();
    }
    public function point()
    {
        $settings = Setting::first();
        //echo "<pre>";print_r( $settings);die;
        return view('back.settings.points',compact('settings'));
    }
    public function pointstore(Request $request)
    {  
        $input=$request->all();
        //echo "<pre>";print_r($input);die;
        $request->validate([
            'new_reg_point'    => 'required',
            'social_point'    => 'required',
            'workmailpoint'    => 'required',
            'profileuploadpoint'  => 'required',
            'govtidpoint'   => 'required',
            'referralpoint'   => 'required',
            'ads_view_point'   => 'required',
            'ads_post_point'   => 'required',
            'free_ad_view_count'   => 'required',
            'feature_ads_point'   => 'required',
            //'user_buys_product_point'   => 'required',
            'redeemable_amount' => 'required',
            'no_free_ads_point_per_month' => 'required',
            'no_free_ads_post_per_month' => 'required',
            'no_feature_ads_post_per_month' => 'required',
            'minimum_ojaak_point_use_payment' => 'required',
            //'choosepaymentgateway' => 'required',
            //'paymentgateway' => 'required'
        ]);
        if(isset($input['infinitefreelimit'])){
            Setting::updateOrCreate(
            [ 'id'       => 1 ],
            [
                'new_reg_point'    => $request->new_reg_point,
                'social_reg'    => $request->social_point,
                'work_mail_point'  => $request->workmailpoint,
                'profile_upload_point' => $request->profileuploadpoint,
                'govt_id_point'  => $request->govtidpoint,
                'referral_point'  =>$request->referralpoint,
                'ads_view_point'  =>$request->ads_view_point,
                'ads_post_point'  =>$request->ads_post_point,
                'free_ad_view_count'  =>$request->free_ad_view_count,
                'infinitefreelimit'=> $request->infinitefreelimit,
                'feature_ads_point'   => $request->feature_ads_point,
                'redeemable_amount'   => $request->redeemable_amount,
                'no_free_ads_point_per_month'   => $request->no_free_ads_point_per_month,
                'no_free_ads_post_per_month'   => $request->no_free_ads_post_per_month,
                'no_feature_ads_post_per_month'   => $request->no_feature_ads_post_per_month,
                'minimum_ojaak_point_use_payment'   => $request->minimum_ojaak_point_use_payment,
                /*'choosepaymentgateway'   => $request->choosepaymentgateway,
                'paymentgateway'   => $request->paymentgateway*/
            ]);
        }else{
            if(!empty($input['freeadslimit'])){
                Setting::updateOrCreate(
                [ 'id'       => 1 ],
                [
                    'new_reg_point'    => $request->new_reg_point,
                    'social_reg'    => $request->social_point,
                    'work_mail_point'  => $request->workmailpoint,
                    'profile_upload_point' => $request->profileuploadpoint,
                    'govt_id_point'  => $request->govtidpoint,
                    'referral_point'  =>$request->referralpoint,
                    'freeadslimit'  =>$request->freeadslimit,
                    'ads_view_point'  =>$request->ads_view_point,
                    'ads_post_point'  =>$request->ads_post_point,
                    'free_ad_view_count'  =>$request->free_ad_view_count,
                    'infinitefreelimit'=> '0',
                    'feature_ads_point'   => $request->feature_ads_point,
                    'redeemable_amount'   => $request->redeemable_amount,
                    'no_free_ads_point_per_month'   => $request->no_free_ads_point_per_month,
                    'no_free_ads_post_per_month'   => $request->no_free_ads_post_per_month,
                    'no_feature_ads_post_per_month'   => $request->no_feature_ads_post_per_month,
                    'minimum_ojaak_point_use_payment'   => $request->minimum_ojaak_point_use_payment,
                    /*'choosepaymentgateway'   => $request->choosepaymentgateway,
                    'paymentgateway'   => $request->paymentgateway*/
                ]);

            }else{
                toastr()->error('freeadslimit Empty!');
                return back();
            }

        }
        toastr()->success('Updated successfully!');
        return back();
    }
    public function profile()
    {
        $profile = Auth::user();
        //echo "<pre>";print_r($profile);die;
        return view('back.settings.profile',compact('profile'));
    }
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'image'     => 'image|mimes:jpeg,jpg,png|max:1024'
        ]);

        $user = User::find(Auth::id());

        $image = $request->file('image');
        if(isset($image)){
            $path = public_path('uploads/profile/original/');
            $base64_image = base64_encode(file_get_contents($image));
            $profile_img = user_profile_img($path,$base64_image);

        }else{
            $profile_img = $user->photo;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->photo = $profile_img;

        $user->save();
        toastr()->success(' Profile Updated successfully!');
        return back();
    }
    public function changePassword()
    {
        return view('back.settings.changepassword');

    }
    public function changePasswordUpdate(Request $request)
    {	
    	$input=$request->all();
    	//echo "<pre>";print_r($input);die;
        if (!(Hash::check($request->get('currentpassword'), Auth::user()->password))) {
        	toastr()->error('Your current password does not matches with the password you provided! Please try again.');
            return redirect()->back();
        }
        if(strcmp($request->get('currentpassword'), $request->get('newpassword')) == 0){
        	toastr()->error('New Password cannot be same as your current password! Please choose a different password.');
            return redirect()->back();
        }
        if(!strcmp($request->get('newpassword'), $request->get('confirm')) == 0){
        	toastr()->error('New Password cannot be same as your confirm password!');
            return redirect()->back();
        }
        
        $user = Auth::user();
        $user->password =Hash::make($request->get('newpassword'));
        $user->save();
        toastr()->success('Password changed successfully.');
        return redirect()->back();
    }



    public function settingpaytm(Request $request)
    {  
        $input=$request->all();
        $request->validate([
            'PAYTM_ENV'     => 'required',
            'MERCHANT_ID'   => 'required',
            'MERCHANT_KEY'  => 'required',
            'WEBSITE'       => 'required',
            'CHANNEL'       => 'required',
            'INDUSTRY_TYPE' => 'required',
            'SALESWALLETGUID'=> 'required',
        ]);

        Setting::updateOrCreate(
            [ 'id'       => 1 ],
            [
                'PAYTM_ENV'     => $request->PAYTM_ENV,
                'MERCHANT_ID'    => $request->MERCHANT_ID,
                'MERCHANT_KEY'    => $request->MERCHANT_KEY,
                'WEBSITE'  => $request->WEBSITE,
                'CHANNEL'   => $request->CHANNEL,
                'INDUSTRY_TYPE' => $request->INDUSTRY_TYPE,
                'SALESWALLETGUID'  => $request->SALESWALLETGUID,
            ]
        );

        //$settings = Setting::first();
        toastr()->success('Updated successfully!');
        return back();
    }

    public function settingrazorpay(Request $request)
    {  
        $input=$request->all();
        $request->validate([
            'RAZORPAY_KEY'     => 'required',
            'RAZORPAY_SECRET'     => 'required',
        ]);

        Setting::updateOrCreate(
            [ 'id'       => 1 ],
            [
                'RAZORPAY_KEY'     => $request->RAZORPAY_KEY,
                'RAZORPAY_SECRET'  => $request->RAZORPAY_SECRET,
            ]
        );

        toastr()->success('Updated successfully!');
        return back();
    }
    public function payment()
    {
        $settings = Setting::first();
        //echo "<pre>";print_r( $settings);die;
        return view('back.settings.payment',compact('settings'));
    }
    public function settingChooseGateway(Request $request)
    {  
        $input=$request->all();
        $request->validate([
            'choosepaymentgateway' => 'required',
            'paymentgateway' => 'required'
        ]);

        Setting::updateOrCreate(
            [ 'id'       => 1 ],
            [
                'choosepaymentgateway'   => $request->choosepaymentgateway,
                'paymentgateway'   => $request->paymentgateway
            ]
        );

        toastr()->success('Updated successfully!');
        return back();
    }

    public function read_notification($notUuid=null)
    {
        $notifAdmin = DB::table("notification_admin")->where("uuid",$notUuid)->first();
        if(!empty($notifAdmin)){
            $getAdUuid = DB::table("ads")->where('id',$notifAdmin->adsid)->first();

            DB::table("notification_admin")->where("uuid",$notUuid)->update(['read_status'=>1]);

            return redirect('admin/ads/adsdata/'.$getAdUuid->uuid);
        }else{
            return back();
        }
    }

}
