<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Uuid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\User;
use App\Setting;
use App\ReportUsers;
use App\ReportAds;
use App\AdsFeatures;
use App\Ads;
use App\TopAds;
use Image;
use App\PlansPurchase;
 use App\Notification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth'], ['except' => ['ajaxotp','ajaxemailupdate','referralregister','registercheck','mobile_verify_reg','ajaxmobilecheck','ajaxreportusersubmit','ajaxreportadssubmit','welcome','useravailable','emailregister','mobileregister','searchlocation','search','getlanlat','invalidLoginError','deva','deva1']]);
    }



    public function deva()
    {        
        echo '<a href="'.route("test2").'">Redirect</a>';
    }

    public function deva1()
    {        
        echo "hi";die;
    }

    protected function guard()
    {
        return Auth::guard();
    }
     protected function registered(Request $request, $user)
    {
        //
    }
    protected function invalidLoginError(Request $request)
    {
        return response()->json(['success'=>false,'message'=>'Please Login '], 422); 
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {   //echo"<pre>34";print_r($request->route('id'));die;
        if ($request->route('id') != $request->user()->getKey()) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            addpoint($user->id,'new_reg_point');
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    

    public function mailstatus()
    {
        return view('mailstatus');
    }

    public function referralregister($referralid)
    {   $ref=$referralid;
        $referral=User::where('referral_code',$referralid)->first();
        if(!empty($referral)){
            //$ref= substr($referralid,6);
            toastr()->success('Valid Referral Code !');
            return view('auth.register',compact('ref'));
        }else{
            toastr()->error('Invalid Referral Code !');
            return view('auth.register');
        }
    }

    public function ajaxmobilecheck(Request $request)
    {
        $phonecheck=User::where('phone_no',$request->mobile)->where('id','!=',Auth::id())->first();
        if(empty($phonecheck)){
            if(strlen($request->mobile) == 10){
                return response()->json(['success' => 'new']);
            }else{
                return response()->json(['invalid' => 'invalid format']);
            }
            
        }else{
            return response()->json(['error' => 'already taken']);
        }
        
    }

    public function ajaxotp(Request $request)
    {
        $phonecheck=User::where('phone_no',$request->mobile)->first();
        if(!empty($phonecheck)){
            $user=User::find($request->id);
            if($user){
                $user->phone_no = $request->mobile;
                $user->phone_verified_at =date("Y-m-d h:i:s");
                $user->save();
                addpoint($user->id,'new_reg_point');
                referralpoints($user->id,$user->referral_register);
            }
            return response()->json(['success' => 'new']);
        }else{
            return response()->json(['error' => 'already taken']);
        }
        
    }

    public function ajaxemailupdate(Request $request)
    {
        $emailcheck=User::where('email',$request->email)->first();
        if(empty($emailcheck)){
            $user=User::find($request->id);
            if($user){
                $user->email = $request->email;
                $user->save();
                event(new Registered($user));
                referralpoints($user->id,$user->referral_register);
            }
            return response()->json(['success' => 'registered']);
        }else{
            return response()->json(['error' => 'already taken']);
        }
        
    }

    protected function create(array $data)
    {   
        //echo "<pre>";print_r($data);die;
        $referralcode=referral();
        $uuid = Uuid::generate(4);
        $collection = collect(["Avatar1.png","Avatar2.png", "Avatar3.png", "default.png"]);
        $image=$collection->random();

         return User::create([
            'name' => "User".time(),
            'email' => $data['email'],
            'phone_no' => $data['phone'],
            'role_id'=>"2",
            'uuid'=>$uuid,
            //'phone_verified_at'=>date("Y-m-d H:i:s"),
            'password' => Hash::make($data['password']),
            'referral_code' =>$referralcode,
            'photo'=>$image,
            'account_register_reference'=> '2',
            ]);

        // if(is_numeric($data['email'])){

        //     return User::create([
        //     'name' => "User".time(),
        //     /*'phone_no' =>$data['phone'],*/
        //     'phone_no' => $data['email'],
        //     'role_id'=>"2",
        //     'uuid'=>$uuid,
        //     'phone_verified_at'=>date("Y-m-d H:i:s"),
        //     'password' => Hash::make($data['password']),
        //     'referral_code' =>$referralcode,
        //     'photo'=>$image,
        //     'account_register_reference'=> '2',
        //     ]);

        // }else{

        //     return User::create([
        //     'name' => "User".time(),
        //     /*'phone_no' =>$data['phone'],*/
        //     'email' => $data['email'],
        //     'role_id'=>"2",
        //     'uuid'=>$uuid,
        //     'password' => Hash::make($data['password']),
        //     'referral_code' =>$referralcode,
        //     'photo'=>$image,
        //     'account_register_reference'=> '1',
        //     ]);
        // } 
        
    }

    

    public function ajaxreportusersubmit(Request $request)
    {
        $input = $request->all();
         $request->validate([
            'file'     => 'mimes:doc,docx,pdf,jpeg,jpg,png|max:1024',
        ]);

        $ads  = Ads::where('uuid',$input['uuuid'])->first();
        $imgname = null;
        if($request->file('file') !=''){
            $file = $request->file('file');
            //echo '<pre>';print_r( $file );die;
            $imgname = time().$input['uuuid'].'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/complaints/');
            $file->move($destinationPath, $imgname);
        }

        //echo"<pre>";print_r($input); die;
        $users  = User::where('uuid',$input['uuuid'])->first();
        $reportusers = new ReportUsers;
        $reportusers->user_id = auth::id();
        $reportusers->report_user_id = $users->id;
        $reportusers->reason = $input['reportuser'];
        $reportusers->comments = $request['commentss'];
        $reportusers->image = $imgname;
        $reportusers->save();
        
        return 1;
        
    }
    public function ajaxreportadssubmit(Request $request)
    {
        $input = $request->all();
        /*$request->validate([
            'file'     => 'mimes:doc,docx,pdf,jpeg,jpg,png|max:1024',
        ]);*/
        //return 2;
        $ads  = Ads::where('uuid',$input['uuuid'])->first();


        $imgname = null;
        if($request->file('file') !=''){
             $request->validate([
                'file'     => 'mimes:doc,docx,pdf,jpeg,jpg,png|max:1024',
            ]);
            $file = $request->file('file');
            $imgname = time().$input['uuuid'].'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/complaints/');
            $file->move($destinationPath, $imgname);
        }


        $reportads = new ReportAds;
        $reportads->user_id = Auth::id();
        $reportads->report_ads_id = $ads->id;
        $reportads->reason = $input['reportads'];
        $reportads->comments = $request['commentss'];
        $reportads->image = $imgname;
        $reportads->save();
        return 1;
        
    }

    public function welcome(Request $request)
    {
        //$date = Carbon::now();
        //$current_date=$date->toDateString();
        $categories =   DB::table('parent_categories')
                        ->select('id','name','image','icon')
                        ->where('status',1)
                        ->get()->toArray();

        // $featuedAd  =   DB::table('ads_features')
        //                 ->leftJoin('ads', 'ads.id', '=', 'ads_features.ads_id')
        //                 ->where('expire_date','>=',$current_date)
        //                 ->get()->toArray();
        $featuedAd  =   DB::table('ads_features')->get()->toArray();
       
        return view('welcome',compact('categories','featuedAd'));
    }

    // public function welcome()
    // {   
    //     $date = Carbon::now();
    //     $current_date=$date->toDateString();
    //     $adsfeatures=DB::table('ads_features')->where('expire_date','>=',$current_date)->offset(0)->limit(4)->pluck("ads_id");
    //     $setting=Setting::first();
    //     if(!empty($adsfeatures)){
            
    //         $aditems = DB::table('ads')
    //         ->leftJoin('ads_image', 'ads_image.ads_id', '=', 'ads.id')
    //         ->where('ads.status',"1")
    //         ->whereIn('ads.id',$adsfeatures)
    //         ->select('ads.*','ads_image.id as adsimgid','ads_image.image')
    //         ->groupBy("ads.id")
    //         ->get()->toArray();


    //         $favads = array();
    //         if(Auth::check()){
    //             $favads = DB::table('favourites')
    //                   ->where('user_id',Auth::user()->id)
    //                   ->get()->toArray();
    //         }
    //         $ads = array();
    //         foreach ($aditems as $key => $ad) {
    //             if(!empty($favads)){
    //                 foreach ($favads as $key => $fad) {
    //                     $ads[$ad->id] = $ad;
    //                     if($fad->ads_id == $ad->id){
    //                         $ads[$ad->id]->favv = "1";
    //                     }                  
    //                 }
    //             }else{
    //                 $ads[$ad->id] = $ad;                
    //             }
    //             if($setting->ads_view_point <= $ads[$ad->id]->point){
    //                 $ads[$ad->id]->viewpoint = "1";
    //             }
    //         }
    //         $featued=TopAds::where('type',1)->where('status',1)->get();
    //         //echo"<pre>";print_r($ads); die;
    //         return view('welcome',compact('ads','featued'));
    //     }else{
    //         return view('welcome');
    //     }
    // }



    public function useravailable(Request $request)
    {
        $input=$request->all();
        $available=0;
        $result=DB::table('users')->where('email', $input['email'])->orWhere('work_mail', $input['email'])->orWhere('phone_no', $input['phone'])->first();
            if(!empty($result)){
                //toastr()->error('Mobile Number Already Taken');
                $available=0; //not available
            }else{
                $available=1;
               
            }
        return $available;
        

        // if(is_numeric($input['email'])){
        //         $result=DB::table('users')->where('phone_no', $input['email'])->first();
        //         if(!empty($result)){
        //             //toastr()->error('Mobile Number Already Taken');
        //             $available=0; //not available
        //         }else{
        //             if(strlen($input['email'])==10){
        //                 $available=1;
        //             }else{
        //                  $available=0;
        //             }
        //         }
        //     return $available;
        // }else{
        //     $result=DB::table('users')->where('email', $input['email'])->orWhere('work_mail', $input['email'])->first();
        //     if(!empty($result)){
        //         //toastr()->error('Mobile Number Already Taken');

        //         $available=0; //not available
        //     }else{
        //         $available=2; // available
        //         if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        //                 $available=0;
        //         }
        //     }
        //     return $available;
        // }
    }
    public function emailregister(Request $request)
    {
        
        $input=$request->all();
        //echo"<pre>";print_r($input); die;
        if(!empty($input['referral_register'])){
            $refferralcheck  = User::where('referral_code',$input['referral_register'])->first();
            
            if(!empty($refferralcheck)){
                event(new Registered($user = $this->create($request->all())));
                $this->guard()->login($user);


                $user->referral_register = $input['referral_register'];
                $user->save();


                //referralpoints($user->id,$input['referral_register']);
                predefined_privacy_table($user->id);
                return 1;
            }else{
                event(new Registered($user = $this->create($request->all())));
                $this->guard()->login($user);
                predefined_privacy_table($user->id);
                return 1;   
            }
            

        }else{
            
            event(new Registered($user = $this->create($request->all())));
            $this->guard()->login($user);
            predefined_privacy_table($user->id);
            return 1;
        }
    }
    public function mobileregister(Request $request)
    {
        $input=$request->all();
        //echo "<pre>";print_r($input);die;
        if(!empty($input['referral_register'])){
                event(new Registered($user = $this->create($request->all())));
                $this->guard()->login($user);
                addpoint($user->id,'new_reg_point');

                $user->referral_register = $input['referral_register'];
                $user->save();

                //referralpoints($user->id,$input['referral_register']);
                predefined_privacy_table($user->id);
                //return $this->registered($request, $user) ?: redirect($this->redirectPath());
        }else{
            //echo"<pre>";print_r($input); die;
            event(new Registered($user = $this->create($request->all())));
            $this->guard()->login($user);
            addpoint($user->id,'new_reg_point');
            predefined_privacy_table($user->id);
            //return $this->registered($request, $user) ?: redirect($this->redirectPath());
        }
        return 1;
    }
    /*public function mobileregister(Request $request)
    {
        $input=$request->all();
        //echo "<pre>";print_r($input);die;
        $otp = getOTP();
        if($otp==$input['otp'])
        {
            if(!empty($input['referral_register'])){
                    event(new Registered($user = $this->create($request->all())));
                    $this->guard()->login($user);
                    addpoint($user->id,'new_reg_point');

                    $user->referral_register = $input['referral_register'];
                    $user->save();

                    //referralpoints($user->id,$input['referral_register']);
                    predefined_privacy_table($user->id);
                    //return $this->registered($request, $user) ?: redirect($this->redirectPath());
            }else{
                //echo"<pre>";print_r($input); die;
                event(new Registered($user = $this->create($request->all())));
                $this->guard()->login($user);
                addpoint($user->id,'new_reg_point');
                predefined_privacy_table($user->id);
                //return $this->registered($request, $user) ?: redirect($this->redirectPath());
            }
            return 1;
        }else{
            return 0;
        }
    }*/

    public function searchlocation(Request $request)
    {
        if($request->ajax()) {
            $searchValue = $request->q;
            $output = '<ul>';                      
            $outputdata = $this->searchLocations($searchValue);
            
            if($outputdata == ''){
                $output .= '<li class="list-group-item li_pincode" disabled>'.'No results'.'</li>';
            }else{
                $output .= $outputdata;
            }
            $output .= '</ul>';
            return $output;
        }

    }

    public function searchLocations($searchValue='')
    {
        /*$areaname = DB::table('areas')
                ->leftjoin('cities', 'cities.id', '=', 'areas.city_id')
                ->select('cities.id as city_id',DB::raw("CONCAT(areas.name,', ',cities.name) as area"));*/
        $areaname = DB::table('areas')
                ->leftjoin('cities', 'cities.id', '=', 'areas.city_id')
                ->select('areas.id as area_id','cities.id as city_id','cities.state_id as state_id',DB::raw("CONCAT(areas.name,', ',cities.name) as area"));
            $areaname->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("areas.name","like","%".$searchValue."%");
            });     
        $areanameresults = $areaname->where("areas.status",1)->limit(5)->groupby('areas.name')->get();
        /*$cityname = DB::table('cities')
                ->leftjoin('states', 'states.id', '=', 'cities.state_id')
                ->select('cities.id as city_id',DB::raw("CONCAT(cities.name,', ',states.name) as city"));*/
        $cityname = DB::table('cities')
                ->leftjoin('states', 'states.id', '=', 'cities.state_id')
                ->select('cities.id as city_id','states.id as state_id',DB::raw("cities.name as city"));
            $cityname->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("cities.name","like","%".$searchValue."%");
            });     
        $citynameresults = $cityname->where("cities.status",1)->limit(5)->groupby('cities.name')->get();
        $output = '';
        //$output .= '<ul>';
        if(!empty($areanameresults)){
            foreach ($areanameresults as $key => $areas) {
                $output .= '<li class=""><i class="ojaak_icons_pin"></i><span class="li_locationaddr" data-cityid="'.$areas->city_id.'" data-cityName="'.$areas->area.'"data-stateid="'.$areas->state_id.'" data-areaid="'.$areas->area_id.'">'.$areas->area.'</span></li>';
            }
        }
        if(!empty($citynameresults)){
            foreach ($citynameresults as $key => $cities) {
                $output .= '<li class=""><i class="ojaak_icons_pin"></i><span class=" li_locationaddr" data-cityid="'.$cities->city_id.'"data-cityName="'.$cities->city.'" data-stateid="'.$cities->state_id.'" data-areaid="0">'.$cities->city.'</span></li>';
            }
        }
        //$output .= '</ul>';

        return $output;
    }



    public function search(Request $request)
    {
        //if($request->ajax()) {
            $searchValue = $request->q;                      
            $output = $this->searchProducts($searchValue);
            if($output == ''){
                $output .= '<li class="list-group-item li_pincode" disabled>'.'No results'.'</li>';
            }
            //echo '<pre>';print_r( $output );die;
            return $output;
        //}

    }

    public function searchProducts($searchValue='')
    {
        
        $getbrands = DB::table('fields_options')
                ->leftjoin('category_field', 'category_field.field_id', '=', 'fields_options.field_id')
                ->leftjoin('sub_categories', 'sub_categories.id', '=', 'category_field.category_id')
                //->leftjoin('parent_categories', 'parent_categories.id', '=', 'sub_categories.parent_id')
                ->select('fields_options.value as title','sub_categories.name as sub_title','sub_categories.id as sub_cate_id','sub_categories.parent_id as category_id');
            $getbrands->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("fields_options.value","like","%".$searchValue."%");
            });     
        $getbrandsresults = $getbrands->limit(5)->groupBy("fields_options.value")->get()->toArray();

        $getsubcategory = DB::table('sub_categories')
                ->leftjoin('parent_categories', 'parent_categories.id', '=', 'sub_categories.parent_id')
                ->select('sub_categories.name as title','parent_categories.name as sub_title','sub_categories.id as sub_cate_id','sub_categories.parent_id as category_id');
            $getsubcategory->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("sub_categories.name","like","%".$searchValue."%");
            });     
        $getsubcategoryresults = $getsubcategory->limit(5)->groupBy("sub_categories.name")->get()->toArray();


        $getads = DB::table('ads')
                ->leftjoin('sub_categories', 'sub_categories.id', '=', 'ads.sub_categories')
                ->select('ads.title as title','sub_categories.name as sub_title','sub_categories.id as sub_cate_id','sub_categories.parent_id as category_id');
            $getads->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("ads.title","like","%".$searchValue."%");
                $query->orWhere("ads.ads_ep_id","like","%".$searchValue."%");
            });     
        $getadsresults = $getads->limit(5)->groupBy("ads.title")->get()->toArray();

        /*echo '<pre>';print_r( $getbrandsresults );
        echo '<pre>';print_r( $getsubcategoryresults );
        echo '<pre>';print_r( $getadsresults );*/

        $mergearray = array();

        $mergearray = array_merge($getbrandsresults,$getsubcategoryresults);
        $finalmergearray = array_merge($mergearray,$getadsresults);

        //return $finalmergearray;

        $output = '';
        $output .= '<ul >';
        if(!empty($finalmergearray)){
            foreach ($finalmergearray as $key => $final) {
                $output .= '<li class="li_product_data" data-categoryId="'.$final->category_id.'" data-subCategoryId="'.$final->sub_cate_id.'" data-cate-search="'.$final->title.'">'.$final->title.'<br/><span>'.$final->sub_title.'</span></li>';
            }
        }
        $output .= '</ul>';
        return $output;

    }
    public function getlanlat(Request $request)
    {   $location=array();
        $input=$request->all();
        //echo '<pre>';print_r( $input );die;
        $address = str_replace(' ', '+', $input['address']); //google api not allow space
        $requestedg = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.',&key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4&sensor=false';
        /*$requestedg = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.',&key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4&sensor=false';*/
        $file_contents = file_get_contents($requestedg);
        $json_decode = json_decode($file_contents);
        //echo '<pre>';print_r( $file_contents);die;
        $location = array('lat'=>$json_decode->results[0]->geometry->location->lat,'lng'=>$json_decode->results[0]->geometry->location->lng);
        return $location;
       

    }


    public function getavailablefreeads()
    {   
        $postcount=PlansPurchase::where('user_id',Auth::user()->id)->where('type','0')->where('plan_id','1')->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30)->toDateString())->get()->count();
        $settings= DB::table('settings')->first();
        if($postcount<=$settings->no_free_ads_point_per_month){
            echo "1";die;
        }else{
            echo "0";die;
        }
    }



    public function shownotificationicon()
    {

        $notification=Notification::where('user_id',Auth::User()->id)->orderBy('id','desc')->count();
        //echo '<pre>';print_r( $notification );die;
        $findchats=DB::table('chats')
                    ->orWhere(function($query) {
                        $query->where('chats.user_1','=',Auth::user()->id);
                        $query->orwhere('chats.user_2','=',Auth::user()->id);
                    })
        ->get()->toArray();

        if(!empty($findchats)){
            $unread_msg_count_id = array();
            foreach ($findchats as $key => $chatid) {
            
                if($chatid->user_1 == Auth::user()->id && $chatid->user_1_read_status=='0'){
                    $unread_msg_count_id[]=$chatid->id;
                    
                }
                if($chatid->user_2 == Auth::user()->id && $chatid->user_2_read_status=='0'){
                    $unread_msg_count_id[]=$chatid->id;
                }
            }

            $chatUnreadCount = count($unread_msg_count_id);
            echo $notification."@@@".$chatUnreadCount;die;
        }
    }
    
}
