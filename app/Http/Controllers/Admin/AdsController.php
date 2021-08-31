<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Ads;
use Auth;
use App\RejectReason;
use App\Customfield;
use App\Customfield_Options;
use App\Sub_categories;
use App\City_requests;
use App\Adsimage;
use Uuid;
use DateTime;
use Carbon\Carbon;
use Image;
use App\PostValues;
use Illuminate\Support\Str;
use App\Verification;
use App\AdsFeatures;
use App\PlansPurchase;
use App\TopLists;
use App\Pearls;
use App\Block_Reason;
class AdsController extends Controller
{
    public function index(Request $request)
    {
        return view('back.ads.adsverify');

    }
    public function adsVerificationDetails()
    {
        $adsdatas = DB::table('ads')
                    ->leftJoin('users', 'ads.seller_id', '=', 'users.id')
                    ->select('ads.*')->where('ads.status','!=',5)->where('users.status',1)->orderBy('ads.status','asc')->orderBy('ads.created_at','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $adsdatas->Where(function ($query) use ($searchValue) {
                $query->orWhere("ads.title","like","%".$searchValue."%");
                //$query->orWhere("ads.id","like","%".$searchValue."%");
                $query->orWhere("ads.ads_ep_id","like","%".$searchValue."%");
                $query->orWhere("ads.approved_date","like","%".$searchValue."%");
                $query->orWhere("ads.created_at","like","%".$searchValue."%");
                $query->orWhere("users.name","like","%".$searchValue."%");
                $query->orWhere("users.email","like","%".$searchValue."%");
                $query->orWhere("users.id","like","%".$searchValue."%");
                //$query->orWhere("users.email","like","%".$searchValue."%");
                if(strtolower($searchValue)=='pending' || strtolower($searchValue)=='pe' || strtolower($searchValue)=='pen' || strtolower($searchValue)=='pend' || strtolower($searchValue)=='pendi' || strtolower($searchValue)=='pendin'){
                    $searchValueStatus=0;
                    $query->orWhere("ads.status","like","%".$searchValueStatus."%");
                }
                if(strtolower($searchValue) =='approved' || strtolower($searchValue) =='ap' || strtolower($searchValue) =='app' || strtolower($searchValue) =='appr' || strtolower($searchValue) =='appro' || strtolower($searchValue) =='approv' || strtolower($searchValue) =='approve'){
                    $searchValueStatus=1;
                    $query->orWhere("ads.status","like","%".$searchValueStatus."%");
                }
                if(strtolower($searchValue)=='rejected' || strtolower($searchValue)=='re' || strtolower($searchValue)=='rej' || strtolower($searchValue)=='reje' || strtolower($searchValue)=='rejec' || strtolower($searchValue)=='reject' || strtolower($searchValue)=='rejecte'){
                    $searchValueStatus=2;
                    $query->orWhere("ads.status","like","%".$searchValueStatus."%");
                }
                if(strtolower($searchValue)=='sold' || strtolower($searchValue)=='so' || strtolower($searchValue)=='sol'){
                    $searchValueStatus=3;
                    $query->orWhere("ads.status","like","%".$searchValueStatus."%");
                }
                if(strtolower($searchValue)=='bl' || strtolower($searchValue)=='blo' || strtolower($searchValue)=='bloc' || strtolower($searchValue)=='block' || strtolower($searchValue)=='blocke' || strtolower($searchValue)=='blocked'){
                    $searchValueStatus=4;
                    $query->orWhere("ads.status","like","%".$searchValueStatus."%");
                }
                if(strtolower($searchValue)=='fr' || strtolower($searchValue)=='fre' || strtolower($searchValue)=='free'){
                    $searchValueType=0;
                    $query->orWhere("ads.type","like","%".$searchValueType."%");
                }
                if(strtolower($searchValue)=='premium' || strtolower($searchValue)=='pr' || strtolower($searchValue)=='pre' || strtolower($searchValue)=='prem' || strtolower($searchValue)=='premi' || strtolower($searchValue)=='premiu'){
                    $searchValueType=1;
                    $query->orWhere("ads.type","like","%".$searchValueType."%");
                }
                
            });
                     
        }
        
        return $adsdatas;
    }
    public function adsverification(Request $request){
        $count = $this->adsVerificationDetails();
        $totalCount = count($count->get());

        $getData = $this->adsVerificationDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['ads'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['ads'] as $ads) {

            $status='';
            $type='';
            
            if($ads->status==0){
                $status="<center><a href='#' class='btn bg-orange btn-flat margin disabled' >Pending</a></center>";
            }else if($ads->status==1){
                $status="<center><a href='#' class='btn bg-olive btn-flat margin disabled'>Approved</a></center>";
            }else if($ads->status==2){
                $status="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >Rejected</a></center>";
            }else if($ads->status==3){
                $status="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >Sold</a></center>";
            }else {
                $status="<center><a href='#' class='btn bg-red btn-flat margin disabled' >Blocked</a></center>";
            }

            if($ads->ads_expire_date !='' && $ads->ads_expire_date !=null){
                if($ads->ads_expire_date<date('Y-m-d H:s:i')){
                    $status="<center><a href='#' class='btn bg-red btn-flat margin disabled' >Expired</a></center>";
                }
            }
            

            if($ads->type==0){
                $type="<center><div  class='btn bg-orange btn-flat margin disabled'>Free</div></center>";
            }else{
                $type="<center><div class='btn bg-olive btn-flat margin disabled' >Premium</div></center>";
            }

            $action = '<center><a  href="'.url('admin/ads/adsdata',$ads->uuid).'" class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square "></i></a></center>';

            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",getUserUuid($ads->seller_id)).'">'.get_name($ads->seller_id).' [ Userid : '.$ads->seller_id.' ]</a>';

            $row = array("title"=>$ads->title,"adsid"=>$ads->id,"userdisplayadsid"=>$ads->ads_ep_id,"user"=>$link,"status"=>$status,"admin"=>getUserEmail($ads->approved_by),"verify"=>$ads->approved_date,"create"=>$ads->created_at,"type"=>$type,"action"=>$action);
            
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

    
    public function view($id){
    	$ads= DB::table('ads')->where('uuid',$id)->first();
    	if(!empty($ads)){
    		$str = $ads->tags;
    		$tags = explode(",", $str);
    		$adsimage=DB::table('ads_image')->where('ads_id',$ads->id)->get();
    		$postvalues=DB::table('post_values')->where('post_id',$ads->id)->get();
    		$reasons=RejectReason::where('ads_id',$ads->id)->orderBy('id','desc')->get();
            
            $reportingAdsUsers=DB::table('report_ads')
                            ->select('users.uuid','users.name as user_name')
                            ->leftJoin('users', 'report_ads.user_id', '=', 'users.id')
                            ->where('report_ads_id',$ads->id)
                            ->groupby('users.id')
                            ->get()->toArray();
            //echo '<pre>';print_r( $reportingAds );die;

    		//echo"<pre>";print_r($postvalues);die;
            $adpointhistory = DB::table('user_viewed_ads')
            ->select('users.uuid','users.name as ad_view_user', 'user_viewed_ads.created_at as viewed_at')
            ->leftJoin('users','user_viewed_ads.user_id', '=', 'users.id')
            ->where('ads_id',$ads->id)->get();  
            //echo"<pre>";print_r($adpointhistory);die;              
            $blockreason =Block_Reason::where('ads_id',$ads->id)->orderby('id','desc')->get();
    		return view('back.ads.adsverified',compact('ads','tags','adsimage','postvalues','reasons','reportingAdsUsers','blockreason','adpointhistory'));
    	}else{
    		toastr()->error('Data Not Found');
    		return back();
    	}
    }
    public function verified($id)
    {   
        $ads= Ads::where('id',$id)->first();
        //echo"<pre>";print_r($ads);die;
        
        if(!empty($ads)){
            $date = new DateTime('now');
            $date->modify('+30 day'); 
            $date = $date->format('Y-m-d H:i:s');
            
            if($ads->point_expire_date == null  || $ads->point_expire_date=='' || empty($ads->point_expire_date)){
               $ads->point_expire_date= $date;
            }
            if($ads->ads_expire_date == null  || $ads->ads_expire_date=='' || empty($ads->ads_expire_date)){
                $ads->ads_expire_date= $date;
            }
            $ads->status = 1;
            $ads->reason = "";
            $ads->approved_by = Auth::id();
            $ads->approved_date = date("Y-m-d H:m:s");
            $ads->save();
            if($ads->type=='0'){
                $verify=Verification::where('user_id',$ads->seller_id)->where('verified_id',2)->whereYear('created_at', \Carbon\Carbon::now()->subDays(30)->toDateString())->get()->count();
                    $settings= DB::table('settings')->first();

                if($verify <= $settings->no_free_ads_post_per_month){
                    addpoint($ads->seller_id,'ads_post_point',$id);
                }
            }
        }
		return redirect()->route('admin.ads');
    }
    public function block($id,Request $request)
    {   $input=$request->all();
        //echo"<pre>";print_r($input);die;
        $ads= Ads::where('id',$id)->first();
        //

        if(!empty($ads)){
            if( $ads->status != 4){
                $blockReason= [
                    'user_id'  =>  null,
                    'ads_id'  =>  $ads->id,
                    'reason'    => $input['reason'],
                    'blocked_by'    => auth()->user()->id,
                    'status' =>'Block',
                ];
                $ads->status = 4;
            }else{
                $blockReason= [
                    'user_id'  =>  null,
                    'ads_id'  =>  $ads->id,
                    'reason'    => $input['reason'],
                    'blocked_by'    => auth()->user()->id,
                    'status' =>'Unblock',
                ];
                $ads->status = 1;
            }
            $newReason = new Block_Reason($blockReason);
            $newReason->save();
            $ads->save(); 
        }
        return redirect()->route('admin.ads');
    }
    public function addvalidity(Request $request)
    {   
        $input=$request->all();
        //$test = date("Y-m-d H:i:s", strtotime($input['Adexpiredate']));
        //echo"<pre>";print_r($test);die;
        $ads= Ads::where('id',$input['id'])->first();
        if(!empty($ads)){
             
            $ads->ads_expire_date = date("Y-m-d H:i:s", strtotime($input['Adexpiredate']));
            $ads->point_expire_date = date("Y-m-d H:i:s", strtotime($input['Adpointexpiredate']));
            $ads->point = $input['Adpointbalance'];
            $ads->save();
        }
        return back();
    }
    public function reject(Request $request)
    {   
    	$input=$request->all();
    	//echo"<pre>";print_r($input);die;
        $ads= Ads::where('id',$input['id'])->first();
        //echo"<pre>";print_r($ads);die;
        if(!empty($ads)){
        	 if(!empty($input['reason'])){
        	 	$ads->reason = $input['reason'];
        	 }else{
        	 	$ads->reason = "Ads rejected";
        	 }
            $ads->status = 2;
            $ads->approved_by = Auth::id();
            $ads->approved_date = date("Y-m-d H:m:s");
            $ads->save();
        }
        $RejectReason= [
            'ads_id'  => $ads->id,
            'reason'    => $ads->reason,
            'rejected_by'    => Auth::id(),
        ];
        $newReason = new RejectReason($RejectReason);
        $newReason->save();
		return redirect()->route('admin.ads');
    }
    public function edit($id){
        $ads= DB::table('ads')->where('uuid',$id)->first();
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
                        $customfields.=$this->geteditfieldHtml($field->field_id,$ads->id);
                                    
                    }
                }
            $ads->images=json_encode($images);
            return view('back.ads.adsedit',compact('parent','ads','sub','customfields'));
        }else{
            toastr()->error('Data Not Found');
            return back();
        }
    }
    public function geteditfieldHtml($fields,$postid){
        $customfields="";
        $get_custom_field=Customfield::where('id',$fields)->where('active',1)->first(); 
        $cusval=DB::table('post_values')->where('field_id',$get_custom_field->id)->where('post_id',$postid)->first();
        if(empty($cusval)){
            $cusval = (object) array();
            $cusval->value='';
        }
        if(!empty($get_custom_field->type)){

            if($get_custom_field->required == 1){
                $sub="*";
            }else{
                $sub="";
            }

            if($get_custom_field->type =="text"){
                
                 $customfields.="<div class='form-group row'>
                                    <label for='cf.$get_custom_field->id' class='col-sm-3 col-form-label'>$get_custom_field->name<sup>$sub</sup></label>
                                    <div class='col-sm-9'>
                                    <input type='$get_custom_field->type' class='form-control mb-2'  id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' placeholder='' value='$cusval->value'  max='$get_custom_field->max'>
                                    </div>
                                </div>";
            }

            if($get_custom_field->type =="number"){
                
                 $customfields.="<div class='form-group row'>
                                    <label for='cf.$get_custom_field->id' class='col-sm-3 col-form-label'>$get_custom_field->name<sup>$sub</sup></label>
                                    <div class='col-sm-9'>
                                    <input type='$get_custom_field->type' class='form-control mb-2'  id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' placeholder='' value='$cusval->value'  max='$get_custom_field->max'>
                                    </div>
                                </div>";
            }

            if($get_custom_field->type =="checkbox"){

                 $customfields.="<div class='form-group row'>
                                    <label for='cf.$get_custom_field->id' class='col-sm-3 col-form-label'>$get_custom_field->name<sup>$sub</sup></label>
                                    <div class='col-sm-9'>
                                        <div class='form-check'>
                                        <input type='checkbox' class='form-check-input'  id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' placeholder='' "; 
                if(!empty($cusval) && $cusval->value==1){
                    $customfields.="checked ";
                }         
                $customfields.=" value='$get_custom_field->default' max='$get_custom_field->max'></div></div></div>";
            }

            if($get_custom_field->type =="textarea"){
                 $customfields.="<div class='form-group row'>
                                    <label for='cf.$get_custom_field->id' class='col-sm-3 col-form-label'>$get_custom_field->name<sup>$sub</sup></label>
                                    <div class='col-sm-9'>
                                    <textarea class='form-control' rows='3' id='cf.$get_custom_field->id' name='cf[$get_custom_field->id]' placeholder=''  value='$get_custom_field->default' max='$get_custom_field->max'>";
                if(!empty($cusval)){
                    $customfields.="$cusval->value";
                }
                $customfields.="</textarea></div></div>";
            }
            
            if($get_custom_field->type == "radio"){
                 $customfields.="<div class='form-group row'>
                                    <label for='cf.$get_custom_field->id' class='col-sm-3 col-form-label'>$get_custom_field->name<sup>$sub</sup></label>
                                    <div class='col-sm-9'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get();
                foreach ($options as $key => $option) {
                     
                         $customfields.="<div class='form-check form-check-inline'>
                                          <input class='form-check-input' type='radio' name='cf[$get_custom_field->id]' id='cf.$get_custom_field->id.$option->id' value='$option->id'"; 
                        if($option->id == $cusval->value){
                              $customfields.="checked";  
                        }
                        $customfields.=" >
                                          <label class='form-check-label' for='cf[$get_custom_field->id]'>
                                            $option->value
                                          </label>
                                    </div>";

                }  

                $customfields.="</div></div>";
            }

            if($get_custom_field->type == "select"){
                $customfields.="<div class='form-group row'>
                                    <label for='cf.$get_custom_field->id' class='col-sm-3 col-form-label'>$get_custom_field->name<sup>$sub</sup></label>
                                    <div class='col-sm-9'>
                                    <select class='form-control'  name='cf[$get_custom_field->id]' id='cf.$get_custom_field->id' >";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {
                        
                        $customfields.="<option value='$option->id'";
                        if($option->id == $cusval->value){
                          $customfields.="selected";  
                        }
                        $customfields.= ">$option->value</option>";
                }  
                $customfields.="</select></div></div>";
            }

            if($get_custom_field->type == "checkbox_multiple"){
                 $customfields.="<div class='form-group row'>
                                    <label for='cf.$get_custom_field->id' class='col-sm-3 col-form-label'>$get_custom_field->name<sup>$sub</sup></label>
                                    <div class='col-sm-9'>";
                $options=Customfield_Options::where('field_id',$get_custom_field->id)->get(); 
                foreach ($options as $key => $option) {
                    $cusoptionval=DB::table('post_values')->where('option_id',$option->id)->first();
                    $customfields.="<div class='form-check form-check-inline'>
                                          <input class='form-check-input mulchkbox' type='checkbox' name='cf[$get_custom_field->id][$option->id]' id='cf.$get_custom_field->id.$option->id' value='$option->id'";
                    if(!empty($cusoptionval)){
                        if($option->id == $cusoptionval->value){
                              $customfields.="checked";  
                        }
                    }

                    $customfields.=" data-field='$get_custom_field->id' data-optionid='$option->id' data-valueid='$option->id'  ><label class='form-check-label' for='cf.$get_custom_field->id.$option->id'>
                                            $option->value
                                          </label>
                                    </div>";
                }  

                $customfields.="</div></div><div id='mulchbox'></div>";
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
    public function save(Request $request)
    {   
        $input=$request->all();
        
        if(!isset($input['old'])){
            if(!isset($input['photos'])){
                toastr()->error(' Image Empty!');
                return back()->withInput(); 
            }
        }
        
        $request->validate([
            'id'          =>'required',
            /*'category'          =>'required',
            'sub-category'      =>'required',
            */'description'       =>'required',
            /*'city_name'         =>'required',
            */'title'             =>'required',
            'price'             =>'required',
            'photos.*'           =>'image|mimes:jpeg,jpg,png|max:2048',
            ]);
        /*$image = $request->file('image');
        echo "<pre>";print_r($image);die;*/
        $uuid = Uuid::generate(4);
        /*if(empty($request->get('hidden-tags')))
        {
             toastr()->error(' Tags Empty!');
            return back();
        }*/
        // if(empty($request->get('old')))
        // {
        //      toastr()->error(' Image Empty!');
        //     return back();
        // }
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
        
        //ads update
        if(!empty($ads))
        {
            $ads->title = $input['title'];
           /* $ads->cities = $input['city_name'];*/
            $ads->price = $input['price'];
            $ads->description = $input['description'];
            /*$ads->tags = $input['hidden-tags'];*/
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
                    $watermark = Image::make(public_path('img/ojaak_watermark.png'));
                    $watermark->resize(99,27);
                    $img->insert($watermark, 'bottom-right', 10, 10);
                    //$img->insert(public_path('img/ojaak_watermark.png'), 'bottom-right', 10, 10);
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
            /*//city request
            if($input['city_name'] == "-1"){
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
            return back();

        }else{
            toastr()->error(' Invalid Ads!');
            return back();
        }
        
    }
    public function featureAdsList(Request $request)
    {
        return view('back.ads.featueadslist');
    }
    public function getfeatureadsDetails()
    {
        $date = Carbon::now();
        $current_date=$date->toDateTimeString();
        $plans = DB::table('ads_features')
                ->leftjoin('users', function($leftJoin){
                    $leftJoin->on('ads_features.user_id', '=', 'users.id');
                })
                ->leftjoin('ads', function($leftJoin){
                    $leftJoin->on('ads_features.ads_id', '=', 'ads.id');
                });

                $plans->select('ads_features.*','users.name as username','users.uuid as useruuid', 'ads.uuid as adsuuid');
         // echo "<pre>";print_r($plans);die;        
         if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $plans->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("ads_features.id","like","%".$searchValue."%");
                $query->orWhere("users.id","like","%".$searchValue."%");
                $query->orWhere("users.name","like","%".$searchValue."%");
                $query->orWhere("ads_features.expire_date","like","%".$searchValue."%");
                $query->orWhere("ads.title","like","%".$searchValue."%");
            });          
        }
        $plans->where('ads_features.expire_date','>=',$current_date)->orderBy('id','desc');        
            return $plans;
    }
    public function getfeatureads(Request $request){
        $count = $this->getfeatureadsDetails();
        $totalCount = count($count->get());

        $getData = $this->getfeatureadsDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['featureads'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['featureads'] as $plan) {
            //echo "<pre>";print_r($plan);die;
            $status='';
            $type='';
            
            
            $action = '<center><button style="margin:3px;" class=" btn bg-red" onclick="expireplan('.$plan->id.')"><i class="fa  fa-trash"></i></button><center>';

            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",$plan->useruuid).'">'.$plan->username.' [ Userid : '.$plan->user_id.' ]</a>';

            $adsname = '<a class="text-danger" target="_blank" href="'.url('admin/ads/adsdata',$plan->adsuuid).'">'.get_adsname($plan->ads_id).'</a>';
            //echo "<pre>";print_r($adsname);die;

            $row = array("id"=>$plan->id,"adsname"=>$adsname,"username"=>$link,"expiredate"=>$plan->expire_date,"action"=>$action);
            
            $datas[] = $row;
        }
            // echo "<pre>";print_r($datas);die;

        $output = array(
            "draw"=> $_POST['draw'],
            "recordsTotal" => $totalCount, //$this->companies->count_all(),
            "recordsFiltered" => $totalCount, //$this->companies->count_filtered(),
            "data" => $datas,
        );
        //output to json format
        // echo "<pre>";print_r($output);die;
        echo json_encode($output);die;

    }
    public function featureAdsListRemove(Request $request)
    {   
        $input=$request->all();
        //return 1;die;
         
        $plans = AdsFeatures::where('id',$input['featureddata'])->first();
        if(!empty($plans)){
            //echo "<pre>";print_r($plans);
            $purchase=PlansPurchase::where('type','1')->where('feature_plan_id',$plans->id)->where('expire_date',$plans->expire_date)->where('user_id',$plans->user_id)->orderBy('id','desc')->first();
            if(!empty($purchase)){
                //echo "<pre></br>";print_r($purchase);die;
                $purchase->expire_date=date('Y-m-d H:i:s');
                $purchase->save();
            }
            return 0;
            $plans->expire_date=date('Y-m-d H:i:s');
            $plans->save();
            return 1; //success
        }
        return 0;//failed

    }
    public function topAdsList(Request $request)
    {
        return view('back.ads.topadslist');
    }
    public function gettopAdsListadsDetails()
    {
        $date = Carbon::now();
        $current_date=$date->toDateTimeString();
        $plans = DB::table('toplists_ads')->leftjoin('users', function($leftJoin){
            $leftJoin->on('toplists_ads.user_id', '=', 'users.id');})->leftjoin('ads', function($leftJoin){
            $leftJoin->on('toplists_ads.ads_id', '=', 'ads.id');});

                $plans->select('toplists_ads.*','users.name as username','ads.title as adsname','users.uuid as useruuid');
         // echo "<pre>";print_r($plans);die;        
         if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $plans->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("users.id","like","%".$searchValue."%");
                $query->orWhere("users.name","like","%".$searchValue."%");
                $query->orWhere("ads.title","like","%".$searchValue."%");
                $query->orWhere("toplists_ads.expire_date","like","%".$searchValue."%");
            });          
        }
        $plans->where('toplists_ads.expire_date','>=',$current_date)->orderBy('id','desc');        
            return $plans;
    }
    public function gettopAdsListads(Request $request){
        $count = $this->gettopAdsListadsDetails();
        $totalCount = count($count->get());

        $getData = $this->gettopAdsListadsDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['top'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['top'] as $plan) {
            //echo "<pre>";print_r($plan);die;
            $status='';
            $type='';
            
            
            $action = '<center><button style="margin:3px;" class=" btn bg-red" onclick="expireplan('.$plan->id.')"><i class="fa  fa-trash"></i></button><center>';
             
            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",$plan->useruuid).'">'.$plan->username.' [ Userid : '.$plan->user_id.' ]</a>';

            $row = array("adsname"=>$plan->adsname,"username"=>$link,"expiredate"=>$plan->expire_date,"action"=>$action);
            
            $datas[] = $row;
        }
            // echo "<pre>";print_r($datas);die;

        $output = array(
            "draw"=> $_POST['draw'],
            "recordsTotal" => $totalCount, //$this->companies->count_all(),
            "recordsFiltered" => $totalCount, //$this->companies->count_filtered(),
            "data" => $datas,
        );
        //output to json format
        // echo "<pre>";print_r($output);die;
        echo json_encode($output);die;

    }
    public function topAdsListRemove(Request $request)
    {   
        $input=$request->all();
        //return 1;die;
        $topplans = TopLists::where('id',$input['topdata'])->first();
        if(!empty($topplans)){
            $topplans->expire_date=date('Y-m-d H:i:s');
            $topplans->save();
            return 1; //success
        }
        return 0;//failed

    }
    public function pearlAdsList(Request $request)
    {
        return view('back.ads.pearladslist');
    }
    public function getpearlAdsListadsDetails()
    {
        $date = Carbon::now();
        $current_date=$date->toDateTimeString();
        $plans = DB::table('pearl_ads')->leftjoin('users', function($leftJoin){
            $leftJoin->on('pearl_ads.user_id', '=', 'users.id');})->leftjoin('ads', function($leftJoin){
            $leftJoin->on('pearl_ads.ads_id', '=', 'ads.id');})->leftjoin('parent_categories', function($leftJoin){
            $leftJoin->on('pearl_ads.category_id', '=', 'parent_categories.id');});

                $plans->select('pearl_ads.*','users.uuid as useruuid','users.name as username','ads.title as adsname','parent_categories.name as catename');
         // echo "<pre>";print_r($plans);die;        
         if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $plans->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("users.name","like","%".$searchValue."%");
                $query->orWhere("users.id","like","%".$searchValue."%");
                $query->orWhere("ads.title","like","%".$searchValue."%");
                $query->orWhere("parent_categories.name","like","%".$searchValue."%");
                $query->orWhere("pearl_ads.expire_date","like","%".$searchValue."%");
            });          
        }
        $plans->where('pearl_ads.expire_date','>=',$current_date)->orderBy('id','desc');        
            return $plans;
    }
    public function getpearlAdsListads(Request $request){
        $count = $this->getpearlAdsListadsDetails();
        $totalCount = count($count->get());

        $getData = $this->getpearlAdsListadsDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['pearl'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['pearl'] as $plan) {
            //echo "<pre>";print_r($plan);die;
            $status='';
            $type='';
            
            
            $action = '<center><button style="margin:3px;" class=" btn bg-red" onclick="expireplan('.$plan->id.')"><i class="fa  fa-trash"></i></button><center>';

            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",$plan->useruuid).'">'.$plan->username.' [ Userid : '.$plan->user_id.' ]</a>';

            $row = array("adsname"=>$plan->adsname,"username"=>$link,"catename"=>$plan->catename,"expiredate"=>$plan->expire_date,"action"=>$action);
            
            $datas[] = $row;
        }
            // echo "<pre>";print_r($datas);die;

        $output = array(
            "draw"=> $_POST['draw'],
            "recordsTotal" => $totalCount, //$this->companies->count_all(),
            "recordsFiltered" => $totalCount, //$this->companies->count_filtered(),
            "data" => $datas,
        );
        //output to json format
        // echo "<pre>";print_r($output);die;
        echo json_encode($output);die;

    }
    public function pearlAdsListRemove(Request $request)
    {   
        $input=$request->all();
        //return 1;die;
        $topplans = Pearls::where('id',$input['topdata'])->first();
        if(!empty($topplans)){
            $topplans->expire_date=date('Y-m-d H:i:s');
            $topplans->save();
            return 1; //success
        }
        return 0;//failed

    }
}
