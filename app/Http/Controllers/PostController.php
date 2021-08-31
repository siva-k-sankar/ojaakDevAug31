<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\PlansLimit;
use App\Sub_categories;
use App\Parent_categories;
use App\Ads;
use App\City_requests;
use App\Adsimage;
use Uuid;
use Carbon\Carbon;
use Image;
use Illuminate\Support\Str;
use App\Customfield;
use App\Customfield_Options;
use App\Category_field;
use App\PostValues;
use App\AdsLimits;
use App\PlansLimits;
use App\PaidAds;
use App\PlansPurchase;
use App\Setting;
use DateTime;
use App\AdsTemp;
use App\PostValuesTemp;
use App\PostValueTemp;
use App\AdsimageTemp;
use App\Premiumadsplan;
use App\Premiumplansdetails;
class PostController extends Controller
{   
    public function __construct()
    {
        $this->middleware(['auth','users'],['except' => ['getcities','getareas','getpincode']]);
    }
    public function index()
    {   
        if(empty(Auth::check())){
            toastr()->warning('login to Post Ads !');
            return redirect('/');
        }
        if (session()->has('adsid')) {
            return redirect()->route('ads.prevent');
        }
        $parent=Parent_categories::where('status',1)->get();
        $sub=Sub_categories::where('status',1)->get();
        
        return view('ads.post',compact('parent','sub'));
    }
    public function ajaxsubcate(Request $request)
    {   
        $input=$request->all();
        $sub=Sub_categories::where('status',1)->where('parent_id',$input['id'])->get();
        $cate='';
        $id='#';
        foreach ($sub as $key => $value) {
            $id=route('ads.post.attributes',$value['uuid']);
            $cate.='<li><a href="'.$id.'">'.$value['name'].'</a>
                            </li>';
        }

        return $cate;
    }
    public function redirectPost(Request $request)
    {   $input=$request->all();
        return redirect()->route('ads.post.attributes',$input['category']);
    }
    public function getcities(Request $request)
    {   
        $cities = DB::table("cities")->where('state_id',$request->get('stateid'))->where('status',"1")->pluck("name","id");
        return json_encode($cities);
    }
    public function getareas(Request $request)
    {   
        $area = DB::table("areas")->where('city_id',$request->get('cityid'))->where('status',"1")->pluck("name","id");
        return json_encode($area);
    }
    public function getpincode(Request $request)
    {   
        $area = DB::table("areas")->where('id',$request->get('areaid'))->where('status',"1")->pluck("pincode");
        //echo"<pre>";print_r($area);die;
        return json_encode($area);
    }
    public function attributes($id)
    {   
        if(empty(Auth::check())){
                toastr()->warning('login to Post Ads !');
                return redirect('/');
        }
        //session()->put('adsid', 1);
        if (session()->has('adsid')) {
            return redirect()->route('ads.prevent');
            //session()->forget('adsid');
        }
    	$customfields="";
        $nowdate = new DateTime('now');
        $nowdate = $nowdate->format('Y-m-d h:i:s');
        $sub=Sub_categories::where('uuid',$id)->first();
        if(!empty($sub)){
        	$parent=Parent_categories::where('id',$sub->parent_id)->first();
            $plans=PlansPurchase::where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->whereDate('expire_date','>',$nowdate)->get();
            //echo"<pre>";print_r($plans);die;
        	if(!empty($parent)){
                $get_category_field=DB::table('category_field')->where('category_id',$sub->id)->orderBy('field_id', 'ASC')->get();
                $brands= DB::table('category_brands')->where('sub_cate_id',$sub->id)->get();
                $states= DB::table('states')->where('status','1')->get();
                //echo"<pre>";print_r($states);die;
                if(!empty($get_category_field)){
                    foreach ($get_category_field as $key => $field) {
                        $customfields.=$this->getfieldHtml($field->field_id);
                                                          
                    }

                    // echo"<pre>";print_r($get_category_field);die;

                    return view('ads.attributes',compact('parent','states','sub','customfields','plans','brands'));
                }else{

                    return view('ads.attributes',compact('parent','states','sub','plans','brands'));
                }
                
            }else{
        		toastr()->error('Category Not Found');
        		return back();
        	}

        }else{
        	toastr()->error('Category Not Found');
        	return back();
        }
    }

