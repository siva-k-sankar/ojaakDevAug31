<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Privacy;
use DB;
use Auth;
use Uuid;
use Hash;
use DateTime;
use App\Customer_manage_user;
use App\Chats;
use App\ChatsMessage;

class SettingController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function index()
    {   
        return view('setting.setting');
    }
    public function privacy()
    {   
    	$privacy=DB::table('privacy')->where('user_id',Auth::id())->first();
    	//echo"<pre>1";print_r($privacy);die;
        // $nowdate = new DateTime('now');
        // $nowdate = $nowdate->format('Y-m-d h:i:s');
        // $date = strtotime(date("Y-m-d", strtotime($nowdate)) . " +6 month");
        // $date = date("Y-m-d H:i:s", $date);
        //echo"<pre>1";print_r($date);die;
        return view('setting.privacy',compact('privacy'));
    }
    public function privacyupdate(Request $request)
    {   
    	$input = $request->all();
    	$input['user_id']=Auth::id();
    	//echo"<pre>1";print_r($input);die;
        
        

    	$privacy=Privacy::where('user_id',Auth::id())->first();
    	if(!empty($privacy)){
    		$privacy->phone=$input['phone'];
    		$privacy->mail=$input['mail'];
    		$privacy->online=$input['online'];
    		$privacy->view_chat=$input['chat'];
    		$privacy->save();
    		toastr()->success(' Privacy Settings Updated Successfully !');
    	}else{
    		$privacy=Privacy::create([
    			'user_id' => $input['user_id'],
    			'phone' => $input['phone'],
    			'mail' => $input['mail'],
    			'online' => $input['online'],
    			'view_chat' => $input['chat']
    		]);
    		toastr()->success(' Privacy Settings Created Successfully !');
    	}
    	return back();
    }
    public function notification()
    {   
    	$notification=DB::table('privacy')->where('user_id',Auth::id())->first();
    	//echo"<pre>1";print_r($privacy);die;
        return view('setting.notification',compact('notification'));
    }
    public function notificationupdate(Request $request)
    {   
    	$input = $request->all();
    	$input['user_id']=Auth::id();
    	//echo"<pre>1";print_r($input);die;
        
    	$notification=Privacy::where('user_id',Auth::id())->first();
    	if(!empty($notification)){
    		$notification->recommendations=$input['recommend'];
    		$notification->offers=$input['offers'];
    		$notification->save();
    		toastr()->success(' Notification Settings Updated Successfully !');
    	}else{
    		$notification=Privacy::create([
    			'user_id' => $input['user_id'],
    			'recommendations' => $input['recommend'],
    			'offers' => $input['offers']
    		]);
    		toastr()->success(' Notification Settings Created Successfully !');
    	}
    	return back();
    }
    public function manageusers()
    {   
    	$users=DB::table('users')->where('id','!=',Auth::id())->where('role_id','=','2')->where('status',1)->get();
    	//echo"<pre>";print_r($users);die;
        $userdata=array();
        foreach ($users as $usersarray) {
            array_push($userdata, $usersarray->id);
        }
        
    	$block_user= DB::table('customer_manage_user')->where('user_id',Auth::id())->whereIn('block_user_id',$userdata)->get();
        return view('setting.manage_user',compact('users','block_user'));
    }
    public function manageusersblock(Request $request)
    {   
    	$input = $request->all();
        //echo"<pre>";print_r($input);die;
        if(!empty($input['user'])){
    		$block_user= DB::table('customer_manage_user')->where('user_id',Auth::id())->where('block_user_id',$input['user'])->first();
			if(!empty($block_user)){
				toastr()->error('This User has been Blocked Already !');
    			return back();
			}else{
                $ifadmin=DB::table('users')->where('id',$input['user'])->where('role_id',2)->first();
                if (!empty($ifadmin)) {
                    $block_user_create=Customer_manage_user::firstOrCreate([
                    'user_id' => Auth::id(),
                    'block_user_id' =>$input['user']
                    ]);
                    toastr()->success('This User has been Blocked !');
                    
                    if($input['chat']=='chat'){
                        return back();
                    }
                    return back();
                } else {
                        toastr()->warning('You are not  eligible for blocking this user!');
                        return back();
                }
			}
    	}else{
    		toastr()->error(' Not Yet Select User !');
    		return back();	
    	}
    }
     public function manageusersunblockchat(Request $request)
    {   
        $input = $request->all();
        //echo"<pre>";print_r($input);die;
        if(!empty($input)){
            $block_user_unblock=Customer_manage_user::where('user_id',Auth::id())->where('block_user_id',$input['user'])->first();
            if(!empty($block_user_unblock)){
                $chat=DB::table('chats')->where('user_1',Auth::id())->orwhere('user_2',Auth::id())->pluck('unique_chats_id');
                $chats_message=DB::table('chats_message')->whereIn('chat_id',$chat)->where('status',0)->get();
                //echo"<pre>";print_r($chats_message);die;
                foreach ($chats_message as $key => $status) {
                    $statusfind=ChatsMessage::find($status->id); 
                    $statusfind->status=1;
                    $statusfind->save();
                }
                $block_user_unblock->delete();
                toastr()->success(' Un Blocked Successfully!');
                return back();
            }else{
                toastr()->error(' Invalid User Data!');
                return back();
            }
        }else{
                toastr()->error('Not Yet User Data Received!');
                return back();  
        }
    }

    public function manageusersunblock($block_user)
    {   
    	if(!empty($block_user)){
    		$block_user_unblock=Customer_manage_user::where('user_id',Auth::id())->where('block_user_id',$block_user)->first();
    		if(!empty($block_user_unblock)){
                $chat=DB::table('chats')->where('user_1',Auth::id())->orwhere('user_2',Auth::id())->pluck('unique_chats_id');
                $chats_message=DB::table('chats_message')->whereIn('chat_id',$chat)->where('status',0)->get();
                //echo"<pre>";print_r($chats_message);die;
                foreach ($chats_message as $key => $status) {
                    $statusfind=ChatsMessage::find($status->id); 
                    $statusfind->status=1;
                    $statusfind->save();
                }
                $block_user_unblock->delete();
                toastr()->success(' UnBlocked Successfully!');
    			return back();
    		}else{
    			toastr()->error(' Invalid User Data!');
    			return back();
    		}
    	}else{
    			toastr()->error('Not Yet User Data Received!');
    			return back();	
    	}
    }
    public function deactiveUser(Request $request)
    {   
    	return view('setting.deactive_user');
    }
    public function deactiveUserconfirm(Request $request)
    {    
        $input=$request->all();
        //echo"<pre>";print_r($input);die;
        if(empty($input['reason'])){
            toastr()->error('Your Not Select The Reason !');
            return back();
        }
        $reason=$input['reason'];
        if(!empty($input['other'])){
           $reason=$input['other'];
        }
        $users=User::where('id',Auth::id())->first();
    	if(!empty($users)){
    		$users->status='0';
            $users->deactive_reason=$reason;
    		$users->save();
    	}
        toastr()->error('Your Account has been deactivate');
    	return redirect()->route('ajaxlogout');
    }	
    public function logoutPage(Request $request)
    {   
        $loginsession=DB::table('sessions')->where('user_id',Auth::user()->id)->groupBy('ip_address')->get();
        return view('setting.logoutpage',compact('loginsession'));
    }
    public function logoutSave(Request $request)
    {   $input=$request->all();
        //echo"<pre>";print_r($input);die;
        if (Auth::check()) {
            if(!isset($input)){
                toastr()->warning('Request Missmatch');
                return back();
            }
            if($input['Slogout']=='1'){
                    \Auth::Logout();  
                    toastr()->success('Logout Successfully');
                    return back();
            }
            if($input['Slogout']=='0'){
                    toastr()->warning('Do not take any action');
                    return back();
            }
            
            $userdata = User::find(Auth::user()->id);
            
            if (Hash::check($request->get('password'), $userdata->password)) {
                
                if($input['Slogout']=='2'){
                    \Auth::LogoutOtherDevices($input['password']);
                    //Auth::user()->AauthAcessToken()->delete();  
                    toastr()->success('Logout Other Devices Successfully.But the changes will change for a few minutes');
                    return back();
                }
                if($input['Slogout']=='3'){
                    \Auth::LogoutOtherDevices($input['password']);
                    //Auth::user()->AauthAcessToken()->delete();
                    \Auth::Logout();  
                    toastr()->success('Logout All Devices Successfully.But the changes will change for a few minutes');
                }
                    
            }else{
                    toastr()->warning('Current Password is Mismatch.try again...');
                    return redirect()->back();   
            }
        }
        return back();
    }
    public function getlogindevice(Request $request)
    {   
        $loginsession=DB::table('sessions')->where('user_id',Auth::user()->id)->groupBy('ip_address');

        $totalCount = count($loginsession->get());
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $loginsession->offset($_REQUEST['start']);
            $loginsession->limit($_REQUEST['length']);

        }
        $data['login'] = $loginsession->get();
        $datas = array();
        $row = array();
        foreach ($data['login'] as $login) {
            $row = array("ip"=>$login->ip_address,"useragent"=>Str_limit($login->user_agent,90));
            $datas[] = $row;
        }
        $output = array(
            "draw"=> $_POST['draw'],
            "recordsTotal" => $totalCount, //$this->login->count_all(),
            "recordsFiltered" => $totalCount, //$this->login->count_filtered(),
            "data" => $datas,
        );
        echo json_encode($output);die;
    }
}
