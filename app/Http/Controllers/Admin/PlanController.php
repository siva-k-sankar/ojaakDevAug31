<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Plan;
use App\Plan_history;
use Uuid;
use Auth;
use App\PaidAds;
use App\TopAds;
use App\Premiumadsplan;
use App\Premiumplansdetails;
use Carbon\Carbon;
use App\Parent_categories;
class PlanController extends Controller
{
    public function index()
    {   
        return view('back.plans.plan');
    }
    public function history()
    {   
        return view('back.plans.planhistory');
    }
    public function createplans()
    {
        return view('back.plans.createplan');

    }
    public function getplansDetails()
    {
        $plans = DB::table('plans')
                     ->select('*')->orderBy('id','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $plans->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("plans.plan_name","like","%".$searchValue."%")
                ->orWhere("plans.plan_price","like","%".$searchValue."%")
                ->orWhere("plans.plan_Ads","like","%".$searchValue."%");
            });          
        }
        return $plans;
    }
    public function getplans(Request $request){
        $count = $this->getplansDetails();
        $totalCount = count($count->get());

        $getData = $this->getplansDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['plan'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['plan'] as $plan) {
            $status='';
            $type='';
            
            if($plan->status==1){
                $planstatus="<center><a href='#' class='btn bg-olive btn-flat margin disabled' >Active</a></center>";
            }else{
                $planstatus="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >InActive</a></center>";
            }

            $status =  route('admin.plans.status',$plan->id);
            $delete =  route('admin.plans.delete',$plan->id);
            $edit =  route('admin.plans.edit',$plan->uuid);
            $action = '<center><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$plan->id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a></center>
                
                <form action="'.$status.'" method="POST" id="status-plan-'.$plan->id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';

            $row = array("name"=>$plan->plan_name,"price"=>$plan->plan_price,"status"=>$planstatus,"createdby"=>get_name($plan->created_by),"updated"=>$plan->updated_at,"create"=>$plan->created_at,"plan_Ads"=>$plan->plan_Ads,"action"=>$action);
            
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
    public function plansdelete($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= DB::table('plans')->where('id',$id)->first();
        //echo"<pre>";print_r($check);die;
        if(!empty($check)){
            
            $res=Plan::destroy($id);
            
            $random = Uuid::generate(4);
            plan_history($random,$check->id,$check->name,$check->price,$check->limit,Auth::id(),2);
            
            toastr()->success(' Plan deleted successfully!');
            return back();
        }
        toastr()->error(' Data not found');
        return back();
    }
    public function plansstatus($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= Plan::where('id',$id)->first();
        //echo"<pre>";print_r($check->status);die;

        if(!empty($check)){
            
            if($check->status=="0"){
                $check->status="1";
                $check->save();
            }else{
                $check->status="0";
                $check->save();
            }

            $random = Uuid::generate(4);
        //echo"<pre>";print_r($check->id);die;
            plan_history($random,$check->id,$check->plan_name,$check->plan_price,$check->plan_Ads,Auth::id(),3);

            toastr()->success(' Plan Status Changed successfully!');
            return back();
        }
        
    }
    public function addplans(Request $request){
    	$input=$request->all();
        $uuid = Uuid::generate(4);
        
        $request->validate([
            'name'          =>'required', 
            'price'          =>'required',
            'limit'          =>'required',
            
        ]);
        if($input['price'] <= 0){
            toastr()->warning('Price is Lesser!');
            return back();
        }
        if($input['limit'] <= 0){
            toastr()->warning('Limit is Lesser!');
            return back();
        }
        $check=Plan::where('plan_name',$input['name'])->first();
        if (!empty($check)) {
        	toastr()->error(' Plan Already Exists!');
        	return back();
        } else {
			$plan= new Plan;
        	$plan->uuid=$uuid;
        	$plan->plan_name=$input['name'];
        	$plan->plan_price=$input['price'];
        	$plan->plan_Ads=$input['limit'];
        	$plan->created_by=Auth::id();
        	$plan->save();

            $random = Uuid::generate(4);
            plan_history($random,$plan->id,$input['name'],$input['price'],$input['limit'],Auth::id(),0);

        	toastr()->success(' Plan Created successfully!');
        	return redirect()->route('admin.plans');
        }
    }
    public function editplans($id)
    {   
        $data= DB::table('plans')->where('uuid',$id)->first();
        if(empty($data)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            return view('back.plans.editplan',compact('data'));
        }
    }
    public function updateplans(Request $request){
        $input=$request->all();
        $request->validate([
            'price'          =>'required',
            'limit'          =>'required',
            
        ]);
        if($input['price'] <= 0){
            toastr()->warning('Price is Lesser!');
            return back();
        }
        if($input['limit'] <= 0){
            toastr()->warning('Limit is Lesser!');
            return back();
        }
        $dataplan=Plan::where('uuid',$input['id'])->first();
        if(empty($dataplan)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            $dataplan->plan_price=$input['price'];
            $dataplan->plan_Ads=$input['limit'];
            $dataplan->save();

            $random = Uuid::generate(4);
            plan_history($random,$dataplan->id,$dataplan->plan_name,$input['price'],$input['limit'],Auth::id(),1);
            
            toastr()->success(' Plan Updated successfully!');
            return redirect()->route('admin.plans');
        }
       
    }
    public function gethistory(){
         $plandatas = DB::table('plans_history')
                     ->select('*')->orderBy('id','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $plandatas->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("plans_history.modified_name","like","%".$searchValue."%")
                ->orWhere("plans_history.modified_price","like","%".$searchValue."%")
                ->orWhere("plans_history.modified_limit","like","%".$searchValue."%");
            });          
        }
        return $plandatas;

    }
    public function historyview(Request $request){
        $count = $this->gethistory();
        $totalCount = count($count->get());

        $getData = $this->gethistory();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['plan'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['plan'] as $plan) {
            $status='';
            $check=null;
            //echo "<pre>";print_r($plan);die;

            $checkdata=DB::table('plans')->where('id',$plan->plan_id)->first();
            if(!empty($checkdata)){
                $check=$checkdata->created_at;
            }
            if($plan->status==0){
                $planstatus='Create';;
            }else if($plan->status==1){
                $planstatus ='Update';
            }else if($plan->status==2){
                $planstatus ='Delete';
            }else{
                $planstatus ='Status Changed';
            } 

            $row = array("name"=>$plan->modified_name,"price"=>$plan->modified_price,"status"=>$planstatus,"modifiedby"=>get_name($plan->modified_admin_id),"modifieddate"=>$plan->modified_date,"plan"=>$check,"modifiedlimit"=>$plan->modified_limit,);
            
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
    public function paidAdsPlan(){
        return view('back.plans.paidadsplan');
    }
     public function paidAdsPlanCreate(){
        return view('back.plans.paidadsplancreate');
    }
    public function topAdsPlan(){
        return view('back.plans.topadsplan');
    }
    public function topAdsPlanCreate(){
        return view('back.plans.topadsplancreate');
    }
    public function addPaidAdsPlan(Request $request){
        $input = $request->all();
        //echo"<pre>";print_r($input);die;
        $uuid = Uuid::generate(4);
        $request->validate([
                'category'          =>'required', 
                'name'              =>'required',
                'validity'          =>'required',
                'points'            =>'required',
                'quantity_1'        =>'required',
                'quantity_3'        =>'required',
                'quantity_5'        =>'required',
                'quantity_10'       =>'required',
                
               

        ]);
         if($input['validity'] <= 0){
            toastr()->warning('Validity cannot be empty!');
            return back();
        }
        $check = PaidAds::where('plan_name',$input['name'])->first();
        if(!empty($check)){
         toastr()->warning('Plan already exists!');
            return back();   
        }else{
           $paidAds = new PaidAds;
           $paidAds->uuid=$uuid;
            $paidAds->category=$input['category'];
            $paidAds->plan_name=$input['name'];
            $paidAds->validity=$input['validity'];
            $paidAds->wallet_points=$input['points'];
            $paidAds->quantity_1=$input['quantity_1'];
            $paidAds->quantity_3=$input['quantity_3'];
            $paidAds->quantity_5=$input['quantity_5'];
            $paidAds->quantity_10=$input['quantity_10'];
            $paidAds->discount=$input['discount'];
            $paidAds->comments=$input['comment'];
            $paidAds->save();
            
        }
        toastr()->success(' Plan Created successfully!');
            return redirect()->route('admin.paidadsplan');
        
    }
    public function getPaidPlansDetail(){
        $plans = DB::table('paidadsplan')
                 ->select('*')->orderBy('id','desc');
         // echo "<pre>";print_r($plans);die;        
         if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $plans->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("paidadsplan.plan_name","like","%".$searchValue."%")
                ->orWhere("paidadsplan.quantity_1","like","%".$searchValue."%")
                ->orWhere("paidadsplan.quantity_3","like","%".$searchValue."%")
                ->orWhere("paidadsplan.quantity_5","like","%".$searchValue."%")
                ->orWhere("paidadsplan.quantity_10","like","%".$searchValue."%")
                ->orWhere("paidadsplan.category","like","%".$searchValue."%");
            });          
        }        
            return $plans;
    }
    public function getPaidPlans(Request $request){

        $count = $this->getPaidPlansDetail();
        $totalCount = count($count->get());

        $getData = $this->getPaidPlansDetail();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['paidadsplan'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['paidadsplan'] as $plan) {
            $status='';
            $type='';
            
            if($plan->status==1){
                $planstatus="<center><a href='#' class='btn bg-olive btn-flat margin disabled' >Active</a></center>";
            }else{
                $planstatus="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >InActive</a></center>";
            }

            $status =  route('admin.plans.paidplanstatus',$plan->id);
            $delete =  route('admin.plans.delete',$plan->id);
            $edit =  route('admin.paidplans.edit',$plan->uuid);
            $action = '<center><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$plan->id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn  bg-yellow"><i class="fa  fa-pencil-square-o"></i></a></center>
                <form action="'.$status.'" method="POST" id="status-plan-'.$plan->id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';

            $row = array("category"=>$plan->category,"name"=>$plan->plan_name,"validity"=>$plan->validity,"points"=>$plan->wallet_points,"quantity_1"=>$plan->quantity_1,"quantity_3"=>$plan->quantity_3,"quantity_5"=>$plan->quantity_5,"quantity_10"=>$plan->quantity_10,"discount"=>$plan->discount,"comments"=>$plan->comments,"status"=> $planstatus,"action"=>$action);
            
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
    public function paidPlansStatus($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= PaidAds::where('id',$id)->first();
        //echo"<pre>";print_r($check->status);die;

        if(!empty($check)){
            
            if($check->status=="0"){
                $check->status="1";
                $check->save();
            }else{
                $check->status="0";
                $check->save();
            }
            toastr()->success(' Plan Status Changed successfully!');
            return back();
        }
        
    }
    public function paidPlansEdit($uuid){
        $data= DB::table('paidadsplan')->where('uuid',$uuid)->first();
         // echo "<pre>";print_r($data);die;
        if(empty($data)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            return view('back.plans.editpaidplans',compact('data'));
        }
    }
    public function paidPlansUpdate(Request $request){
         $input = $request->all();
        // echo"<pre>";print_r($input['uuid']);die;
        // $uuid = Uuid::generate(4);
        $request->validate([
                'category'          =>'required', 
                'name'              =>'required',
                'validity'          =>'required',
                'points'            =>'required',
                'quantity_1'        =>'required',
                'quantity_3'        =>'required',
                'quantity_5'        =>'required',
                'quantity_10'       =>'required',
                
               

        ]);
         if($input['validity'] <= 0){
            toastr()->warning('Validity cannot be empty!');
            return back();
        }
        $paidAds = PaidAds::where('uuid',$input['uuid'])->first();
            $paidAds->category=$input['category'];
            $paidAds->plan_name=$input['name'];
            $paidAds->validity=$input['validity'];
            $paidAds->wallet_points=$input['points'];
            $paidAds->quantity_1=$input['quantity_1'];
            $paidAds->quantity_3=$input['quantity_3'];
            $paidAds->quantity_5=$input['quantity_5'];
            $paidAds->quantity_10=$input['quantity_10'];
            $paidAds->discount=$input['discount'];
            $paidAds->comments=$input['comment'];
            $paidAds->save();
            toastr()->success(' Plan Updated successfully!');
            return redirect()->route('admin.paidadsplan');
        
    }    
    public function addTopAdsPlan(Request $request){
        $input = $request->all();
        //echo"<pre>";print_r($input);die;
        $uuid = Uuid::generate(4);
        $request->validate([
                'category'          =>'required', 
                'name'              =>'required',
                'validity_7'        =>'required',
                'validity_15'       =>'required',
                'validity_30'       =>'required',
                'type'       =>'required',
            ]);
        $check = TopAds::where('name',$input['name'])->first();
        if(!empty($check)){
         toastr()->warning('Plan already exists!');
            return back();   
        }else{
           $topAds = new TopAds;
           $topAds->uuid=$uuid;
            $topAds->category=$input['category'];
            $topAds->name=$input['name'];
            $topAds->validity_7=$input['validity_7'];
            $topAds->validity_15=$input['validity_15'];
            $topAds->validity_30=$input['validity_30'];
            $topAds->discount=$input['discount'];
            $topAds->comments=$input['comment'];
            $topAds->type=$input['type'];
            $topAds->save();
        }
        toastr()->success(' Plan Created successfully!');
        return redirect()->route('admin.topadsplan.create');  
    }
    public function getTopPlansDetail(){
        $plans = DB::table('top_ads_plan')
                 ->select('*')->orderBy('id','desc');
         // echo "<pre>";print_r($plans);die;        
         if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $plans->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("top_ads_plan.name","like","%".$searchValue."%")
                ->orWhere("top_ads_plan.validity_7","like","%".$searchValue."%")
                ->orWhere("top_ads_plan.validity_15","like","%".$searchValue."%")
                ->orWhere("top_ads_plan.validity_30","like","%".$searchValue."%")
                ->orWhere("top_ads_plan.category","like","%".$searchValue."%");
            });          
        }        
            return $plans;
    }
    public function getTopPlans(Request $request){
        $count = $this-> getTopPlansDetail();
        $totalCount = count($count->get());
        $getData = $this-> getTopPlansDetail();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['top_ads_plan'] = $getData->get();
        $datas = array();
        $row = array();
        foreach ($data['top_ads_plan'] as $plan) {
            $status='';
            $type='';
            if($plan->status==1){
                $planstatus="<center><a href='#' class='btn bg-olive btn-flat margin disabled' >Active</a></center>";
            }else{
                $planstatus="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >InActive</a></center>";
            }
            if($plan->type==1){
                $type="<center><a href='#' class='btn bg-olive btn-flat margin disabled' >Platinum</a></center>";
            }else{
                $type="<center><a href='#' class='btn bg-blue btn-flat margin disabled' >Featured</a></center>";
            }
            $status =  route('admin.plans.topplanstatus',$plan->id);
            $delete =  route('admin.plans.delete',$plan->id);
            $edit =  route('admin.topplans.edit',$plan->uuid);
            $action = '<center><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$plan->id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn  bg-yellow"><i class="fa  fa-pencil-square-o"></i></a></center>
                <form action="'.$status.'" method="POST" id="status-plan-'.$plan->id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';
            $row = array("category"=>$plan->category,"name"=>$plan->name,"type"=>$type,"validity_7"=>$plan->validity_7,"validity_15"=>$plan->validity_15,"validity_30"=>$plan->validity_30,"discount"=>$plan->discount,"comments"=>$plan->comments,"status"=> $planstatus,"action"=>$action);
            
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
    public function topPlansStatus($id)
    {   

        $check= TopAds::where('id',$id)->first();
           if(!empty($check)){
            
                if($check->status=="0"){
                    $check->status="1";
                    $check->save();
                }else{
                    $check->status="0";
                    $check->save();
                }
            toastr()->success(' Plan Status Changed successfully!');
            return back();
        }
        
    }
    public function topPlansUpdate(Request $request){
         $input = $request->all();
        // echo"<pre>";print_r($input);die;
        // $uuid = Uuid::generate(4);
        $request->validate([
                'category'          =>'required', 
                'name'              =>'required',
                'validity_7'        =>'required',
                'validity_15'       =>'required',
                'validity_30'       =>'required',
                'type'       =>'required',
        ]);
        
        $topAds = TopAds::where('uuid',$input['uuid'])->first();
            $topAds->category=$input['category'];
            $topAds->name=$input['name'];
            $topAds->validity_7=$input['validity_7'];
            $topAds->validity_15=$input['validity_15'];
            $topAds->validity_30=$input['validity_30'];
            $topAds->discount=$input['discount'];
            $topAds->comments=$input['comment'];
            $topAds->type=$input['type'];
            $topAds->save();
            toastr()->success(' Plan Updated successfully!');
            return redirect()->route('admin.topadsplan');
        
    }
    public function topPlansEdit($uuid){
        $data= DB::table('top_ads_plan')->where('uuid',$uuid)->first();
          // echo "<pre>";print_r($data);die;
        if(empty($data)){
            toastr()->warning('Illegal Access !');
            return back();
        }else{
            return view('back.plans.edittopplans',compact('data'));
        }
    }

    public function premiumAdsPlan(){
        return view('back.plans.premiumadsplan');
    }

    public function premiumAdsPlanCreate(){
        $catelist=Parent_categories::where('status',1)->get();
        return view('back.plans.premiumadsplancreate',compact('catelist'));
    }
    public function addpremiumAdsPlan(Request $request){
        $input = $request->all();
        //echo"<pre>";print_r($input);die;
        //$detailscounts=$input['detailscount'];
        
        $category = implode(",", $input['category']);
        //echo"<pre>";print_r($category);die;
        $uuid = Uuid::generate(4);
        $premium = new Premiumadsplan;
        $premium->uuid=$uuid;
        $premium->category=$category;
        $premium->plan_name=$input['name'];
        $premium->validity=$input['advalidity'];
        $premium->ads_points=$input['points'];
        $premium->discount=$input['discount'];
        $premium->comments=$input['comment'];
        $premium->save();

        for ($i=0; $i < $input['detailscount']; $i++) { 
            //echo"<pre>";print_r($input);die;
            // echo"<pre> quantity ";print_r($input['quantity'][$i]);
            // echo"<pre> price ";print_r($input['price'][$i]);
            // echo"<pre> planvalitity ";print_r($input['planvalitity'][$i]);
            $privacy=Premiumplansdetails::create([
                'plan_id' => $premium->id,
                'quantity' => $input['quantity'][$i],
                'price' => $input['price'][$i], 
                'discounts' => $input['discounts'][$i], 
                'validity' => $input['planvalitity'][$i],
            ]);
        }

        toastr()->success('Plan Saved successfully!');
        return redirect()->route('admin.premiumadsplan');
    }
    public function premiumPlansEdit($uuid){
        $data= DB::table('premiumadsplans')->where('uuid',$uuid)->first();
        //echo "<pre>";print_r($data);die;
        if(empty($data)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            $details= DB::table('premiumplansdetails')->where('plan_id',$data->id)->get();
            $detailscount=$details->count();
            $catelist=Parent_categories::where('status',1)->get();
            $plancategory = explode(",", $data->category);
            //echo "<pre>";print_r($details);die;
            return view('back.plans.editpremiumplans',compact('data','details','detailscount','catelist','plancategory'));
        }
    }
    public function premiumPlansUpdate(Request $request){
        $input = $request->all();
        //echo"<pre>";print_r($input);die;
        $premium = Premiumadsplan::where('uuid',$input['id'])->first();
        if(empty($premium)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            $category = implode(",", $input['category']);
            $premium->category=$category;
            $premium->plan_name=$input['name'];
            $premium->validity=$input['advalidity'];
            $premium->ads_points=$input['points'];
            $premium->discount=$input['discount'];
            $premium->comments=$input['comment'];
            $premium->save();
            
            DB::table('premiumplansdetails')->where('plan_id',$premium->id)->delete();
            
            for ($i=0; $i < $input['detailscount']; $i++) { 
                $privacy=Premiumplansdetails::create([
                    'plan_id' => $premium->id,
                    'quantity' => $input['quantity'][$i],
                    'price' => $input['price'][$i],
                    'discounts' => $input['discounts'][$i], 
                    'validity' => $input['planvalitity'][$i],
                ]);
            }

            toastr()->success('Plan Updated successfully!');
            return redirect()->route('admin.premiumadsplan');
        }
    }
    public function premiumPlansStatus($id)
    {   
        $check= Premiumadsplan::where('id',$id)->first();
        if(!empty($check)){
            
            if($check->status=="0"){
                $check->status="1";
                $check->save();
            }else{
                $check->status="0";
                $check->save();
            }
            toastr()->success(' Plan Status Changed successfully!');
            return back();
        }
    }
    public function getPremiumPlansDetail(){
        $plans = DB::table('premiumadsplans')
                 ->select('*')->orderBy('id','asc');
         // echo "<pre>";print_r($plans);die;        
         if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $plans->Where(function ($query) use ($searchValue) {
                $query->orWhere("premiumadsplans.plan_name","like","%".$searchValue."%");
                $query->orWhere("premiumadsplans.category","like","%".$searchValue."%");
                $query->orWhere("premiumadsplans.comment","like","%".$searchValue."%");
            });          
        }        
            return $plans;
    }
    public function getPremiumPlans(Request $request){

        $count = $this->getPremiumPlansDetail();
        $totalCount = count($count->get());

        $getData = $this->getPremiumPlansDetail();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['premiumadsplans'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['premiumadsplans'] as $plan) {
            $status='';
            $type='';
            
            if($plan->status==1){
                $planstatus="<center><a href='#' class='btn bg-olive btn-flat margin disabled' >Active</a></center>";
            }else{
                $planstatus="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >InActive</a></center>";
            }

            $status =  route('admin.premiumadsplans.status',$plan->id);
            $edit =  route('admin.premiumadsplans.edit',$plan->uuid);
            $action = '<center><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$plan->id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn  bg-yellow"><i class="fa  fa-pencil-square-o"></i></a></center>
                <form action="'.$status.'" method="POST" id="status-plan-'.$plan->id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';

            $row = array("category"=>plancategoryName($plan->category),"name"=>$plan->plan_name,"validity"=>$plan->validity,"points"=>$plan->ads_points,"discount"=>$plan->discount,"comments"=>$plan->comments,"status"=> $planstatus,"action"=>$action);
            
            $datas[] = $row;
        }
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
    
}




 