    public function getbrandmodels(Request $request)
    {   
        $input=$request->all();
        $customfields = '';
        if(!empty($input['brandId'])){

            if ($input['brandId'] >= 86 && $input['brandId'] <= 107){
                $customfields.="<div class='form-group'>
                                <label>Model <sup>*</sup></label>
                                    <input type='text' name='models' required class='form-control' ></div>";
            }else{


                $category_models= DB::table('category_models')->where('cate_brand_id',$input['brandId'])->get();
                 $customfields.="<div class='form-group'>
                                <label>Model <sup>*</sup></label>
                                    <select class='form-control' required name='models'>";
                    $customfields.="<option value='0'>Select Model</option>";
                foreach ($category_models as $key => $model) {
                    $customfields.="<option value='$model->id'>$model->model</option>";
                }  

                $customfields.="</select></div>";
            }

            return $customfields;  
        }else{
            return 0;
        }
    }
    public function getfieldHtml($fields){
        $customfields="";
        $get_custom_field=Customfield::where('id',$fields)->where('active',1)->first(); 
         
        if(!empty($get_custom_field->type)){

            if($get_custom_field->required == 1){
                $sub="*";
                $required='required';
            }else{
                $sub="";
                $required='';
            }

            if($get_custom_field->type =="text"){
                $customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                            <input type='$get_custom_field->type' class='form-control'  name='cf[$get_custom_field->id]' placeholder='' $required max=''></div>";
            }
            if($get_custom_field->type =="date"){
                $customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                            <input type='text' class='form-control date_form_input'  name='cf[$get_custom_field->id]' placeholder=''  $required max=''></div>";
            }

            if($get_custom_field->type=="number"){
                $customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                            <input type='number' class='form-control' min='0'  name='cf[$get_custom_field->id]' placeholder='' $required max=''></div>";
            }

            if($get_custom_field->type =="checkbox"){
                $checked='checked';
                $customfields.="<div class='multicheck_box_wrap form-group'>
                            <label class='multicheck_label_wrap'>$get_custom_field->name <sup>$sub</sup></label>
                            <div class='multicheck_outer_wrap'>
                                <label class='multicheck_box_inner_wrap'>
                                    <input type='checkbox' id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' value='$get_custom_field->default' max='$get_custom_field->max' $required $checked>
                                    <span class='checkmark'></span>
                                </label>
                            </div>
                        </div>";
                $checked='';
            }

            if($get_custom_field->type =="textarea"){
                 $customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                            <textarea class='form-control' rows='3' id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' placeholder=''  value='$get_custom_field->default' max='$get_custom_field->max' $required></textarea></div>";
            }
            
            if($get_custom_field->type == "radio"){
                $customfields.="<div class='multicheck_box_wrap radio_btn_wrap form-group cf_$get_custom_field->id'>
                            <label class='multicheck_label_wrap'>$get_custom_field->name <sup>$sub</sup></label>
                            <div class='multicheck_outer_wrap'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {
                    $checked='';    
                    $customfields.="<label class='multicheck_box_inner_wrap'>$option->value
                                    <input type='radio' name='cf[$get_custom_field->id]' id='cf.$get_custom_field->id.$option->id' value='$option->id' $required $checked>
                                    <span class='checkmark'></span>
                                </label>";
                                $required='';
                                $checked='';
                }  

                $customfields.="</div></div>";
            }

            if($get_custom_field->type == "select"){
                 $customfields.="<div class='form-group common_select_option contact_form_select_btn_wrap  cf_$get_custom_field->id'>
                                <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                                    <select class='form-control'  name='cf[$get_custom_field->id]' id='cf.$get_custom_field->id' >";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {
                        
                    $customfields.="<option value='$option->id'>$option->value</option>";
                                    
                }  

                $customfields.="</select></div>";
            }

            if($get_custom_field->type == "checkbox_multiple"){
                $customfields.="<div class='multicheck_box_wrap form-group'>
                            <label class='multicheck_label_wrap'>$get_custom_field->name <sup>$sub</sup></label>
                            <div class='multicheck_outer_wrap'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {
                    $checked='checked';    
                    $customfields.="<label class='multicheck_box_inner_wrap'>$option->value
                                    <input type='checkbox' name='cf[$get_custom_field->id][$option->id]' id='cf.$get_custom_field->id.$option->id' value='$option->id' $required $checked>
                                    <span class='checkmark'></span>
                                </label>";
                    $required='';
                    $checked='';            
                }  

                $customfields.="</div></div>";
            }

            if($get_custom_field->type =="file"){
                 $customfields.="<div class='form-group row'>
                                    <label for='$get_custom_field->type' class='col-sm-3 col-form-label'>$get_custom_field->name<sup>$sub</sup></label>
                                    <div class='col-sm-9'>
                                    <input type='$get_custom_field->type' class='form-control-file mb-2  id='$get_custom_field->name' name='$get_custom_field->name' placeholder=''  value='$get_custom_field->default' max='$get_custom_field->max'>
                                    </div>
                                </div>";

            }

        return $customfields;    
        }  

    }
    public function choosePlanPost(Request $request)
    {   
        if(Auth::check()){

            PlansPurchase::where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->where('plan_id',1)->delete();

            // $adsid =1;
            // $cateid=2;
            // $plansPreferCategory=plancategorychoose($cateid);
            //echo '<pre>';print_r($plansPreferCategory);die;
            if (session()->has('adsid')) {

                 $adsid =session()->get('adsid');
                 //echo '<pre>';print_r($adsid);die;
            }else{
                toastr()->warning('Error Occoured Try To Reposting ! ');
                return redirect()->route('ads.post');
            }

            if (session()->has('catesid')) {
                $cateid =session()->get('catesid');
                $plansPreferCategory=plancategorychoose($cateid);
            }else {
                toastr()->warning('Error Occoured Try To Reposting ! ');
                return redirect()->route('ads.post');
            }
            //echo '<pre>';print_r($plansPreferCategory);die;

            $nowdate = new DateTime('now');
            $nowdate = $nowdate->format('Y-m-d h:i:s');
            $plans=PlansPurchase::where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->whereDate('expire_date','>',$nowdate)->whereIN('plan_id',$plansPreferCategory)->orderBy('id','desc')->get();
            $freeplans=Premiumadsplan::where('status',1)->whereIN('id',$plansPreferCategory)->get();
            $plansdetails=Premiumplansdetails::get();
            $settings = Setting::first();
            $ads_view_point = $settings->ads_view_point;

            return view('plan.chooseplan',compact('plans','freeplans','plansdetails','ads_view_point','settings'));
        }else{
            toastr()->error('Login to continue');
            return redirect('/');
        }
    }

    public function choosePremiumPlan(Request $request)
    {   
        if(Auth::check()){

            PlansPurchase::where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->where('plan_id',1)->delete();

            // $adsid =1;
            // $cateid=2;
            // $plansPreferCategory=plancategorychoose($cateid);
            //echo '<pre>';print_r($plansPreferCategory);die;
            if (session()->has('adsid')) {

                 $adsid =session()->get('adsid');
                 //echo '<pre>';print_r($adsid);die;
            }else{
                toastr()->warning('Error Occoured Try To Reposting ! ');
                return redirect()->route('ads.post');
            }

            if (session()->has('catesid')) {
                $cateid =session()->get('catesid');
                $plansPreferCategory=plancategorychoose($cateid);
            }else {
                toastr()->warning('Error Occoured Try To Reposting ! ');
                return redirect()->route('ads.post');
            }
            //echo '<pre>';print_r($plansPreferCategory);die;

            $nowdate = new DateTime('now');
            $nowdate = $nowdate->format('Y-m-d h:i:s');
            $plans=PlansPurchase::where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->whereDate('expire_date','>',$nowdate)->whereIN('plan_id',$plansPreferCategory)->orderBy('id','desc')->get();
            $freeplans=Premiumadsplan::where('status',1)->whereIN('id',$plansPreferCategory)->get();
            $plansdetails=Premiumplansdetails::get();
            $settings = Setting::first();
            $ads_view_point = $settings->ads_view_point;

            return view('plan.chooseplan',compact('plans','freeplans','plansdetails','ads_view_point','settings'));
        }else{
            toastr()->error('Login to continue');
            return redirect('/');
        }
    }

    public function selectplanpost($planid)
    {   
        if (session()->has('adsid')) {
             $adsid =session()->get('adsid');
             // session()->forget('adsid');
             //echo '<pre>';print_r($adsid);die;
        }else{
            toastr()->warning('Error Occoured Try To Reposting ! ');
            return redirect()->route('ads.post');
            //return redirect()->route('contactus');
        }
        $nowdate = new DateTime('now');
        $nowdate = $nowdate->format('Y-m-d h:i:s');
        $plandetails=PlansPurchase::where('uuid',$planid)->where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->whereDate('expire_date','>',$nowdate)->first();
        
        if(!empty($plandetails)){

            $paidads=Premiumadsplan::where('id',$plandetails->plan_id)->first();
            if(!empty($paidads)){
                $walletpoint=$paidads->ads_points;
                // $postcount=PlansPurchase::where('uuid',$planid)->where('user_id',Auth::user()->id)->where('type','0')->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->get()->count();
                $postcount=PlansPurchase::where('user_id',Auth::user()->id)->where('type','0')->where('plan_id','1')->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->get()->count();
                if($plandetails->plan_id==1){
                    if($postcount<6){
                        $walletpoint=$paidads->ads_points;
                    }else{
                        $walletpoint=0;
                    }
                }
                
                $plan_id=$paidads->id;
                $type=paidorfree($plan_id);
                $findads=AdsTemp::find($adsid);
                if(!empty($findads)){
                    
                    $findads->type=$type;
                    $findads->point = $walletpoint;
                    $findads->plan_id = $plan_id;
                    $findads->purchase_id = $plandetails->id;
                    $findads->save();

                    $ads =  new Ads;
                    $ads->uuid = $findads->uuid;
                    $ads->categories = $findads->categories;
                    $ads->sub_categories = $findads->sub_categories;
                    $ads->title = $findads->title;
                    $ads->cities = $findads->cities;
                    $ads->area_id = $findads->area_id;
                    $ads->pincode = $findads->pincode;
                    $ads->price = $findads->price;
                    $ads->description = $findads->description;
                    $ads->tags = $ads->findads;
                    $ads->seller_id = $findads->seller_id;
                    $ads->plan_id = $findads->plan_id;
                    $ads->purchase_id=$findads->purchase_id;
                    $ads->type = $findads->type;
                    $ads->point = $findads->point;
                    $ads->ads_ep_id=$findads->ads_ep_id;
                    $ads->seller_information = $findads->seller_information;
                    $ads->phone_no=$findads->phone_no;
                    $ads->name= $findads->name;
                    $ads->latitude = $findads->latitude;
                    $ads->longitude =  $findads->longitude;
                    $ads->save();

                    $adsimagedata=AdsimageTemp::where('ads_id',$adsid)->get();
                    if(!empty($adsimagedata)){
                        foreach ($adsimagedata as $adimage) {
                            $adsimage =  new Adsimage;
                            $adsimage->ads_id = $ads->id;
                            $adsimage->uuid = $adimage->uuid;
                            $adsimage->image = $adimage->image;
                            $adsimage->save();
                        }
                    }
                                            
                    $postvaluedata=PostValuesTemp::where('post_id',$adsid)->get();
                    if(!empty($postvaluedata)){
                        foreach ($postvaluedata as $postvalue) {
                            $postdata =  new PostValues;
                            $postdata->post_id = $ads->id;
                            $postdata->uuid = $postvalue->uuid;
                            $postdata->field_id = $postvalue->field_id;
                            $postdata->option_id = $postvalue->option_id;
                            $postdata->value = $postvalue->value;
                            $postdata->save();
                        }
                    }
                    
                    $PlansLimit =PlansPurchase::where('uuid',$planid)->first();
                    $PlansLimit->ads_count=$PlansLimit->ads_count-1;
                    $PlansLimit->save();

                    $adsdelete =AdsTemp::where('id',$adsid)->delete();
                    $adsimagedelete =AdsimageTemp::where('ads_id',$adsid)->delete();
                    $postvaluedelete =PostValuesTemp::where('post_id',$adsid)->delete();

                    //Notificaiton Admin
                    $randomuuid = Uuid::generate(4);
                    
                    $adsnewid = $ads->id;

                    $adminNotification = DB::table('notification_admin')->insert(['user_id' => Auth::user()->id,'uuid' =>$randomuuid,'adsid' => $adsnewid,'message'=>Auth::user()->name." has added the new ad",'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
                    //Notificaiton Admin
                    
                    session()->forget('adsid');
                    session()->forget('catesid');
                    toastr()->success('Ads Successfully Posted!');
                    return redirect()->route('adsview.getads',$ads->uuid);
                    //return redirect()->route('welcome');
                    
                }
                //echo '<pre>sacasca';die;
                toastr()->error('Plan Invalid');
                return redirect()->route('welcome');
            }else{
               toastr()->error('Plan Invalid');
                return back()->withInput(); 
            }
        }else{
            toastr()->error('Plan Invalid');
            return back()->withInput();
        }

    }


    public function selectplanpostforplatinam($planid,$adUuid)
    {   
        $nowdate = new DateTime('now');
        $nowdate = $nowdate->format('Y-m-d h:i:s');
        $plandetails=PlansPurchase::where('uuid',$planid)->where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->whereDate('expire_date','>',$nowdate)->first();
        
        if(!empty($plandetails)){

            $paidads=Premiumadsplan::where('id',$plandetails->plan_id)->first();
            if(!empty($paidads)){
                $walletpoint=$paidads->ads_points;
                
                $plan_id=$paidads->id;
                $type=paidorfree($plan_id);


                $date = new DateTime('now');
                $date->modify('+30 day'); 
                $date = $date->format('Y-m-d H:i:s');


                $findads =  Ads::where('uuid', $adUuid)
                      ->update(['type' => $type,'plan_id' => $plan_id,'purchase_id' => $plandetails->id,'point_expire_date' => $date,'ads_expire_date' => $date]);

                $findads =  Ads::where('uuid', $adUuid)->increment('point',$walletpoint);
                    
                    
                $PlansLimit =PlansPurchase::where('uuid',$planid)->first();
                $PlansLimit->ads_count=$PlansLimit->ads_count-1;
                $PlansLimit->save();

                return redirect()->route('adsview.getads',$adUuid);
                  
            }else{
               toastr()->error('Plan Invalid');
                return back()->withInput(); 
            }
        }else{
            toastr()->error('Plan Invalid');
            return back()->withInput();
        }

    }

    public function addpost(Request $request)
    {   
        $input=$request->all();
        if(empty(Auth::check())){
                toastr()->warning('login to Post Ads !');
                return redirect('/');
        }
        

        $latitude = '';
        if(!isset($input['latitude'])){
            toastr()->error('Please Select address');
            return back()->withInput();
        }
        $longitude = '';
        if(!isset($input['longitude'])){
            toastr()->error('Please Select address');
            return back()->withInput();
        }
        $latitude = $input['latitude'];
        $longitude = $input['longitude'];

        //$getaddress = getaddress($latitude,$longitude);
        /*echo '<pre>';print_r( $getaddress );die;
        if($getaddress == 0){            
            toastr()->error('Please Select address');
            return back()->withInput();
        }*/
        
        $request->validate([
            'category'          =>'required',
            'sub-category'      =>'required',
            'description'       =>'required',
            //'city_name'         =>'required',
            'title'             =>'required',
            'price'             =>'required',
            /*'sell-email'        =>'required',
            'sell-phone'        =>'required',*/
            //'images'           =>"required|array|min:1|max:5",
            //'images.*'           =>'image|mimes:jpeg,jpg,png|max:2048',
        ]);
        



        $uuid = Uuid::generate(4);
        
        $subcategories =  DB::table("sub_categories")->where('uuid',$input['sub-category'])->first();
        if(empty($subcategories)){
                toastr()->error('Incorrect Data');
                return back()->withInput();
        }

       $cat_fields = DB::table("category_field")->select('field_id')->where('category_id',$subcategories->id)->get();
        if(!empty($cat_fields) && isset($input['cf']))
        {
            $ids=array();
            $i=array();
            foreach ($cat_fields as $key => $value) {
                $ids[$key]=$value->field_id;
            }
            foreach ($input['cf'] as $key => $value) {
                // if(empty($value)){
                    
                // }else{
                //     $i[$key]=$key;
                // }
                $i[$key]=$key;
                
            }

            array_push($i,6);
            array_push($i,19);

            $cffields = DB::table("fields")->whereIn('id',$ids)->whereNotIn('id',$i)->get();
            if ($cffields->count() > 0) {
                foreach ($cffields as $key1 => $cffield) {
                    if ($cffield->required == 1) {
                        $messages['cf.' . $cffield->id . '.required'] = __('The :field is required.', ['field' => mb_strtolower($cffield->name)]);
                    }
                }
                //echo "<pre>";print_r($messages);die;
                return back()->withInput()->withErrors($messages);
            }
        }
        //echo '<pre>';print_r($input);die;
        //
        /*if(empty($request->get('hidden-tags')))
        {
             toastr()->error(' Tags Empty!');
            return back()->withInput();

        }*/
        if(!isset($input['images']))
        {
            toastr()->error(' Image Empty!');
            return back()->withInput();
        }
        $user =  DB::table("users")->where('id',Auth::id())->first();

        if(!empty($user)){


            $now = now();
            
            $ads =  new AdsTemp;
            $ads->uuid = $uuid;
            $ads->categories = $subcategories->parent_id;
            $ads->sub_categories = $subcategories->id;
            $ads->brand_id = (isset($input['brands'])?$input['brands']:null);
            $ads->model_id = (isset($input['models'])?$input['models']:null);
            $ads->title = $input['title'];
            // $ads->cities = (isset($getaddress['city_id'])?$getaddress['city_id']:2);
            // $ads->area_id = (isset($getaddress['area_id'])?$getaddress['area_id']:9);
            $ads->cities = $input['cities'];
            $ads->area_id = $input['areas'];
            //$ads->cities = 2;
            //$ads->area_id = 9;
            $ads->price = $input['price'];
            $ads->description = $input['description'];
            $ads->tags = null;
            //$ads->tags = $input['hidden-tags'];
            $ads->seller_id = $user->id;
            $ads->plan_id = "";
            $ads->type = 1;
            $ads->point = "";
            $ads->pincode = $input['pincode'];
            
            $ads->ads_ep_id=$now->timestamp.$now->milli;
            $ads->seller_information = $input['information'];
            $ads->phone_no=$input['sell-phone'];
            $ads->name=$input['sell-name'];
            if(isset($input['latitude'])){
                $ads->latitude = $input['latitude'];
            }
            if(isset($input['longitude'])){
                $ads->longitude = $input['longitude'];
            }
            //echo "<pre>123 ";print_r($ads->type);die;
            $ads->save();

            
            if (!empty($request->file('images'))) {
                $image = $request->file('images');
                foreach ($image as  $files) {
                    $random = Uuid::generate(4);
                    $imgname = time().$random.'.'.$files->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/ads/');
                    $img = Image::make($files->getRealPath());
                    /* insert watermark at bottom-right corner with 10px offset */
                    $watermark = Image::make(public_path('img/ojaak_watermark.png'));
                    $watermark->resize(99,27);
                    $img->insert($watermark, 'bottom-right', 10, 10);
                    //$img->insert(public_path('img/ojaak_watermark.png'), 'bottom-right', 10, 10);
                    /* insert watermark at bottom-right corner with 10px offset */
                    $img->resize(500, null, function ($constraint) { $constraint->aspectRatio(); });
                    $img->save($destinationPath.'/'.$imgname);
                    $adsimage =  new AdsimageTemp;
                    $adsimage->ads_id = $ads->id;
                    $adsimage->uuid = $random;
                    $adsimage->image = $imgname;
                    $adsimage->save();
                }
            }
            

            $fields = DB::table("category_field")->where('category_id',$subcategories->id)->get();
            if ($fields->count() > 0) {
                foreach ($fields as $field) {
                    $randomid = Uuid::generate(4);
                    if (isset($input['cf'][$field->field_id])) {
                        
                        $inputs = $input['cf'][$field->field_id];

                        if (is_array($inputs)) {
                            foreach ($inputs as $optionId => $optionValue) {
                                $postValueInfo = [
                                    'post_id'   => $ads->id,
                                    'uuid'      => $randomid,
                                    'field_id'  => $field->field_id,
                                    'option_id' => $optionId,
                                    'value'     => $optionValue,
                                ];
                                
                                $newPostValue = new PostValuesTemp($postValueInfo);
                                $newPostValue->save();
                            }
                        } else {

                            $postValueInfo = [
                                'post_id'  => $ads->id,
                                'uuid'     => $randomid,
                                'field_id' => $field->field_id,
                                'value'    => $inputs,
                            ];
                            
                            $newPostValue = new PostValuesTemp($postValueInfo);
                            $newPostValue->save();
                        }
                    }
                }
            }

            /*$PlansLimit =PlansPurchase::where('uuid',$input['planid'])->first();
            $PlansLimit->ads_count=$PlansLimit->ads_count-1;
            $PlansLimit->save();*/
            /*if (session()->has('adsid')) {
                session()->forget('adsid');
            }else{
                session()->put('adsid', $ads->id);
            }
            if (session()->has('catesid')) {
                session()->forget('catesid');
            }else{
                session()->put('catesid', $ads->categories);
            }*/
            session()->forget('adsid');
            session()->forget('catesid');

            session()->put('adsid', $ads->id);
            session()->put('catesid', $ads->categories);
                
            // if (session()->has('adsid')) {
            //     session()->put('adsid', $ads->id);
            // }
            // if (session()->has('catesid')) {
            //    session()->put('catesid', $ads->categories);
            // }


            return redirect()->route('ads.choosePlanPost');
            
        }else{
            toastr()->error(' Seller Credentials Mismatch');
            return redirect()->route('login');
        }
    }
    public function edit($id)
    {   
        $parent=Parent_categories::where('status',1)->get();
        $sub=Sub_categories::where('status',1)->get();
       
        return view('ads.post',compact('parent','sub'));
    }


}
