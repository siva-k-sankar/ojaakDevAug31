<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\EmailUpdate;
use App\Mail\WorkEmailUpdate;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Parent_categories;
use App\Sub_categories;
use App\Setting;
use App\Customfield;
use App\Customfield_Options;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;
use Auth;
use Hash;
use Image;

class HomeController extends Controller
{
   	public $successStatus = 200;
    //public $adPath = url('public/uploads/ads/');
   	public function __construct()
    {
    	$this->middleware('auth');
	}
	public function home()
    {   
        $categories =   Parent_categories::select('id','uuid','name','icon')
                        ->where('status',1)
                        ->get()->toArray();
        $featuedAd  =   DB::table('ads_features')->get()->toArray();
        $homeAd = array();
        if(!empty($featuedAd)){
            foreach ($featuedAd as $key => $fad) {
                if(!empty($fad->ads_id)){
                    //$homeAd[] = $fad->ads_id;
                    $aditems = DB::table('ads')
                        ->leftJoin('ads_image', 'ads_image.ads_id', '=', 'ads.id')
                        ->where('ads.status',"1")
                        ->where('ads.id',$fad->ads_id)
                        ->select('ads.*','ads_image.id as adsimgid','ads_image.image')
                        ->groupBy("ads.id")
                        ->first();
                    $homeAd[] = $aditems;

                }else{
                    $json=json_decode('{}');
                    $homeAd[] = $json;
                }
            }
        }
        if(!empty($categories)){
            return response()->json(['success'=>true,'categories'=>$categories,'featureAd'=>$homeAd,'ad_image_path'=>url('public/uploads/ads/')], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'message'=>'No Categories Found'], $this->successStatus);            
        }
    }

    public function aditem($auuid)
    {   
         $ads= DB::table('ads')
               ->select('ads.*','users.id as user_id','users.created_at as user_created_at','users.photo as photo','favourites.uuid as fav_uuid','ads_features.expire_date as featureadsexp')
               ->leftJoin('users','ads.seller_id','=','users.id')
               ->leftjoin('favourites','favourites.ads_id','=','ads.id')
               ->leftjoin('ads_features','ads_features.ads_id','=','ads.id')
               ->where('ads.uuid',$auuid)
               ->where('ads.status',"1")
               ->first();
                //echo "<pre>";print_r($ads);die;
        if(!empty($ads)){
            $setting=Setting::first();
            if($setting->ads_view_point <= $ads->point){
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
            ->where('ads.uuid','!=',$auuid)
            ->groupBy("ads.id")
            ->orderby('ads.id', 'desc')
            ->offset(0)->limit(6)->get();
            $favads = array();
            if(Auth::check()){
                $favads = DB::table('favourites')
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
            //echo"<pre>";print_r($relatedads);die;
            $adsimg = DB::table('ads_image')->where('ads_id',$ads->id)->get();
            $users = Auth::user();
            $date = new Carbon( $ads->user_created_at);
            $date->format('Ymd');
            $year= $date->year;
            $month = date("F", mktime(0, 0, 0,$date->month , 10));
            /*$customfields='';
            $get_category_field=DB::table('category_field')->where('category_id',$ads->sub_categories)->orderBy('field_id', 'ASC')->get();
            if(!empty($get_category_field)){
                foreach ($get_category_field as $key => $field) {
                    //echo"<pre>";print_r($field->field_id);die;
                    $customfields.=$this->getDataView($field->field_id,$ads->id);
                }
            }*/
             
        $posted = array('year'=>$year,'month'=>$month);

            return response()->json(['success'=>true,'data'=>$ads,'adsimg'=>$adsimg,'seller'=>$users,'relatedads'=>$relatedads,'posted'=>$posted,'ad_image_path'=>url('public/uploads/ads/')], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'message'=>'Ads does not exist !'], $this->successStatus);            
        }
    }

    public function getDataView($fields,$postid){
        $customfields="";
        $get_custom_field=Customfield::where('id',$fields)->where('active',1)->first(); 
        $cusval=DB::table('post_values')->where('field_id',$get_custom_field->id)->where('post_id',$postid)->first();
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


    public function getads(Request $request){

        $input = $request->all();
        $pricerange = explode(';',$input['price']);
        $location=$input['state']; 
        $ads = DB::table('ads')
                    ->select('ads.*','ads_image.id as adsimgid','ads_image.image','users.photo','users.uuid as uuuid','cities.name as cityname','favourites.uuid as fav_uuid')
                    ->leftJoin('users','ads.seller_id','=','users.id')
                    ->leftjoin('favourites','favourites.ads_id','=','ads.id')
                    ->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')
                    ->leftJoin('post_values', 'ads.id', '=', 'post_values.post_id')
                    ->leftjoin('cities', function($leftJoin){
                        $leftJoin->on('ads.cities', '=', 'cities.id');
                    })
                    ->where(function($query) use($pricerange,$location) {
                        $query->WhereRaw("price between $pricerange[0] and $pricerange[1]");

                       if( $location != '0'){
                            $query->Where('cities.state_id',$location);
                        } 
                    });
                    
        if($input['sortingfilters'] == '0'){
            $ads =$ads->orderBy('ads.approved_date','desc');
        }

        if($input['sortingfilters'] == '1'){
            $ads = $ads->orderBy('ads.price','asc');
        }
        if(!empty($adsIDD)){
            $ads = $ads->whereIn('post_values.post_id', $adsIDD);
        }

        if($input['sortingfilters'] == '2'){
            $ads =$ads->orderBy('ads.id','desc');
        }  

        if($input['subcate'] != '0'){
            $ads =$ads->where('ads.sub_categories', $input['subcate']);
        } 

        $ads->where('users.status', 1)->where('ads.status', 1);

        $ads->groupBy("ads.id")
                    ->offset($input['row'])
                    ->limit(10);
        $adsf = $ads->get();
        if(!empty($adsf)){
         return response()->json(['success'=>true,'data'=>$adsf,'ad_image_path'=>url('public/uploads/ads/')], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'message'=>'Ads does not exist !'], $this->successStatus);            
        }
        //echo "<pre>";print_r($adsf);die;
        /*$category = DB::table('ads')
                        ->leftJoin('parent_categories','ads.categories','=','parent_categories.id')
                        ->select('ads.categories','ads.sub_categories', DB::raw('count(ads.categories) as total'),'parent_categories.name as cate_name')
                        ->limit(5)
                        ->where('ads.status',1)
                        ->orderBy('total','desc')
                        ->groupBy('ads.categories')
                        ->groupBy('ads.sub_categories')
                        ->get()->toArray();
             $catelist=array(); 
        $location = DB::table('ads')
                        ->leftJoin('cities','ads.cities','=','cities.id')
                        ->leftJoin('states','states.id','=','cities.state_id')
                        ->select('cities.id','ads.cities', DB::raw('count(ads.cities) as total'),'states.name as state_name','states.id as state_id')
                        ->where('ads.status',1)
                        //->groupBy('ads.cities')
                        ->groupBy('states.id')
                        ->orderBy('ads.cities','asc')
                        //->limit(1)
                        ->get()->toArray();
        //$location = array();
        $price=array('min'=>'0','max'=>'4000000');*/
                        //echo "<pre>";print_r($category);die;

        //return response()->json(['success'=>true,'category'=>$category,'location'=>$location,'price'=>$price,'ad_image_path'=>url('public/uploads/ads/')], $this->successStatus);

        
    }


    public function categories($cuuid = '')
    {
        if($cuuid !=''){
            $categoriess =Parent_categories::where('status',1)->where('uuid',$cuuid)->first();
            $sub_cate =  Sub_categories::where('status',1)->where('parent_id',$categoriess->id)->get();
        }else{
            $sub_cate=Parent_categories::where('status',1)->get();
        }

        if(!empty($sub_cate)){
         return response()->json(['success'=>true,'data'=>$sub_cate], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'message'=>'Categories does not exist!'], $this->successStatus);            
        }

    }

}
