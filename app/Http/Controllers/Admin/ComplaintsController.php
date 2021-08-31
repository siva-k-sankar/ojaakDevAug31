<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Ads;
use App\Chats;
use App\ChatsMessage;
class ComplaintsController extends Controller
{
    public function user(Request $request)
    {
    	return view('back.complaints.users');
	}
	public function usercomplaint()
    {
        $usersdatas = DB::table('report_users')
                     ->select('*')->leftJoin('users', 'report_users.user_id', '=', 'users.id')->where('users.status',1)->orderBy('report_users.id','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->Where(function ($query) use ($searchValue) {
                $query->orWhere("report_users.reason","like","%".$searchValue."%");
                $query->orWhere("report_users.user_id","like","%".$searchValue."%");
                $query->orWhere("report_users.report_user_id","like","%".$searchValue."%");
                $query->orWhere("users.id","like","%".$searchValue."%");
            });          
        }
        return $usersdatas;
    }
    public function getusercomplaint(Request $request){
    	$count = $this->usercomplaint();
        $totalCount = count($count->get());

        $getData = $this->usercomplaint();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['users'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['users'] as $user) {

            $action = '<a target="_blank" href='.route("admin.users.view",getUserUuid($user->report_user_id)).' class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square"></i></a>';
            
            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",getUserUuid($user->user_id)).'">'.get_name($user->user_id).' [ Userid : '.$user->user_id.' ]</a>';
            $link1='<a class="text-danger"target="_blank" href="'.route("admin.users.view",getUserUuid($user->report_user_id)).'">'.get_name($user->report_user_id).' [ Userid : '.$user->report_user_id.' ]</a>';
            
            $row = array("user"=>$link,"report"=>$link1,"reason"=>$user->reason,"comments"=>$user->comments,"date"=>$user->created_at,"action"=>$action);
            
            $datas[] = $row;
        }
            //echo "<pre>";print_r($user->report_user_id);die;

        $output = array(
            "draw"=> $_POST['draw'],
            "recordsTotal" => $totalCount, //$this->companies->count_all(),
            "recordsFiltered" => $totalCount, //$this->companies->count_filtered(),
            "data" => $datas,
        );
        //output to json format
        //echo "<pre>";print_r($user->report_user_id);die;
        echo json_encode($output);die;
    }
	public function ads(Request $request)
    {
    	return view('back.complaints.ads');
	}
	public function adscomplaint()
    {
        $usersdatas = DB::table('report_ads')
        			->leftJoin('ads', 'report_ads.report_ads_id', '=', 'ads.id')
        			->leftJoin('users', 'report_ads.user_id', '=', 'users.id')
                    ->select('report_ads.*','ads.uuid as uuid','ads.title as title','users.name as username','users.uuid as useruuid')->orderBy('id','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("report_ads.reason","like","%".$searchValue."%")
                		->orWhere("users.name","like","%".$searchValue."%")
                		->orWhere("report_ads.comments","like","%".$searchValue."%")
                		->orWhere("report_ads.created_at","like","%".$searchValue."%")
                		->orWhere("ads.title","like","%".$searchValue."%")
                        ->orWhere("report_ads.report_ads_id","like","%".$searchValue."%")
                        ->orWhere("users.id","like","%".$searchValue."%");
            });          
        }
        return $usersdatas;
    }
    public function getadscomplaint(Request $request){
    	$count = $this->adscomplaint();
        $totalCount = count($count->get());

        $getData = $this->adscomplaint();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['ads'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['ads'] as $ads) {

            
			$action = '<a target="_blank" href='.url('admin/ads/adsdata',$ads->uuid).' class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square "></i></a>';
            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",$ads->useruuid).'">'.$ads->username.' [ Userid : '.$ads->user_id.' ]</a>'; 
            $adslink='<a class="text-danger" href="'.url('admin/ads/adsdata',$ads->uuid).'">'.$ads->title.' [ Ads Primary id : '.$ads->report_ads_id.' ]</a>';
            $row = array("user"=>$link,"report"=>$adslink,"reason"=>$ads->reason,"comments"=>$ads->comments,"date"=>$ads->created_at,"action"=>$action);
            
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

    public function getcomplaintdetails(Request $request){
        $input = $request->all();
        $reportingAdsUsers = array();
        $showdata = '';
        if(!empty($input)){
            $reportingAdsdata=DB::table('report_ads')
                        ->select('report_ads.*','users.name as username')
                        ->leftJoin('users', 'report_ads.user_id', '=', 'users.id')
                        ->where('report_ads_id',$input['adid'])
                        ->where('users.uuid',$input['useruuid'])
                        ->get()->toArray();
            foreach ($reportingAdsdata as $key => $report) {
                $showdata .='<div class="form-group">
                    <label class="customradio"><span class="radiotextsty">Reason : </span></label>
                    '.$report->reason.'
                </div>
                <div class="form-group">
                    <label class="customradio"><span class="radiotextsty">Comments : </span></label>
                    '.$report->comments.'
                </div>
                <div class="form-group">
                    <label class="customradio"><span class="radiotextsty">Username : </span></label>
                    '.$report->username.'
                </div>';
                if(!empty($report->image)){
                    $showdata .='<div class="form-group">
                        <label class="customradio"><span class="radiotextsty">Attachments : </span></label>
                        <a href="'.url('public/uploads/complaints/').'/'.$report->image.'" target="_blank" >View</a> 
                    </div>';
                } 
                $showdata .='<div class="form-group">
                    <label class="customradio"><span class="radiotextsty">Created At : </span></label>
                    '.$report->created_at.'
                </div><hr>'; 
                
            }
        }

        return $showdata;
    }

    public function getcomplaintuserdetails(Request $request){
        $input = $request->all();
        $reportingAdsUsers = array();
        $showdata = '';
        if(!empty($input)){
            
            $reportingUsers=DB::table('report_users')
                            ->select('report_users.*','users.name as username')
                            ->leftJoin('users', 'report_users.user_id', '=', 'users.id')
                            ->where('users.uuid',$input['useruuid'])
                            ->get()->toArray();
            if(!empty($reportingUsers)){
                foreach ($reportingUsers as $key => $report) {
                    $showdata .='<div class="form-group">
                        <label class="customradio"><span class="radiotextsty">Reason : </span></label>
                        '.$report->reason.'
                    </div>
                    <div class="form-group">
                        <label class="customradio"><span class="radiotextsty">Comments : </span></label>
                        '.$report->comments.'
                    </div>
                    <div class="form-group">
                        <label class="customradio"><span class="radiotextsty">Username : </span></label>
                        '.$report->username.'
                    </div>';
                    if(!empty($report->image)){
                        $showdata .='<div class="form-group">
                            <label class="customradio"><span class="radiotextsty">Attachments : </span></label>
                            <a href="'.url('public/uploads/complaints/').'/'.$report->image.'" target="_blank" >View</a> 
                        </div>';
                    } 

                    $showdata .='<div class="form-group">
                        <label class="customradio"><span class="radiotextsty">Created At : </span></label>
                        '.$report->created_at.'
                    </div><hr>';

                    
                }
            }
        }

        return $showdata;
    }


    public function adminchattouser(Request $request)
    {
        $input=$request->all();
        if(isset($input['useruuid']) && $input['useruuid']!=''){
            $userid = DB::table('users')->where('uuid',$input['useruuid'])->first();
            if(isset($input['adsid']) && $input['adsid']!=''){
                //echo '<pre>';print_r( $userid->id );die;
                $ads=Ads::where('id',$input['adsid'])->first();
                $unique_chats_id=Auth::user()->id."_".$userid->id."_".$ads->id;
                $ulternative_unique_chats_id=$userid->id."_".Auth::user()->id."_".$ads->id;
                $chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
                if(empty($chat)){
                    $chat= Chats::firstOrCreate([
                        'unique_chats_id' => $unique_chats_id,
                        'ads_id' =>isset($ads->id)?$ads->id:null,
                        'user_1'=>Auth::user()->id,
                        'user_2'=>$userid->id,
                    ]);
                }

            }else{
                $unique_chats_id=Auth::user()->id."_".$userid->id;
                $ulternative_unique_chats_id=$userid->id."_".Auth::user()->id;
                $chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
                if(empty($chat)){
                    $chat= Chats::firstOrCreate([
                        'unique_chats_id' => $unique_chats_id,
                        'ads_id' =>null,
                        'user_1'=>Auth::user()->id,
                        'user_2'=>$userid->id,
                    ]);
                }

            }
        }else{
            $ads=Ads::where('id',$input['adsid'])->first();
            $unique_chats_id=Auth::user()->id."_".$ads->seller_id."_".$ads->id;
            $ulternative_unique_chats_id=$ads->seller_id."_".Auth::user()->id."_".$ads->id;
            $chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
                if(empty($chat)){
                    $chat= Chats::firstOrCreate([
                        'unique_chats_id' => $unique_chats_id,
                        'ads_id' =>isset($ads->id)?$ads->id:null,
                        'user_1'=>Auth::user()->id,
                        'user_2'=>$ads->seller_id,
                    ]);
                }
                
        }
                
        

        if(!empty($input['typemessage'])){
            $chatcreate= ChatsMessage::Create([
                    'chat_id' => $chat->unique_chats_id,
                    'msg' =>$input['typemessage'],
                    'user_id'=>Auth::user()->id,
                    ]);
            return 1;
        }
        return 0;
    }

}
