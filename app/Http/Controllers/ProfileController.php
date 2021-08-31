<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use App\Mail\EmailUpdate;
use App\Mail\WorkEmailUpdate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\User;
use App\Proof;
use Carbon\Carbon;
use DB;
use Auth;
use Socialite;
use Hash;
use Image;
use PDF;
use Uuid;
use App\Referral;
use App\Setting; 
use App\PlansPurchase;
use App\BillingInformation;
use App\PurchaseBilling;
use App\UsersRating;
use App\Privacy;
use Cache;
use App\Chats;
use App\ChatsMessage;
use App\UserReview;
class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'], ['except' => ['mailverify','workmailverify','displayuser','UserStatusCheck']]);
    }

    public function UserStatusCheck(Request $request)
    {
        $profile = Auth::user();
        if($profile->status=="1"){
            return 1;
        }else{
            return 0;
        }
    } 
    public function index()
    {   
        //addpoint(Auth::id(),'google_point');

        $profile = Auth::user();
        $badge=verified_profile(Auth::id());
        //echo"<pre>";print_r($badge); die;
        $prooflist=get_prooflist();
        $proofs= DB::table('proofs')->where('user_id',Auth::id())->orderBy('proof', 'asc')->get();
        return view('profile.profile',compact('profile','badge','prooflist','proofs'));
    }

    public function disconsocial(Request $request)
    {   
        $input = $request->all();
        //echo"<pre>";print_r($input);die;
        if($input['socialinput']=='google'){
            $user = User::find(Auth::id());
            $user->google_id=null;
            $user->save();
        }
        if($input['socialinput']=='facebook'){
            $user = User::find(Auth::id());
            $user->facebook_id=null;
            $user->save();
        }
        toastr()->success(' Social Account Disconnected !');
        return back();

    }

    public function referraluser()
    {   
        //addpoint(Auth::id(),'google_point');

        $profile = Auth::user();
        $badge=verified_profile(Auth::id());
        $users= DB::table('referrals')->where('referal_user_id',Auth::id())->orderBy('id', 'desc')->get();
        $a=get_name(9);
        //echo"<pre>";print_r($a); die;
        return view('profile.referral',compact('profile','badge','users'));
    }
    public function govtproof()
    {   
        $user = Auth::user();
        $badge=verified_profile(Auth::id());
        $prooflist=get_prooflist();
        $proofs= DB::table('proofs')->where('user_id',Auth::id())->get();
        //echo"<pre>";print_r($proofs); die;
        return view('profile.govtproof',compact('user','badge','prooflist','proofs'));
    }
    public function govtproofupdate(Request $request)
    {   
        $input = $request->all();
        $uuid = Uuid::generate(4);

        if(!empty($request->file('aadharcard'))){
            old_proofdelete(1);
            $uuid = Uuid::generate(4);
            $image = $request->file('aadharcard');
            $imgname = time().$uuid.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/proof/');
            $img = Image::make($image->getRealPath());
            $img->resize(500, 250);
            $img->save($destinationPath.'/'.$imgname);
            
            $proofsupdate =  new Proof;
            $proofsupdate->uuid = $uuid;
            $proofsupdate->user_id = Auth::id();
            $proofsupdate->proof = 1;
            $proofsupdate->comments='';
            $proofsupdate->verified = '0';
            $proofsupdate->image = $imgname;
            $proofsupdate->save();
                
        }
        if(!empty($request->file('drivinglicense'))){
            old_proofdelete(2);
            $uuid = Uuid::generate(4);
            $image = $request->file('drivinglicense');
            $imgname = time().$uuid.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/proof/');
            $img = Image::make($image->getRealPath());
            $img->resize(500, 250);
            $img->save($destinationPath.'/'.$imgname);
            
            $proofsupdate =  new Proof;
            $proofsupdate->uuid = $uuid;
            $proofsupdate->user_id = Auth::id();
            $proofsupdate->proof = 2;
            $proofsupdate->comments='';
            $proofsupdate->verified = '0';
            $proofsupdate->image = $imgname;
            $proofsupdate->save();
                
        }
        if(!empty($request->file('pancard'))){
            old_proofdelete(3);
            $uuid = Uuid::generate(4);
            $image = $request->file('pancard');
            $imgname = time().$uuid.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/proof/');
            $img = Image::make($image->getRealPath());
            $img->resize(500, 250);
            $img->save($destinationPath.'/'.$imgname);
            
            $proofsupdate =  new Proof;
            $proofsupdate->uuid = $uuid;
            $proofsupdate->user_id = Auth::id();
            $proofsupdate->proof = 3;
            $proofsupdate->comments='';
            $proofsupdate->verified = '0';
            $proofsupdate->image = $imgname;
            $proofsupdate->save();
                
        }
        if(!empty($request->file('votecard'))){
            old_proofdelete(4);
            $uuid = Uuid::generate(4);
            $image = $request->file('votecard');
            $imgname = time().$uuid.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/proof/');
            $img = Image::make($image->getRealPath());
            $img->resize(500, 250);
            $img->save($destinationPath.'/'.$imgname);
            
            $proofsupdate =  new Proof;
            $proofsupdate->uuid = $uuid;
            $proofsupdate->user_id = Auth::id();
            $proofsupdate->proof = 4;
            $proofsupdate->comments='';
            $proofsupdate->verified = '0';
            $proofsupdate->image = $imgname;
            $proofsupdate->save();
                
        }
        toastr()->success(' Proof Upload  successfully!');
        return back();
    }
    // public function govtproofedit($proofid)
    // {   
    //     $user = Auth::user();
    //     $badge=verified_profile(Auth::id());
    //     $proofid= DB::table('proofs')->where('uuid',$proofid)->where('user_id',Auth::id())->first();
    //     if(empty($proofid)){
    //         toastr()->warning(' illegal Access !');
    //         return redirect()->route('profile.govtproof');
    //     }
    //     $prooflist=array($proofid->proof=>get_prooflist($proofid->proof));
    //     $proofs= DB::table('proofs')->where('user_id',Auth::id())->get();
    //     return view('profile.updategovtproof',compact('user','badge','prooflist','proofid','proofs'));
    // }
    // public function govtproofdelete($proofid)
    // {   
    //     $proofs= DB::table('proofs')->where('id',$proofid)->first();
    //     //echo"<pre>";print_r($proofs->image);die;
    //     if(!empty($proofs->image) && file_exists(public_path('/uploads/proof/'.$proofs->image)))
    //     {
    //         unlink(public_path('uploads/proof/'.$proofs->image));
    //     }   

    //     $res=Proof::destroy($proofid);
    //     toastr()->success(' Proof deleted successfully!');
    //     return back();
    // }
    // public function govtproofupdate_old(Request $request)
    // {   
    //     $input = $request->all();
    //     $uuid = Uuid::generate(4);
    //     //echo"<pre>";print_r($input);die;
    //     $request->validate([
    //         'list'          =>'required',
    //         'govtproof'     => 'image|mimes:jpeg,jpg,png|max:1024',
    //     ]);
        
    //     $check=Proof::where('user_id',Auth::id())->where('proof',$input['list'])->first();
    //     if(!empty($check)){
    //         if(!empty($check->image) && file_exists(public_path('/uploads/proof/'.$check->image)))
    //         {
    //             unlink(public_path('uploads/proof/'.$check->image));
    //         }
    //         if(!empty($request->file('govtproof'))){
    //             $image = $request->file('govtproof');
    //             $imgname = time().$uuid.'.'.$image->getClientOriginalExtension();
    //             $destinationPath = public_path('uploads/proof/');
    //             $img = Image::make($image->getRealPath());
                
    //             $img->resize(500, 250);
    //             $img->save($destinationPath.'/'.$imgname);
    //             $check->image = $imgname;
    //             $check->verified = '0';
    //             $check->save();
    //             toastr()->success(' Proof Updated successfully!');
    //             return back();
    //         }else{
    //             toastr()->error(' Image not found !');
    //             return back();
    //         }
    //     }else{
           
    //         toastr()->error(' Request Invalid !');
    //         return back();
    //     }
    // }
    public function govtproofadd(Request $request)
    {   
        $input = $request->all();
        $uuid = Uuid::generate(4);
        $prooflist=get_prooflist($input['list']);
        //echo"<pre>";print_r($prooflist);die;
        $request->validate([
            'list'          =>'required',
            'govtproof'     => 'image|mimes:jpeg,jpg,png|max:1024',
        ]);
        
        $check=Proof::where('user_id',Auth::id())->where('proof',$input['list'])->first();
        if(!empty($check)){
            toastr()->error(' Already Exists!');
            return back();
        }else{
            if(!empty($request->file('govtproof'))){
                $image = $request->file('govtproof');
                $imgname = time().$uuid.'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/proof/');
                $img = Image::make($image->getRealPath());
                
                $img->resize(500, 250);
                $img->save($destinationPath.'/'.$imgname);
                $proofs =  new Proof;
                $proofs->uuid = $uuid;
                $proofs->user_id = Auth::id();
                $proofs->proof = $input['list'];
                $proofs->image = $imgname;
                $proofs->save();
                toastr()->success(' Proof Updated successfully!');
                return back();
            }else{
                toastr()->error(' Image not found !');
                return back();
            }
        }
    }
    public function profileupdate(Request $request)
    {   
        $input = $request->all();
        //echo '<pre>';print_r($input);die;
        $log=$request->validate([
            'name'      => 'required',
            'gender'      => 'required',
            //'image'     => 'image|mimes:jpeg,jpg,png|max:1024',

        ]);
        $age = age($input['dob']);
        if(18 > $age)
        {
            toastr()->error('You are under 18 years !.');
            return back();
        }
        $user = User::find(Auth::id());

        $oldusername = $user->name;
        
        // $image = $request->file('image');
        // if(isset($image)){
        //     $path = public_path('uploads/profile/original/');
        //     $base64_image = base64_encode(file_get_contents($image));
        //     $profile_img = user_profile_img($path,$base64_image);
        //     //addpoint($user->id,'profile_upload_point');
        //     if($user->photo_status == 1){
        //         $user->photo=$profile_img;
        //         $user->photo_status = 1;
        //     }else{
        //         $user->photo_temp = $profile_img;
        //         $user->photo_status = 0;
        //         toastr()->success('The profile photo will be changed after being approved by OJAAK Team!');
        //     }
            
        // }
        
        $user->name = $request->name;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->save();


        DB::table('ads')
            ->where('name',$oldusername)
            ->where('seller_id',Auth::id())
            ->update(['name'=>$request->name]);

        toastr()->success(' Profile Updated successfully!');
        return back();
    }
    public function profileimageupdate(Request $request)
    { 
        $input = $request->all();
        $user = User::find(Auth::id());
        if(isset($input['image'])){
            $path = public_path('uploads/profile/original/');
            $base64_image =  base64_encode(file_get_contents($input['image']));
            $profile_img = user_profile_img($path,$base64_image);
            //echo '<pre>';print_r($profile_img);die;
            if($user->photo_status == 1){
                $user->photo=$profile_img;
                $user->photo_status = 1;
                $user->save();
                return 0;
            }else{
                $user->photo_temp = $profile_img;
                $user->photo_status = 0;
                $user->save();
                return 1;
            }
        }
        return 2;
    }
    public function mobile()
    {   
        $badge=verified_profile(Auth::id());
        return view('profile.mobile',compact('badge'));
    }
    public function changepassword()
    {
        return view('profile.changepassword');
    }
    public function updatepassword(Request $request)
    {   
        $input = $request->all();
        $user = Auth::user();
        $userdata = User::find($user->id);
        if (strlen($request->get('new')) < 8 || strlen($request->get('confirm')) < 8 ) {
             toastr()->error('Password is short and minimum 8 digit!');
            return redirect()->back();
        }
        if(!strcmp($request->get('new'), $request->get('confirm')) == 0){
            toastr()->error('New Password cannot be same as your confirm password!');
            return redirect()->back();
        }
        if (Hash::check($request->get('old'), $userdata->password)) {
            
                if (strcmp($request->get('old'), $request->get('new')) == 0) {
                     toastr()->error('New Password is same as old !');
                    return redirect()->back();
                }

                $userdata->password = Hash::make($request->get('new'));
                $userdata->save();
                toastr()->success('Password changed successfully.');
                return redirect()->back(); 
        }else{
                toastr()->error('Current Password is Wrong');
                return redirect()->back();   
        }
    }
    // public function mobileupdate(Request $request)
    // {   
    //     $input = $request->all();
    //     $findEmail=User::where('phone_no',$input['mobile'])->first();
    //     //echo '<pre>';print_r(  $findEmail );die;
    //     if(!empty($findEmail)){
    //         toastr()->error('Mobile Number Already Exists');
    //         return redirect()->back(); 
    //     }else{
    //         $otp = getOTP();
    //         if($otp==$input['otp']){
    //             $user = Auth::user();
    //             $userdata = User::find($user->id);
    //             $userdata->phone_no =$input['mobile'];
    //             $userdata->save();
    //             addpoint($user->id,'new_reg_point');
    //             toastr()->success('Mobile Number Updated');
    //             return redirect()->back(); 
    //         }else{
    //             toastr()->success('Invaild Otp');
    //             return redirect()->back();
    //         }
    //     }
         
        
    // }
    public function Ajaxmobilecheck(Request $request)
    {   
        $input = $request->all();
        $findEmail=User::where('phone_no',$input['mobile'])->first();
        //echo '<pre>';print_r(  $findEmail );die;
        if(!empty($findEmail)){
            return 0; 
        }else{
            return 1;
        }
    }
    public function Ajaxmobileupdate(Request $request)
    {   
        $input = $request->all();
        //echo"<pre>";print_r($input); die;
        $userdata=User::where('id',Auth::id())->first();
        if(!empty($userdata)){
            $otp = getOTP();
            if($otp==$input['otp']){
                $userdata->phone_no =$input['mobile'];
                $userdata->save();
                addpoint($userdata->id,'new_reg_point');
                return 1; 
            }else{
                return 0;
            } 
        }else{
           return 2;
        }
         
        
    }
    public function mail()
    {    
        $mail = Auth::user();
        $badge=verified_profile(Auth::id());
        return view('profile.updatemail',compact('mail','badge'));
    }
    public function mailupdate(Request $request)
    {   
        $input=$request->all();
        $findEmail=User::where('email', $request->mail)->first();
        if(!empty($findEmail))
        {
            toastr()->error('This Mail Already Taken.');
            return redirect()->back();
        }
        $findworkEmail=User::where('work_mail', $request->mail)->first();
        if(!empty($findworkEmail))
        {
            toastr()->error('This Mail Already Taken.');
            return redirect()->back();
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
        //echo '<pre>';print_r(  $data['path'] );die;
        $sendmail = Mail::to($request->mail)->send(new EmailUpdate($data));
        toastr()->success('A New verification link has been sent to your email address.');
        return redirect()->back();
    }
    public function Ajaxmailupdate(Request $request)
    {   
        $input=$request->all();
        $findworkEmail=User::where('work_mail', $request->mail)->first();
        if(!empty($findworkEmail))
        {
            return 0;
        }
        $findEmail=User::where('email', $request->mail)->first();
        if(!empty($findEmail))
        {
            return 0;
        }
        $strcode =Str::random(12);
        $numcode = mt_rand(100000, 999999);
        $randomcode=$strcode.$numcode;
        $user = User::find(Auth::id());
        $user->temp_mail = $request->mail;
        $user->random_code = $randomcode;
        $user->save();
        $data['path']=url("/profile/mail/verify").'/'.$randomcode;
        $sendmail = Mail::to($request->mail)->send(new EmailUpdate($data));
        return 1;
    }
    public function mailverify($randomcode)
    {   
        $findEmail=User::where('random_code', $randomcode)->first();
        if(!empty($findEmail))
        {   
            $user = User::find($findEmail->id);
            //echo '<pre>';print_r(  $user );die;
           
            $user->email = $user->temp_mail;
            $user->temp_mail = null;
            $user->random_code = null;
            $user->save();
            addpoint($user->id,'new_reg_point');
            toastr()->success('Email Verification Successfully.');
            //return view('profile.updatemail');
            return view('/mailstatus');
        }
        else{
            toastr()->error('Verify Code Invalid.');
            return redirect()->route('welcome');
        }
    }
    public function social()
    {   
        $badge=verified_profile(Auth::id());
        return view('profile.social',compact('badge'));
    }
    public function socialredirect($provider)
    {   
        return Socialite::driver($provider)->redirect();
    }
    public function workmail()
    {
        $badge=verified_profile(Auth::id());
        return view('profile.workmail',compact('badge'));
    }
    public function Ajaxworkmailupdate(Request $request)
    {   
        $input=$request->all();
        //echo "<pre>";print_r($input);die;
        $findworkEmail=User::where('work_mail', $request->mail)->first();
        if(!empty($findworkEmail))
        {
            return 0;
        }
        $findEmail=User::where('email', $request->mail)->first();
        if(!empty($findEmail))
        {
            return 0;
        }
        $strcode =Str::random(18);
        $numcode = mt_rand(100000, 999999);
        $randomcode=$strcode.$numcode;
        $user = User::find(Auth::id());
        $user->temp_mail = $request->mail;
        $user->random_code = $randomcode;
        $user->save();
        $data['path']=url("/profile/workmail/verify").'/'.$randomcode;
        $sendmail = Mail::to($request->mail)->send(new WorkEmailUpdate($data));
        //toastr()->success('A New verification link has been sent to your email address.');
        return 1;
    }
    // public function workmailupdate(Request $request)
    // {   
    //     $input=$request->all();
    //     $findworkEmail=User::where('work_mail', $request->mail)->first();
    //     if(!empty($findworkEmail))
    //     {
    //         toastr()->error('This Mail Already Taken.');
    //         return redirect()->back();
    //     }
    //     $findEmail=User::where('email', $request->mail)->first();
    //     if(!empty($findEmail))
    //     {
    //         toastr()->error('This Mail Already Taken.');
    //         return redirect()->back();
    //     }
    //     $strcode =Str::random(18);
    //     $numcode = mt_rand(100000, 999999);
    //     $randomcode=$strcode.$numcode;
    //     //echo"<pre>";print_r($input); die;
    //     $user = User::find(Auth::id());
    //     $user->temp_mail = $request->mail;
    //     $user->random_code = $randomcode;
    //     $user->save();
    //     $data['path']=url("/profile/workmail/verify").'/'.$randomcode;
    //     //echo '<pre>';print_r($data);die;
    //     $sendmail = Mail::to($request->mail)->send(new WorkEmailUpdate($data));
    //     toastr()->success('A New verification link has been sent to your email address.');
    //     return redirect()->back();
    // }
    public function workmailverify($randomcode)
    {   
        $findEmail=User::where('random_code', $randomcode)->first();
        if(!empty($findEmail))
        {   
            $user = User::find($findEmail->id);
            $user->work_mail = $user->temp_mail;
            $user->temp_mail = null;
            $user->random_code = null;
            $user->save();
            addpoint($user->id,'work_mail_point');
            toastr()->success('Email Verification Successfully.');
           // return redirect()->route('dashboard');
            return view('/mailstatus');
        }
        else{
            toastr()->error('Verify Code Invalid.');
            return redirect()->route('welcome');
        }
    }
    public function displayuser($usruuid){
        if(!Auth::check()){
            toastr()->warning('Login to continue...  !');
            return redirect('/');
        }
        $userdetails = DB::table('users')->where('uuid',$usruuid)->first();


        if(!empty($userdetails)){
            $adss= DB::table('ads')
                ->leftJoin('ads_image', 'ads_image.ads_id', '=', 'ads.id')
                ->leftjoin('ads_features','ads_features.ads_id','=','ads.id')
                ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_features.expire_date as featureadsexp')
                ->where('ads.seller_id',$userdetails->id)
                ->where('ads.ads_expire_date','!=',null)->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'))
                ->where('ads.status',"1")
                ->groupBy("ads.id")
                ->orderBy("ads.id",'desc')
                ->get();
            
            if(Auth::check()){
                $favads = DB::table('favourites')
                      ->where('user_id',Auth::user()->id)
                      ->get()->toArray();
            }

            $ads = array();
            foreach ($adss as $key => $ad) {
                if(!empty($favads)){
                    foreach ($favads as $key => $fad) {
                        $ads[$ad->id] = $ad;
                        if($fad->ads_id == $ad->id){
                            $ads[$ad->id]->favv = "1";
                        }                  
                    }
                }else{
                    $ads[$ad->id] = $ad;                
                }
                
            }
            $fcount['following']= DB::table('followers')->where('user_id',$userdetails->id)->get()->count();
            $fcount['follower']= DB::table('followers')->where('following',$userdetails->id)->get()->count();

            $date = new Carbon( $userdetails->created_at); 
            $date->format('Ymd'); 
            $year = $date->year;
            $month = date("F", mktime(0, 0, 0, $date->month, 10));
            //echo "<pre>";print_r($ads);die;
           // $user = array();
            $privacy = array();
            if(Auth::check()){
                $user = auth()->user();
                $following = DB::table('followers')->where('user_id',$user->id)->where('following',$userdetails->id)->first();
                if(!empty($following)){
                    $prop[1]="btn btn-danger";
                    $prop[2]="Unfollow";
                }else{
                    $prop[1]="btn btn-success";
                    $prop[2]="Follow";                  
                }

                $privacy=Privacy::where('user_id',$userdetails->id)->first();
                return view('ads.userdetails',compact('userdetails','prop','year','month','ads','fcount','usruuid','privacy'));
            }else{
                return view('ads.userdetails',compact('userdetails','year','month','ads','fcount','usruuid','privacy'));
            }
        }else{
            toastr()->warning('User Not Found !');
            return redirect('/');
        }
    }


    public function profileReviews($usruuid){
        $userdetails = DB::table('users')->where('uuid',$usruuid)->first();

        if(!empty($userdetails)){
            $usersrating=UsersRating::where('user_id',$userdetails->id)->select('users_ratings.*')->leftjoin('users','users_ratings.rating_from_user_id','=','users.id')->where('users.status', 1)->latest()->get();
            $usersreviews=UserReview::where('user_id',$userdetails->id)->orderby('id','asc')->get();
            //echo "<pre>s";print_r($usersrating);die;
            $reviews = '';
            $reviews .= '<div class="py-4">
                            
                                <div class="customer_review_outer_row_wrap">';
                                    if(!$usersrating->isEmpty()){
                                        foreach($usersrating as $msg){
                                            $keysss = 0;
                                        $reviews .= '<div class="customer_review_single_wrap">
                                            <div class="customer_img_outer_wrap">
                                                <div class="customer_img_inner_wrap">
                                                    <img src="'.asset('public/uploads/profile/small/'.get_userphoto($msg->rating_from_user_id)).'" title="avatar" alt="avatar">
                                                </div>
                                                <h3>'.get_name($msg->rating_from_user_id).'</h3>
                                            </div>
                                            <div class="star_rating pt-2">';
                                                for($i=1;$i<6;$i++){
                                                    if($i<=$msg->rating){
                                                        $reviews .= '<span class="fa fa-star active"></span>';
                                                    }else{
                                                        $reviews .= '<span class="fa fa-star "></span>';
                                                    }
                                                }
                                                $reviews .= '<p>Reviewed on '.date('d F Y', strtotime($msg->updated_at)).'</p>
                                            </div>
                                            <div class="customer_msg_wrap">';
                                                foreach($usersreviews as $review){
                                                    if($review->review_from_user_id == $msg->rating_from_user_id){
                                                        if($keysss == 0){
                                                            $reviews .= '<p>'.$review->review.'</p>';
                                                        }else{
                                                            $reviews .= '<p>Update : '.$review->review.'</p>';
                                                        }
                                                        $keysss++;
                                                    }
                                                }
                                            $keysss = 0;
                                            $reviews .= '</div>
                                        </div>';
                                        }
                                    }else{
                                    $reviews .= '<div class="alert alert-info alert-dismissible" style="width: 68% !important;">
                                        <strong>No Review</strong> 
                                    </div>';
                                    }
                                $reviews .= '</div>
                            
                        </div>';
            return $reviews;
            //return view('ads.userreviews',compact('userdetails','usersrating','usersreviews'));
        }else{
            return 0;
        }
    }

    public function displayuserinfo($usruuid){
        $userdetails = DB::table('users')->where('uuid',$usruuid)->first();
        if(!empty($userdetails)){
            $ads= DB::table('ads')
                ->leftJoin('ads_image', 'ads_image.ads_id', '=', 'ads.id')
                ->select('ads.*','ads_image.id as adsimgid','ads_image.image')
                ->where('ads.seller_id',$userdetails->id)
                ->where('ads.status','!=',5)
                ->groupBy("ads.id")
                ->orderBy("ads.id",'desc')
                ->get();
            $fcount['following']= DB::table('followers')->where('user_id',$userdetails->id)->get()->count();
            $fcount['follower']= DB::table('followers')->where('following',$userdetails->id)->get()->count();
            $following = DB::table('followers')
                                ->rightJoin('users','users.id','=','followers.following')
                                ->where('user_id',$userdetails->id)
                                ->select('followers.*','users.photo','users.name')
                                ->get();

            //echo "<pre>";print_r($following);die;                
            $followers = DB::table('followers')
                       ->leftJoin('users','users.id','=','followers.user_id')
                       ->where('followers.following',$userdetails->id)
                       ->select('followers.*','users.photo','users.name')
                       ->get();                    
            //echo "<pre>";print_r( $followers);die;

            $date = new Carbon( $userdetails->created_at); 
            $date->format('Ymd'); 
            $year = $date->year;
            $month = date("F", mktime(0, 0, 0, $date->month, 10));
            //echo "<pre>";print_r($count);die;
            if(Auth::check()){
                $user = auth()->user();
                $following = DB::table('followers')->where('user_id',$user->id)->where('following',$userdetails->id)->first();
                if(!empty($following)){
                    $prop[1]="btn btn-danger";
                    $prop[2]="Unfollow";
                }else{
                    $prop[1]="btn btn-success";
                    $prop[2]="Follow";                  
                }
                return view('ads.userdetails',compact('userdetails','prop','year','month','ads','fcount','following','followers'));
            }else{
                return view('ads.userdetails',compact('userdetails','year','month','ads','fcount','followers','following'));
            }
            }else{
            toastr()->warning('User Not Found !');
            return redirect('/');
        }

    }



    public function userwallets($usruuid){
        if(!Auth::check()){
            toastr()->warning('Login to continue...  !');
            return redirect('/');
        }
        $userdetails = DB::table('users')->where('uuid',$usruuid)->first();
        //echo "<pre>";print_r($userdetails);die;
        if(!empty($userdetails)){
            $now=date('Y-m-d H:i:s');         
        
            $pointstable = DB::table('freepoints')->where('user_id',$userdetails->id)
                                    ->where('status','1')
                                    ->where('used','0')
                                    ->where('expire_date','!=',null)->whereDate('expire_date','>=',$now)->get(); 
            //echo "<pre>";print_r($pointstable);die;
            $reedemableamt = Setting::select('redeemable_amount')->where('id',1)->first();
            return view('wallet.userswallet',compact('reedemableamt','pointstable','userdetails'));

        }else{
            toastr()->warning('User Not Found !');
            return redirect('/');
        }
    }


    public function notifications($usruuid)
    {   
        if(!Auth::check()){
            toastr()->warning('Login to continue...  !');
            return redirect('/');
        }
        $userdetails = DB::table('users')->where('uuid',$usruuid)->first();
        //echo "<pre>";print_r($userdetails);die;
        if(!empty($userdetails)){
            $notification=DB::table('privacy')->where('user_id',$userdetails->id)->first();
            //echo"<pre>1";print_r($notification);die;
            return view('wallet.usersnotifications',compact('notification','userdetails'));
        }else{
            toastr()->warning('User Not Found !');
            return redirect('/');
        }
    }

    public function boughtpackages($usruuid)
    {   


        if(!Auth::check()){
            toastr()->warning('Login to continue...  !');
            return redirect('/');
        }
        $userdetails = DB::table('users')->where('uuid',$usruuid)->first();
        //echo "<pre>";print_r($userdetails);die;
        if(!empty($userdetails)){
            $currentdate=date('Y-m-d H:s:i');
            
            $activeplans=PlansPurchase::where('user_id',$userdetails->id)->orderby('id','desc')->whereDate('expire_date','>=', $currentdate)->get();
            $expiredplans=PlansPurchase::where('user_id',$userdetails->id)->orderby('id','desc')->whereDate('expire_date','<', $currentdate)->get();
            //echo"<pre>";print_r($expiredplans);die;
            return view('wallet.boughtpackages',compact('activeplans','expiredplans','userdetails'));
        }else{
            toastr()->warning('User Not Found !');
            return redirect('/');
        }

    }

    public function userbilling($usruuid)
    {   
        if(!Auth::check()){
            toastr()->warning('Login to continue...  !');
            return redirect('/');
        }
        $userdetails = DB::table('users')->where('uuid',$usruuid)->first();
        if(!empty($userdetails)){
            //$billinfo = array();
            $billinfo= BillingInformation::where('user_id',$userdetails->id)->first();
            //echo "<pre>";print_r($billinfo);die;
            $states= DB::table('states')->where('status','1')->get();
            return view('wallet.billinginfo',compact('billinfo','states','userdetails'));
        }else{
            toastr()->warning('User Not Found !');
            return redirect('/');
        }
    }

    public function userblocked($usruuid)
    {   
        if(!Auth::check()){
            toastr()->warning('Login to continue...  !');
            return redirect('/');
        }
        $userdetails = DB::table('users')->where('uuid',$usruuid)->first();
        if(!empty($userdetails)){
            
            $block_user= DB::table('customer_manage_user')->where('user_id',$userdetails->id)->get();
            return view('wallet.userblocked',compact('block_user','userdetails'));
        }else{
            toastr()->warning('User Not Found !');
            return redirect('/');
        }

    }


    public function userinvoices($usruuid)
    {   
        if(!Auth::check()){
            toastr()->warning('Login to continue...  !');
            return redirect('/');
        }
        $userdetails = DB::table('users')->where('uuid',$usruuid)->first();
        if(!empty($userdetails)){
            return view('wallet.invoices',compact('userdetails'));
        }else{
            toastr()->warning('User Not Found !');
            return redirect('/');
        }

    }

    public function getinvoicedetails($req)
    {
        $input = $req;
        if(!empty($input)){

            $fromdate=$input['invoice_from_date_pick'];
            $todate=$input['invoice_to_date_pick'];
            if($todate==""){
              $todate=date('Y-m-d');  
            }
        }else{
            $fromdate='';
            $todate='';
            
        }

        $plans=DB::table('plans_purchase')->where('user_id',$input['useruuid'])->orderby('plans_purchase.id','desc');
        if($fromdate!='' && $todate!=''){
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = date('Y-m-d', strtotime($todate));
            //echo"<pre>";print_r($fromdate);die;
            $plans=$plans->whereDate('created_at','>=', $fromdate);
            $plans=$plans->whereDate('created_at','<=', $todate);
        }
           
        return $plans;
    }
    public function invoicedetails(Request $request)
    {   $input=$request->all();
        //echo"<pre>";print_r($input);die;
        $count = $this->getinvoicedetails($input);
        $totalCount = count($count->get());

        $getData = $this->getinvoicedetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);
        }
        $data['invoicedetails'] = $getData->get();
        
         //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['invoicedetails'] as $plan) {
            $action='<div class="action_btn_wrap">
                        <a target="_blank"  href="'.route('ads.invoicePdfdownload',[$plan->uuid,'preview']).'">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <a href="'.route('ads.invoicePdfdownload',[$plan->uuid,'download']).'">
                            <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>
                        </a>
                    </div>';
            $row = array("orderid"=>"#OPID".$plan->id,"amount"=>$plan->payment,"date"=>date("d-M-Y",strtotime($plan->created_at)),"action"=>$action);
            $datas[] = $row;
        }
            //echo "<pre>";print_r($datas);die;
        $output = array(
            "draw"=> $_POST['draw'],
            "recordsTotal" => $totalCount, //$this->companies->count_all(),
            "recordsFiltered" => $totalCount, //$this->companies->count_filtered(),
            "data" => $datas,
        );
        //output to json format
        echo json_encode($output);die;
    }


    public function invoicePdfdownload($id,$option)
    {
       //$purchase=PlansPurchase::where('user_id',Auth::user()->id)->where('uuid',$id)->first();
       $purchase=PlansPurchase::where('uuid',$id)->first();
        //echo "<pre>";print_r($purchase);die;
        if(!empty($purchase)){
            $bill=PurchaseBilling::where('plan_id',$purchase->id)->first();
            if(!empty($bill)){
                    
                    if($purchase->type==0){
                        if($purchase->plan_id==1){
                            $Information="Free Posting Plan";
                        }else {
                            $Information="Premium Ads Plan";
                        }
                    }else if($purchase->type==1){
                        $Information="Platinum Ads Plan";
                    }else{
                         $Information="Feature Ads Plan";
                    }
                    $purpayment=number_format((float)$purchase->payment, 2, '.', '');
                    $data = [
                    'invoiceid' => "OPID".$purchase->id,
                    'created_at' => $purchase->created_at,
                    'expire' => $purchase->expire_date,
                    'payment' => $purchase->payment,
                    'businessname' => $bill->businessname,
                    'addr1' => $bill->addr1,
                    'addr2' => $bill->addr2,
                    'gst' => $bill->gst,
                    'state' => $bill->state,  
                    'city' => $bill->city,  
                    'information' => $Information,
                    'customername' => $bill->username, 
                    'email' => $bill->email,
                    'totalinvoiceamut' =>  getIndianCurrency($purpayment),   
                ];
                if($option=='download'){// 1=download,2=preview
                    $pdf = PDF::loadView('bought_billing.invoicePdf', $data);
                    $pdf->setPaper('A4', 'landscape'); // portrait,landscape
                    $pdfname='Invoice_'."OPID_".$purchase->id.'.pdf'; 
                   /* $pdfname='invoice_'.str_pad($purchase->id, 7, "0", STR_PAD_LEFT).'.pdf';*/ 
                    return $pdf->download($pdfname);
                }
                if($option=='preview'){// 1=download,2=preview
                    $pdf = PDF::loadView('bought_billing.invoicePdf', $data);
                    $pdf->setPaper('A4', 'landscape'); // portrait,landscape
                    $pdfname='Invoice_'."OPID_".$purchase->id.'.pdf'; 
                    //$pdfname='invoice_'.str_pad($purchase->id, 7, "0", STR_PAD_LEFT).'.pdf'; 
                    return $pdf->stream('invoice.pdf');
                }
                return back();
            }else{
                toastr()->warning('Invaild Purchase Invoice !');
                //return redirect()->route('ads.billing');
                return back();
            }
            
        }else{
            toastr()->warning('Invaild Invoice !');
            return redirect()->route('ads.invoice');
        }
       
    }
    public function ratinguser(Request $request)
    {   $input=$request->all();
        //echo "<pre>";print_r($input);die;
        if(!empty($input)){
            $userid=getUserId($input['user']);
            $rating=userstarratingPoint($input['rating']);
            $ratefromuser=getUserId($input['ratefromuser']);
            if($userid !=0 && $rating !=0 && $ratefromuser !=0){
                $userrate=UsersRating::updateOrCreate(
                        ['user_id' => $userid, 'rating_from_user_id' => $ratefromuser],
                        ['user_id' => $userid, 'rating_from_user_id' => $ratefromuser,'rating' => $rating,]
                    );
                return 1;
            }else{
               return 0; 
            }
        }
        return 0;
    }
    public function Userajaxreview(Request $request)
    {   
        $input=$request->all();
        $userrate=UsersRating::updateOrCreate(
                        ['user_id' => $input['seller_id'], 'rating_from_user_id' => Auth::id()],
                        ['user_id' => $input['seller_id'], 'rating_from_user_id' => Auth::id(),'rating' => $input['starrate'],]
                    );
        $userrate=UserReview::Create(['user_id' => $input['seller_id'], 'review_from_user_id' => Auth::id(),'review' => $input['commentss'],]
                    );
        echo "1";die;
    }
    public function chathistory($usruuid){
        if(!Auth::check()){
            toastr()->warning('Login to continue...  !');
            return redirect('/');
        }
        $user = DB::table('users')->where('uuid',$usruuid)->first();
        if(!empty($user)){
            return view('adminchat',compact('user'));
        }else{
            toastr()->warning('User Not Found !');
            return redirect('/');
        }
    }
    public function getadmintouserdata(Request $request){
        $msg='';
        $input=$request->all();
        //echo "<pre>";print_r($input['userdata']);die;
        $findchats=DB::table('chats')
            ->orWhere(function($query) use ($input) {
                $query->where('chats.user_1','=',$input['userdata']);
                $query->orwhere('chats.user_2','=',$input['userdata']);
            })
            ->orderBy('chats.updated_at','desc')
            ->get()->toArray();

        $chatids=array();
        $countunread=0;
        foreach ($findchats as $key => $chatid) {
                if($chatid->user_1 == $input['userdata'] && $chatid->user_1_read_status=='0'){
                    $countunread++;
                }
                if($chatid->user_2 == $input['userdata'] && $chatid->user_2_read_status=='0'){
                    $countunread++;
                }
        }
        if($input['message_status'] == '0'){ //0=unread 1=read 3=all
            
            foreach ($findchats as $key => $chatid) {
                
                if($chatid->user_1 == $input['userdata'] && $chatid->user_1_read_status==$input['message_status']){
                    $chatids[]=$chatid->unique_chats_id;
                    
                }
                if($chatid->user_2 == $input['userdata'] && $chatid->user_2_read_status==$input['message_status']){
                    $chatids[]=$chatid->unique_chats_id;
                }
            }
        }else if($input['message_status'] == '1'){
            foreach ($findchats as $key => $chatid) {
                if($chatid->user_1 == $input['userdata'] && $chatid->user_1_read_status==$input['message_status']){
                    $chatids[]=$chatid->unique_chats_id;
                }
                if($chatid->user_2 == $input['userdata'] && $chatid->user_2_read_status==$input['message_status']){
                    $chatids[]=$chatid->unique_chats_id;
                }
            }
        }else {
            foreach ($findchats as $key => $chatid) {
                if($chatid->user_1 == $input['userdata']){
                    $chatids[]=$chatid->unique_chats_id;
                }
                if($chatid->user_2 == $input['userdata']){
                    $chatids[]=$chatid->unique_chats_id;
                }
            }
        }
        $data=DB::table('chats')
            ->select('user_1','user_2','ads_id','updated_at')
            ->whereIn('chats.unique_chats_id',$chatids)
            ->orderBy('chats.updated_at','desc')
            ->get()->toArray();

        /*$data=DB::table('chats')
            ->select('user_1','user_2','ads_id','updated_at')
            ->orWhere(function($query) {
                $query->where('chats.user_2','=',Auth::user()->id);
                $query->orwhere('chats.user_1','=',Auth::user()->id);
                
            })
            
            ->where('chats.user_2_read_status',1)
            ->orwhere('chats.user_1_read_status',1)
            ->orderBy('chats.updated_at','desc')
            ->get()->toArray();*/
            
            /*$data=DB::table('chats')
            ->select('user_1','user_2','ads_id','updated_at')
            ->where('chats.user_1','=',Auth::user()->id)
            ->orwhere('chats.user_2','=',Auth::user()->id)
            ->orderBy('chats.updated_at','desc')
            ->get()->toArray();*/

        if(!empty($data)){  
            $uniquesusers = array();

            foreach ($data as $key => $userids) {
                
                if($userids->user_2 == $input['userdata']){
                    $uniquesusers[$key] = $userids->user_1.','.$userids->ads_id.','.$userids->updated_at;
                }else{
                    $uniquesusers[$key] = $userids->user_2.','.$userids->ads_id.','.$userids->updated_at;               
                }

            }
            foreach ($uniquesusers as $key => $value) {
                    $chatdata=explode(",",$value);
                    
                    $imageurl=url('public/uploads/profile/small',get_userphoto($chatdata[0]));
                    if(isset($chatdata[1]) && $chatdata[1]!=''){
                        $urlid=getUserUuid($input['userdata'])."_".getUserUuid($chatdata[0])."_".getAdsUuid($chatdata[1]);
                        //$time=get_chatTime($chatdata[0],$chatdata[1]);
                        $time=$chatdata[2];
                        
                        $msg.='<li><a href="'.url('profile/chathistory/msg',[$urlid,getUserUuid($input['userdata'])]).'" class="chat-user-outer-wrap">
                                    <div class="row chat-username-outer-wrap" id="chatlist_'.$urlid.'">
                                        <div class="col-md-2 chat-img-wrap">
                                            <img src="'.$imageurl.'">
                                        </div>
                                        <div class="col-md-8 chat-user-name">
                                            <h6>'.get_username($chatdata[0]).'</h6>
                                            <h5>'.str_limit(get_adsname($chatdata[1]),17).'<span>'.time_elapsed_string($time).'</span></h5>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="chat-details">
                                                <h6><i class="fa fa-ellipsis-h"></i></h6>
                                            </div>
                                        </div>
                                    </div>
                                </a></li>';
                    }else{
                        $urlid=getUserUuid($input['userdata'])."_".getUserUuid($chatdata[0]);
                        /*$time=get_adminchatTime($chatdata[0]);*/
                        $time=$chatdata[2];
                        $msg.='<li><a href="'.url('profile/chathistory/msg',[$urlid,getUserUuid($input['userdata'])]).'" class="chat-user-outer-wrap">
                                    <div class="row chat-username-outer-wrap" id="chatlist_'.$urlid.'">
                                        <div class="col-md-2 chat-img-wrap">
                                            <img src="'.$imageurl.'">
                                        </div>
                                        <div class="col-md-8 chat-user-name">
                                            <h6>'.get_username($chatdata[0]).'</h6>
                                            <h5><span>'.time_elapsed_string($time).'</span></h5>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="chat-details">
                                                <h6><i class="fa fa-ellipsis-h"></i></h6>
                                            </div>
                                        </div>
                                    </div>
                                </a></li>';
                    }
                    
                    
                    
            }
        }
        if($msg==""){
            $msg='<a href="javascript:void(0);">
                    <li style="border:none!important;">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="alert alert-success" role="alert">
                                  <h4 class="alert-heading">No messages, yet?</h4>
                                  <p>We’ll keep messages for any item you’re selling in here</p>
                                  <hr>
                                  
                                </div>
                            </div>
                        </div>
                    </li>
                    </a>';
        }
        $userajax['notification']=$countunread;
        $userajax['msg']=$msg;  
        return $userajax;die;
    }
    public function adminsyncmessageview($id){
        if(Auth::check()){
            $urldata=explode("_",$id);
            if(isset($urldata[2]) && $urldata[2]!=''){
                $unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1])."_".getAdsId($urldata[2]);
                $ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0])."_".getAdsId($urldata[2]);
            }else{

                $unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1]);
                $ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0]);
            }
            $chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
            if(!empty($chat)){
                $user=User::find(getUserId($urldata[1]));
                $privacy=Privacy::where('user_id',$user->id)->first();
                //echo "<pre>";print_r($chat);die;
                return view('adminchatsync',compact('user','chat','privacy'));
            }else{
                toastr()->error('Unauthorized Access');
                    return back();
            }
        }else{
            toastr()->warning('Login to continue!');
            return back();
        }
    }
    public function AdminajaxSyncGetMessage(Request $request){
        $input=$request->all();
        //echo "<pre>";print_r($input);die;
        $urldata=explode("_",$input['urlid']);
        if(isset($urldata[2]) && $urldata[2]!=''){
            $unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1])."_".getAdsId($urldata[2]);
            $ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0])."_".getAdsId($urldata[2]);
        }else{
            $unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1]);
            $ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0]);
        }

        $chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
        
        $Blockcheck=BlockedUserChat(getUserId($urldata[0]),getUserId($urldata[1]));
        if($Blockcheck['ref']==1 ){
            $chatcreate= ChatsMessage::where('chat_id',$chat->unique_chats_id)->orderby('created_at','asc')->get();
        }/*elseif($Blockcheck['ref']==3){
            $chatcreate= ChatsMessage::where('chat_id',$chat->unique_chats_id)->orderby('created_at','desc')->orwhere('status',getUserId($urldata[1]))->get();
        }*/else{
            $chatcreate= ChatsMessage::where('chat_id',$chat->unique_chats_id)->where('status',1)->orderby('created_at','asc')->get();
        }
        $msg="";
        foreach ($chatcreate as $key => $message) {
            if($message->user_id == getUserId($input['userdata'])){
                if(!empty($message->image)){
                    $chatimage=url('public/uploads/chat',$message->image);
                    
                    $msg.='<div class="media msg_sender chat_M_'.$message->id.'">
                            <div class="msg_body ml-3">
                                <div class="bg-green rounded image_sent_outer_wrap py-2 px-3 msg_delete_indiv" data-chatmessage="'.$message->id.'" data-chattype="2">
                                    <div class="img_sent_wrap">
                                        <img src="'.$chatimage.'" title="" alt="">
                                    </div>
                                </div>
                            </div>
                                    <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
                        </div>';
                            


                }
                if(!empty($message->location)){
                    $location=explode(",", $message->location);
                    $mapkey=getStaticMapKey();
                    $locationurl="https://www.google.co.in/maps/@".$location[0].",".$location[1].",15z";
                    $locationimage="https://maps.googleapis.com/maps/api/staticmap?center=".$location[0]."%2C".$location[1]."&amp;language=en&amp;size=640x256&amp;zoom=16&amp;scale=1&amp;&markers=color:red%7Clabel:%7C".$location[0].",".$location[1]."&key=".$mapkey."";
                    $msg.='<div class="media msg_sender chat_M_'.$message->id.'">
                            <div class="msg_body ml-3">
                                <div class="bg-green rounded image_sent_outer_wrap py-2 px-3 msg_delete_indiv" data-chatmessage="'.$message->id.'" data-chattype="1" >
                                    <div class="img_sent_wrap">
                                        <a href="'.$locationurl.'" target="_blank">
                                          <img class="img-fluid" src="'.$locationimage.'"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                                <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
                        </div>';

                }
                if(!empty($message->msg)){
                    $msg.='<div class="media msg_sender chat_M_'.$message->id.'">
                                            <div class="msg_body ml-3">
                                                <div class="bg-green rounded py-2 px-3 msg_delete_indiv" data-chatmessage="'.$message->id.'" data-chattype="1">
                                                <p class="text-small mb-0 receiver-text">'.$message->msg.'</p>
                                                </div>
                                            </div><img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
                                        </div>';

                }
                if(!empty($message->video)){
                    $chatvideo=url('public/uploads/chatvideo',$message->video);
                    $msg.='<div class="media msg_sender chat_M_'.$message->id.'">
                            <div class="msg_body ml-3">
                                <div class="bg-green rounded image_sent_outer_wrap py-2 px-3 msg_delete_indiv" data-chatmessage="'.$message->id.'" data-chattype="3">
                                    <div class="img_sent_wrap">
                                        <a href="'.$chatvideo.'" download>
                                            <video class="embed-responsive" >
                                              <source src="'.$chatvideo.'" type="video/mp4">
                                              <source src="'.$chatvideo.'" type="video/ogg">
                                              <source src="'.$chatvideo.'" type="video/3gp">
                                            </video>
                                        </a>
                                    </div>
                                </div>
                            </div>
                                <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
                        </div>
                    ';

                }
                
            }else{
                if(!empty($message->image)){
                    $chatimage=url('public/uploads/chat',$message->image);
                    
                    $msg.='<div class="media">
                            <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
                            <div class="msg_body ml-3">
                                <div class="bg-light rounded image_sent_outer_wrap py-2 px-3">
                                    <div class="img_sent_wrap">
                                        <img src="'.$chatimage.'" title="" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>';


                }
                if(!empty($message->location)){
                    $location=explode(",", $message->location);
                    $mapkey=getStaticMapKey();
                    $locationurl="https://www.google.co.in/maps/@".$location[0].",".$location[1].",15z";
                    $locationimage="https://maps.googleapis.com/maps/api/staticmap?center=".$location[0]."%2C".$location[1]."&amp;language=en&amp;size=640x256&amp;zoom=16&amp;scale=1&amp;&markers=color:red%7Clabel:%7C".$location[0].",".$location[1]."&key=".$mapkey."";
                        $msg.='<div class="media">
                            <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
                            <div class="msg_body ml-3">
                                <div class="bg-light rounded image_sent_outer_wrap py-2 px-3">
                                    <div class="img_sent_wrap">
                                        <a href="'.$locationurl.'" target="_blank">
                                          <img class="img-fluid" src="'.$locationimage.'"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>';

                }
                if(!empty($message->msg)){
                    $msg.='<div class="media">
                            <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
                            <div class="msg_body ml-3">
                                <div class="bg-light rounded py-2 px-3">
                                <p class="text-small mb-0 sender-text">'.$message->msg.'</p>
                                </div>
                            </div>
                        </div>';
                }
                if(!empty($message->video)){
                    $chatvideo=url('public/uploads/chatvideo',$message->video);
                    
                    $msg.='<div class="media">
                            <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
                            <div class="msg_body ml-3">
                                <div class="bg-light rounded image_sent_outer_wrap py-2 px-3">
                                    <div class="img_sent_wrap">
                                        <a href="'.$chatvideo.'" download>
                                            <video class="embed-responsive" >
                                              <source src="'.$chatvideo.'" type="video/mp4">
                                              <source src="'.$chatvideo.'" type="video/ogg">
                                              <source src="'.$chatvideo.'" type="video/3gp">
                                            </video>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
            }
        }
        return $msg;die;
    }
}
