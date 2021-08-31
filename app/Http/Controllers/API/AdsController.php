<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\EmailUpdate;
use App\Mail\WorkEmailUpdate;
use Illuminate\Support\Facades\Mail;
use App\User;
use Auth;
use Image;
use Validator;
use DateTime;
use App\Sub_categories;
use App\Parent_categories;
use App\PlansPurchase;
use DB;
use App\Customfield;
use App\Customfield_Options;
use App\Category_field;
use App\PostValues;
use Uuid;
use App\Ads;
use App\Favourite;
use App\Setting;

class AdsController extends Controller
{
   	public $successStatus = 200;
   	public function __construct()
    {
    	$this->middleware('auth');
	}
	public function ad_post(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'category'          =>'required',
            'sub_category'      =>'required',
            'description'       =>'required',
            'title'             =>'required',
            'price'             =>'required',
            'latitude'             =>'required',
            'longitude'             =>'required',
            //'images'           =>"required|array|min:1|max:5",
            //'images.*'           =>'image|mimes:jpeg,jpg,png|max:2048',
        ]);
        if ($validator->fails()) {     
            $message = $validator->errors();
            return response()->json(['success' => false, 'messages' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);      
        }

        $uuid = Uuid::generate(4);
        
        $subcategories =  DB::table("sub_categories")->where('uuid',$input['sub_category'])->first();
        if(empty($subcategories)){
            return response()->json(['success'=>false,'message'=>'Categories does not exist!'], $this->successStatus); 
        }

        $cat_fields = DB::table("category_field")->select('field_id')->where('category_id',$subcategories->id)->get();
        if(!empty($cat_fields))
        {
            $ids=array();
            $i=array();
            foreach ($cat_fields as $key => $value) {
                $ids[$key]=$value->field_id;
            }
            foreach ($input['cf'] as $key => $value) {
                if(empty($value)){
                    
                }else{
                    $i[$key]=$key;
                }
                
            }
            $cffields = DB::table("fields")->whereIn('id',$ids)->whereNotIn('id',$i)->get();
            if ($cffields->count() > 0) {
                foreach ($cffields as $key1 => $cffield) {
                    if ($cffield->required == 1) {
                        $messages['cf.' . $cffield->id . '.required'] = __('The :field is required.', ['field' => mb_strtolower($cffield->name)]);
                    }
                }
                return response()->json(['success'=>false,'message'=>$messages], $this->successStatus); 
                //return back()->withInput()->withErrors($messages);
            }
        }
        /*if(!isset($input['images']))
        {
            return response()->json(['success'=>false,'message'=>'Upload Image'], $this->successStatus); 
        }*/
        $user =  DB::table("users")->where('id',Auth::id())->first();


        $latitude = $input['latitude'];
        $longitude = $input['longitude'];

        /*$getaddress = getaddress($latitude,$longitude);
        if($getaddress == 0){         
            return response()->json(['success'=>false,'message'=>'Please Select Valid Address'], $this->successStatus); 
        }*/

        if(!empty($user)){


            //echo "<pre>123 ";print_r($user);die;
            $now = now();
            
            $ads =  new Ads;
            $ads->uuid = $uuid;
            $ads->categories = $subcategories->parent_id;
            $ads->sub_categories = $subcategories->id;
            $ads->title = $input['title'];
            $ads->cities = (isset($getaddress['city_id'])?$getaddress['city_id']:2);
            $ads->area_id = (isset($getaddress['area_id'])?$getaddress['area_id']:9);
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

            
            /*if (!empty($request->file('images'))) {
                $image = $request->file('images');
                foreach ($image as  $files) {
                    $random = Uuid::generate(4);
                    $imgname = time().$random.'.'.$files->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/ads/');
                    $img = Image::make($files->getRealPath());
                    // insert watermark at bottom-right corner with 10px offset 
                    $img->insert(public_path('img/ojaak_watermark.png'), 'bottom-right', 10, 10);
                    // insert watermark at bottom-right corner with 10px offset 
                    $img->resize(500, null, function ($constraint) { $constraint->aspectRatio(); });
                    $img->save($destinationPath.'/'.$imgname);
                    $adsimage =  new AdsimageTemp;
                    $adsimage->ads_id = $ads->id;
                    $adsimage->uuid = $random;
                    $adsimage->image = $imgname;
                    $adsimage->save();
                }
            }*/
            

            $fields = DB::table("category_field")->where('category_id',$subcategories->id)->get();
                    if ($fields->count() > 0) {
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

            return response()->json(['success'=>true,'message'=>"Ads successfully posted"], $this->successStatus); 
            
        }else{
            return response()->json(['success'=>false,'message'=>"Seller Credentials Mismatch"], $this->successStatus); 
        }



    }

    public function getextraattributes($subCateuuid)
    {   
        $customfieldss = array();
        $nowdate = new DateTime('now');
        $nowdate = $nowdate->format('Y-m-d h:i:s');
        $sub=Sub_categories::where('uuid',$subCateuuid)->first();
        //echo"<pre>";print_r($sub);die;
        if(!empty($sub)){
            $parent=Parent_categories::where('id',$sub->parent_id)->first();
            $plans=PlansPurchase::where('user_id',Auth::user()->id)->where('ads_count','!=',0)->where('type','0')->whereDate('expire_date','>',$nowdate)->get();
            //echo"<pre>";print_r($plans);die;
            if(!empty($parent)){
                $get_category_field=DB::table('category_field')->where('category_id',$sub->id)->orderBy('field_id', 'ASC')->get();


                if(!empty($get_category_field)){
                    foreach ($get_category_field as $key => $field) {
                        //echo"<pre>";print_r($field->field_id);die;
                        $customfieldss[] = $this->getfieldHtml($field->field_id);
                                    
                    }
                    return response()->json(['success'=>true,'data'=>$customfieldss], $this->successStatus);
                }
                
            }else{
                return response()->json(['success'=>false,'message'=>'Categories does not exist!'], $this->successStatus); 
            }

        }else{
            return response()->json(['success'=>false,'message'=>'Categories does not exist!'], $this->successStatus); 
        }
    }


    public function getfieldHtml($fields){
        $customfields = array();
        $get_custom_field=Customfield::where('id',$fields)->where('active',1)->first(); 
        //echo"<pre>";print_r($get_custom_field);die;
         
        if(!empty($get_custom_field->type)){

            if($get_custom_field->required == 1){
                $sub="*";
            }else{
                $sub="";
            }

            if($get_custom_field->type =="text"){

                $customfields['type'] = $get_custom_field->type;
                $customfields['name'] = 'cf['.$get_custom_field->id.']';
                $customfields['label'] = $get_custom_field->name;
                $customfields['max'] = $get_custom_field->max;


            }
            if($get_custom_field->type == "select"){
                $customfields['type'] = $get_custom_field->type;
                $customfields['name'] = 'cf['.$get_custom_field->id.']';
                $customfields['label'] = $get_custom_field->name;
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {
                        
                    $customfieldsoptionvalues[]=$option->value;
                                    
                } 

                $customfields['values'] = $customfieldsoptionvalues;
            }

            if($get_custom_field->type =="checkbox"){

                $customfields['type'] = $get_custom_field->type;
                $customfields['name'] = 'cf['.$get_custom_field->id.']';
                $customfields['label'] = $get_custom_field->name;
                //$customfields['max'] = $get_custom_field->max;
                $customfields['value'] = $get_custom_field->default;

            }
            if($get_custom_field->type == "radio"){
                $customfields['type'] = $get_custom_field->type;
                $customfields['name'] = 'cf['.$get_custom_field->id.']';
                $customfields['label'] = $get_custom_field->name;
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {
                        
                    $customfieldsoptionvalues[]=$option->value;
                                    
                } 

                $customfields['values'] = $customfieldsoptionvalues;

            }

            if($get_custom_field->type == "checkbox_multiple"){
                $customfields['type'] = $get_custom_field->type;
                $customfields['name'] = 'cf['.$get_custom_field->id.']';
                $customfields['label'] = $get_custom_field->name;
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {
                        
                    $customfieldsoptionvalues[]=$option->value;
                                    
                } 

                $customfields['values'] = $customfieldsoptionvalues;
            }
        //echo"<pre>";print_r($customfields);die;
        return $customfields;    
        }  

    }

    public function myactiveads(Request $request){
        
        //$ads=DB::table('ads')->where('seller_id',Auth::id())->where('status',1)->orderby('id','desc')->get();

        $ads=DB::table('ads')
        ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id','ads_features.expire_date as featureadsexp')->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id') ->leftjoin('ads_features','ads_features.ads_id','=','ads.id')->whereIn('status',[0,1])->where('seller_id',Auth::id())->orderby('id','desc')->groupBy("ads.id")->get();

        if(!empty($ads)){
         return response()->json(['success'=>true,'data'=>$ads,'ad_image_path'=>url('public/uploads/ads/')], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'message'=>'Ads does not exist !'], $this->successStatus);            
        }
        
    }



    public function mysoldads(Request $request){
        
        //$ads=DB::table('ads')->where('seller_id',Auth::id())->where('status',1)->orderby('id','desc')->get();

        $ads=DB::table('ads')
        ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id')->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')->where('status','3')->where('seller_id',Auth::id())->orderby('id','desc')->groupBy("ads.id")->get();

        if(!empty($ads)){
         return response()->json(['success'=>true,'data'=>$ads,'ad_image_path'=>url('public/uploads/ads/')], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'message'=>'Ads does not exist !'], $this->successStatus);            
        }
        
    }

    

    public function myfavads(Request $request){
        $ads = DB::table('ads')
                ->leftjoin('favourites', 'favourites.ads_id', '=', 'ads.id')->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')
                ->leftjoin('ads_features','ads_features.ads_id','=','ads.id')
                ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id','ads_features.expire_date as featureadsexp')
                ->where('favourites.user_id',Auth::id())
                ->groupby('ads.id')
                ->where('ads.status','1')
                ->get();

        if(!empty($ads)){
         return response()->json(['success'=>true,'data'=>$ads,'ad_image_path'=>url('public/uploads/ads/')], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'message'=>'Ads does not exist !'], $this->successStatus);            
        }
        
    }

    

    public function myinactiveads(Request $request){
        
        $ads=DB::table('ads')
        ->select('ads.*','ads_image.id as adsimgid','ads_image.image','ads_image.ads_id as ads_id')->leftJoin('ads_image', 'ads.id', '=', 'ads_image.ads_id')->whereIn('status',[2,4,6])->where('seller_id',Auth::id())->orderby('id','desc')->groupBy("ads.id")->get();

        if(!empty($ads)){
         return response()->json(['success'=>true,'data'=>$ads,'ad_image_path'=>url('public/uploads/ads/')], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'message'=>'Ads does not exist !'], $this->successStatus);            
        }
        
    }

    public function favouriteads(Request $request){
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'ad_uuid'          =>'required',
            'ads_id'          =>'required',
            'fav'          =>'required',
        ]);
        if ($validator->fails()) {     
            $message = $validator->errors();
            return response()->json(['success' => false, 'messages' => 'The given data was invalid.', 'errors' => $validator->errors()], 422);      
        }

        if(!empty($input['ad_uuid'])){
            /*$favad = DB::table('favourites')
                    ->leftJoin('ads','favourites.uuid','=','ads.uuid')
                    ->select('favourites.*','ads.id as adsid')
                    ->where('favourites.ads_id',$input['ad_uuid'])
                    ->where('favourites.user_id',auth()->user()->id)
                    ->groupby('favourites.id')
                    ->first();*/
            if($input['fav'] == '1'){
                $favourite = Favourite::firstOrCreate(['user_id'=>auth()->user()->id,'ads_id'=>$input['ads_id'],'uuid'=>$input['ad_uuid']],['user_id'=>auth()->user()->id,'ads_id'=>$input['ads_id'],'uuid'=>$input['ad_uuid']]);
                return response()->json(['success'=>true,'messages'=>"Ads favorited successfully"], $this->successStatus);
            }else{
                $unfavourite = DB::table('favourites')
                            ->where('user_id',auth()->user()->id)
                            ->where('uuid',$input['ad_uuid'])
                            ->delete();
                return response()->json(['success'=>true,'messages'=>"Ads favorite removed successfully"], $this->successStatus);     
            }
        }       
    }


    public function search(Request $request)
    {
        $input = $request->all();
        //echo "<pre>";print_r($input);die;
        $setting=Setting::first();
        $pricerange = explode(';',$input['price']);
        $location=$input['state']; 

        $adsIDD = array();

        if(!empty($input['customFilters'])){
            $customfieldfilters = explode('&',$input['customFilters']);
            $filterss = array_filter(array_unique($customfieldfilters));
            $cusfvalues = array();
            foreach ($filterss as $key => $cffvla) {
                $customfieldfiltersValues = explode('=',$cffvla);
                if($customfieldfiltersValues[0] != '6'){
                    $cusfval =DB::table('post_values')->select('post_id')->where('field_id',$customfieldfiltersValues[0])->where('value',$customfieldfiltersValues[1])->get();
                    $convertjson = json_decode(json_encode($cusfval),true);
                    $cusfvalues[] = array_column($convertjson, "post_id");
                }else{
                    $finval = explode(',',$customfieldfiltersValues[1]);

                    $cusfval =DB::table('post_values')->select('post_id')->where('field_id',$customfieldfiltersValues[0])->whereBetween('value', $finval)->get();
                    $convertjson = json_decode(json_encode($cusfval),true);
                    $cusfvalues[] = array_column($convertjson, "post_id");             
                }
            }
            $singlearr = array_reduce($cusfvalues, 'array_merge', array());
            $adsIDD = array_unique($singlearr);
        }
   

        
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
            $ads =$ads->orderBy('ads.price','desc');
        }  

        if($input['subcate'] != '0'){
            $ads =$ads->where('ads.sub_categories', $input['subcate']);
        } 

        $ads->where('users.status', 1)->where('ads.status', 1);

        $ads->groupBy("ads.id")
                    ->offset($input['row'])
                    ->limit(10);
        $adsfv = $ads->get();

        $viewads = array();
        
        if(Auth::check()){
            $viewads = DB::table('user_viewed_ads')
              ->where('user_id',Auth::user()->id)
              ->get()->toArray();
        }
        $adsf = array();
        foreach ($adsfv as $key => $ad) {
            if(!empty($viewads)){
                foreach ($viewads as $key => $fad) {
                    $adsf[$ad->id] = $ad;
                    if($fad->ads_id == $ad->id){
                        $adsf[$ad->id]->alreadyviwed = "1"; //viewd
                    }else{
                        $adsf[$ad->id]->alreadyviwed = "0"; //not viewd
                    }                  
                }
            }else{
                $ad->alreadyviwed = "0";
                $adsf[$ad->id] = $ad;                
            }
            
        }


        if(!empty($adsf)){
            return response()->json(['success'=>true,'data'=>$adsf], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'messages'=>"No ads found"], $this->successStatus);     
        }

    }


    public function get_location($value='')
    {
        
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
        //echo "<pre>";print_r($location);die;  
        if(!empty($location)){
            return response()->json(['success'=>true,'data'=>$location], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'messages'=>"No location found"], $this->successStatus);     
        }
    }
}
