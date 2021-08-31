<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;
use Auth;
use App\Sub_categories;
use App\Ads;
use App\City_requests;
use App\Adsimage;
use Uuid;
use Carbon\Carbon;
use Image;
use App\PostValues;
use Illuminate\Support\Str;
use App\Customfield;
use App\Customfield_Options;
use App\ViewedAds;
use App\Favourite;
use App\Setting;
use App\Freepoints;
use App\PlansPurchase;
use App\TopAds;
use App\Pearls;
use App\TopLists;
use App\AdsFeatures;
use App\Parent_categories;
use App\Cities;
use App\AdsTemp;
use App\PostValuesTemp;
use App\PostValueTemp;
use App\AdsimageTemp;
use App\Privacy;
use App\FeatureadsLists;
use App\Verification;
use App\UsersRating;
use App\UserReview;
use App\Makeanoffers;
use App\Chats;
use App\ChatsMessage;
use App\Premiumadsplan;
use App\Premiumplansdetails;

class AdsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'], ['except' => ['index','getcategories','getsubcategories','autocomplete','addpost','getsubcategoriestags','getads','displayads','getitems','getcity','showfads','loadmoresearch','getExtraFillter','loadcategory','loadlocation']]);
    }
    public function index()
    {   
    	$parent = DB::table("parent_categories")->where('status',"1")->pluck("name","id");
        return view('ads.ads',compact('parent'));
    }
    public function getcategories()
    {   
        $parent = DB::table("parent_categories")->where('status',"1")->pluck("name","id");
        return json_encode($parent);
    }
    public function getsubcategories(Request $request)
    {   $input=$request->all();
        //echo"<pre>";print_r($input['id']);die;
        $subcategories =  DB::table("sub_categories")->where('status',"1")->where('parent_id',$input['id'])->pluck("name","uuid");
        return json_encode($subcategories);
    }

    public function autocomplete(Request $request)
    {   $country =  DB::table("countries")->where('status','1')->pluck("id");
        $state =  DB::table("states")->where('status','1')->whereIn('country_id',$country)->pluck("id");
        $cities =  DB::table("cities")->where('status','1')->whereIn('state_id',$state)->pluck("name","id");
        return json_encode($cities);
    }
    public function userindex()
    {   
        //$parent = DB::table("parent_categories")->where('status',"1")->pluck("name","id");
        $ads=DB::table('ads')->where('seller_id',Auth::id())->where('status',1)->where('ads.ads_expire_date','!=',null)->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'))->orderby('id','desc')->get();
        $Plans=PlansPurchase::where('type','!=','0')->where('ads_count','>','0')->whereDate('expire_date','>',date('Y-m-d H:i:s'))->where('user_id',Auth::id())->get();
        $feature=AdsFeatures::where('user_id',Auth::id())->where('ads_id','!=',null)->whereDate('expire_date','>',date('Y-m-d H:i:s'))->get();
        $top=TopLists::where('user_id',Auth::id())->whereDate('expire_date','>',date('Y-m-d H:i:s'))->get();
        //echo"<pre>";print_r($Plans);die;
        // $pearls=Pearls::where('user_id',Auth::id())->whereDate('expire_date','>',date('Y-m-d H:i:s'))->get();
        return view('ads.userads',compact('ads','Plans','feature','top'));
    }
    public function activeadsget()
    {   
        $ads=DB::table('ads')
        ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id','ads_features.expire_date as featureadsexp','toplists_ads.expire_date as expireDDD')->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')->leftjoin('ads_features','ads_features.ads_id','=','ads.id')
            ->leftjoin('toplists_ads', function($leftJoin){
                $leftJoin->on('ads.id', '=', 'toplists_ads.ads_id');
            })
            ->whereIn('status',[0,1])->where('seller_id',Auth::id())->orderby('id','desc')->groupBy("ads.id")->get();
        //->where('ads.ads_expire_date','!=',null)->whereDate('ads_expire_date','>',date('Y-m-d H:i:s'))
        //echo"<pre>";print_r($ads);die;
        $activeads='';
        foreach ($ads as $key => $ad) {
            $action="";
            $status="";
            $type="";
            if($ad->type==0){
                $type="Free";
            }else{
                $type="Paid";
            }
            if($ad->status==0){
                $status="Pending";
            }else if($ad->status==1){
                $status="Approved";
            }else if($ad->status==2){
                $status="Rejected";
            }
            else if($ad->status==3){
                $status="Sold";
            }else if($ad->status==4){
                $status="Blocked";
            }else{
                $status="Delete";
            }

            $show = 1;
            if($ad->ads_expire_date !='' && $ad->ads_expire_date !=null){
                if($ad->ads_expire_date<date('Y-m-d H:s:i')){
                    $status="Expired";
                    $show = 0;
                }
            }

            if($show == 1){

                $PlansPurchase=PlansPurchase::where('id',$ad->purchase_id)->where('refund_orderi_d', '!=' , '')->first();
                //echo"<pre>";print_r($PlansPurchase);

                $sold =  route('ads.user.all.sold',$ad->id);
                $delete =  route('ads.user.free.delete',$ad->id);
                $edit =  route('ads.user.free.edit',$ad->uuid);
                $inactive = route('ads.user.free.inactive',$ad->id);
                $activeads.='<div class="ads_management_listing_outer_wrap" >
                                        <div class="ads_manage_img_wrap "style="position:relative;">
                                            <img src="'.asset("public/uploads/ads/$ad->image").'" title="ads_manage_img">';
                                            if($ad->expireDDD !=''  && $ad->expireDDD > date("Y-m-d h:m:s")){
                                                $activeads.='<div class="ads_status_wrap" style="background: #daa520;left: 9px;"><p>Featured</p></div>';
                                            }else if( $ad->featureadsexp!='' && $ad->featureadsexp > date("Y-m-d h:m:s")){
                                                $activeads.='<div class="ads_status_wrap" style="background: #daa520;left: 9px;"><p>Platinum</p></div>';
                                            }
                                        $activeads.='</div><div class="ads_manage_content_wrap">
                                            <a  href="'.route('adsview.getads',$ad->uuid).'">
                                                <h3>₹ '.formatMoney($ad->price).'</h3>
                                                <h4>'.ucfirst($ad->title).'</h4>
                                                <p>Area: <strong>'.ucwords(get_areaname($ad->area_id,80)).'</strong></p>
                                                <p>Type: <strong>'.$type.'</strong></p>
                                                <p>Status: <strong>'.$status.'</strong></p>
                                                <p>Ads Id: <strong>'.$ad->ads_ep_id.'</strong></p>
                                                <p>Created Date: <strong>'.date("d-M-Y  h:s A",strtotime($ad->created_at)).'</strong></p>';
                                        if(!empty($PlansPurchase)){
                                            $activeads.='<p>Refund: <strong> Yes </strong></p>';
                                            $activeads.='<p>Refund Status: <strong>'.(($PlansPurchase->refund_order_status!='')?strtoupper($PlansPurchase->refund_order_status):"Pending").'</strong></p>';

                                        }
                            // if($ad->approved_date != '' && $ad->approved_date != null){
                            //     $activeads.='<p>Approved Date: <strong>'.date("d-M-Y  h:s A",strtotime($ad->approved_date)).'</strong></p>';
                            // } 
                            if($ad->ads_expire_date != '' && $ad->ads_expire_date != null){
                                $activeads.='<p>Expiry Date: <strong>'.date("d-M-Y  h:s A",strtotime($ad->ads_expire_date)).'</strong></p>';
                            }                    
                                                
                            $activeads.='</a>
                                        </div><div class="ads_manage_btn_wrap common_btn_wrap">';
                if($status!='Expired'){                
                    if($ad->status==0){
                        $activeads.='<a href="'.$edit.'" class="edit_btn_wrap">Edit</a>';
                    }
                    if($ad->status==1){
                        $activeads.='<a href="'.$edit.'" class="edit_btn_wrap">Edit</a><button type="button" onclick="soldbtn('.$ad->id.')"class="sold_btn_wrap">Sold</button><button type="button" onclick="inactive('.$ad->id.')"class="inactive_btn_wrap">Inactive</button>';
                    }
                    $activeads.='<button class="delete_btn_wrap" onclick="deleteadsbtn('.$ad->id.')">Delete</button>
                    <form action="'.$inactive.'" method="POST" id="inactive-'.$ad->id.'"><input type="hidden" name="_token" value="'.csrf_token().'"></form>';
                }
                $activeads.='</div></div>';
            }
            
        }
        return $activeads;
    }
    public function adssold(Request $request)
    {   $input=$request->all();
        $check= Ads::where('id',$input['adsid'])->first();
        if(!empty($check)){
            
            $check->status=3;
            $check->save();
            echo "1";die;
        }
        echo "0";die;
    }
    public function republish(Request $request)
    {   $input=$request->all();
        $check= Ads::where('id',$input['adsid'])->first();
        if(!empty($check)){


            $plans=DB::table('premiumplansdetails')->select('premiumadsplans.id as id','premiumadsplans.plan_name as plan_name','premiumadsplans.id as planid','premiumplansdetails.quantity','premiumplansdetails.validity as plandays')->leftJoin('premiumadsplans', 'premiumplansdetails.plan_id', '=', 'premiumadsplans.id')->where('premiumadsplans.id',1)->first();

            $date = strtotime("$plans->plandays day", strtotime("now"));
            $date=date("Y-m-d H:i:s", $date);
            
            $uuid = Uuid::generate(4);

            $purchase=PlansPurchase::Create([
            'uuid' => $uuid,
            'user_id' => Auth::id(),
            'plan_id' => $plans->id,
            'ads_limit' => $plans->quantity,
            'type' => '0',
            'expire_date'=>$date,
            'payment_method'=>"OjaakFree",
            'payment_id'=>"OFP_".uniqid(),
            'ads_count'=> $plans->quantity,
            ]);


            $check->status=0;
            $check->type="0";
            $check->plan_id=$plans->id;
            $check->purchase_id=$purchase->id;
            $check->point=0;
            $check->point_expire_date=null;
            $check->ads_expire_date=null;
            $check->approved_by=null;
            $check->approved_date=null;
            $check->save();
            echo "1";die;
        }
        echo "0";die;
    }
    public function adsdelete(Request $request)
    {   $input=$request->all();

        //echo "1";die;

        $check= Ads::where('id',$input['adsid'])->first();
        if(!empty($check)){
            
            /*$res=Ads::destroy($input['adsid']);
            if(!empty($res)){
                DB::delete('delete from ads_image where ads_id = ?',[$input['adsid']]);
            }*/
            $check->status=5;
            $check->save();
            $date = new DateTime('now');
            $date = $date->format('Y-m-d H:i:s');
            $feature=AdsFeatures::where('ads_id',$check->id)->get();
            if(!empty($feature)){
                foreach ($feature as  $data) {
                    //echo"<pre>";print_r($data['id']);die;
                    /*$PlansPurchase=PlansPurchase::where('type','1')->where('feature_plan_id',$data['id'])->where('user_id',Auth::id())->first();
                    if(!empty($PlansPurchase)){
                        echo"<pre>";print_r($PlansPurchase);die;
                        $PlansPurchase->expire_date=$date;
                        $PlansPurchase->save();
                    }else{
                        echo"<pre>1";print_r( $PlansPurchase);die;
                    }*/
                    $data->expire_date=$date;
                    $data->save();
                    
                }

            }
            echo "1";die;
        }
        echo "0";die;
    }
    public function adsedit($id)
    {   
        $ads= DB::table('ads')->where('uuid',$id)->first();
        //echo"<pre>";print_r($ads);die;
        if(!empty($ads)){
            
            $sub=DB::table("sub_categories")->where('id',$ads->sub_categories)->first();
            $parent = DB::table("parent_categories")->where('id',$sub->parent_id)->first();
            $adsimage= DB::table('ads_image')->select('uuid','image')->where('ads_id',$ads->id)->get();
            
            foreach ($adsimage as $key => $value) {
                $ads_image['id']=$value->uuid;
                $ads_image['src']=url('public/uploads/ads/').'/'.$value->image;
                $images[$key]=$ads_image;
            }
            $customfields='';
            $get_category_field=DB::table('category_field')->where('category_id',$sub->id)->orderBy('field_id', 'ASC')->get();
                if(!empty($get_category_field)){
                    foreach ($get_category_field as $key => $field) {
                        //echo"<pre>";print_r($field->field_id);die;
                        $customfields.=$this->geteditfieldHtml($field->field_id,$ads->id,1); //1=edit,2,prevent
                                    
                    }
                }
            $brands= DB::table('category_brands')->where('sub_cate_id',$sub->id)->get();
            $category_models= DB::table('category_models')->where('sub_cate_id',$sub->id)->get();
            //echo"<pre>";print_r($category_models);die;
            $ads->images=json_encode($images);
            //return json_encode($images);
            $getfulladress = getfulladress($ads->area_id);
            //echo '<pre>';print_r( $getfulladress );die;
            $stateid= DB::table('cities')->select('state_id')->where('id',$ads->cities)->first();
            //echo '<pre>';print_r( $stateid );die;
            $states= DB::table('states')->where('status','1')->get();
            return view('ads.editads',compact('parent','states','stateid','ads','sub','customfields','getfulladress','brands','category_models'));

        }else{
            toastr()->error(' Data not found');
            return back();
        }
        
    }
    public function geteditfieldHtml($fields,$postid,$Changetype){
        $customfields="";
        $get_custom_field=Customfield::where('id',$fields)->where('active',1)->first(); 
        if($Changetype==1){
            $cusval=DB::table('post_values')->where('field_id',$get_custom_field->id)->where('post_id',$postid)->first();
        }else{
            $cusval=DB::table('post_values_temp')->where('field_id',$get_custom_field->id)->where('post_id',$postid)->first();
        }
        
        if(empty($cusval)){
            $cusval = (object) array();
            $cusval->value='';
        }
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
                            <input type='$get_custom_field->type' class='form-control'  id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' placeholder='' required value='$cusval->value'  max='$get_custom_field->max'></div>";
            }

            if($get_custom_field->type =="date"){
                
                 $customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                            <input type='text' class='form-control date_form_input'  id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' placeholder='' required value='$cusval->value'  max='$get_custom_field->max'></div>";
            }

            if($get_custom_field->type =="number"){
                
                 $customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                            <input type='$get_custom_field->type' class='form-control'  id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' placeholder='' required value='$cusval->value'  ></div>";
            }

            if($get_custom_field->type =="checkbox"){

                 $customfields.="<div class='multicheck_box_wrap form-group'>
                            <label class='multicheck_label_wrap'>$get_custom_field->name <sup>$sub</sup></label>
                            <div class='multicheck_outer_wrap'>
                                <label class='multicheck_box_inner_wrap'>
                                    <input type='checkbox' id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' "; 
                if(!empty($cusval) && $cusval->value==1){
                    $customfields.="checked ";
                }         
                $customfields.=" value='$get_custom_field->default' max='$get_custom_field->max'>
                                    <span class='checkmark'></span>
                                </label>
                            </div>
                        </div>";
            }

            if($get_custom_field->type =="textarea"){
                 $customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                            <textarea class='form-control' rows='3' id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' placeholder='' max='$get_custom_field->max'>";
                if(!empty($cusval)){
                    $customfields.="$cusval->value";
                }
                $customfields.="</textarea></div>";
            }
            
            if($get_custom_field->type == "radio"){
                 $customfields.="<div class='multicheck_box_wrap radio_btn_wrap form-group'>
                            <label class='multicheck_label_wrap'>$get_custom_field->name <sup>$sub</sup></label>
                            <div class='multicheck_outer_wrap'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get();
                foreach ($options as $key => $option) {
                     
                         $customfields.="<label class='multicheck_box_inner_wrap'>$option->value
                                    <input type='radio' name='cf[$get_custom_field->id]' id='cf.$get_custom_field->id' value='$option->id'"; 
                        if($option->id == $cusval->value){
                              $customfields.="checked";  
                        }
                        $customfields.=" >
                                    <span class='checkmark'></span>
                                </label>";

                }  

                $customfields.="</div></div>";
            }

            if($get_custom_field->type == "select"){
                $customfields.="<div class='form-group common_select_option contact_form_select_btn_wrap'>
                                <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                                    <select class='form-control'  name='cf[$get_custom_field->id]' id='cf.$get_custom_field->id' >";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {
                        
                        $customfields.="<option value='$option->id'";
                        if($option->id == $cusval->value){
                          $customfields.="selected";  
                        }
                        $customfields.= ">$option->value</option>";
                }  
                $customfields.="</select></div>";
            }

            if($get_custom_field->type == "checkbox_multiple"){
                 $customfields.="<div class='multicheck_box_wrap form-group'>
                            <label class='multicheck_label_wrap'>$get_custom_field->name <sup>$sub</sup></label>
                            <div class='multicheck_outer_wrap'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                
                foreach ($options as $key => $option) {
                    $cusoptionval=DB::table('post_values')->where('field_id',$option->field_id)->where('option_id',$option->id)->where('post_id',$postid)->first();
                    $customfields.="<label class='multicheck_box_inner_wrap'>$option->value
                                    <input type='checkbox' name='cf[$get_custom_field->id][$option->id]' id='cf.$get_custom_field->id.$option->id' value='$option->id'";
                    if(!empty($cusoptionval)){

                        if($option->id == $cusoptionval->option_id){
                              $customfields.="checked";  
                        }
                    }

                    $customfields.=">
                                    <span class='checkmark'></span>
                                </label>";
                }  

                $customfields.="</div></div>";
            }

            if($get_custom_field->type =="file"){
                 $customfields.="<div class='form-group row'>
                                    <label for='$get_custom_field->type' class='col-sm-3 col-form-label'>$get_custom_field->name <sup>$sub</sup></label>
                                    <div class='col-sm-9'>
                                    <input type='$get_custom_field->type' class='form-control-file mb-2  id='$get_custom_field->name' name='$get_custom_field->name' placeholder=''  value='$get_custom_field->default' max='$get_custom_field->max'>
                                    </div>
                                </div>";
            }

        return $customfields;    
        }  

    }

    
    public function adsupdate(Request $request)
    {   
        $input=$request->all();
        //echo "<pre>";print_r($input);die;
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
        $request->validate([
            'id'          =>'required',
            /*'category'          =>'required',
            'sub-category'      =>'required',
            */'description'       =>'required',
            //'city_name'         =>'required',
            'title'             =>'required',
            'price'             =>'required',
            //'photos'           =>"required|array|min:1|max:5",
            'photos.*'           =>'image|mimes:jpeg,jpg,png|max:2048',
            ]);
        /*$image = $request->file('image');
        echo "<pre>";print_r($image);die;*/
        $uuid = Uuid::generate(4);
        if(!empty($input['information']))
        {   
            if($input['information'] == 'on'){
                $input['information']=1;
            }
            
        }else{
            $input['information']=0;
        }
        /*if(empty($request->get('hidden-tags')))
        {
             toastr()->error(' Tags Empty!');
            return back();
        }*/
        if(!isset($input['old'])){
            if(!isset($input['photos'])){
                toastr()->error(' Image Empty!');
                return back()->withInput(); 
            }
        }
        /*if($input['city_name'] == "-1"){
            if(empty($input['not_in_list'])){
                toastr()->error('cities Empty');
                return back();
            } 
        }*/
        //echo "<pre>";print_r($input);die;
        $ads= Ads::where('uuid',$input['id'])->first();
        if(empty($ads)){
                toastr()->error('Ads Not Found');
                return back();
        }

        /* custom fields validation */
        $cat_fields = DB::table("category_field")->select('field_id')->where('category_id',$ads->sub_categories)->get();
        //echo "<pre>";print_r($cat_fields);die;
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
        
        //previous image delete
        $adsimage=DB::table('ads_image')->select('uuid')->where('ads_id',$ads->id)->get();
        foreach ($adsimage as $key => $value) {

            $imagedata[$key]=$value->uuid;
        }
        
        $adsimageintersect=array();
        if(!empty($request->get('old')))
        {
            $adsimageintersect=array_intersect($imagedata,$input['old']);
            
        }
        //$adsimageintersect=array_intersect($imagedata,$input['old']);
        if(!empty($adsimageintersect))
        {   
            $adsimage=DB::table('ads_image')->where('ads_id',$ads->id)->whereNotIn('uuid',$adsimageintersect)->get();
            
            if(!empty($adsimage)){
                
                foreach ($adsimage as $key => $value) {
                    //echo "<pre>123";print_r($value);die;
                    if(!empty($value->image) && file_exists(public_path('/uploads/ads/'.$value->image)))
                    {
                        $url='public/uploads/ads/';
                        //echo "<pre>1";print_r($url);die;
                        unlink($url.$value->image);
                    }
                    //echo "<pre>2";print_r($value);die;
                    //Adsimage::destroy($value->id);
                    $deleteimage= Adsimage::find($value->id);
                    $deleteimage->delete();
                }
            }
        }else{

            $adsimage=DB::table('ads_image')->where('ads_id',$ads->id)->get();
            if(!empty($adsimage)){
                
                foreach ($adsimage as $key => $value) {
                    //echo "<pre>123";print_r($value);die;
                    if(!empty($value->image) && file_exists(public_path('/uploads/ads/'.$value->image)))
                    {
                        $url='public/uploads/ads/';
                        //echo "<pre>1";print_r($url);die;
                        unlink($url.$value->image);
                    }
                    //echo "<pre>2";print_r($value);die;
                    //Adsimage::destroy($value->id);
                    $deleteimage= Adsimage::find($value->id);
                    $deleteimage->delete();
                }
            }

        }
        $latitude = $input['latitude'];
        $longitude = $input['longitude'];
        //$getaddress = getaddress($latitude,$longitude);
        //ads update
        if(!empty($ads))
        {
            $ads->title = $input['title'];
            $ads->price = $input['price'];
            $ads->description = $input['description'];
            $ads->seller_information = $input['information'];
            $ads->phone_no=$input['sell-phone'];
            $ads->tags = null;
            //$ads->tags = $input['hidden-tags'];
            //$ads->cities = $input['city_name'];
            /*$ads->cities = $getaddress['city_id'];
            $ads->area_id = $getaddress['area_id'];*/
            //$ads->cities = 2;
            //$ads->area_id = 9;
            $ads->cities = $input['cities'];
            $ads->area_id = $input['areas'];
            $ads->pincode = $input['pincode'];
            /*$ads->cities = (isset($getaddress['city_id'])?$getaddress['city_id']:2);
            $ads->area_id = (isset($getaddress['area_id'])?$getaddress['area_id']:9);*/
            $ads->brand_id = (isset($input['brands'])?$input['brands']:null);
            $ads->model_id = (isset($input['models'])?$input['models']:null);
            $ads->latitude = $input['latitude'];
            $ads->longitude = $input['longitude'];
            $ads->reason = "";
            $ads->status = 0;
            $ads->save();
            if (!empty($request->file('photos'))) {
                $image = $request->file('photos');
                foreach ($image as  $files) {
                    $random = Uuid::generate(4);
                    $imgname = time().$random.'.'.$files->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/ads/');
                    $img = Image::make($files->getRealPath());
                    /* insert watermark at bottom-right corner with 10px offset */
                    $img->insert(public_path('img/ojaak_watermark.png'), 'bottom-right', 35, 35);
                    /* insert watermark at bottom-right corner with 10px offset */
                    $img->resize(500, null, function ($constraint) { $constraint->aspectRatio(); });
                    $img->save($destinationPath.'/'.$imgname);
                    $adsimage =  new Adsimage;
                    $adsimage->ads_id = $ads->id;
                    $adsimage->uuid = $random;
                    $adsimage->image = $imgname;
                    $adsimage->save();
                }
            }
            //city request
            /*if($input['city_name'] == "-1"){
               if(!empty($input['not_in_list'])){
                $random = Uuid::generate(4);
                $city_requests =  new City_requests;
                $city_requests->uuid=$random;
                $city_requests->name=$input['not_in_list'];
                $city_requests->user_id=Auth::id();
                $city_requests->post_id=$ads->id;
                $city_requests->save();
                toastr()->success(' Cities Requested ');
               } 
            }*/

            //custom field insert
            $fields = DB::table("category_field")->where('category_id',$ads->sub_categories)->get();
                    if ($fields->count() > 0) {
                        $newPostValue =PostValues::where('post_id',$ads->id)->delete();
                        foreach ($fields as $field) {
                            $randomid = Uuid::generate(4);
                            //echo "<pre>111";print_r($input['cf'][$field->field_id]);die;
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
                                        
                                        $newPostValue = new PostValues($postValueInfo);
                                        $newPostValue->save();
                                    }
                                } else {
                                    $postValueInfo = [
                                        'post_id'  => $ads->id,
                                        'uuid'     => $randomid,
                                        'field_id' => $field->field_id,
                                        'value'    => $inputs,
                                    ];
                                    
                                    $newPostValue = new PostValues($postValueInfo);
                                    $newPostValue->save();
                                }
                            }
                        }
                    }
                    
            toastr()->success('Ads updated successfully!');
            return redirect()->route('ads.user.index');

        }else{
            toastr()->error(' Invalid Ads!');
            return back();
        }
        
    }
    public function getsubcategoriestags($id)
    {
        $tags =  DB::table("sub_categories")->select('tag')->where('id',$id)->first();
        return json_encode($tags);
    }
    public function userinactiveadsget()
    {   
        $ads=DB::table('ads')
        ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id')->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')->whereIn('status',[0,1,2,4,6])->where('seller_id',Auth::id())->orderby('id','desc')->groupBy("ads.id")->get();
        $activeads='';
        foreach ($ads as $key => $ad) {
            $action="";
            $status="";
            $type="";
            if($ad->type==0){
                $type="Free";
            }else{
                $type="Paid";
            }
            if($ad->status==0){
                $status="Pending";
            }else if($ad->status==1){
                $status="Approved";
            }else if($ad->status==2){
                $status="Rejected";
            }
            else if($ad->status==3){
                $status="Sold";
            }else if($ad->status==4){
                $status="Blocked";
            }else if($ad->status==6){
                $status="Inactive";
            }else{
                $status="Delete";
            }

            $show = 0;
            $rePublish = 0;
            if($ad->status == 2 || $ad->status == 4 || $ad->status == 6){
                $show = 1;
            }


            if($ad->ads_expire_date !='' && $ad->ads_expire_date !=null){
                if($ad->ads_expire_date<date('Y-m-d H:s:i')){
                    $status="Expired";
                    $show = 1;
                    $rePublish = 1;
                }
            }

            if($show == 1){
                $sold =  route('ads.user.all.sold',$ad->id);
                $delete =  route('ads.user.free.delete',$ad->id);
                $edit =  route('ads.user.free.edit',$ad->uuid);
                $inactive = route('ads.user.free.inactive',$ad->id);
                $activeads.='<div class="ads_management_listing_outer_wrap">
                                        <div class="ads_manage_img_wrap">
                                            <img src="'.asset("public/uploads/ads/$ad->image").'" title="ads_manage_img">
                                        </div>
                                        <div class="ads_manage_content_wrap">
                                             <a  href="'.route('adsview.getads',$ad->uuid).'">
                                             <h3>₹ '.formatMoney($ad->price).'</h3>
                                                <h4>'.ucfirst($ad->title).'</h4>
                                                <p>Area: <strong>'.ucwords(get_areaname($ad->area_id,80)).'</strong></p>
                                                <p>Type: <strong>'.$type.'</strong></p>
                                                <p>Status: <strong>'.$status.'</strong></p>
                                                <p>Ads Id: <strong>'.$ad->ads_ep_id.'</strong></p>
                                                <p>Created Date: <strong>'.$ad->created_at.'</strong></p>
                                                </a>';
                // if($ad->approved_date != '' && $ad->approved_date != null){
                //             $activeads.='<p>Approved Date: <strong>'.date("d-M-Y  h:s A",strtotime($ad->approved_date)).'</strong></p>';
                // } 
                if($ad->ads_expire_date != '' && $ad->ads_expire_date != null){
                    $activeads.='<p>Expiry Date: <strong>'.date("d-M-Y  h:s A",strtotime($ad->ads_expire_date)).'</strong></p>';
                } 
                if($ad->status==2){
                    $reason =  DB::table("reject_reason")->where('ads_id',$ad->id)->latest()->first();
                    if(!empty($reason)){
                        $activeads.='<p>Reason: <strong>'.$reason->reason.'</strong></p>';
                    }
                    
                } 
                                  
                $activeads.='</div><div class="ads_manage_btn_wrap common_btn_wrap">';


                if($rePublish == 1){
                    $activeads.='<button type="button" onclick="republish('.$ad->id.')" class="republish_btn_wrap" >Republish</button>';
                }
                if($status!="Expired"){
                    if($ad->status==2){
                        $activeads.='<a href="'.$edit.'" class="edit_btn_wrap">Edit</a><button class="delete_btn_wrap" onclick="deleteadsbtn('.$ad->id.')">Delete</button><button type="button" onclick="inactiveinvalidaccess()" class="inactive_btn_wrap" >Active</button>';
                    }
                    if($ad->status==6){
                        $activeads.='<a href="javascript:void(0);" onclick="inactiveinvalidaccess()" class="edit_btn_wrap">Edit</a><button class="delete_btn_wrap" onclick="inactiveinvalidaccess()">Delete</button><button type="button" onclick="inactive('.$ad->id.')"class="inactive_btn_wrap">Active</button><form action="'.$inactive.'" method="POST" id="inactive-'.$ad->id.'"><input type="hidden" name="_token" value="'.csrf_token().'"></form>';
                    }
                    if($ad->status==4){
                        $activeads.='<a href="javascript:void(0);" onclick="inactiveinvalidaccess()" class="edit_btn_wrap">Edit</a><button class="delete_btn_wrap" onclick="inactiveinvalidaccess()">Delete</button><button type="button" class="inactive_btn_wrap" onclick="inactiveinvalidaccess()">Active</button>';
                    }
                }
                $activeads.='</div></div>';
            }
            
        }
        return $activeads;
    }
    public function usersolditemsget()
    {   
        $ads=DB::table('ads')
        ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id')->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')->where('status','3')->where('seller_id',Auth::id())->orderby('id','desc')->groupBy("ads.id")->get();
        //echo"<pre>";print_r($ads);die;
        $activeads='';
        foreach ($ads as $key => $ad) {
            $action="";
            $status="";
            $type="";
            if($ad->type==0){
                $type="Free";
            }else{
                $type="Paid";
            }
            if($ad->status==0){
                $status="Pending";
            }else if($ad->status==1){
                $status="Approved";
            }else if($ad->status==2){
                $status="Rejected";
            }
            else if($ad->status==3){
                $status="Sold";
            }else if($ad->status==4){
                $status="Blocked";
            }else{
                $status="Delete";
            }
            $sold =  route('ads.user.all.sold',$ad->id);
            $delete =  route('ads.user.free.delete',$ad->id);
            $edit =  route('ads.user.free.edit',$ad->uuid);
            $inactive = route('ads.user.free.inactive',$ad->id);
            $activeads.='<div class="ads_management_listing_outer_wrap">
                                    <div class="ads_manage_img_wrap">
                                        <img src="'.asset("public/uploads/ads/$ad->image").'" title="ads_manage_img">
                                    </div>
                                    <div class="ads_manage_content_wrap">
                                        <h3>₹ '.formatMoney($ad->price).'</h3>
                                            <h4>'.ucfirst($ad->title).'</h4>
                                            <p>Area: <strong>'.ucwords(get_areaname($ad->area_id,80)).'</strong></p>
                                            <p>Type: <strong>'.$type.'</strong></p>
                                            <p>Status: <strong>'.$status.'</strong></p>
                                            <p>Ads Id: <strong>'.$ad->ads_ep_id.'</strong></p>
                                            <p>Created Date: <strong>'.$ad->created_at.'</strong></p>';
                // if($ad->approved_date != '' && $ad->approved_date != null){
                //             $activeads.='<p>Approved Date: <strong>'.date("d-M-Y  h:s A",strtotime($ad->approved_date)).'</strong></p>';
                // } 
                if($ad->ads_expire_date != '' && $ad->ads_expire_date != null){
                    $activeads.='<p>Expiry Date: <strong>'.date("d-M-Y  h:s A",strtotime($ad->ads_expire_date)).'</strong></p>';
                }
                        $activeads.='</div>
                                    <div class="ads_manage_btn_wrap common_btn_wrap">';
            if($ad->status==0){
                $activeads.='<a href="'.$edit.'" class="edit_btn_wrap">Edit</a>';
            }
            if($ad->status==1){
            $activeads.='<button type="button" onclick="soldbtn('.$ad->id.')"class="sold_btn_wrap">Sold</button>';
            }
            $activeads.='<button class="delete_btn_wrap" onclick="deleteadsbtn('.$ad->id.')">Delete</button></div></div>';
            
        }
        return $activeads;
    }
    public function userfavouriteitemsget()
    {   
        $getads = DB::table('ads')
                ->leftjoin('favourites', 'favourites.ads_id', '=', 'ads.id')->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')
                ->leftjoin('ads_features','ads_features.ads_id','=','ads.id')
                ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id','ads_features.expire_date as featureadsexp')
                ->where('favourites.user_id',Auth::id())
                ->groupby('ads.id')
                ->where('ads.status','1')
                ->get();
                //cho "<pre>";print_r($getads);die;
        $activeads='';
        foreach ($getads as $key => $ad) {
            $action="";
            $status="";
            $type="";
            if($ad->type==0){
                $type="Free";
            }else{
                $type="Paid";
            }
            if($ad->status==0){
                $status="Pending";
            }else if($ad->status==1){
                $status="Approved";
            }else if($ad->status==2){
                $status="Rejected";
            }
            else if($ad->status==3){
                $status="Sold";
            }else if($ad->status==4){
                $status="Blocked";
            }else{
                $status="Deleted";
            }
            $sold =  route('ads.user.all.sold',$ad->id);
            $delete =  route('ads.user.free.delete',$ad->id);
            $edit =  route('ads.user.free.edit',$ad->uuid);
            $inactive = route('ads.user.free.inactive',$ad->id);
            $activeads.='<div class="ads_management_listing_outer_wrap">
                                    <div class="ads_manage_img_wrap">
                                        <img src="'.asset("public/uploads/ads/$ad->image").'" title="ads_manage_img">';
                                        if( $ad->featureadsexp!='' && $ad->featureadsexp > date("Y-m-d h:m:s")){
                                            $activeads.='<div class="ads_status_wrap pending_ad_wrap"><p>Platinum</p></div>';
                                        }
                                    $activeads.='</div>
                                    <div class="ads_manage_content_wrap">
                                        <a  href="'.route('adsview.getads',$ad->uuid).'">
                                            <h3>₹ '.formatMoney($ad->price).'</h3>
                                            <h4>'.ucfirst($ad->title).'</h4>
                                            <p>Area: <strong>'.ucwords(get_areaname($ad->area_id,80)).'</strong></p>
                                            <p>Ads Id: <strong>'.$ad->ads_ep_id.'</strong></p>
                                            <span>'.time_elapsed_string($ad->created_at).'</span>
                                        </a>
                                    </div></div>';
            }
            
        return $activeads;
    }
    /*public function getads(){
        $aditems = DB::table('ads')
            ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id')
            ->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')
            ->where('ads.status',"1")
            ->groupBy("ads.id")
            ->get()->toArray();
        $setting=Setting::first();
        
        $favads = array();
        if(Auth::check()){
            $favads = DB::table('favourites')
                  ->where('user_id',Auth::user()->id)
                  ->get()->toArray();
        }

        $ads = array();
        foreach ($aditems as $key => $ad) {
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
            if($setting->ads_view_point <= $ad->point){
                $ads[$ad->id]->viewpoint = "1";
            }
        }
        //echo "<pre>";print_r($aditems);die;
        return view('ads.adsview',compact('ads'));      
    }*/
    public function content_read($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
        curl_close($ch);

        return $result;
    }
    public function getads(){
        /*$ads = DB::table('ads')
            ->leftJoin('ads_image', 'ads_image.ads_id', '=', 'ads.id')
            ->where('ads.status',"1")
            ->select('ads.*','ads_image.id as adsimgid','ads_image.image')
            ->groupBy("ads.id")
            ->orderBy("ads.id","desc")
            ->get();*/
        $category = DB::table('ads')
                        ->leftJoin('parent_categories','ads.categories','=','parent_categories.id')
                        ->select('ads.categories','ads.sub_categories', DB::raw('count(ads.categories) as total'),'parent_categories.name as cate_name')
                        ->limit(5)
                        ->where('ads.status',1)
                        ->orderBy('total','desc')
                        ->groupBy('ads.categories')
                        ->groupBy('ads.sub_categories')
                        ->where('ads.ads_expire_date','!=',null)->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'))
                        ->get()->toArray();
             $catelist=array(); 
             $catelistcomplete=array();           
            foreach ($category as $key => $value) {
                $catelist[$value->categories]=$value->categories;
                
            }

            foreach ($catelist as $key2 => $value2) {
                $sitemap =array();
                foreach ($category as $key1 => $value1) {
                    //echo "<br><pre>";print_r($value1->cities);die;
                    
                    if($value1->categories==$value2){
                        //array_push($sitemap,$value1);
                        $sitemap[$value1->sub_categories] = $value1;
                    } 
                    //echo "<br><pre>";print_r($sitemap);die;
                }
                $catelistcomplete[$value2] = $sitemap;
                
            }
             
        /*echo "<pre>";print_r($catelist);
        echo "<pre>";print_r($category);*/
        //echo "<pre>";print_r($catelistcomplete);die;
        $location = DB::table('ads')
                        ->leftJoin('cities','ads.cities','=','cities.id')
                        ->leftJoin('states','states.id','=','cities.state_id')
                        ->select('cities.id','ads.cities', DB::raw('count(ads.cities) as total'),'states.name as state_name','states.id as state_id')
                        ->where('ads.status',1)
                        ->where('ads.ads_expire_date','!=',null)->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'))
                        //->WhereRaw("price between 0 and 4000000")
                        //->groupBy('ads.cities')
                        ->groupBy('states.id')
                        ->orderBy('ads.cities','asc')
                        //->limit(1)
                        ->get()->toArray();
        //$location = array();
        $price=array('min'=>'0','max'=>'4000000');
        $categories =   DB::table('parent_categories')
                        ->select('id','name','image','icon')
                        ->where('status',1)
                        ->get()->toArray();
                        //echo "<pre>";print_r($category);die;
        return view('ads.adsview',compact('category','location','price','catelistcomplete','categories'));
        
    }
    /*public function loadcategory(Request $request){
        $input = $request->all();
        //echo "<br><pre>";print_r($input);die;
        $state=$input['state'];
        $city=$input['city'];
        $pricerange = explode(';',$input['price']);
        $activecategory=activecategory();
        $categorys = DB::table('ads')
                        ->leftJoin('parent_categories','ads.categories','=','parent_categories.id')
                        ->leftJoin('cities','ads.cities','=','cities.id')
                        ->select('ads.categories','ads.sub_categories', DB::raw('count(ads.categories) as total'),'parent_categories.name as cate_name')
                        ->limit(5)
                        ->where('ads.status',1)
                        ->orderBy('total','desc')
                        ->groupBy('ads.categories')
                        ->groupBy('ads.sub_categories')
                        ->where('ads.ads_expire_date','!=',null)->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'));
                        if($input['cate']!="0"){
                            $categorys = $categorys->Where('ads.categories', $input['cate']);
                        }
                        if($input['subcate']!="0"){
                            $categorys = $categorys->Where('ads.sub_categories', $input['subcate']);
                        }
                        if($state != '0'){
                            $categorys = $categorys->Where('cities.state_id',$state);
                        }
                        if($city != '0'){
                            $categorys = $categorys->Where('ads.cities',$city);
                        }
                        if(!empty($activecategory)){
                            $categorys = $categorys->WhereIn('ads.categories',$activecategory);
                        }
                if( $pricerange[0] != '' && $pricerange[1] != '' ){
                    $categorys->where(function($query) use($pricerange) {
                            
                                $query->WhereRaw("price between $pricerange[0] and $pricerange[1]");
                                
                            
                       });
                }

            $category =$categorys->get()->toArray();
            $catelist=array(); 
            $catelistcomplete=array();           
            foreach ($category as $key => $value) {
                $catelist[$value->categories]=$value->categories;
                
            }

            foreach ($catelist as $key2 => $value2) {
                $sitemap =array();
                foreach ($category as $key1 => $value1) {
                    //echo "<br><pre>";print_r($value1->cities);die;
                    
                    if($value1->categories==$value2){
                        //array_push($sitemap,$value1);
                        $sitemap[$value1->sub_categories] = $value1;
                    } 
                    //echo "<br><pre>";print_r($sitemap);die;
                }
                $catelistcomplete[$value2] = $sitemap;
                
            }
            $catekey="cate('0','0')";
            $data='<ul class="list-group"><li class = "list-group-item" onclick="'.$catekey.'">All Categories</li>';
            foreach($catelistcomplete as $key => $cate) {
                 $data.='<li class = "list-group-item category">
                                        <a data-toggle = "collapse" href = "#cate_'.$key.'">'.get_P_Cate_Name($key).'</a>
                                        <div id ="cate_'.$key.'" class="panel-collapse collapse">
                                          <ul class = "list-group">';
                                          foreach($cate as $key1 => $subcate) {
                                            $catekey="cate('".$key."','".$subcate->sub_categories."')";
                                            $data.='<li class = "list-group-item" onclick="'.$catekey.'">'.get_S_Cate_Name($subcate->sub_categories).' </li>';
                                          }
                                          $data.='</ul>
                                        </div>
                                    </li>';
            }
           $data.='</ul>'; 
           return $data;
    }*/

    public function loadcategory(Request $request){
        $input = $request->all();
        //echo "<br><pre>";print_r($input);die;
        $state=$input['state'];
        $city=$input['city'];
        $pricerange = explode(';',$input['price']);
        if (!empty($state)) {
            $state = explode(',',$input['state']);
        }
        $activecategory=activecategory();
        $categorys = DB::table('ads')
                        ->leftJoin('parent_categories','ads.categories','=','parent_categories.id')
                        ->leftJoin('cities','ads.cities','=','cities.id')
                        ->select('ads.categories','ads.sub_categories', DB::raw('count(ads.categories) as total'),'parent_categories.name as cate_name')
                        //->limit(10)
                        ->where('ads.status',1)
                        ->orderBy('total','desc')
                        ->groupBy('ads.categories')
                        ->groupBy('ads.sub_categories')
                        ->where('ads.ads_expire_date','!=',null)->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'));
                        if($input['cate']!="0"){
                            $categorys = $categorys->Where('ads.categories', $input['cate']);
                        }
                        if($input['subcate']!="0"){
                            $categorys = $categorys->Where('ads.sub_categories', $input['subcate']);
                        }
                        if(!empty($state)){
                            $categorys = $categorys->whereIn('cities.state_id',$state);
                        }
                        if($city != '0'){
                            $categorys = $categorys->Where('ads.cities',$city);
                        }
                        if(!empty($activecategory)){
                            $categorys = $categorys->WhereIn('ads.categories',$activecategory);
                        }
                if( $pricerange[0] != '' && $pricerange[1] != '' ){
                    $categorys->where(function($query) use($pricerange) {
                            
                                $query->WhereRaw("price between $pricerange[0] and $pricerange[1]");
                                
                            
                       });
                }

            $category =$categorys->get()->toArray();
            $catelist=array(); 
            $catelistcomplete=array();           
            foreach ($category as $key => $value) {
                $catelist[$value->categories]=$value->categories;
                
            }

            foreach ($catelist as $key2 => $value2) {
                $sitemap =array();
                foreach ($category as $key1 => $value1) {
                    //echo "<br><pre>";print_r($value1->cities);die;
                    
                    if($value1->categories==$value2){
                        //array_push($sitemap,$value1);
                        $sitemap[$value1->sub_categories] = $value1;
                    } 
                    //echo "<br><pre>";print_r($sitemap);die;
                }
                $catelistcomplete[$value2] = $sitemap;
                
            }
            $catekey="categoriesSelect('0','0')";
            $data='<ul class="list-group"><li class = "list-group-item" onclick="'.$catekey.'">All Categories</li>';
            foreach($catelistcomplete as $key => $cate) {
                 $data.='<li class = "list-group-item category">
                                        <a data-toggle = "collapse" href = "#cate_'.$key.'">'.get_P_Cate_Name($key).'</a>
                                        <div id ="cate_'.$key.'" class="panel-collapse collapse">
                                          <ul class = "list-group">';
                                          foreach($cate as $key1 => $subcate) {
                                            //echo "<br><pre>";print_r($subcate);die;
                                            $catekey="categoriesSelect('".$key."','".$subcate->sub_categories."')";
                                            $data.='<li class = "list-group-item" onclick="'.$catekey.'">'.get_S_Cate_Name($subcate->sub_categories).' </li>';
                                            //.'('.(isset($subcate->total)?$subcate->total:"0").')
                                          }
                                          $data.='</ul>
                                        </div>
                                    </li>';
            }
           $data.='</ul>'; 
           return $data;
    }

    public function loadlocation(Request $request){
            $input = $request->all();
            $state=$input['state'];
            $city=$input['city'];
            //echo"<pre>";print_r($input['cate']);die;
            // if($input['cate']=="0"){
            //     echo"<pre>1";die;  
            // }else{
            //     echo"<pre>2";die;  
            // }
            $activecategory=activecategory();
            $pricerange = explode(';',$input['price']);
            $locations = DB::table('ads')
                        ->leftJoin('cities','ads.cities','=','cities.id')
                        ->leftJoin('states','states.id','=','cities.state_id')
                        ->select('cities.id','cities.name as cityname','ads.cities', DB::raw('count(ads.cities) as total'),'states.name as state_name','states.id as state_id')
                        ->where('ads.status',1)
                        ->where('ads.ads_expire_date','!=',null)->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'));
                        
                        /*if($input['cate']!="0"){
                            $locations = $locations->Where('ads.categories', $input['cate']);
                        }
                        if($input['subcate']!="0"){
                            $locations = $locations->Where('ads.sub_categories', $input['subcate']);
                        }*/
                        
                        /*if( $pricerange[0] != '' && $pricerange[1] != '' ){
                            $locations =$locations->where(function($query) use($pricerange) {
                                $query->WhereRaw("price between $pricerange[0] and $pricerange[1]");
                                
                            });
                        }*/
                        /*if($state != '0'){
                            $locations = $locations->Where('cities.state_id',$state);
                        }
                        if($city != '0'){
                            $locations = $locations->Where('ads.cities',$city);
                        }*/
                        if(!empty($activecategory)){
                            $locations = $locations->WhereIn('ads.categories',$activecategory);
                        }

                        
                      $location=$locations->groupBy('states.id')
                        ->orderBy('ads.cities','asc')
                        //->limit(1)
                        ->get()->toArray();
            //return $location;
            
            if($city != '0'){
                $data='<ul id="location_filter_ul" class = "list-group">';
                foreach($location as $locate ) {
                    $locationkey="locate('".$locate->state_id."','".$city."')";
                    $data.=
                    '<li class = "list-group-item">
                        <label class="multicheck_box_inner_wrap location_label" for="'.$locate->state_id.'" >'
                            .$locate->cityname.
                            '<input type="checkbox" class="state_wrap" onclick="'.$locationkey.'" id="'.$locate->state_id.'" value="'.$locate->state_id.'">
                            <span class="checkmark"></span>
                        </label>
                    </li>';
                }
                $data.='</ul>';
            }else{
                $data='<ul id="location_filter_ul" class = "list-group">';
                foreach($location as $locate ) {
                    $locationkey="locate('".$locate->state_id."','0')";
                    $data.='<li class = "list-group-item">
                        <label class="multicheck_box_inner_wrap location_label" for="'.$locate->state_id.'">'
                            .$locate->state_name.
                            '<input type="checkbox" class="state_wrap"  onclick="'.$locationkey.'" id="'.$locate->state_id.'" value="'.$locate->state_id.'">
                            <span class="checkmark"></span>
                        </label>
                    </li>';
                }
                $data.='</ul>'; 
            }

           //  $data='<ul class = "list-group">';
           //  foreach($location as $locate ) {
           //      $locationkey="locate('".$locate->state_id."')";
           //       $data.='<li class = "list-group-item"  onclick="'.$locationkey.'">'.$locate->state_name.'('.$locate->total.')</li>';
           //  }
           // $data.='</ul>'; 
           return $data;

    }    

    public function getExtraFillter(Request $request){
        $input=$request->all();
        //echo"<pre>";print_r($input);die;
        $get_category_field=DB::table('category_field')->where('category_id',$input['subcate'])->orderBy('field_id', 'ASC')->get();
        $customfields='';
        if(!empty($get_category_field)){
            foreach ($get_category_field as $key => $field) {

                if(!empty($input['customFilters'])){
                    $customfieldfilters = explode('&',$input['customFilters']);


                    $getarrays = array();
                    foreach ($customfieldfilters as $key => $explodevalues) {
                        if(!empty($explodevalues)){
                            $customfieldfiltersValuesMerge = explode('=',$explodevalues); 
                            if($customfieldfiltersValuesMerge[1] != ''){
                                $getarrays[$customfieldfiltersValuesMerge[0]]= $customfieldfiltersValuesMerge[1];
                            }else{
                                unset($getarrays[$customfieldfiltersValuesMerge[0]]);
                            }
                            
                        }
                    } 


                    $customfields.=$this->getExtraFillterHtml($field->field_id,$getarrays);
                }else{
                    $customfields.=$this->getExtraFillterHtml($field->field_id);
                }
                //$customfieldfilters = explode('&',$input['customFilters']);
                //$filterss = array_filter(array_unique($customfieldfilters));
                //foreach ($filterss as $key => $cffvla) {
                    //$customfieldfiltersValues = explode('=',$cffvla);
                    //echo"<pre>";print_r($customfieldfiltersValues);die;
                    // if(!empty($customfieldfiltersValues)){
                    //     $customfields.=$this->getExtraFillterHtml($field->field_id,$customfieldfiltersValues);
                    // }else{
                    //     $customfields.=$this->getExtraFillterHtml($field->field_id);
                    // }
                //}
            }
        }
        //echo"<pre>";print_r($customfields);die;
        return $customfields;die;
    }
    public function getExtraFillterHtml($fields,$input=array()){


        $customfields="";
        $get_custom_field=Customfield::where('id',$fields)->where('active',1)->first(); 
         
        if(!empty($get_custom_field->type)){

            if($get_custom_field->required == 1){
                $sub="*";
            }else{
                $sub="";
            }

            if($get_custom_field->type =="text"){
                $value = '';
                $keyvalues = array_keys($input);
                if(!empty($keyvalues) && in_array($get_custom_field->id, $keyvalues)){
                    $value = urldecode($input[$get_custom_field->id]);
                }
                // echo"<pre>";print_r($input);
                // echo"<pre>";print_r($keyvalues);
                // echo"<pre>";print_r($get_custom_field->id);die;
                $customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                            <input type='$get_custom_field->type' class='form-control customfinputtext cfs_$get_custom_field->id'  name='cf[$get_custom_field->id]' placeholder='' max='' data-customfieldid='$get_custom_field->id' value='$value'></div>";
                /*$customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name</label><div class='min_max_input_outer_wrap row'>
                                <div class='col-md-5 pl-0 pr-0'>
                                    <input type='number' name='min' placeholder='Min' class='customfinputmin' data-customfieldid='$get_custom_field->id'>
                                </div>
                                <div class='col-md-5 pl-0 pr-0'>
                                    <input type='number' name='max' placeholder='Max' class='customfinputmax' data-customfieldid='$get_custom_field->id'>
                                </div>
                                <div class='col-md-2 pl-0 pr-0'>
                                    <button><i class='fa fa-chevron-right customfinputclick' aria-hidden='true'></i></button>
                                </div>
                              </div></div>";*/
            }


            if($get_custom_field->type =="number"){
                $value = '';
                $keyvalues = array_keys($input);
                if(!empty($keyvalues) && in_array($get_custom_field->id, $keyvalues)){
                    $value = urldecode($input[$get_custom_field->id]);
                }
                $customfields.="<div class='form-group fields_common_wrap'>
                            <label for='cf.$get_custom_field->id'>$get_custom_field->name <sup>$sub</sup></label>
                            <input type='$get_custom_field->type' class='form-control customfinputtext cfs_$get_custom_field->id'  name='cf[$get_custom_field->id]' placeholder='' max='' data-customfieldid='$get_custom_field->id' value='$value'></div>";
            }

            if($get_custom_field->type =="checkbox"){

                $checked = '';
                $keyvalues = array_keys($input);
                if(!empty($keyvalues) && in_array($get_custom_field->id, $keyvalues)){
                    $checked = "checked";
                }

                $customfields.="<div class='multicheck_box_wrap form-group'>
                            <label class='multicheck_label_wrap'>$get_custom_field->name</label>
                            <div class='multicheck_outer_wrap multicheck_rectangle_wrap'>
                                <label class='multicheck_box_inner_wrap'>
                                    <input type='checkbox' id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' value='$get_custom_field->default' max='$get_custom_field->max' class='customfcheckbox cfs_$get_custom_field->id' data-customfieldid='$get_custom_field->id' $checked>
                                    <span class='checkmark'></span>
                                </label>
                            </div>
                        </div>";
            }

            if($get_custom_field->type =="textarea"){
                 $customfields.="";
            }
            
            if($get_custom_field->type == "radio"){
                $customfields.="<div class='multicheck_box_wrap radio_btn_wrap form-group'>
                            <label class='multicheck_label_wrap'>$get_custom_field->name</label>
                            <div class='multicheck_outer_wrap'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {

                    $checked = '';
                    $keyvalues = array_keys($input);
                    if(!empty($keyvalues) && in_array($option->id, $input)){
                        $checked = "checked";
                    }
                    // echo"<pre>";print_r($input);
                    // echo"<pre>";print_r($option->id);
                    // echo"<pre>";print_r($keyvalues);
                    // echo"<pre>";print_r($get_custom_field->id);die;
                        
                    $customfields.="<label class='multicheck_box_inner_wrap'>$option->value
                                    <input type='radio' name='cf[$get_custom_field->id]' id='cf.$get_custom_field->id.$option->id' value='$option->id' class='customfradio cfs_$get_custom_field->id' data-customfieldid='$get_custom_field->id' $checked >
                                    <span class='checkmark'></span>
                                </label>";
                }  

                $customfields.="</div></div>";
            }

            if($get_custom_field->type == "select"){

                 $customfields.="<div class='form-group common_select_option contact_form_select_btn_wrap'>
                                <label for='cf.$get_custom_field->id'>$get_custom_field->name</label>
                                    <select class='form-control customfselect cfs_$get_custom_field->id'  name='$get_custom_field->name' id='$get_custom_field->name' data-customfieldid='$get_custom_field->id'><option value=''hidden> Select $get_custom_field->name</option>";
                
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) { 
                    $selected = '';
                    if(!empty($input[$get_custom_field->id]) && $input[$get_custom_field->id] == $option->id){
                        $selected = 'selected';
                    }
                        
                    $customfields.="<option value='$option->id' $selected>$option->value</option>";
                                    
                }  

                $customfields.="</select></div>";
            }

            if($get_custom_field->type == "checkbox_multiple"){
                $customfields.="<div class='multicheck_box_wrap form-group'>
                            <label class='multicheck_label_wrap'>$get_custom_field->name</label>
                            <div class='multicheck_outer_wrap multicheck_rectangle_wrap'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {


                    $checked = '';
                    $keyvalues = array_keys($input);
                    if(!empty($keyvalues) && in_array($get_custom_field->id, $keyvalues)){
                        $checked = "checked";
                    }
                        
                    $customfields.="<label class='multicheck_box_inner_wrap'>$option->value
                                    <input type='checkbox' name='cf[$get_custom_field->id][$option->id]' id='cf.$get_custom_field->id.$option->id' value='$option->id'  class='customfcheckboxmultiple cfs_$get_custom_field->id' data-customfieldid='$get_custom_field->id' $checked>
                                    <span class='checkmark'></span>
                                </label>";
                }  

                $customfields.="</div></div>";
            }

            /*if($get_custom_field->type =="file"){
                 $customfields.="<div class='form-group row'>
                                    <label for='$get_custom_field->type' class='col-sm-3 col-form-label'>$get_custom_field->name<sup>$sub</sup></label>
                                    <div class='col-sm-9'>
                                    <input type='$get_custom_field->type' class='form-control-file mb-2  id='$get_custom_field->name' name='$get_custom_field->name' placeholder=''  value='$get_custom_field->default' max='$get_custom_field->max'>
                                    </div>
                                </div>";

            }*/

        return $customfields;    
        }  

    }
    public function loadmoresearch(Request $request)
    {
        $input = $request->all();
        $setting=Setting::first();
        $pricerange = explode(';',$input['price']);
        $state=$input['state'];
        $city=$input['city'];
        $area=$input['area'];
        $searchquery=$input['query']; 
        //echo "<pre>";print_r($input);
        $listing_data=$input['listing_id'];

        $stateValuee = array();
        if(isset($state)){
            $stateValuee = explode(",", $state);
        }
        $stateValueFilter = array_filter($stateValuee);

        $adsIDD = array();
        if($pricerange[0] ==''){
            $pricerange[0]=0;
        }
        if($pricerange[1] ==''){
            $pricerange[1]=7500000;
        }
        
        $customfiled = 0;
        if(!empty($input['customFilters'])){
            $customfieldfilters = explode('&',$input['customFilters']);
            $customfieldfilters = array_filter(array_unique($customfieldfilters));
            if(!empty($customfieldfilters)){
                $newarr = array();
                foreach ($customfieldfilters as $key => $explodevalues) {
                    if(!empty($explodevalues)){
                        $customfieldfiltersValuesMerge = explode('=',$explodevalues); 
                        if($customfieldfiltersValuesMerge[1] != ''){
                            $newarr[$customfieldfiltersValuesMerge[0]]= $customfieldfiltersValuesMerge[1];
                        }else{
                            unset($newarr[$customfieldfiltersValuesMerge[0]]);
                        }
                        
                    }
                }  

                $newarrkeys = [];
                $newarrvaluess = [];
                foreach ($newarr as $kesy => $newarrvalue) {
                    $newarrkeys[] = $kesy;
                    $newarrvaluess[] = $newarrvalue;
                }

                // echo "<pre>";print_r($newarrkeys);
                // echo "<pre>";print_r($newarrvaluess);die;

                //$filterss = array_filter(array_unique($customfieldfilters));
                $cusfvalues = array();
                //foreach ($newarr as $keyad => $cffvla) {
                    //$customfieldfiltersValues = explode('=',$cffvla);    

                    $cusfval =DB::table('post_values')->select('post_id')->whereIn('field_id',$newarrkeys)->whereIn('value',$newarrvaluess)->get();
                
                    $convertjson = json_decode(json_encode($cusfval),true);
                    $cusfvalues[] = array_column($convertjson, "post_id");
                        //echo "<pre>";print_r($cusfvalues);die;

                    /*if($customfieldfiltersValues[0] != '6'){
                        $cusfval =DB::table('post_values')->select('post_id')->where('field_id',$newarrkeys)->where('value',$newarrvaluess)->get();
                        $convertjson = json_decode(json_encode($cusfval),true);
                        $cusfvalues[] = array_column($convertjson, "post_id");
                        echo "<pre>";print_r($cusfvalues);die;    
                    }else{
                        $finval = explode(',',$customfieldfiltersValues[1]);

                        $cusfval =DB::table('post_values')->select('post_id')->where('field_id',$customfieldfiltersValues[0])->whereBetween('value', $finval)->get();
                        $convertjson = json_decode(json_encode($cusfval),true);
                        $cusfvalues[] = array_column($convertjson, "post_id");  
                        //echo "<pre>";print_r($cusfvalues);die;                  
                    }*/
                //}
                //echo "<pre>";print_r($cusfvalues);die;
                $singlearr = array_reduce($cusfvalues, 'array_merge', array());
                $adsIDD = array_unique($singlearr);
            
                $customfiled = 1;
            }
        }
        //echo "<pre>";print_r($adsIDD);die;
        /*if(!empty($adsIDD)){
            $ads =$ads->whereIn('ads.id', $adsIDD);
        }*/



        //$activecategory=activecategory();
        $cateValue = array();
        $subCateValue = array();
        if(!empty($searchquery)){
            //$data= DB::table('parent_categories')->Where("name","like","%".$searchquery."%")->where('status',1)->get();
            $getlastletter = substr($searchquery, -1);
            $searchqueryaddS = $searchquery;
            if($getlastletter != 's'){
                $searchqueryaddS = $searchquery."s";
            }
            //echo "<pre>";print_r($searchqueryaddS);die;
            $data= DB::table('parent_categories')->Where("name",$searchqueryaddS)->where('status',1)->get();
            $cateValue = array();
            foreach ($data as $value) {
                array_push($cateValue,$value->id);
            }

            //$subCatt = DB::table('sub_categories')->Where("name","like","%".$searchquery."%")->where('status',1)->get();
            $subCatt = DB::table('sub_categories')->Where("name",$searchqueryaddS)->where('status',1)->get();
            foreach ($subCatt as $svalue) {
                array_push($subCateValue,$svalue->id);
            }
        }
        if($input['cate'] != '0'){
            array_push($cateValue,$input['cate']);
        }
        if($input['subcate'] != '0'){
            array_push($subCateValue,$input['subcate']);
        }

        //echo "<pre>";print_r($cateValue);
        //echo "<pre>";print_r($subCateValue);die;

        $ads = DB::table('ads')
                    ->select('ads.*','ads_image.id as adsimgid','ads_image.image','users.photo','users.uuid as uuuid','cities.name as cityname','ads_features.expire_date as featureadsexp','toplists_ads.expire_date as expireDDD','toplists_ads.id as toplistidd')
                    // ->select('ads.*','ads_image.id as adsimgid','ads_image.image','users.photo','users.uuid as uuuid','cities.name as cityname','favourites.uuid as fav_uuid')
                    ->leftJoin('users','ads.seller_id','=','users.id')
                    //->leftjoin('favourites','favourites.ads_id','=','ads.id')
                    ->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')
                    ->leftjoin('ads_features','ads_features.ads_id','=','ads.id')
                    ->leftJoin('post_values', 'ads.id', '=', 'post_values.post_id')
                    ->leftjoin('cities', function($leftJoin){
                        $leftJoin->on('ads.cities', '=', 'cities.id');
                    })
                    ->leftjoin('toplists_ads', function($leftJoin){
                        $leftJoin->on('ads.id', '=', 'toplists_ads.ads_id');
                        $leftJoin->where('toplists_ads.expire_date','>=',date("Y-m-d h:m:s"));
                    })
                    ->where(function($query) use($pricerange,$state,$city,$searchquery,$area,$stateValueFilter,$cateValue,$subCateValue,$adsIDD) {
                        // if($pricerange[1] == ''){
                        //     $query->Where('ads.price','>=',$pricerange[0]);
                        // }else{
                        //     if( $pricerange[1] != '' ){
                        //         $query->WhereRaw("ads.price between $pricerange[0] and $pricerange[1]");
                        //     }
                        // }
                        // if none of them is null
                        
                    
                        if ( !is_null($pricerange[0]) && !is_null($pricerange[1]) ) {
                            // fetch all between min & max values
                            //$query->whereBetween('ads.price', [$min_value, $max_value]);
                            $query->WhereRaw("ads.price between $pricerange[0] and $pricerange[1]");
                        }
                        // if just min_value is available (is not null)
                        elseif (! is_null($pricerange[0])) {
                            // fetch all greater than or equal to min_value
                            $query->where('ads.price', '>=', $pricerange[0]);
                        }
                        // if just max_value is available (is not null)
                        elseif (! is_null($pricerange[1])) {
                            // fetch all lesser than or equal to max_value
                            $query->where('ads.price', '<=', $pricerange[1]);
                        }

                        
                        if(!empty($cateValue)){
                            //echo"<br><pre> cateValue";print_r($cateValue);
                            $query->whereIn('ads.categories', $cateValue);
                        }
                        if(!empty($subCateValue)){
                            //echo"<br><pre>";print_r($subCateValue);die;
                            $query->whereIn('ads.sub_categories', $subCateValue);
                        }else if( $searchquery != ''){
                            $query->where("ads.title","like","%".$searchquery."%");
                            $query->orWhere("ads.description","like","%".$searchquery."%");
                        }
                        if(!empty($stateValueFilter)){
                            $query->WhereIn('cities.state_id',$stateValueFilter);
                        }
                        if( $city != '0'){
                            $query->where('ads.cities',$city);
                        }
                        if( $area != '0'){
                            $query->where('ads.area_id',$area);
                        } 
                    
                    });

            if(!empty($adsIDD)){
                $ads = $ads->whereIn('ads.id', $adsIDD);
            }
        
        // if(is_null($pricerange[1]) == '' ){
        //     $ads = $ads->Where('ads.price','>=',$pricerange[0]);
        // }else{
        //     if(! is_null($pricerange[1]) ){
        //         $ads = $ads->WhereRaw("ads.price between $pricerange[0] and $pricerange[1]");
        //     }
        // }

        /*if($input['subcate'] != '0'){
            $ads =$ads->where('ads.sub_categories', $input['subcate']);
        }
        if($input['cate'] != '0'){
            $ads =$ads->where('ads.categories', $input['cate']);
        } */
        /*if(!empty($activecategory)){
            $ads = $ads->WhereIn('ads.categories',$activecategory);
        }*/
        $ads->where('users.status', 1)->where('ads.status', 1)->where('ads.ads_expire_date','!=',null)->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'));

        if($input['sortingfilters'] == '0'){
            $ads = $ads->orderBy('toplists_ads.id','desc')->orderBy('toplists_ads.expire_date','desc')->orderBy('ads.type','desc')->orderBy('ads.approved_date','desc');
        }

        if($input['sortingfilters'] == '1'){
            $ads = $ads->orderBy('toplists_ads.id','desc')->orderBy('toplists_ads.expire_date','desc')->orderBy('ads.type','desc')->orderBy('ads.price','asc');
        }

        if($input['sortingfilters'] == '2'){
            $ads =$ads->orderBy('toplists_ads.id','desc')->orderBy('toplists_ads.expire_date','desc')->orderBy('ads.type','desc')->orderBy('ads.price','desc');
        }  

        $ads->groupBy("ads.id")
                    ->offset($input['row'])
                    ->limit(9);
        $adsfv = $ads->get();

        $viewads = array();
        $favads  = array();
        if(Auth::check()){
            $viewads = DB::table('user_viewed_ads')
              ->where('user_id',Auth::user()->id)
              ->get()->toArray();
            $favads = DB::table('favourites')
                  ->where('user_id',Auth::user()->id)
                  ->get()->toArray();
        }

        foreach ($adsfv as $key => $ad) {
            $ad->fav_uuid ='';
            if(!empty($favads)){
                foreach ($favads as $key => $fads) {
                    $adsf[$ad->id] = $ad;
                    if($fads->ads_id == $ad->id){
                        $adsf[$ad->id]->fav_uuid =$fads->uuid; 
                    }                  
                }
            }else{
                $ad->fav_uuid ='';
                $adsf[$ad->id] = $ad;                
            }
            
        }

        foreach ($adsfv as $key => $ad) {
            $ad->alreadyviwed = "0";
            if(!empty($viewads)){
                foreach ($viewads as $key => $fad) {
                    $adsf[$ad->id] = $ad;
                    if($fad->ads_id == $ad->id){
                        $adsf[$ad->id]->alreadyviwed = "1"; //viewd
                    }                  
                }
            }else{
                $ad->alreadyviwed = "0";
                $adsf[$ad->id] = $ad;                
            }
            
        }

        /*if($customfiled == 1 && !empty($adsIDD)){
            $finalads = array();
            if(!empty($adsf)){
                foreach ($adsf as $key => $ad) {
                    if(in_array($ad->id, $adsIDD)){
                        $finalads[$ad->id] = $ad;  
                    }
                }
            }
        }else if($customfiled == 1 && empty($adsIDD)){
            $adsf = array();
        }else{
            $finalads = $adsf;
        }*/


        //echo"<pre>";print_r($viewads);
        //echo"<br><pre>";print_r($finalads);die;
        
        $showads='';
        $loadmorebtnexists = '';
        $loadmorebtnexists = 'loadmorebtnexists_'.$input['row'];
        if($listing_data == 1){
            $listing_class="list-group-item";
        }else{
             $listing_class="";
        }
        if(!empty($adsf)){
            $highest  = 0;
            
            foreach ($adsf as $key => $ad) {

                if ($ad->price > $highest){
                    $highest = $ad->price;
                }
                $showads.='<div class="item col-md-6 col-lg-6 col-xl-4 filter cat-2 product_count '.$loadmorebtnexists.' " id="product_count">

                                <div class="thumbnail card">';
                    //echo "<pre>";print_r($ad->expireDDD );die;
                    if($ad->expireDDD !=''  && $ad->expireDDD > date("Y-m-d h:m:s")){
                        $showads.='<div class="ads_status_wrap pending_ad_wrap"><p>Featured</p></div>';
                    }else if($ad->featureadsexp!='' && $ad->featureadsexp > date("Y-m-d h:m:s")){
                        $showads.='<div class="ads_status_wrap pending_ad_wrap"><p>Platinum</p></div>';
                    }/*elseif($ad->type =='0'){
                        $showads.='<div class="ads_status_wrap pending_ad_wrap"><p>Free</p></div>';
                    }*/

                $adfav="'".$ad->uuid."','".$ad->id."'";  
                
                if(Auth::check()){
                    if($ad->fav_uuid != null){
                        $showads.='<i class="fa fa-heart heart_wrap" onclick="fav('.$adfav.')"  id="favourite-'.$ad->id.'"aria-hidden="true"></i>';
                    }else if($ad->seller_id != auth()->user()->id){
                        $showads.='<i class="fa fa-heart-o heart_wrap" onclick="fav('.$adfav.')"  id="favourite-'.$ad->id.'"aria-hidden="true"></i>';
                    }else{

                    }

                }else{
                    $showads.='<i class="fa fa-heart-o heart_wrap" onclick="fav('.$adfav.')"  id="favourite-'.$ad->id.'"aria-hidden="true"></i>';
                } 

                $showads.='<a href="'.route('adsview.getads',$ad->uuid).'">
                                    <div class="img-event">
                                        
                                            <img class="group list-group-image img-fluid" src="'.asset('public/uploads/ads/'.$ad->image).'" alt="" />
                                        </div>';
                if($ad->featureadsexp!='' && $ad->featureadsexp > date("Y-m-d h:m:s")){
                    $showads.='<div class="ads_status_wrap pending_ad_wrap"><p>Platinum</p></div>';
                }
            
                $showads.='<div class="caption card-body">
                            
                                <h4 class="group card-title inner list-group-item-heading">
                                    ₹'.$ad->price.'</h4>
                                <p class="group inner list-group-item-text">
                                    <span>'.ucwords(str_limit($ad->title,18)).'</span><br>
                                    <span>'.strtoupper(get_areaname($ad->area_id)).'</span>
                                </p>
                            
                            <div class="row bottom_card_wrap">
                                <div class="col-xs-12 col-md-6 col-sm-4">
                                    <p class="day_wrap">'.time_elapsed_string($ad->approved_date).'</p>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-8">';
                            
                            if(Auth::check()){
                                if($ad->seller_id != auth()->user()->id){
                                   if($ad->alreadyviwed=='0' && $ad->point_expire_date > date('Y-m-d H:i:s') && $ad->ads_expire_date > date('Y-m-d H:i:s') && $setting->ads_view_point <= $ad->point && $ad->point != null){
                                        //$showads.='<span class="view_earn_wrap">View to Earn</span>';
                                    }
                                }    
                            }else{
                                if($ad->alreadyviwed=='0' && $ad->point_expire_date > date('Y-m-d H:i:s') && $ad->ads_expire_date > date('Y-m-d H:i:s') && $setting->ads_view_point <= $ad->point && $ad->point != null){
                                        //$showads.='<span class="view_earn_wrap">View to Earn</span>';
                                }
                            }

                            $showads.='</div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>';
            }
                            $showads.='<input type="hidden" value="'.$highest.'" id="highest">';

            echo($showads);die;
        }else{
            echo($showads);die;
        }

    }
    //map getads
    /*public function getads(Request $request){

         $data['lat']  = 0;
        $data['long'] = 0;
        



        $aditems = DB::table('ads')
            ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id')
            ->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')
            ->where('ads.status',"1")
            ->groupBy("ads.id")
            ->get()->toArray();
        $parent=Parent_categories::get();
        $sub=Sub_categories::get();
        $setting=Setting::first();
        
        $favads = array();
        if(Auth::check()){
            $favads = DB::table('favourites')
                  ->where('user_id',Auth::user()->id)
                  ->get()->toArray();
        }

        $ads = array();
        foreach ($aditems as $key => $ad) {
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
            if($setting->ads_view_point <= $ad->point){
                $ads[$ad->id]->viewpoint = "1";
            }
        }
        //echo "<pre>";print_r($aditems);die;
        return view('ads.adsview',compact('ads','data','parent','sub','setting'));      
    }*/

    public function getitems(Request $request)
    {
        //$input=$request->all();
        $map_details   = $request->input('map_details');
        $minprice=$request->input('min_price');
        $maxprice=$request->input('max_price');
        $subcategory=$request->input('subcategory');
        $parentcategory=$request->input('parentcategory');
        //$location=$request->input('location');
        $sort=$request->input('sort');
        //echo '<pre>';print_r($sort);die;
        /*$users_where['users.status']    = '1';
        $address  = "Chennai,+Tamil+Nadu,+India";
        $map_where    = 'https://maps.google.com/maps/api/geocode/json?key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4
&address='.$address.'&sensor=false&libraries=places';
        $geocode      = $this->content_read($map_where);
        $json         = json_decode($geocode);
        */
        if($map_details != '')
        {
            $map_data=   explode('~', $map_details);
            $minLat     =   $map_data[2];
            $minLong    =   $map_data[3];
            $maxLat     =   $map_data[4];
            $maxLong    =   $map_data[5];

        }

        /*else
        {
            if(@$json->{'results'})
            {
                $data['lat'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                $data['long'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

                $minLat = $data['lat']-0.35;
                $maxLat = $data['lat']+0.35;
                $minLong = $data['long']-0.35;
                $maxLong = $data['long']+0.35;
            }
            else
            {
                $data['lat'] = 0;
                $data['long'] = 0;

                $minLat = -1100;
                $maxLat = 1100;
                $minLong = -1100;
                $maxLong = 1100;
            }
        }*/
        /*echo '<pre>';print_r( $minLat );
        echo '<pre>';print_r( $maxLat );
        echo '<pre>';print_r( $minLong );
        echo '<pre>';print_r( $maxLong );die;*/

        $ads = DB::table('ads')
                    ->select('ads.*','ads_image.id as adsimgid','ads_image.image','users.photo','users.uuid as uuuid','cities.name as cityname','favourites.uuid as fav_uuid')
                    ->leftJoin('users','ads.seller_id','=','users.id')
                    ->leftjoin('favourites','favourites.ads_id','=','ads.id')
                    ->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')
                    ->leftjoin('cities', function($leftJoin){$leftJoin->on('ads.cities', '=', 'cities.id');})
                    ->where(function($query) use($minLat, $maxLat, $minLong, $maxLong,$minprice,$maxprice) {
                        $query->whereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");

                        $query->WhereRaw("price between $minprice and $maxprice");
                        
                        
                    });
                    if($parentcategory!='' || !empty($parentcategory)){
                        $ads->where('ads.categories', $parentcategory);
                    }
                    if($subcategory!='' || !empty($subcategory)){
                        $ads->where('ads.sub_categories', $subcategory);
                    }
                    /*if($location!='' || !empty($location)){
                        $ads->where('ads.cities', $location);
                    }*/
                    if($sort=='0'){
                        $ads->orderby('ads.approved_date', 'desc');
                    }else if($sort=='1'){
                        $ads->orderby('ads.price', 'desc');
                    }else{
                        $ads->orderby('ads.price', 'asc');
                    }
                    //echo '<pre>3';die;
                    $ads->where('users.status', 1)
                    ->where('ads.status', 1)
                    ->groupBy("ads.id");
        $ads = $ads->paginate(10)->toJson();
        echo $ads;die; 
    }

    public function displayads($id){
        $ads= DB::table('ads')
                ->select('ads.*','users.id as user_id','users.created_at as user_created_at','users.photo as photo','ads_features.expire_date as featureadsexp','toplists_ads.expire_date as expireDDD','parent_categories.name as categoryName','sub_categories.name as subCategoryName')
                ->leftJoin('users','ads.seller_id','=','users.id')
               //->leftjoin('favourites','favourites.ads_id','=','ads.id')
               ->leftjoin('parent_categories','parent_categories.id','=','ads.categories')
               ->leftjoin('sub_categories','sub_categories.id','=','ads.sub_categories')
                ->leftjoin('ads_features','ads_features.ads_id','=','ads.id')
                ->leftjoin('toplists_ads', function($leftJoin){
                    $leftJoin->on('ads.id', '=', 'toplists_ads.ads_id');
                })
               // ->where('ads.status',"1")
                ->where('ads.uuid', $id)
                ->first();
        $ads->alreadyviewed = "0";
        //echo "<pre>";print_r($ads);die;
        if(!empty($ads)){
            
            if(Auth::check()){

                $favads = DB::table('favourites')
                      ->where('user_id',Auth::user()->id)
                      ->where('ads_id',$ads->id)
                      ->first();

                if(!empty($favads)){
                    $ads->fav_uuid =$favads->uuid;
                }else{
                     $ads->fav_uuid ='';
                }
                $viewalready = ViewedAds::where('user_id',Auth::user()->id)
                      ->where('ads_id',$ads->id)
                      ->first();

                
                if(!empty($viewalready)){
                    $ads->alreadyviewed ="1";
                }else{
                    $ads->alreadyviewed = "0";
                }

                //echo "<pre>";print_r($ads);die;
                if($ads->seller_id != Auth::user()->id){
                    if($ads->status != 1){
                        toastr()->warning('Your ad is waiting for OJAAK Approval!');
                        return back();
                    }
                    if($ads->ads_expire_date == null || $ads->ads_expire_date < date('Y-m-d H:s:i')){
                        toastr()->warning('Ad Expired!');
                        return back();
                    }
                }else{
                    if($ads->status != 1){
                        toastr()->warning('Your ad is waiting for OJAAK Approval!');
                    }
                    if($ads->ads_expire_date != null || $ads->ads_expire_date != ''){
                        if($ads->ads_expire_date < date('Y-m-d H:s:i')){
                            toastr()->warning('Ad Expired!');
                        }
                    }
                }

            }else{

                if($ads->status != 1){
                    toastr()->warning('Your ad is waiting for OJAAK Approval!');
                    return back();
                }

                if($ads->ads_expire_date == null || $ads->ads_expire_date < date('Y-m-d H:s:i')){
                    toastr()->warning('Ad Expired!');
                    return back();
                }
            }
            

            $setting=Setting::first();
            if($setting->ads_view_point <= $ads->point && $ads->point != null){
                $ads->viewpoint = "1";
            }else{
                $ads->viewpoint = "0";
            }

            

            $relads = DB::table('ads')
            ->leftJoin('ads_image', 'ads_image.ads_id', '=', 'ads.id')
            ->leftjoin('ads_features','ads_features.ads_id','=','ads.id')
            ->where('ads.cities',$ads->cities)
            ->where('ads.categories',$ads->categories)
            ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_features.expire_date as featureadsexp')
            ->where('ads.status',"1")
            ->where('ads.ads_expire_date','!=',null)->where('ads.ads_expire_date','>',date('Y-m-d H:s:i'))
            ->where('ads.uuid','!=',$id)
            ->groupBy("ads.id")
            ->orderby('ads.id', 'desc')
            ->offset(0)->limit(6)->get();
            $favads = array();
            $viewads = array();
            if(Auth::check()){
                $favads = DB::table('favourites')
                      ->where('user_id',Auth::user()->id)
                      ->get()->toArray();
                $viewads = DB::table('user_viewed_ads')
                  ->where('user_id',Auth::user()->id)
                  ->get()->toArray();
            }

            $relatedads = array();
            foreach ($relads as $key => $ad) {
                if(!empty($favads)){
                    foreach ($favads as $key => $fad) {
                        $relatedads[$ad->id] = $ad;
                        if($fad->ads_id == $ad->id){
                            $relatedads[$ad->id]->favv = "1";
                        }                  
                    }
                }else{
                    $relatedads[$ad->id] = $ad;                
                }
                
            }

            foreach ($relads as $key => $ad) {
                $ad->alreadyviwed = "0";
                if(!empty($viewads)){
                    foreach ($viewads as $key => $fad) {
                        $relatedads[$ad->id] = $ad;
                        if($fad->ads_id == $ad->id){
                            $relatedads[$ad->id]->alreadyviwed = "1"; //viewd
                        }                 
                    }
                }else{
                    $ad->alreadyviwed = "0";
                    $relatedads[$ad->id] = $ad;                
                }
                
            }
            //echo"<pre>";print_r($relatedads);die;
            $adsimg = DB::table('ads_image')->where('ads_id',$ads->id)->get();
            $users = Auth::user();
            $year= date('Y',strtotime($ads->user_created_at));
            $month = date('M',strtotime($ads->user_created_at));
            $customfields='';
            $get_category_field=DB::table('category_field')->where('category_id',$ads->sub_categories)->orderBy('field_id', 'ASC')->get();
            if(!empty($get_category_field)){
                foreach ($get_category_field as $key => $field) {
                    //echo"<pre>";print_r($field->field_id);die;
                    $customfields.=$this->getDataView($field->field_id,$ads->id);
                }
            }
            $privacy=Privacy::where('user_id',$ads->seller_id)->first();
            $details=array();
            $usersrating=UsersRating::where('user_id',$ads->seller_id)->select('users_ratings.*')->leftjoin('users','users_ratings.rating_from_user_id','=','users.id')->where('users.status', 1)->limit(15)->offset(0)->latest()->get();

            $usersreviews=UserReview::where('user_id',$ads->seller_id)->orderby('id','asc')->get();
            $details['viewcount']=ViewedAds::where('ads_id',$ads->id)->get()->count();
            
           
            
            //$viewdetails=ViewedAds::where('ads_id',$ads->id)->limit(10)->offset(0)->latest()->get();

            $viewdetails=ViewedAds::where('ads_id',$ads->id)->select('user_viewed_ads.*','privacy.view_chat' )
            ->leftjoin('privacy','user_viewed_ads.user_id','=','privacy.user_id')->limit(10)->offset(0)->latest()->get();

            $details['favcount']=Favourite::where('ads_id',$ads->id)->get()->count();

            $details['offersmade']=Makeanoffers::where('adsid',$ads->id)->get()->count();
            $offersmade=Makeanoffers::where('adsid',$ads->id)->limit(10)->offset(0)->latest()->get();

            $favuser = Favourite::where('ads_id',$ads->id)->select('favourites.*', 'users.name')->leftjoin('users','favourites.user_id','=','users.id')->limit(10)->offset(0)->latest()->get();
            //echo '<pre>';print_r($viewdetails);die;
            $freeAd = "1";
            $platinamAd = "0";
            $premiumAd = "0";
            $featuredAd = "0";

            if($ads->type == '1'){
                $freeAd = "0";
                $premiumAd = "1";
            }
            if($ads->expireDDD !=''  && $ads->expireDDD > date("Y-m-d h:m:s")){
                $freeAd = "0";
                $featuredAd = "1";
                $premiumAd = "0";
            }
            if($ads->featureadsexp !='' && $ads->featureadsexp > date("Y-m-d h:m:s")){
                $platinamAd = "1";
                $freeAd = "0";
                $premiumAd = "0";
                $featuredAd = "0";
            }


            if($ads->status == '0'){
                $platinamAd = "0";
                $freeAd = "0";
                $premiumAd = "0";
                $featuredAd = "0";
            }
            // echo '<pre>freeAd';print_r($freeAd);
            // echo '<pre>platinamAd';print_r($platinamAd);
            // echo '<pre>premiumAd';print_r($premiumAd);
            // echo '<pre>featuredAd';print_r($featuredAd);die;

            $categories =   DB::table('parent_categories')
                        ->select('id','name','image','icon')
                        ->where('status',1)
                        ->get()->toArray();
            //echo"<pre>";print_r($ads);die;
            return view('ads.adsdisplay',compact('ads','adsimg','year','month','users','customfields','relatedads','setting','usersrating','usersreviews','details','favuser','viewdetails','privacy','categories','offersmade','freeAd','platinamAd','premiumAd','featuredAd'));            
        }else{
            toastr()->error('Ads does not exist !');
            return redirect('items');
        }     
    }
    public function getDataView($fields,$postid){
        $customfields="";
        $get_custom_field=Customfield::where('id',$fields)->where('active',1)->first(); 
        $cusval=DB::table('post_values')->where('field_id',$get_custom_field->id)->where('post_id',$postid)->first();
        if(empty($cusval)){
            $cusval = (object) array();
            $cusval->value=' None';
        }
        if(!empty($get_custom_field->type)){

            if($get_custom_field->type =="text"){
                
                $customfields.="<li><h3>$get_custom_field->name</h3><p>$cusval->value</p></li>";
            }

            if($get_custom_field->type =="checkbox"){
                
                $customfields.="<li><h3>$get_custom_field->name</h3><p>";
                if(!empty($cusval) && $cusval->value==1){
                    $customfields.="Yes";
                }else{
                    $customfields.="No";
                } 
                
                $customfields.="</p></li>";
            }

            if($get_custom_field->type =="textarea"){
                 $customfields.="<li><h3>$get_custom_field->name</h3><p>$cusval->value</p></li>";
            }
            
            if($get_custom_field->type == "radio"){

                $customfields.="<li><h3>$get_custom_field->name</h3><p>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get();
                if($cusval->value ==" None"){
                    $customfields.="$cusval->value";
                }
                foreach ($options as $key => $option) {
                    if($option->id == $cusval->value){
                              $customfields.="$option->value";  
                    }
                } 
                
                 

                $customfields.="</p></li>";
            }

            if($get_custom_field->type == "select"){

                $customfields.="<li><h3>$get_custom_field->name</h3><p>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get();
                if($cusval->value ==" None"){
                    $customfields.="$cusval->value";
                }
                foreach ($options as $key => $option) {
                    if($option->id == $cusval->value){
                              $customfields.="$option->value";  
                    }
                }
                $customfields.="</p></li>";
            }

            if($get_custom_field->type == "checkbox_multiple"){
                 $customfields.="<li><h3>$get_custom_field->name</h3><p>";
                $cusoptionval=DB::table('fields_options')
                ->select('fields_options.value')
                ->rightjoin('post_values','fields_options.id','=','post_values.value')
                ->where('fields_options.field_id',$get_custom_field->id)
                ->pluck('fields_options.value')->toArray();
                $val=implode(',', $cusoptionval); 
                $customfields.="$val</p></li>";
            }
            if($get_custom_field->type =="file"){
                 $customfields.="";
            }
        return $customfields;    
        }  
    }
    /*public function getDataView($fields,$postid){
        $customfields="";
        $get_custom_field=Customfield::where('id',$fields)->where('active',1)->first(); 
        $cusval=DB::table('post_values')->where('field_id',$get_custom_field->id)->where('post_id',$postid)->first();
        if(!empty($get_custom_field->type)){

            if($get_custom_field->type =="text"){
                
               $customfields.="<div class='col-md-6 py-1'>
                            <p >$get_custom_field->name
                            <span class='pull-right px-4'>$cusval->value</span></p></div>";
            }

            if($get_custom_field->type =="checkbox"){
                
                $customfields.="<div class='col-md-6 py-1'>
                            <p >$get_custom_field->name
                            <span class='pull-right px-4'>";
                if(!empty($cusval) && $cusval->value==1){
                    $customfields.="Yes";
                }else{
                    $customfields.="No";
                } 
                
                $customfields.="</span></p></div>";
            }

            if($get_custom_field->type =="textarea"){
                 $customfields.="<div class='col-md-6 py-1'>
                            <p >$get_custom_field->name
                            <span class='pull-right px-4'>$cusval->value</span></p></div>";
            }
            
            if($get_custom_field->type == "radio"){

                $customfields.="<div class='col-md-6 py-1'>
                            <p >$get_custom_field->name
                            <span class='pull-right px-4'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get();
                foreach ($options as $key => $option) {
                     if($option->id == $cusval->value){
                              $customfields.="$option->value";  
                        }
                }  

                $customfields.="</span></p></div>";
            }

            if($get_custom_field->type == "select"){

                $customfields.="<div class='col-md-6 py-1 '>
                            <p >$get_custom_field->name
                            <span class='pull-right px-4'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get();
                foreach ($options as $key => $option) {
                     if($option->id == $cusval->value){
                              $customfields.="$option->value";  
                        }
                }  

                $customfields.="</span></p></div>";
            }

            if($get_custom_field->type == "checkbox_multiple"){
                 $customfields.="<div class='col-md-6 py-1'>
                            <p >$get_custom_field->name
                            <span class='pull-right px-4'>";
                $cusoptionval=DB::table('fields_options')
                ->select('fields_options.value')
                ->rightjoin('post_values','fields_options.id','=','post_values.value')
                ->where('fields_options.field_id',$get_custom_field->id)
                ->pluck('fields_options.value')->toArray();
                $val=implode(',', $cusoptionval); 
                $customfields.="$val</span></p></div>";
            }
            if($get_custom_field->type =="file"){
                 $customfields.="";
            }
        return $customfields;    
        }  
    }*/
    public function inactive($id)
    {  
        $check= Ads::where('id',$id)->first();
        if(!empty($check)){
            if($check->status==1){
                $check->status=6;
            }else{
                $check->status=1;
            }
            $check->save();
            toastr()->success('Ads Status Changed successfully!');
            return back();
        }   
    }

    /*public function getaddress(Request $request)
    {
        $input=$request->all();

        
        $latitude = $input['latitude'];
        $longitude = $input['longitude'];
        $geolocation = $latitude.','.$longitude;
        //$request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false';
        $request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4&sensor=false';

        $file_contents = file_get_contents($request);
        $json_decode = json_decode($file_contents);
        //echo '<pre>';print_r( $json_decode );die;
        if(isset($json_decode->results[0])) {
            $response = array();
             //echo '<pre>';print_r( $json_decode->results[0] );die;
            foreach($json_decode->results[0]->address_components as $addressComponet) {
                
                if(in_array('route', $addressComponet->types)){
                        $response['route'] = $addressComponet->long_name;             
                }
                if(in_array('sublocality_level_1', $addressComponet->types)){
                        $response['area'] = $addressComponet->long_name;             
                }else if(in_array('sublocality_level_2', $addressComponet->types)) {
                        $response['area'] = $addressComponet->long_name; 
                }

                // if(in_array('locality', $addressComponet->types) || in_array('administrative_area_level_2', $addressComponet->types)) {
                if(in_array('locality', $addressComponet->types)){
                        $response['city'] = $addressComponet->long_name;             
                }
                
                if(in_array('administrative_area_level_1', $addressComponet->types)) {
                        $response['state'] = $addressComponet->long_name; 
                }
                if(in_array('country', $addressComponet->types)) {
                        $response['country'] = $addressComponet->short_name; 
                }
                // if(in_array('postal_code', $addressComponet->types)) {
                //         $response['postal_code'] = $addressComponet->long_name; 
                // }
            }
        }
        $address = '';
        if(!empty($response)){
            $address = implode(',', $response);
        }
        return $address;
    }*/


    public function getcity(Request $request)
    {
        $input=$request->all();

        $latitude = $input['latitude'];
        $longitude = $input['longitude'];
        $geolocation = $latitude.','.$longitude;
        $request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4&sensor=false';

        $file_contents = file_get_contents($request);
        $json_decode = json_decode($file_contents);
        //echo '<pre>';print_r( $json_decode );die;
        if(isset($json_decode->results[0])) {
            $response = array();
            foreach($json_decode->results[0]->address_components as $addressComponet) {
                //echo '<pre>';print_r( $addressComponet );die;
                // if(in_array('locality', $addressComponet->types)){
                //         $response['city'] = $addressComponet->long_name;             
                // }
                if(in_array('administrative_area_level_2', $addressComponet->types)){
                        $response['city'] = $addressComponet->long_name;             
                }
            }
        }
        $city = array();
        if(!empty($response)){
            $city = $response['city'];
            $cityname = DB::table('cities')->orWhere("cities.name",$city)->first();
            if(!empty($cityname)){
                $details = array('cityName'=>$city,'cityId'=>$cityname->id ,'state'=>$cityname->state_id);
            }else{
               $details = array('cityName'=>$city,'cityId'=>0 ,'state'=>0); 
            }

            //return $cityname->id;
            return $details;
        }else{
            $details = array('cityName'=>"",'cityId'=>0,'state'=>0);
        }
        return $details;
    }

    public function viewedads_new(Request $request){
        $input = $request->all();
        $viewed = ViewedAds::firstOrCreate(['user_id'=>auth()->user()->id,'ads_id'=>$input['ads_id']],['user_id'=>auth()->user()->id,'ads_id'=>$input['ads_id']]);
        $ads = Ads::where('id',$input['ads_id'])->first();
        $setting=Setting::first();
        if(!empty($ads)){
            if($ads->point_expire_date == "" || $ads->point_expire_date == null){
                return "Points Not Found";die;
            }
            $viewcount=Freepoints::where('user_id',auth()->user()->id)->where('ads_id','!=',null)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)->get()->count();
            //echo "<pre>";print_r($viewcount);die;
            if($setting->free_ad_view_count > $viewcount){
                if($setting->ads_view_point <= $ads->point){
                    $pointcheck=Freepoints::where('user_id',auth()->user()->id)->where('ads_id','=',$ads->id)->first();
                    if(!empty($pointcheck)){
                        return "Points already collected";die;
                    }else{
                        $ads->point= $ads->point-$setting->ads_view_point;
                        $ads->save();
                        addviewpoint(auth()->user()->id,'ads_view_point',$ads->id);
                        return "Added Ads point";die;
                    }
                }else{
                   return "Reached Ads Balance";die; 
                }
            }else{
                return "Reached Limit for ads view ";die;
            }
        }else{
           return "Ads Not Found";die; 
        }
    }

    public function viewedads(Request $request){
        $input = $request->all();
        $ads = Ads::where('id',$input['ads_id'])->first();
        $setting=Setting::first();
        //$sameAd=Freepoints::where('user_id',auth()->user()->id)->where('ads_id','=',$ads->id)->whereDate('created_at',Carbon::today())->count();
        //if($sameAd <= 0){
        if(!empty($ads)){
            if($ads->point_expire_date == "" || $ads->point_expire_date == null){
                return "Points Not Found";die;
            }
            //$viewcount=Freepoints::where('user_id',auth()->user()->id)->where('ads_id','!=',null)->whereDate('created_at',Carbon::today())->get()->count();
            $viewpoint=0;
            $todayviewads=Freepoints::where('user_id',auth()->user()->id)->where('ads_id','!=',null)->where('description','Points for View Ads')->whereDate('created_at',Carbon::today())->get();
            foreach ($todayviewads as $key => $view) {
                //return $value;die;
                 $viewpoint=$viewpoint+$view->point;
            }
            //echo '<pre>';print_r( $viewpoint );die;

            if($setting->free_ad_view_count >= $viewpoint){
                if($setting->ads_view_point <= $ads->point){
                    $pointcheck=Freepoints::where('user_id',auth()->user()->id)->where('ads_id','=',$ads->id)->first();
                    if(!empty($pointcheck)){
                        return "Points already collected";die;
                    }else{

                        $viewed = ViewedAds::firstOrCreate(['user_id'=>auth()->user()->id,'ads_id'=>$input['ads_id']],['user_id'=>auth()->user()->id,'ads_id'=>$input['ads_id']]);

                        $ads->point= $ads->point-$setting->ads_view_point;
                        $ads->save();
                        addviewpoint(auth()->user()->id,'ads_view_point',$ads->id);
                        return "Added Ads point";die;
                    }
                }else{
                   return "Reached Ads Balance";die; 
                }
            }else{
                return "Reached View Points";die;
            }
        }else{
           return "Ads Not Found";die; 
        }
    }
    
    public function favouriteads(Request $request){
        $input = $request->all();
        //echo "<pre>";print_r($input);die;
        if(!empty($input['uuid'])){
            $favad = DB::table('favourites')
                    ->leftJoin('ads','favourites.uuid','=','ads.uuid')
                    ->select('favourites.*','ads.id as adsid')
                    ->where('favourites.uuid',$input['uuid'])
                    ->where('favourites.user_id',auth()->user()->id)
                    ->groupby('favourites.id')
                    ->first();
                    //echo "<pre>";print_r($favad);die;
            if(empty($favad)){
                $favourite = Favourite::firstOrCreate(['user_id'=>auth()->user()->id,'ads_id'=>$input['ads_id'],'uuid'=>$input['uuid']],['user_id'=>auth()->user()->id,'ads_id'=>$input['ads_id'],'uuid'=>$input['uuid']]);
                echo "1";die; 
            }else{
                $unfavourite = DB::table('favourites')
                            ->where('user_id',auth()->user()->id)
                            ->where('uuid',$input['uuid'])
                            ->delete();
                echo "2";die;                
            }
        }       
    }

    public function showfads(Request $request)
    {
        $input = $request->all();
        $date = Carbon::now();
        $current_date=$date->toDateTimeString();
        //echo '<pre>';print_r( date('Y-m-d h:s:i') );die;
        $featuedAd = array();
        $featuedAd =  DB::table('ads_features')
                        ->leftJoin('ads', 'ads.id', '=', 'ads_features.ads_id')
                        ->leftJoin('users', 'users.id', '=', 'ads.seller_id')
                        ->leftJoin('ads_image','ads_image.ads_id', '=','ads.id')
                        ->leftJoin('top_ads_plan', 'top_ads_plan.id', '=', 'ads_features.id')
                        ->select('ads.*','ads_image.image','top_ads_plan.uuid as topads')
                        ->where('ads_features.ads_id','!=',null)
                        ->where('ads_features.expire_date','!=',null)
                        ->where('ads_features.expire_date','>=',$current_date)
                        ->where('ads.ads_expire_date','>=',$current_date)
                        ->where('ads.status',1)
                        ->where('users.status',1)
                        ->where('ads_features.id','=',$input['fad'])
                        ->first();

        $featuedbuyedAd =  DB::table('ads_features')->select('ads_features.*')
                    ->leftJoin('users', 'users.id', '=', 'ads_features.user_id')
                    ->where('ads_features.expire_date','>=',$current_date)
                    ->where('ads_features.id','=',$input['fad'])
                    //->where('users.status',1)
                    ->first();
        
        //echo '1<pre>';print_r( $featuedbuyedAd );die;
        // $topplan =  DB::table('top_ads_plan')
        //                 ->select('top_ads_plan.*')
        //                 ->where('top_ads_plan.id','=',$input['fad'])
        //                 ->first();
        $topplan =  DB::table('top_ads_plan')
                        ->select('top_ads_plan.*')
                        ->where('type',1)
                        ->first();
        $favads = array();
        $viewads = array();
        if( Auth::check() && !empty($featuedAd)){
            $favads = DB::table('favourites')
                  ->where('user_id',Auth::user()->id)
                  ->get()->toArray();
            $viewads = DB::table('user_viewed_ads')
              ->where('user_id',Auth::user()->id)->where('ads_id',$featuedAd->id)
              ->get()->toArray();
        }
         
        
        
        $setting=Setting::first();

        $ads = array();
        if(!empty($favads)){
            foreach ($favads as $key => $fad) {
                $ads = $featuedAd;
                if(isset($featuedAd) && $fad->ads_id == $featuedAd->id){
                    $ads->favv = "1";
                }                  
            }
        }else{            
            $ads = $featuedAd;
        }

        if(!empty($viewads)){
            foreach ($viewads as $key => $fad) {
                if(!empty($featuedAd)){
                    $featuedAd->alreadyviwed = "0";
                    if($fad->ads_id == $featuedAd->id){
                        $featuedAd->alreadyviwed = "1";  //viewd
                    }  
                }                  
            }
        }else{
            if(!empty($featuedAd)){
                $featuedAd->alreadyviwed = "0";  
            }                
        }
        
        // if(isset($featuedAd->point) && $setting->ads_view_point <= $featuedAd->point ){
        //     $ads->viewpoint = "1";
        // }

        //echo '<pre>';print_r( $featuedAd );die;

        if(!empty($featuedAd)){
            $showfads = '<div class="thumbnail card">';
                
            if(Auth::check()){
                    if(!empty($featuedAd->favv) && $featuedAd->favv == '1'){
                        $showfads .= '<i class="fa fa-heart heart_wrap favitem"  adsuuid-attr="'.$featuedAd->uuid.'" adsid-attr="'.$featuedAd->id.'" id="favourite-'.$featuedAd->id.'"aria-hidden="true"></i>';
                    }else if($featuedAd->seller_id != auth()->user()->id){
                        $showfads .= '<i class="fa fa-heart-o heart_wrap favitem"  adsuuid-attr="'.$featuedAd->uuid.'" adsid-attr="'.$featuedAd->id.'" id="favourite-'.$featuedAd->id.'"aria-hidden="true"></i>';
                    }

                }else{
                    $showfads .= '<i class="fa fa-heart-o heart_wrap favitem"  adsuuid-attr="'.$featuedAd->uuid.'" adsid-attr="'.$featuedAd->id.'" id="favourite-'.$featuedAd->id.'"aria-hidden="true"></i>';
                }

            $showfads .= '<a href="'.route('adsview.getads',$featuedAd->uuid).'">
                            <div class="img-event">
                                <img class="group list-group-image img-fluid" src="'.asset('public/uploads/ads/'.$featuedAd->image).'" alt="" />';
                
                                
                                //<i class="fa fa-heart-o heart_wrap" aria-hidden="true"></i>

            $showfads .= '</div><div class="ads_status_wrap pending_ad_wrap"><p>Platinum</p></div>
                            <div class="caption card-body">
                                
                                <h4 class="group card-title inner list-group-item-heading">
                                    ₹'.$featuedAd->price.'</h4>
                                <p class="group inner list-group-item-text">
                                    <span>'.ucwords(str_limit($featuedAd->title,18)).'</span><br>
                                    <span>'.strtoupper(get_areaname($featuedAd->area_id,80)).'</span>
                                </p>
                                
                                <div class="row bottom_card_wrap">
                                    <div class="col-xs-12 col-md-6 col-sm-4 pl-0 pr-0">
                                        <p class="day_wrap">
                                            '.time_elapsed_string($featuedAd->approved_date).'</p>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-sm-8 pl-0 pr-0">';
                                        
                                        if(Auth::check()){
                                            if($featuedAd->seller_id != auth()->user()->id){
                                               if($featuedAd->alreadyviwed=='0'  && $featuedAd->point_expire_date > date('Y-m-d H:i:s') && $featuedAd->ads_expire_date > date('Y-m-d H:i:s') && $setting->ads_view_point <= $featuedAd->point && $featuedAd->point != null){
                                                    //$showfads.='<span class="view_earn_wrap">View to Earn</span>';
                                                }
                                            }    
                                        }else{
                                            
                                            if($featuedAd->alreadyviwed=='0' && $featuedAd->point_expire_date > date('Y-m-d H:i:s') && $featuedAd->ads_expire_date > date('Y-m-d H:i:s') && $setting->ads_view_point <= $featuedAd->point && $featuedAd->point != null){
                                                    //$showfads.='<span class="view_earn_wrap">View to Earn</span>';
                                                
                                            }
                                        }

                                        $showfads.='</div>
                                        <p class="day_wrap">
                                            Available From : '.date("d M Y h:i a",strtotime($featuedAd->ads_expire_date)).'</p>
                                </div>
                            </div>
                            </a>
                        </div>';
        }else{
            $showfads = '<div class="thumbnail card">    
                            <div class="no_feature_ads_inner_wrap">
                                <i class="fa fa-free-code-camp" aria-hidden="true"></i>
                                <h3>Limited Ads display in front page hurryup</h3>
                                <div class="common_btn_wrap">';
            
            if(Auth::check()){
                
                if(!empty($featuedbuyedAd)){   
                    if(Auth::user()->id == $featuedbuyedAd->user_id){
                        $showfads .='<a href="'.url("user/ads#promote_content").'">Select my Ad</a>';
                    }else{
                        $showfads .='<a href="javascript:void(0);" onclick="postadspurchased()">Post Ad</a>';
                    }
                }else{
                    $showfads .='<a href="'.route('featureads',[$topplan->uuid,base64_encode($input['fad'])]).'" data-featureId="'.$input['fad'].'">Post Ad</a>';
                }
            }else{
                $showfads .='<a href="javascript:void(0);" onclick="postadslogin()">Post Ad</a>';
            }
            // if(!empty($featuedbuyedAd)){                    
            //     $showfads .='<a href="'.url("user/ads#promote_content").'">Existing Ad Post</a>';
            // }else{
            //     $showfads .='<a href="'.route('featureads',[$topplan->uuid,base64_encode($input['fad'])]).'" data-featureId="'.$input['fad'].'">Post Ad</a>';
            // }
            $showfads .='</div>
                        </div>
                    </div>';     

        }
        return $showfads;
    }

    public function promote(Request $request)
    {   $ads=DB::table('ads')->where('seller_id',Auth::id())->where('status',1)->orderby('id','desc')->get();
        $Plans=PlansPurchase::where('type','!=','0')->where('ads_count','>','0')->whereDate('expire_date','>',date('Y-m-d H:i:s'))->where('user_id',Auth::id())->get();
        $feature=AdsFeatures::where('user_id',Auth::id())->where('ads_id','!=',null)->whereDate('expire_date','>',date('Y-m-d H:i:s'))->get();
        $top=TopLists::where('user_id',Auth::id())->whereDate('expire_date','>',date('Y-m-d H:i:s'))->get();
        $pearls=Pearls::where('user_id',Auth::id())->whereDate('expire_date','>',date('Y-m-d H:i:s'))->get();
        return view('ads.useradspromote',compact('ads','Plans','feature','top','pearls'));
    }
    public function promotesave(Request $request)
    {   
        $input = $request->all();
        $request->validate([
            'ads'          =>'required',
            'plan'     => 'required',
        ]);
        $ads=DB::table('ads')->where('seller_id',Auth::id())->where('uuid',$input['ads'])->where('status',1)->orderby('id','desc')->first();
        if(!empty($ads)){
            $plans=PlansPurchase::where('id',$input['plan'])->where('ads_count','>','0')->whereDate('expire_date','>',date('Y-m-d H:i:s'))->first();
            //echo "<pre>";print_r($ads);die;
            if(!empty($plans)){
                if($plans->type==1){
                    $featured=AdsFeatures::where('id',$plans->feature_plan_id)->first();
                    $featured->user_id=Auth::id();
                    $featured->ads_id=$ads->id;
                    $featured->expire_date=$plans->expire_date;
                    $featured->save();
                    $plans->ads_count=$plans->ads_count-1;
                    $plans->save();
                    $featurelist= FeatureadsLists::Create([
                        'ads_id' => $ads->id,
                        'user_id' => Auth::id(),
                        'plan_id' => $plans->id,
                    ]);
                    $verify=Verification::where('user_id',Auth::id())->where('verified_id',5)->whereYear('created_at', date("Y"))->whereMonth('created_at', date("m"))->get()->count();
                    if($verify<3){
                        addpoint($ads->seller_id,'feature_ads_point',$ads->id);
                    }


                    $adsupdate=Ads::where('id',$ads->id)->first();
                    $adsupdate->ads_expire_date=$plans->expire_date;
                    $adsupdate->point_expire_date=$plans->expire_date;
                    $adsupdate->save();


                }elseif ($plans->type==2) {
                    $top=TopLists::Create([
                        'ads_id' => $ads->id,
                        'user_id' => Auth::id(),
                        'plan_id' => $plans->id,
                        'expire_date'=>$plans->expire_date,
                    ]);
                    $plans->ads_count=$plans->ads_count-1;
                    $plans->save();


                    $adsupdateexpireDate=Ads::where('id',$ads->id)->first();

                    if(strtotime($adsupdateexpireDate['ads_expire_date']) < strtotime($plans->expire_date)){
                        $adsupdateexpireDate->ads_expire_date=$plans->expire_date;
                        $adsupdateexpireDate->point_expire_date=$plans->expire_date;
                        $adsupdateexpireDate->save();
                    }





                }else{
                    $top=Pearls::Create([
                        'ads_id' => $ads->id,
                        'user_id' => Auth::id(),
                        'category_id' => $ads->categories,
                        'expire_date'=>$plans->expire_date,
                    ]);
                    $plans->ads_count=$plans->ads_count-1;
                    $plans->save();
                }
                toastr()->success('Ads Promoted');
                return back();
            }else{
                toastr()->warning('Plan Not Found');
                return back();
            }
        }else{
            toastr()->warning('Ads Not Found');
            return back();;
        }
    }
    public function adsreviews(Request $request)
    {
        $input = $request->all();
        //echo "<pre>";print_r($input);die;
        AdsReview::create(['ads_id'=>getAdsId($input['id']),'user_id'=>Auth::user()->id,'review'=>$input['reviewMessage']]);
        toastr()->success('Successfully reviewed !');
        return redirect()->route('adsview.getads',$input['id']);
    }
    public function adspostPrevent()
    {   if (session()->has('adsid')) {
            $ads_id =session()->get('adsid');
        }else{
            return redirect()->route('ads.post');
        }
        $ads= DB::table('ads_temp')->where('id',$ads_id)->first();
        if(!empty($ads)){
            
            $sub=DB::table("sub_categories")->where('id',$ads->sub_categories)->first();
            $parent = DB::table("parent_categories")->where('id',$sub->parent_id)->first();
            $adsimage= DB::table('ads_image_temp')->select('uuid','image')->where('ads_id',$ads->id)->get();
            
            foreach ($adsimage as $key => $value) {
                $ads_image['id']=$value->uuid;
                $ads_image['src']=url('public/uploads/ads/').'/'.$value->image;
                $images[$key]=$ads_image;
            }
            $customfields='';
            $get_category_field=DB::table('category_field')->where('category_id',$sub->id)->orderBy('field_id', 'ASC')->get();
                if(!empty($get_category_field)){
                    //echo"<pre>";print_r($get_category_field);die;
                    foreach ($get_category_field as $key => $field) {
                        //echo"<pre>";print_r($field->field_id);die;
                        $customfields.=$this->geteditfieldHtml($field->field_id,$ads->id,2);//1=edit,2,prevent
                                    
                    }
                }
            $brands= DB::table('category_brands')->where('sub_cate_id',$sub->id)->get();
            $category_models= DB::table('category_models')->where('sub_cate_id',$sub->id)->get();
            //echo"<pre>";print_r($category_models);die;
            $ads->images=json_encode($images);
            //return json_encode($images);
            $getfulladress = getfulladress($ads->area_id);
            //echo '<pre>';print_r( $getfulladress );die;
            $stateid= DB::table('cities')->select('state_id')->where('id',$ads->cities)->first();
            //echo '<pre>';print_r( $stateid );die;
            $states= DB::table('states')->where('status','1')->get();
            return view('ads.preventads',compact('parent','states','stateid','ads','sub','customfields','getfulladress','brands','category_models'));

        }else{
            toastr()->error(' Data not found');
            return back();
        }
        
    }
    public function preventadsupdate(Request $request)
    {   
        $input=$request->all();
        //echo "<pre>";print_r($input);die;
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
        $request->validate([
            'id'          =>'required',
            /*'category'          =>'required',
            'sub-category'      =>'required',
            */'description'       =>'required',
            //'city_name'         =>'required',
            'title'             =>'required',
            'price'             =>'required',
            //'photos'           =>"required|array|min:1|max:5",
            'photos.*'           =>'image|mimes:jpeg,jpg,png|max:2048',
            ]);
        /*$image = $request->file('image');
        echo "<pre>";print_r($image);die;*/
        $uuid = Uuid::generate(4);
        if(!empty($input['information']))
        {   
            if($input['information'] == 'on'){
                $input['information']=1;
            }
            
        }else{
            $input['information']=0;
        }
        /*if(empty($request->get('hidden-tags')))
        {
             toastr()->error(' Tags Empty!');
            return back();
        }*/
        if(!isset($input['old'])){
            if(!isset($input['photos'])){
                toastr()->error(' Image Empty!');
                return back()->withInput(); 
            }
        }
        /*if($input['city_name'] == "-1"){
            if(empty($input['not_in_list'])){
                toastr()->error('cities Empty');
                return back();
            } 
        }*/
        $ads= AdsTemp::where('uuid',$input['id'])->first();
        //echo "<pre>";print_r($ads);die;
        if(empty($ads)){
                toastr()->error('Ads Not Found');
                return back();
        }

        /* custom fields validation */
        $cat_fields = DB::table("category_field")->select('field_id')->where('category_id',$ads->sub_categories)->get();
        //echo "<pre>";print_r($cat_fields);die;
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
        
        //previous image delete
        $adsimage=DB::table('ads_image_temp')->select('uuid')->where('ads_id',$ads->id)->get();
        foreach ($adsimage as $key => $value) {

            $imagedata[$key]=$value->uuid;
        }
        
        $adsimageintersect=array();
        if(!empty($request->get('old')))
        {
            $adsimageintersect=array_intersect($imagedata,$input['old']);
            
        }
        //$adsimageintersect=array_intersect($imagedata,$input['old']);
        if(!empty($adsimageintersect))
        {   
            $adsimage=DB::table('ads_image_temp')->where('ads_id',$ads->id)->whereNotIn('uuid',$adsimageintersect)->get();
            
            if(!empty($adsimage)){
                
                foreach ($adsimage as $key => $value) {
                    //echo "<pre>123";print_r($value);die;
                    if(!empty($value->image) && file_exists(public_path('/uploads/ads/'.$value->image)))
                    {
                        $url='public/uploads/ads/';
                        //echo "<pre>1";print_r($url);die;
                        unlink($url.$value->image);
                    }
                    //echo "<pre>2";print_r($value);die;
                    //Adsimage::destroy($value->id);
                    $deleteimage= AdsimageTemp::find($value->id);
                    $deleteimage->delete();
                }
            }
        }else{

            $adsimage=DB::table('ads_image_temp')->where('ads_id',$ads->id)->get();
            if(!empty($adsimage)){
                
                foreach ($adsimage as $key => $value) {
                    //echo "<pre>123";print_r($value);die;
                    if(!empty($value->image) && file_exists(public_path('/uploads/ads/'.$value->image)))
                    {
                        $url='public/uploads/ads/';
                        //echo "<pre>1";print_r($url);die;
                        unlink($url.$value->image);
                    }
                    //echo "<pre>2";print_r($value);die;
                    //Adsimage::destroy($value->id);
                    $deleteimage= Adsimage::find($value->id);
                    $deleteimage->delete();
                }
            }

        }
        $latitude = $input['latitude'];
        $longitude = $input['longitude'];
        //$getaddress = getaddress($latitude,$longitude);
        //ads update
        if(!empty($ads))
        {
            $ads->title = $input['title'];
            $ads->price = $input['price'];
            $ads->description = $input['description'];
            $ads->seller_information = $input['information'];
            $ads->phone_no=$input['sell-phone'];
            $ads->tags = null;
            $ads->cities = $input['cities'];
            $ads->area_id = $input['areas'];
            $ads->pincode = $input['pincode'];
            $ads->brand_id = (isset($input['brands'])?$input['brands']:null);
            $ads->model_id = (isset($input['models'])?$input['models']:null);
            $ads->latitude = $input['latitude'];
            $ads->longitude = $input['longitude'];
            $ads->reason = "";
            $ads->status = 0;
            $ads->save();
            if (!empty($request->file('photos'))) {
                $image = $request->file('photos');
                foreach ($image as  $files) {
                    $random = Uuid::generate(4);
                    $imgname = time().$random.'.'.$files->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/ads/');
                    $img = Image::make($files->getRealPath());
                    /* insert watermark at bottom-right corner with 10px offset */
                    $img->insert(public_path('img/ojaak_watermark.png'), 'bottom-right', 35, 35);
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
            $fields = DB::table("category_field")->where('category_id',$ads->sub_categories)->get();
                    if ($fields->count() > 0) {
                        $newPostValue =PostValuesTemp::where('post_id',$ads->id)->delete();
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
            // if (session()->has('adsid')) {
            //     session()->forget('adsid');
            // }else{
            //     session()->put('adsid', $ads->id);
            // }
            // if (session()->has('catesid')) {
            //     session()->forget('catesid');
            // }else{
            //     session()->put('catesid', $ads->categories);
            // }  
            session()->put('adsid', $ads->id);
            session()->put('catesid', $ads->categories);
            /*if (session()->has('adsid')) {
                session()->put('adsid', $ads->id);
            }
            if (session()->has('catesid')) {
               session()->put('catesid', $ads->categories);
            }     */ 
            toastr()->success('Ads updated successfully!');
            return redirect()->route('ads.choosePlanPost');

        }else{
            toastr()->error(' Invalid Ads!');
            return back();
        }
        
    }

    public function preventadsReset(Request $request)
    {
        $input=$request->all();
        //echo "<pre>";print_r($input);die;
        $ads= DB::table('ads_temp')->where('uuid',$input['preventadsid'])->first();
        if(!empty($ads)){
            $adsimage=DB::table('ads_image_temp')->where('ads_id',$ads->id)->get();
            if(!empty($adsimage)){
                
                foreach ($adsimage as $key => $value) {
                    //echo "<pre>123";print_r($value);die;
                    if(!empty($value->image) && file_exists(public_path('/uploads/ads/'.$value->image)))
                    {
                        $url='public/uploads/ads/';
                        //echo "<pre>1";print_r($url);die;
                        unlink($url.$value->image);
                    }
                    
                }
               
            }
            DB::table('ads_image_temp')->where('ads_id',$ads->id)->delete();
            DB::table('post_values_temp')->where('post_id',$ads->id)->delete();
            DB::table('ads_temp')->where('id',$ads->id)->delete();
            
            if (session()->has('adsid')) {
                session()->forget('adsid');
            }
            if (session()->has('catesid')) {
                session()->forget('catesid');
            }

            return redirect()->route('welcome');
        }else{
            toastr()->error(' Error Occoured. Please try again !');
            return back();
        }
    }



    public function makeAnOffer(Request $request)
    {
        $input=$request->all();
        $uuid = Uuid::generate(4);

        $viewed = Makeanoffers::Create([
            'uuid' => $uuid,
            'user_id' => Auth::id(),
            'adsid' => $input['make_off_ads_id'],
            'amount' => $input['makeAnOffer'],
        ]);


        $adscheck=Ads::where('id',$input['make_off_ads_id'])->first();

        if(!empty($adscheck)){
            $unique_chats_id=Auth::user()->id."_".$adscheck->seller_id."_".$adscheck->id;
            $ulternative_unique_chats_id=$adscheck->seller_id."_".Auth::user()->id."_".$adscheck->id;
            
            $chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
            
            if(empty($chat)){
                $chat= Chats::firstOrCreate([
                    'unique_chats_id' => $unique_chats_id,
                    'ads_id' =>$adscheck->id,
                    'user_1'=>Auth::user()->id,
                    'user_2'=>$adscheck->seller_id,
                ]);
            }
        }

        $chatcreate= ChatsMessage::Create([
                'chat_id' => $chat->unique_chats_id,
                'image' =>null,
                'user_id'=>Auth::user()->id,
                'status'=>1,
                'read_status'=>0,
                'msg'=>"Your Offer : ".$input['makeAnOffer']
                ]);
    }

    public function buyPremium($adUuid)
    {
        if(Auth::check()){

            PlansPurchase::where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->where('plan_id',1)->delete();
            $ads=DB::table('ads')->where('uuid',$adUuid)->first();
            if(!empty($ads)){

                $cateid = $ads->categories;
                $plansPreferCategory=plancategorychoose($cateid);
                //echo '<pre>';print_r($plansPreferCategory);die;

                $nowdate = new DateTime('now');
                $nowdate = $nowdate->format('Y-m-d h:i:s');
                $plans=PlansPurchase::where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->whereDate('expire_date','>',$nowdate)->whereIN('plan_id',$plansPreferCategory)->orderBy('id','desc')->get();
                $freeplans=Premiumadsplan::where('status',1)->whereIN('id',$plansPreferCategory)->get();
                $plansdetails=Premiumplansdetails::get();
                $settings = Setting::first();
                $ads_view_point = $settings->ads_view_point;

                return view('plan.chooseplanforplatinam',compact('plans','freeplans','plansdetails','ads_view_point','settings','adUuid'));
            }else{
                toastr()->error('Something went wrong!.');
                return back();
            }
        }else{
            toastr()->error('Login to continue');
            return redirect('/');
        }
    }

    
    public function availableplatinamAd(Request $request){
        
        $date = Carbon::now();
        $current_date=$date->toDateTimeString();
        $featuedAd =  DB::table('ads_features')
                    ->select('ads_features.*')
                    ->where('ads_features.expire_date','<=',$current_date)
                    ->first();  

        $topplan =  DB::table('top_ads_plan')
                        ->select('top_ads_plan.*')
                        ->where('type',1)
                        ->first();

        if(!empty($featuedAd)){
            return redirect()->route('featureads',[$topplan->uuid,base64_encode($featuedAd->id)]);
        }else{
            toastr()->error('All platinam ads are sold');
            return back();
        }
    }

}
