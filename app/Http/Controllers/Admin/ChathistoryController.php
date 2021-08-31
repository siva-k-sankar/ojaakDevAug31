<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Ads;
use App\Chats;
use App\ChatsMessage;

class ChathistoryController extends Controller
{
    public function index(){
		return view('back.chathistory.chathistory');
	}
	public function chat(){
		return view('back.complaints.chat');
	}
	public function complaintchat()
    {
        $usersdatas = DB::table('chats')
            ->leftJoin('users', 'chats.user_1', '=', 'users.id')
			->select('chats.unique_chats_id','chats.user_1','chats.user_2','chats.ads_id','chats.created_at')
			->where('chats.user_1','=',Auth::user()->id)
			//->orwhere('chats.user_2','=',Auth::user()->id)
			->orderBy('chats.updated_at','desc');
			//->get()->toArray();

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->Where(function ($query) use ($searchValue) {
                $query->Where("chats.user_2","like","%".$searchValue."%");
            });          
        }
        return $usersdatas;
    }
    public function getcomplaintchat(Request $request){
    	$count = $this->complaintchat();
        $totalCount = count($count->get());

        $getData = $this->complaintchat();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['chats'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['chats'] as $chat) {

            $action = '<a target="_blank" href='.route("admin.complaint.chatmessage",$chat->unique_chats_id).' class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square"></i></a>';
            
            $link1='<a class="text-danger" href="javascript:void(0);">'.getUserEmail($chat->user_1).'</a>';
            
            //$link1='<a class="text-danger"target="_blank" href="'.route("admin.users.view",getUserUuid($user->report_user_id)).'">'.get_name($user->report_user_id).'</a>';
             $link2='<a class="text-danger" href="'.route("admin.users.view",getUserUuid($chat->user_2)).'">'.get_username($chat->user_2).' [ Userid : '.$chat->user_2.' ]</a>';
            
            $row = array("admin"=>$link1,"user"=>$link2,"ads"=>get_adsname($chat->ads_id),"action"=>$action);
            
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
    public function chatmessage($id){
        $chat=Chats::where('unique_chats_id',$id)->first();
        if(!empty($chat)){
            $chatmessage= ChatsMessage::where('chat_id',$chat->unique_chats_id)->where('msg','!=',null)->orderby('created_at','desc')->get();
            $adminid=$chat->user_1;
            return view('back.complaints.chatmessage',compact('chatmessage','adminid'));
        }else{
            toastr()->warning('chat Invalid!');
            return back();
        }
    }
    public function chatmessagesave(Request $request){
        $input = $request->all();
        $id=$input['id'];
        $chat=Chats::where('unique_chats_id',$id)->first();
        if(!empty($chat)){
            $chatcreate= ChatsMessage::Create([
                    'chat_id' => $chat->unique_chats_id,
                    'msg' =>$input['message'],
                    'user_id'=>Auth::user()->id,
                    'status'=>1,
                    ]);
            return redirect()->route('admin.complaint.chatmessage',$id);
        }else{
            toastr()->warning('chat Invalid!');
            return back();
        }
    }
}
