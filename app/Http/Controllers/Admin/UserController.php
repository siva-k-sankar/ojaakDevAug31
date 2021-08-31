<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;
use DB;
use App\Ads;
use App\AdsImage;
use App\Mail\UnBlockMail;
use App\Mail\BlockMail;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Query;
use App\Description;
use App\Mail\SendMail;
use Uuid;
use App\Role;
use App\RoleAccess;
use App\Block_Reason;
use App\Freepoints;
 use Carbon\Carbon;
 use DateTime;

class UserController extends Controller
{
    public function index(Request $request)
    {
    	return view('back.users.users');
	}
	public function getUserDetails($input)
    {   //echo "<pre>";print_r($input);die;
        $usersdatas = DB::table('users')
                     ->select('*')->orderBy('id','desc');
        
       
        //echo "<pre>";print_r($_REQUEST['columns'][1]['search']['value']);die;
        if(isset($_REQUEST['columns'][1]['search']['value']) && $_REQUEST['columns'][1]['search']['value'] !=''){
            $searchVal = $_REQUEST['columns'][1]['search']['value'];
            $usersdatas->Where(function ($query) use ($searchVal) {
                $query->where("users.id",$searchVal);
            });
        }

        if(isset($_REQUEST['columns'][2]['search']['value']) && $_REQUEST['columns'][2]['search']['value'] !=''){
            $searchVal = $_REQUEST['columns'][2]['search']['value'];
            $usersdatas->Where(function ($query) use ($searchVal) {
                $query->where("users.name",$searchVal);
            });
        }

        if(isset($_REQUEST['columns'][3]['search']['value']) && $_REQUEST['columns'][3]['search']['value'] !=''){
            $searchVal = $_REQUEST['columns'][3]['search']['value'];
            $usersdatas->Where(function ($query) use ($searchVal) {
                $query->where("users.email",$searchVal);
            });
        }

        if(isset($_REQUEST['columns'][4]['search']['value']) && $_REQUEST['columns'][4]['search']['value'] !=''){
            $searchVal = $_REQUEST['columns'][4]['search']['value'];
            $usersdatas->Where(function ($query) use ($searchVal) {
                $query->where("users.phone_no",$searchVal);
            });
        }

        if(isset($_REQUEST['columns'][5]['search']['value']) && $_REQUEST['columns'][5]['search']['value'] !=''){
            $searchVal = $_REQUEST['columns'][5]['search']['value'];
            $usersdatas->Where(function ($query) use ($searchVal) {
                $query->where("users.created_at",$searchVal);
            });
        }

        if(isset($_REQUEST['columns'][6]['search']['value']) && $_REQUEST['columns'][6]['search']['value'] !=''){
            $searchVal = $_REQUEST['columns'][6]['search']['value'];
            $usersdatas->Where(function ($query) use ($searchVal) {
                $query->where("users.last_activity",$searchVal);
            });
        }

        if(isset($_REQUEST['columns'][7]['search']['value']) && $_REQUEST['columns'][7]['search']['value'] !=''){
            $searchVal = $_REQUEST['columns'][7]['search']['value'];
            $usersdatas->Where(function ($query) use ($searchVal) {
                if(strtolower($searchVal)=="active"){
                    $query->orWhere("users.status",1);
                }
                if(strtolower($searchVal)=="deactivated"){
                    $query->orWhere("users.status",0);
                }
                if(strtolower($searchVal)=="blocked"){
                    $query->orWhere("users.status",2);
                }
            });
        }

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->Where(function ($query) use ($searchValue) {
                $query->orWhere("users.name","like","%".$searchValue."%")
                ->orWhere("users.phone_no","like","%".$searchValue."%")
                ->orWhere("users.id","like","%".$searchValue."%")
                ->orWhere("users.email","like","%".$searchValue."%");
                if($searchValue=="Active" || $searchValue=="active"){
                    $query->orWhere("users.status",1);
                }
                if($searchValue=="Deactivated" || $searchValue=="deactivated"){
                    $query->orWhere("users.status",0);
                }
                if($searchValue=="Blocked" || $searchValue=="blocked"){
                    $query->orWhere("users.status",2);
                }
            });          
        }
         if($input['userfillter']==2){
            $usersdatas =$usersdatas->Where(function ($query){
                        $query->Where("email_verified_at",'!=',NULL);
                        $query->Where("phone_verified_at",'!=',NULL);
                    });
        }
        if($input['userfillter']==3){
            $usersdatas=$usersdatas->Where(function ($query){
                        $query->orWhere("email_verified_at",NULL);
                        $query->orWhere("phone_verified_at",NULL);
                    });
        }
        if($input['fromdate']!='' && $input['todate']!=''){
            $from    = Carbon::parse($input['fromdate'])
                 ->startOfDay()        // 2018-09-29 00:00:00.000000
                 ->toDateTimeString(); // 2018-09-29 00:00:00

            $to      = Carbon::parse($input['todate'])
                             ->endOfDay()          // 2018-09-29 23:59:59.000000
                             ->toDateTimeString();
            $usersdatas=$usersdatas->whereBetween('created_at', [$from, $to]);
        }
        $usersdatas->Where("users.role_id",'2');
        return $usersdatas;
    }
    public function getuser(Request $request){
    	$input=$request->all();
        $count = $this->getUserDetails($input);
        $totalCount = count($count->get());

        $getData = $this->getUserDetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['users'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['users'] as $user) {

            if($user->status==1){
                $status="<center><a href='#' class='btn bg-olive btn-flat margin disabled'>Active</a></center>";
            }else if($user->status==0){
               $status="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >Deactivated</a></center>";
            }else{
               $status="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >Blocked</a></center>";
            }

            $action = '<a href='.route('admin.users.view',$user->uuid).' class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square "></i></a>';
            $check ='<input type="checkbox" class="single"  name="id[]" value="'.$user->id.'">';
            $row = array("id"=>$check,"name"=>$user->name,"userid"=>$user->id,"email"=>$user->email,"mobile_no"=>$user->phone_no,"status"=>$status,"action"=>$action,'email_verify'=>$user->email_verified_at,'phone_verify'=>$user->phone_verified_at,'created_at'=>$user->created_at,'lastlogin'=>$user->last_activity);
            
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
        $users = DB::table('users')->where('uuid',$id)->first();
        if(!empty($users)){
            $users->following= DB::table('followers')->where('user_id',$users->id)->get()->count();
            $users->follower= DB::table('followers')->where('following',$users->id)->get()->count();
            $ads=DB::table('ads')->where('seller_id',$users->id)->where('status',1)->count();
            
            $fcount['following']= DB::table('followers')->where('user_id',$users->id)->get()->count();
            $fcount['follower']= DB::table('followers')->where('following',$users->id)->get()->count();
            $followingg = DB::table('followers')
                                ->rightJoin('users','users.id','=','followers.following')
                                ->where('user_id',$users->id)
                                ->select('followers.*','users.photo','users.name','users.uuid as useruuid')
                                ->get();
               
            $followers = DB::table('followers')
                       ->leftJoin('users','users.id','=','followers.user_id')
                       ->where('followers.following',$users->id)
                       ->select('followers.*','users.photo','users.name','users.uuid as useruuid')
                       ->get();  

            //echo"<pre>";print_r($followers);die;
            $users->ads=$ads;   
            $proofs = DB::table('proofs')
                ->leftjoin('users','users.id','=','proofs.user_id')
                ->where('proofs.user_id',$users->id)
                ->select('proofs.proof','proofs.verified','proofs.image','proofs.uuid')->get();
                
            $blockreason =Block_Reason::where('user_id',$users->id)->orderby('id','desc')->get();
            $reportingUsers=DB::table('report_users')
                            ->select('users.uuid','users.name as user_name')
                            ->leftJoin('users', 'report_users.user_id', '=', 'users.id')
                            ->where('report_users.report_user_id',$users->id)
                            ->groupby('users.id')
                            ->get()->toArray();

            return view('back.users.usersview',compact('users','fcount','followingg','followers','proofs','reportingUsers','blockreason'));
        }else{
            return back();
        }
    }

    public function adminaddwallet ( Request $request){
        $input=$request->all();
        //echo"<pre>";print_r($input);die;

        $date = new DateTime();
        $date->modify('+180 day');
        $expire_date = $date->format('Y-m-d H:i:s');

        $freepoint= new Freepoints;
        $freepoint->order_id = generateRandomString();
        $freepoint->user_id=$input['userid'];
        $freepoint->description=$input['description'];
        $freepoint->point=$input['point'];
        $freepoint->ads_id=NULL;
        $freepoint->status=1;
        $freepoint->used=0;
        $freepoint->expire_date=$expire_date;
        $freepoint->save();

        $user= User::find($input['userid']);
        $user->wallet_point=$user->wallet_point+$input['point'];
        $user->save();

        return back();
    }

    public function admindeductwallet ( Request $request){
        $input=$request->all();
        //echo"<pre>";print_r($input);die;


        $freepoint= new Freepoints;
        $freepoint->order_id = generateRandomString();
        $freepoint->user_id=$input['deduct_userid'];
        $freepoint->description=$input['deduct_description'];
        $freepoint->point=$input['deduct_point'];
        $freepoint->ads_id=NULL;
        $freepoint->status=0;
        $freepoint->used=1;
        $freepoint->expire_date=null;
        $freepoint->save();

        $user= User::find($input['deduct_userid']);
        $user->wallet_point=$user->wallet_point-$input['deduct_point'];
        $user->save();

        return back();
    }

    public function block(Request $request){
        $input=$request->all();
        //echo"<pre>";print_r($input);die;
        $users = User::where('uuid',$input['id'])->first();
        if(!empty($users)){
           $users->status=2;
           //$users->deactive_reason=$input['reason'];
           $users->save();
            $blockReason= [
                'user_id'  =>  $users->id,
                'reason'    => $input['reason'],
                'blocked_by'    => auth()->user()->id,
                'status' =>'Block',
            ];
            $newReason = new Block_Reason($blockReason);
            $newReason->save();
            $sessions = DB::table('sessions')->where('user_id',$users->id)->delete();

            $data = array('name' =>$users->name,'reason' =>$input['reason']);
            $sendmail = Mail::to($users->email)->send(new BlockMail($data));
            return redirect()->route('admin.users.view',$input['id']);
        }
        return back(); 
    }
    public function unblock(Request $request){
        $input=$request->all();
        $users = User::where('uuid',$input['id'])->first();
        if(!empty($users)){
            $adminmail = DB::table('settings')->first();
            $users->status=1;
            $users->deactive_reason=null;
            $users->save();
            $blockReason= [
                'user_id'  =>  $users->id,
                'reason'    => $input['reason'],
                'blocked_by'    => auth()->user()->id,
                'status' =>'Unblock',
            ];
            $newReason = new Block_Reason($blockReason);
            $newReason->save();
            $data = array('user' =>auth()->user()->name,
                          'user_id' =>auth()->user()->id,
                          'unblocked_user'=>$users->name,
                          'unblocked_user_id'=>$users->id,
                          'unblocked_user_uuid'=>$users->uuid,
                          'unblocked_at'=>$users->updated_at);
            $sendmail = Mail::to($adminmail->email)->send(new UnBlockMail($data));
            return redirect()->route('admin.users.view',$input['id']);
        }
        return back(); 
    }
    public function deleteuser(Request $request){
        $input=$request->all();
        $users = User::where('uuid',$input['userid'])->first();
        if(!empty($users)){

            $ads= DB::table('ads')->where('seller_id',$users->id)->pluck('id');
            if(!empty($ads)){
                DB::table('ads_image')->whereIn('ads_id',$ads)->delete();
                DB::table('ads_features')->whereIn('ads_id',$ads)->delete();
                DB::table('reject_reason')->whereIn('ads_id',$ads)->delete();
                DB::table('report_ads')->whereIn('report_ads_id',$ads)->delete();
                DB::table('post_values')->whereIn('post_id',$ads)->delete();
                DB::table('ads')->where('seller_id',$users->id)->delete();
            }
            DB::table('report_ads')->whereIn('user_id',$users->id)->delete();
            DB::table('ads_limits')->where('user_id',$users->id)->delete();
            DB::table('referrals')->where('user_id',$users->id)->delete();
            DB::table('verifications')->where('user_id',$users->id)->delete();
            DB::table('freepoints')->where('user_id',$users->id)->delete();
            DB::table('privacy')->where('user_id',$users->id)->delete();
            DB::table('proofs')->where('user_id',$users->id)->delete();
            DB::table('report_users')->where('user_id',$users->id)->delete();
            DB::table('city_request')->where('user_id',$users->id)->delete();

            $plans_purchase= DB::table('plans_purchase')->where('user_id',$users->id)->pluck('id');
            if(!empty($plans_purchase)){
                DB::table('purchase_billing')->whereIn('plan_id',$plans_purchase)->delete();
                DB::table('plans_purchase')->where('user_id',$users->id)->delete();
            }
            
            DB::table('billing_information')->where('user_id',$users->id)->delete();
            DB::table('description_user')->where('user_id',$users->id)->delete();
            DB::table('followers')->where('user_id',$users->id)->delete();
            DB::table('followers')->where('following',$users->id)->delete();
            DB::table('notification')->where('user_id',$users->id)->delete();
            DB::table('pearl_ads')->where('user_id',$users->id)->delete();
            DB::table('redeem')->where('user_id',$users->id)->delete();
            DB::table('referrals')->where('user_id',$users->id)->delete();
            DB::table('toplists_ads')->where('user_id',$users->id)->delete();
            DB::table('user_viewed_ads')->where('user_id',$users->id)->delete();
            
            DB::table('users')->where('id',$users->id)->delete();
            
            toastr()->success('User deleted!');
            return redirect()->route('admin.users');
        }
        toastr()->success('NO Data!');
        return back(); 
    }
    public function usermail(Request $request){
            $input = $request->all();
            //echo "<pre>";print_r($input);die;
            $request->validate([
                'email' =>'required|email',
                'description'=>'required',
                'user_id'=>'required'
                ]);
            
            if(!empty($insert)) {
            //echo "<pre>";print_r($insert);die;
            toastr()->warning('Illegal Access!');
            return back();
            //echo "<pre>";print_r($insert);die;
            }else{
            $store= new Description;
            $store->description=$input['description'];
            $store->user_id=$input['user_id'];
            $store->save();
            
            $data = array('email'=>$input['email'],'description'=>$input['description']);
            //echo "<pre>";print_r($data);die;
            Mail::to($data['email'])
                ->send(new SendMail($data));
            toastr()->success('Your Message has been send !');
            return back();
        }
    }

    public function listroles(){
        $roles = DB::table('roles')->where('id','!=','2')->latest()->get();
        //echo '<pre>';print_r( $roles );die;
        return view('back.users.roles',compact('roles'));
    }

    public function addrole(){
        $getSitePages = getSitePages();
        return view('back.users.addrole',compact('roles','getSitePages'));
    }
    public function saverole(Request $request){

        $input = $request->all();

        $request->validate([
            'role' => 'required|max:255'
        ]);

        $uuid = Uuid::generate(4);
        $slug = Str::slug($input['role'], '-');

        $roles_id = DB::table('roles')->insertGetId([
            'uuid' => $uuid,
            'name' => $input['role'],
            'slug'=>$slug,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $getSitePages = getSitePages();
        DB::table('role_accesses')->where('role_id',$roles_id)->delete();
        foreach ($getSitePages as $page_id => $page) {
            DB::table('role_accesses')->insert([  
                'role_id'=>$roles_id,
                'page_id'=>$page_id,
                'allow_all'=>(isset($input['allowallaccess'][$page_id])?$input['allowallaccess'][$page_id]:0),
                'view_all'=>(isset($input['viewaccess'][$page_id])?$input['viewaccess'][$page_id]:0),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        return redirect()->route('admin.listroles');
    }
    public function deleterole(Request $request){
        $input = $request->all();
        //echo '<pre>';print_r( $input);die;
        $users = User::where('role_id',$input['roleid'])->first();
        if(!empty($users)){
            toastr()->warning('Role Already Used !');
            return back();
        }else{
            $res=Role::where('id',$input['roleid'])->delete();
            $res=RoleAccess::where('role_id',$input['roleid'])->delete();
            toastr()->success('Role Deleted !');
            return back(); 
        }
        
    }
    public function editrole($ruuid){
        
        $getSitePages = getSitePages();
        $roledetails = DB::table('roles')->where('uuid',$ruuid)->first();
        $roleaccessdetails = DB::table('role_accesses')->where('role_id',$roledetails->id)->get()->toArray(); 
        $roleaccessdetailsid = array_column($roleaccessdetails, 'page_id'); 

        $extraroles = array();
        $array_keys = array_keys($getSitePages);
        $result = array_diff($array_keys, $roleaccessdetailsid);

        foreach ($result as $key => $value) {
            $extraroles[] = $getSitePages[$value];
        }
        //echo '<pre>';print_r( $getSitePages );die;

        return view('back.users.editrole')->with(['roledetails' => $roledetails,'getSitePages' => $getSitePages,'roleaccessdetails' => $roleaccessdetails,'extraroles'=>$extraroles]);
    }

    public function updaterole(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'role' => 'required|max:255'
        ]);


        $slug = Str::slug($input['role'], '-');
        
        $updateroleinfo = DB::table('roles')->where('uuid',$input['uuid'])->update([
            'name' => $input['role'],
            'slug' => $slug,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);


        $updateroleinfo = DB::table('roles')->where('uuid',$input['uuid'])->first();

        $getSitePages = getSitePages();
        DB::table('role_accesses')->where('role_id',$updateroleinfo->id)->delete();
        foreach ($getSitePages as $page_id => $page) {
            DB::table('role_accesses')
                ->insert([  'role_id'=>$updateroleinfo->id,
                            'page_id'=>$page_id,
                            'allow_all'=>(isset($input['allowallaccess'][$page_id])?$input['allowallaccess'][$page_id]:0),
                            'view_all'=>(isset($input['viewaccess'][$page_id])?$input['viewaccess'][$page_id]:(isset($input['allowallaccess'][$page_id])?$input['allowallaccess'][$page_id]:0)),
                ]);
        }

        return redirect('admin/role_access/role')->with('success','Role details has been added successfully!.');
    }

    public function addsubadmin(){
        $roles = DB::table('roles')->where('id','!=','2')->get();;
        $users = DB::table('users')->whereNotIn('role_id',['1','2'])->get();
        // echo '<pre>';print_r( $users );die;
        return view('back.users.adminusercreate',compact('users','roles'));
    }
    public function adminusers(){
        $users = DB::table('users')
                       ->select('users.*','roles.name as role_name')
                       ->leftJoin('roles','roles.id','=','users.role_id')
                       ->whereNotIn('role_id',['2'])->latest()
                       ->get();
        //echo '<pre>';print_r( $users );die;
        return view('back.users.subuserlist',compact('users'));
    }
    public function savesubadmin(Request $request){
        $input = $request->all();
        $uuid = Uuid::generate(4);

        $request->validate([
                'name' =>'required',
                'email' =>'required|email|unique:users',
                'password' =>'required',
                'role' =>'required',
                ]);

        DB::table('users')->insert(['uuid'=>$uuid,'name'=>$input['name'],'email'=>$input['email'],'password'=>Hash::make($input['password']),'role_id'=>$input['role'],'phone_no'=>time(),'email_verified_at'=>date('Y-m-d H:i:s'),'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
        return redirect()->route('admin.adminusers');
    }
    public function status_subadmin(Request $request){
        $input = $request->all();
        //echo '<pre>';print_r( $input);die;
        $users = User::where('uuid',$input['userid'])->first();
        if(!empty($users)){
            if($users->status==3){
                $users->status=1;
                $users->save();
            }else{
                $users->status=3;
                $users->save();
            }

            toastr()->success('Status Changed !');
            return back();
        }else{
            toastr()->warning('No data found !');
            return back(); 
        }
        
    }

    public function edit_subadmin($useruuid){
        $users = DB::table('users')->where('uuid',$useruuid)->first();
        if(empty($users)){
            //return redirect()->route('admin.adminusers');     
            toastr()->warning('No data found !');
            return back();       
        }

        $roles = DB::table('roles')->where('id','!=','2')->get();
        return view('back.users.adminuseredit',compact('users','roles'));
    }


    public function updatesubadmin(Request $request){
        $input = $request->all();
        if(!empty($input)){
            $users = User::where('uuid',$input['uuuid'])->first();

            $request->validate([
                    'name' =>'required',
                    'email' =>'required|email|unique:users,email,'.$users->id,
                    'role' =>'required',
                    ]);

            $users->name    =   $input['name'];
            $users->email   =   $input['email'];
            $users->role_id   =   $input['role'];
            if(!empty($input['password'])){
                $users->password = Hash::make($input['password']);
            }
            $users->save();
            return redirect()->route('admin.adminusers');
        }else{            
            toastr()->warning('No data found !');
            return back();     
        }
    }
    public function profilephoto(Request $request)
    {
        return view('back.users.profilephoto');
    }
    public function getUserphotoDetails()
    {
        $usersdatas = DB::table('users')
                     ->select('*')->where('users.status',1)->orderBy('id','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->Where(function ($query) use ($searchValue) {
                $query->orWhere("users.name","like","%".$searchValue."%")
                ->orWhere("users.email","like","%".$searchValue."%")
                ->orWhere("users.id","like","%".$searchValue."%");
            });          
        }
        $usersdatas->Where("users.role_id",'2')->Where("users.photo_temp","!=",null)->Where("users.photo_status",'0');
        return $usersdatas;
    }
    public function getuserphoto(Request $request){
        $count = $this->getUserphotoDetails();
        $totalCount = count($count->get());

        $getData = $this->getUserphotoDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['users'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['users'] as $user) {

            if($user->status==1){
                $status="<center><a href='#' class='btn bg-olive btn-flat margin disabled'>Active</a></center>";
            }else if($user->status==0){
               $status="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >Deactivated</a></center>";
            }else{
               $status="<center><a href='#' class='btn bg-maroon btn-flat margin disabled' >Blocked</a></center>";
            }
            
            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",$user->uuid).'">'.$user->name.' [ Userid : '.$user->id.' ]</a>';
            
            $action = '<a href='.route('admin.users.profilephotoview',$user->uuid).' class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square "></i></a>';
            $row = array("name"=>$link,"email"=>$user->email,"mobile_no"=>$user->phone_no,"status"=>$status,"action"=>$action,'email_verify'=>$user->email_verified_at,'phone_verify'=>$user->phone_verified_at,'created_at'=>$user->created_at,'lastlogin'=>$user->last_activity);
            
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
    public function viewphoto($id){
        $users = DB::table('users')->where('uuid',$id)->first();
        if(!empty($users)){
            return view('back.users.profilephotoverify',compact('users'));
        }else{
            return back();
        }
    }
    public function photoverify(Request $request){
        $input=$request->all();
        //echo"<pre>";print_r($input);die;
        $users = User::where('uuid',$input['id'])->first();
        if(!empty($users)){
           
           $users->photo=$users->photo_temp;
           $users->photo_temp=NULL;
           $users->photo_status=1;
           $users->save();
           
           addpoint($users->id,'profile_upload_point');
           
           toastr()->success('Profile Photo Verified !');
           return redirect()->route('admin.users.profilephoto');
        }
        return back(); 
    }
    public function photounverify(Request $request){
        $input=$request->all();
        //echo"<pre>";print_r($input);die;
        $users = User::where('uuid',$input['id'])->first();
        if(!empty($users)){
           $users->photo_temp=NULL;
           $users->photo_status=0;
           $users->save();
           toastr()->success('Profile Photo Rejected!');
           return redirect()->route('admin.users.profilephoto');
        }
        return back();  
    }
    public function unverifieddelete(Request $request){
        $input=$request->all();
        $data = explode(",", $input['deletedata']);
        $users = User::whereIn('id',$data)->get();
        if(!empty($users)){
            foreach ($users as $key => $user) {
                //echo"<pre>";print_r($user->id);die;
                DB::table('users')->where('id',$user->id)->delete();
            }
            echo "1";die;
        }else{
            echo "0";die;
        }

    }

}