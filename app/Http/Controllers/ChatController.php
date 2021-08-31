<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use App\Chats;
use App\ChatsMessage;
use App\Ads;
use App\Privacy;
use Image;
use Uuid;
use Carbon\Carbon;
class ChatController extends Controller
{
	public function __construct()
    {
        $this->middleware(['auth','users']);
    }
   	public function index(Request $request){
   		$input=$request->all();
   		if(Auth::check()){
   			$user=User::find(Auth::user()->id);
	   			return view('chat',compact('user'));
   		}else{
   			toastr()->warning('Login to continue!');
   			return back();
   		}
   	}
   	
   	public function chat($adsid,$sellerid){
		if(Auth::check()){
			$check=Ads::where('uuid',$adsid)->where('seller_id',getUserId($sellerid))->first();
			if(!empty($check)){
				$unique_chats_id=Auth::user()->id."_".$check->seller_id."_".$check->id;
	   			$ulternative_unique_chats_id=$check->seller_id."_".Auth::user()->id."_".$check->id;
	   			
	   			$chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
	   			if(empty($chat)){
					$chat= Chats::firstOrCreate([
						'unique_chats_id' => $unique_chats_id,
						'ads_id' =>$check->id,
						'user_1'=>Auth::user()->id,
						'user_2'=>$check->seller_id,
					]);
	   			}
	   			$user=User::find(getUserId($sellerid));
	   			return view('chatViaAds',compact('user','chat'));
   			}else{
   				toastr()->warning('Invalid Data!');
   				return view('chat');
   			}
   		}else{
   			toastr()->warning('Login to continue!');
   			return back();
   		}
	}
	// public function ajaxSaveMessage(Request $request){
	// 	$input=$request->all();
	// 	$ads=Ads::where('uuid',$input['adsid'])->where('seller_id',getUserId($input['sellerid']))->first();
	// 	$unique_chats_id=Auth::user()->id."_".$ads->seller_id."_".$ads->id;
	//    	$ulternative_unique_chats_id=$ads->seller_id."_".Auth::user()->id."_".$ads->id;
	   			
	//    	$chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
	//    	$Blockcheck=BlockedUserChat(Auth::user()->id,getUserId($input['sellerid']));
	// 	if($Blockcheck['ref']==1 ){
	// 		$status=0;
	// 	}elseif($Blockcheck['ref']==3){
	// 		$status=0;
	// 	}else{
	// 		$status=1;
	// 	}
	// 	if(!empty($chat)){
	// 		$random = Uuid::generate(4);
	// 		if(!empty($request->file('chatimage'))){
	// 				$files=$request->file('chatimage');
	// 				$imgname = time().$random.'.'.$files->getClientOriginalExtension();
 //                    $destinationPath = public_path('uploads/chat/');
 //                    $img = Image::make($files->getRealPath());
 //                    $img->resize(300, null, function ($constraint) { $constraint->aspectRatio(); });
 //                    $img->save($destinationPath.'/'.$imgname);
					
	// 				$chatcreate= ChatsMessage::Create([
	// 				'chat_id' => $chat->unique_chats_id,
	// 				'image' =>$imgname,
	// 				'user_id'=>Auth::user()->id,
	// 				'status'=>$status,
	// 				'read_status'=>0,
	// 				]);

	// 		}
	// 		if(!empty($request->file('chatvideo'))){
	// 				$files=$request->file('chatvideo');
	// 				$videoname = time().$random.'.'.$files->getClientOriginalExtension();
 //                    $destinationPath = public_path('uploads/chatvideo/');
 //                    $files->move($destinationPath, $videoname);
                    
 //                    $chatcreate= ChatsMessage::Create([
	// 				'chat_id' => $chat->unique_chats_id,
	// 				'video' =>$videoname,
	// 				'user_id'=>Auth::user()->id,
	// 				'status'=>$status,
	// 				'read_status'=>0,
	// 				]);
	// 		}
	// 		if(!empty($input['typemessage'])){
	// 				$chatcreate= ChatsMessage::Create([
	// 				'chat_id' => $chat->unique_chats_id,
	// 				'msg' =>$input['typemessage'],
	// 				'user_id'=>Auth::user()->id,
	// 				'status'=>$status,
	// 				'read_status'=>0,
	// 				]);
	// 		}
	// 		if(!empty($input['location'])){
	// 				$mapimage=$input['latitude'].",".$input['longitude'];
	// 				$chatcreate= ChatsMessage::Create([
	// 				'chat_id' => $chat->unique_chats_id,
	// 				'location' =>$mapimage,
	// 				'user_id'=>Auth::user()->id,
	// 				'status'=>$status,
	// 				'read_status'=>0,
	// 				]);
	// 		}
	// 		if(!empty($chatcreate)){
	// 			if($chat->user_1 == Auth::user()->id){
	// 				$chat->user_2_read_status="0";
	// 			}else{
	// 				$chat->user_1_read_status="0";
	// 			}
	// 			$chat->updated_at=$chatcreate->created_at;
	// 			$chat->save();
	// 		}
			
	// 		return 'save';die;
	//    	}
	// 	return 'fail';die;
	// }
	// public function ajaxGetMessage(Request $request){
	// 	$input=$request->all();
	// 	$msg='';
	// 	$ads=Ads::where('uuid',$input['adsid'])->where('seller_id',getUserId($input['sellerid']))->first();
	// 	$unique_chats_id=Auth::user()->id."_".$ads->seller_id."_".$ads->id;
	//    	$ulternative_unique_chats_id=$ads->seller_id."_".Auth::user()->id."_".$ads->id;
	//    	$chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
	   	
	//    	$Blockcheck=BlockedUserChat(Auth::user()->id,getUserId($input['sellerid']));
	// 	if($Blockcheck['ref']==1){
	// 		$chatcreate= ChatsMessage::where('chat_id',$chat->unique_chats_id)->orderby('created_at','asc')->get();
	// 	}else{
	// 		$chatcreate= ChatsMessage::where('chat_id',$chat->unique_chats_id)->where('status',1)->orderby('created_at','asc')->get();
	// 	}
	   	
	//    	$imageurl=url('public/uploads/ads/',get_adsphoto($ads->id));
	//    	$msg='<div class="ads_detail_inner_wrap">
	// 							<a href="'.route('adsview.getads',$ads->uuid).'">
	// 								<div class="ads_img_outer_wrap">
	// 									<img src="'.$imageurl.'" title="" src="">
	// 								</div>
	// 								<h2>'.get_adsname($ads->id).'</h2>
	// 								<span>₹'.get_adsprice($ads->id).'</span>
	// 							</a>
	// 						</div>';
 //           foreach ($chatcreate as $key => $message) {
	//    		if($message->user_id == Auth::user()->id){
	//    			if(!empty($message->image)){
	//    				$chatimage=url('public/uploads/chat',$message->image);
	//    				$msg.='<div class="d-flex justify-content-end receiver_ms_wrap mb-4">
	// 							<div class="msg_cotainer_send  image_sent_outer_wrap">
	// 								<div class="img_sent_wrap">
	// 									<img src="'.$chatimage.'" title="" alt="">
	// 								</div>
	// 								<span class="msg_time_send">'.time_elapsed_string($message->created_at).'</span>
	// 							</div>
	// 						</div>';

	//    			}
	//    			if(!empty($message->location)){
	//    				$location=explode(",", $message->location);
	//    				$mapkey=getStaticMapKey();
	//    				$locationurl="https://www.google.co.in/maps/@".$location[0].",".$location[1].",15z";
	//    				$locationimage="https://maps.googleapis.com/maps/api/staticmap?center=".$location[0]."%2C".$location[1]."&amp;language=en&amp;size=640x256&amp;zoom=16&amp;scale=1&amp;&markers=color:red%7Clabel:%7C".$location[0].",".$location[1]."&key=".$mapkey."";
	//    				$msg.='<div class="d-flex justify-content-end receiver_ms_wrap mb-4">
	// 							<div class="msg_cotainer_send  image_sent_outer_wrap">
	// 								<div class="img_sent_wrap">
	// 									<a href="'.$locationurl.'" target="_blank">
	// 									  <img class="img-fluid" src="'.$locationimage.'"/>
	// 									</a>
	// 								</div>
	// 								<span class="msg_time_send">'.time_elapsed_string($message->created_at).'</span>
	// 							</div>
	// 						</div>';

	//    			}
	//    			if(!empty($message->msg)){
	//    				$msg.='<div class="d-flex justify-content-end receiver_ms_wrap mb-4">
	// 							<div class="msg_cotainer_send">
	// 								<h2>'.$message->msg.'</h2>
	// 								<span class="msg_time_send">'.time_elapsed_string($message->created_at).'</span>
	// 							</div>
	// 						</div>';

	//    			}
	//    			if(!empty($message->video)){
	//    				$chatvideo=url('public/uploads/chatvideo',$message->video);
	//    				$msg.='<div class="d-flex justify-content-end receiver_ms_wrap mb-4">
	// 							<div class="msg_cotainer_send  image_sent_outer_wrap">
	// 								<div class="img_sent_wrap">
	// 									<a href="'.$chatvideo.'" download>
	// 									  	<video class="embed-responsive" >
	// 										  <source src="'.$chatvideo.'" type="video/mp4">
	// 										  <source src="'.$chatvideo.'" type="video/ogg">
	// 										  <source src="'.$chatvideo.'" type="video/3gp">
	// 										</video>
	// 									</a>
	// 								</div>
	// 								<span class="msg_time_send">'.time_elapsed_string($message->created_at).'</span>
	// 							</div>
	// 						</div>
	//    				';

	//    			}
	   			
	// 		}else{
	// 			if(!empty($message->image)){
	// 				$chatimage=url('public/uploads/chat',$message->image);
	// 				$msg.='<div class="d-flex justify-content-start sender_ms_wrap mb-4">
	// 							<div class="msg_cotainer  image_sent_outer_wrap">
	// 								<div class="img_sent_wrap">
	// 									<img src="'.$chatimage.'" title="" alt="">
	// 								</div>
	// 								<span class="msg_time">'.time_elapsed_string($message->created_at).'</span>
	// 							</div>
	// 						</div>';

	// 			}
	// 			if(!empty($message->location)){
	//    				$location=explode(",", $message->location);
	//    				$mapkey=getStaticMapKey();
	//    				$locationurl="https://www.google.co.in/maps/@".$location[0].",".$location[1].",15z";
	//    				$locationimage="https://maps.googleapis.com/maps/api/staticmap?center=".$location[0]."%2C".$location[1]."&amp;language=en&amp;size=640x256&amp;zoom=16&amp;scale=1&amp;&markers=color:red%7Clabel:%7C".$location[0].",".$location[1]."&key=".$mapkey."";
	//    				$msg.='<div class="d-flex justify-content-start sender_ms_wrap mb-4">
	// 							<div class="msg_cotainer  image_sent_outer_wrap">
	// 								<div class="img_sent_wrap">
	// 									<a href="'.$locationurl.'" target="_blank">
	// 									  <img class="img-fluid" src="'.$locationimage.'"/>
	// 									</a>
	// 								</div>
	// 								<span class="msg_time">'.time_elapsed_string($message->created_at).'</span>
	// 							</div>
	// 						</div>';

	//    			}
	// 			if(!empty($message->msg)){
	// 				$msg.='<div class="d-flex justify-content-start sender_msg_wrap mb-4">
	// 							<div class="msg_cotainer">
	// 								<h2>'.$message->msg.'</h2>
	// 								<span class="msg_time">'.time_elapsed_string($message->created_at).'</span>
	// 							</div>
	// 						</div>';
	// 			}
	// 			if(!empty($message->video)){
	// 				$chatvideo=url('public/uploads/chatvideo',$message->video);
	// 				$msg.='<div class="d-flex justify-content-start sender_ms_wrap mb-4">
	// 							<div class="msg_cotainer image_sent_outer_wrap">
	// 								<div class="img_sent_wrap">
	// 									<a href="'.$chatvideo.'" download>
	// 									  	<video class="embed-responsive" >
	// 										  <source src="'.$chatvideo.'" type="video/mp4">
	// 										  <source src="'.$chatvideo.'" type="video/ogg">
	// 										  <source src="'.$chatvideo.'" type="video/3gp">
	// 										</video>
	// 									</a>
	// 								</div>
	// 								<span class="msg_time">'.time_elapsed_string($message->created_at).'</span>
	// 							</div>
	// 						</div>';
	// 			}
	// 		}
	//    	}	   	
	//    	return $msg;die;
	// }

	public function getuserdata(Request $request){
		$msg='';
		$input=$request->all();
		$userblock=array();
		$block_user= DB::table('customer_manage_user')->where('user_id',Auth::user()->id)->get();
		foreach ($block_user as $usersarray) {
			array_push($userblock, $usersarray->block_user_id);
		}
		//echo "<pre>";print_r($userblock);die;

		
		$findchats=DB::table('chats')
			->orWhere(function($query) {
				$query->where('chats.user_1','=',Auth::user()->id);
				$query->orwhere('chats.user_2','=',Auth::user()->id);
            })
            /*->Where(function($query) use ($userblock) {
				$query->whereNotIn('chats.user_1',$userblock);
				$query->whereNotIn('chats.user_2',$userblock);
            })*/
			->orderBy('chats.updated_at','desc')
			->get()->toArray();

			if(!empty($findchats)){
				$unread_msg_count_id = array();
				foreach ($findchats as $key => $chatid) {
				
					if($chatid->user_1 == Auth::user()->id && $chatid->user_1_read_status=='0'){
						$unread_msg_count_id[]=$chatid->id;
						
					}
					if($chatid->user_2 == Auth::user()->id && $chatid->user_2_read_status=='0'){
						$unread_msg_count_id[]=$chatid->id;
					}
				}
			}
		//echo "<pre>";print_r($unread_msg_count_id);die;

		$chatids=array();
		$countunread=0;
		foreach ($findchats as $key => $chatid) {
				if($chatid->user_1 == Auth::user()->id && $chatid->user_1_read_status=='0'){
					$countunread++;
				}
				if($chatid->user_2 == Auth::user()->id && $chatid->user_2_read_status=='0'){
					$countunread++;
				}
		}
		if($input['message_status'] == '0'){ //0=unread 1=read 3=all
			foreach ($findchats as $key => $chatid) {
				
				if($chatid->user_1 == Auth::user()->id && $chatid->user_1_read_status==$input['message_status']){
					$chatids[]=$chatid->unique_chats_id;
					
				}
				if($chatid->user_2 == Auth::user()->id && $chatid->user_2_read_status==$input['message_status']){
					$chatids[]=$chatid->unique_chats_id;
				}
			}
		}else if($input['message_status'] == '1'){
			foreach ($findchats as $key => $chatid) {
				if($chatid->user_1 == Auth::user()->id && $chatid->user_1_read_status==$input['message_status']){
					$chatids[]=$chatid->unique_chats_id;
				}
				if($chatid->user_2 == Auth::user()->id && $chatid->user_2_read_status==$input['message_status']){
					$chatids[]=$chatid->unique_chats_id;
				}
			}
		}else {
			foreach ($findchats as $key => $chatid) {
				if($chatid->user_1 == Auth::user()->id){
					$chatids[]=$chatid->unique_chats_id;
				}
				if($chatid->user_2 == Auth::user()->id){
					$chatids[]=$chatid->unique_chats_id;
				}
			}
		}
		$data=DB::table('chats')
			->select('id','user_1','user_2','ads_id','updated_at')
			->whereIn('chats.unique_chats_id',$chatids)
			->orderBy('chats.updated_at','desc')
			->get()->toArray();
		//echo "<pre>";print_r($chatids);
		//echo "<pre>";print_r($data);die;


   				//echo "<pre>";print_r($chatids);
   				//echo "<pre>";print_r($data);die;

		/*$data=DB::table('chats')
			->select('user_1','user_2','ads_id','updated_at')
			->orWhere(function($query) {
				$query->where('chats.user_2','=',Auth::user()->id);
                $query->orwhere('chats.user_1','=',Auth::user()->id);
                
            })
			
			->where('chats.user_2_read_status',1)
			->orwhere('chats.user_1_read_status',1)
			->orderBy('chats.updated_at','desc')
			->get()->toArray();*/
			
			/*$data=DB::table('chats')
			->select('user_1','user_2','ads_id','updated_at')
			->where('chats.user_1','=',Auth::user()->id)
			->orwhere('chats.user_2','=',Auth::user()->id)
			->orderBy('chats.updated_at','desc')
			->get()->toArray();*/

		if(!empty($data)){
			$uniquesusers = array();

			foreach ($data as $key => $userids) {
				
				if($userids->user_2 == Auth::user()->id){
					$uniquesusers[$key] = $userids->user_1.','.$userids->ads_id.','.$userids->updated_at.','.$userids->id;
				}else{
					$uniquesusers[$key] = $userids->user_2.','.$userids->ads_id.','.$userids->updated_at.','.$userids->id;				
				}

			}

			foreach ($uniquesusers as $key => $value) {
				$chatdata=explode(",",$value);
				$unreadMsg = "";
				if(isset($chatdata[3]) && in_array($chatdata[3], $unread_msg_count_id)){
					$unreadMsg = "color:#000;";
				}
					
				$imageurl=url('public/uploads/profile/small',get_userphoto($chatdata[0]));
				if(isset($chatdata[1]) && $chatdata[1]!=''){
					$urlid=getUserUuid(Auth::user()->id)."_".getUserUuid($chatdata[0])."_".getAdsUuid($chatdata[1]);
					//$time=get_chatTime($chatdata[0],$chatdata[1]);
					$time=$chatdata[2];
					
				  	$msg.='<li><a href="'.url('chat',$urlid).'" class="chat-user-outer-wrap">
								<div class="row chat-username-outer-wrap" id="chatlist_'.$urlid.'">
									<div class="col-md-2 chat-img-wrap">
										<img src="'.$imageurl.'">
									</div>
									<div class="col-md-8 chat-user-name" >
										<h6 style="'.$unreadMsg.'">'.get_username($chatdata[0]).'</h6>
										<h5 style="'.$unreadMsg.'">'.str_limit(get_adsname($chatdata[1]),17).'<span>'.time_elapsed_string($time).'</span></h5>
									</div>
									<div class="col-md-2">
										<div class="chat-details">
											<h6><i class="fa fa-ellipsis-h"></i></h6>
										</div>
									</div>
								</div>
							</a></li>';
				}else{
					$urlid=getUserUuid(Auth::user()->id)."_".getUserUuid($chatdata[0]);
					/*$time=get_adminchatTime($chatdata[0]);*/
					$time=$chatdata[2];
					$msg.='<li><a href="'.url('chat',$urlid).'" class="chat-user-outer-wrap">
								<div class="row chat-username-outer-wrap" id="chatlist_'.$urlid.'">
									<div class="col-md-2 chat-img-wrap">
										<img src="'.$imageurl.'">
									</div>
									<div class="col-md-8 chat-user-name">
										<h6 style="'.$unreadMsg.'">'.get_username($chatdata[0]).'</h6>
										<h5 style="'.$unreadMsg.'"><span>'.time_elapsed_string($time).'</span></h5>
									</div>
									<div class="col-md-2">
										<div class="chat-details">
											<h6><i class="fa fa-ellipsis-h"></i></h6>
										</div>
									</div>
								</div>
							</a></li>';
				}
			}
		}
		if($msg==""){
			$msg='<a href="javascript:void(0);">
					<li style="border:none!important;">
						<div class="row">
					  		<div class="col-12 col-md-12 col-lg-12 col-xl-12">
					  			<div class="alert alert-success" role="alert">
								  <h4 class="alert-heading">No messages, yet?</h4>
								  <p>We’ll keep messages for any item you’re selling in here</p>
								  <hr>
								  
								</div>
							</div>
						</div>
					</li>
					</a>';
		}
		$userajax['notification']=$countunread;
		$userajax['msg']=$msg;	
		return $userajax;die;
	}
	public function syncmessageview($id){
   		if(Auth::check()){
			$urldata=explode("_",$id);
			if(isset($urldata[2]) && $urldata[2]!=''){
				$unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1])."_".getAdsId($urldata[2]);
	   			$ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0])."_".getAdsId($urldata[2]);
   			}else{

				$unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1]);
	   			$ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0]);
   			}
   			$chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();

			if(!empty($chat)){
   				$user=User::find(getUserId($urldata[1]));
   				$privacy=Privacy::where('user_id',$user->id)->first();


				$userblock=array();
				$block_user= DB::table('customer_manage_user')->where('user_id',Auth::user()->id)->get();
				foreach ($block_user as $usersarray) {
					array_push($userblock, $usersarray->block_user_id);
				}
				$blockedUsers = 0;
				if(!empty($userblock) && in_array($user->id, $userblock)){
					$blockedUsers = 1;
				}

				//echo "<pre>";print_r($user);die;

	   			return view('chatsync',compact('user','chat','privacy','blockedUsers'));
   			}else{
   				toastr()->error('Unauthorized Access');
					return back();
   			}
   		}else{
   			toastr()->warning('Login to continue!');
   			return back();
   		}
   	}
	public function ajaxSyncSaveMessage(Request $request){
		$input=$request->all();
		$urldata=explode("_",$input['urlid']);
		if(isset($urldata[2]) && $urldata[2]!=''){
			$unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1])."_".getAdsId($urldata[2]);
			$ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0])."_".getAdsId($urldata[2]);
		}else{
			$unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1]);
			$ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0]);
		}
		$chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
		$Blockcheck=BlockedUserChat(getUserId($urldata[0]),getUserId($urldata[1]));
		if($Blockcheck['ref']==1 ){
			$status=0;
		}elseif($Blockcheck['ref'] == 3){
			$status=0;
		}else{
			$status=1;
		}
		if(!empty($chat)){
				$random = Uuid::generate(4);
				
				if(!empty($request->file('chatimage'))){
					$files=$request->file('chatimage');
					$imgname = time().$random.'.'.$files->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/chat/');
                    $img = Image::make($files->getRealPath());
                    $img->resize(300, null, function ($constraint) { $constraint->aspectRatio(); });
                    $img->save($destinationPath.'/'.$imgname);
					
					$chatcreate= ChatsMessage::Create([
					'chat_id' => $chat->unique_chats_id,
					'image' =>$imgname,
					'user_id'=>Auth::user()->id,
					'status'=>$status,
					'read_status'=>0,
					]);

				}
				if(!empty($request->file('chatvideo'))){
					//echo "<pre>";print_r($input);die;
					$files=$request->file('chatvideo');
					$videoname = time().$random.'.'.$files->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/chatvideo/');
                    $files->move($destinationPath, $videoname);
                    
                    $chatcreate= ChatsMessage::Create([
					'chat_id' => $chat->unique_chats_id,
					'video' =>$videoname,
					'user_id'=>Auth::user()->id,
					'status'=>$status,
					'read_status'=>0,
					]);

				}
				if(!empty($input['typemessage'])){
					$chatcreate= ChatsMessage::Create([
					'chat_id' => $chat->unique_chats_id,
					'msg' =>$input['typemessage'],
					'user_id'=>Auth::user()->id,
					'status'=>$status,
					'read_status'=>0,
					]);
				}
				if(!empty($input['location'])){
					$mapimage=$input['latitude'].",".$input['longitude'];
					$chatcreate= ChatsMessage::Create([
					'chat_id' => $chat->unique_chats_id,
					'location' =>$mapimage,
					'user_id'=>Auth::user()->id,
					'status'=>$status,
					'read_status'=>0,
					]);
				}
			if(!empty($chatcreate)){
				if($chat->user_1 == Auth::user()->id){
					$chat->user_2_read_status="0";
				}else{
					$chat->user_1_read_status="0";
				}
				$chat->updated_at=$chatcreate->created_at;
				$chat->save();
				//echo "<pre>";print_r($chat);die;
			}	
			return 'save';die;
	   	}
		return 'fail';die;
	}
	public function ajaxSyncGetMessage(Request $request){
		$input=$request->all();

		$urldata=explode("_",$input['urlid']);
		if(isset($urldata[2]) && $urldata[2]!=''){
			$unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1])."_".getAdsId($urldata[2]);
			$ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0])."_".getAdsId($urldata[2]);
		}else{
			$unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1]);
			$ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0]);
		}

		$chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
		
		$Blockcheck=BlockedUserChat(getUserId($urldata[0]),getUserId($urldata[1]));
		if($Blockcheck['ref']==1 ){
			$chatcreate= ChatsMessage::where('chat_id',$chat->unique_chats_id)->orderby('created_at','asc')->get();
		}/*elseif($Blockcheck['ref']==3){
			$chatcreate= ChatsMessage::where('chat_id',$chat->unique_chats_id)->orderby('created_at','desc')->orwhere('status',getUserId($urldata[1]))->get();
		}*/else{
			$chatcreate= ChatsMessage::where('chat_id',$chat->unique_chats_id)->where('status',1)->orderby('created_at','asc')->get();
		}
		$msg="";
		foreach ($chatcreate as $key => $message) {
	   		if($message->user_id == Auth::user()->id){
	   			if(!empty($message->image)){
	   				$chatimage=url('public/uploads/chat',$message->image);
	   				
	   				$msg.='<div class="media msg_sender chat_M_'.$message->id.'">
							<div class="msg_body ml-3">
								<div class="bg-green rounded image_sent_outer_wrap sender_delete_btn py-2 px-3" >
									<span class="msg_delete_indiv" data-chatmessage="'.$message->id.'" data-chattype="2">X</span>
									<div class="img_sent_wrap">
										<img src="'.$chatimage.'" title="" alt="">
									</div>
								</div>
							</div>
					                <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.Auth()->user()->photo).'"alt="avatar">
						</div>';
							


	   			}
	   			if(!empty($message->location)){
	   				$location=explode(",", $message->location);
	   				$mapkey=getStaticMapKey();
	   				$locationurl="https://www.google.co.in/maps/@".$location[0].",".$location[1].",15z";
	   				$locationimage="https://maps.googleapis.com/maps/api/staticmap?center=".$location[0]."%2C".$location[1]."&amp;language=en&amp;size=640x256&amp;zoom=16&amp;scale=1&amp;&markers=color:red%7Clabel:%7C".$location[0].",".$location[1]."&key=".$mapkey."";
	   				$msg.='<div class="media msg_sender chat_M_'.$message->id.'">
							<div class="msg_body ml-3">
								<div class="bg-green rounded image_sent_outer_wrap sender_delete_btn py-2 px-3" >
										<span class="msg_delete_indiv" data-chatmessage="'.$message->id.'" data-chattype="1">X</span>
									<div class="img_sent_wrap">
										<a href="'.$locationurl.'" target="_blank">
										  <img class="img-fluid" src="'.$locationimage.'"/>
							 			</a>
									</div>
								</div>
							</div>
				            <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.Auth()->user()->photo).'"alt="avatar">
						</div>';

	   			}
	   			if(!empty($message->msg)){
	   				$msg.='<div class="media msg_sender chat_M_'.$message->id.'">
											<div class="msg_body ml-3">
												<div class="bg-green rounded py-2 px-3 sender_delete_btn">
												<span class="msg_delete_indiv" data-chatmessage="'.$message->id.'" data-chattype="1">X</span>
												<p class="text-small mb-0 receiver-text">'.$message->msg.'</p>
												</div>
											</div><img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.Auth()->user()->photo).'"alt="avatar">
										</div>';

	   			}
	   			if(!empty($message->video)){
	   				$chatvideo=url('public/uploads/chatvideo',$message->video);
	   				$msg.='<div class="media msg_sender chat_M_'.$message->id.'">
							<div class="msg_body ml-3">
								<div class="bg-green rounded image_sent_outer_wrap sender_delete_btn py-2 px-3">
										<span class="msg_delete_indiv" data-chatmessage="'.$message->id.'" data-chattype="3">X</span>
									<div class="img_sent_wrap">
										<a href="'.$chatvideo.'" download>
							 			  	<video class="embed-responsive" >
							 				  <source src="'.$chatvideo.'" type="video/mp4">
							 				  <source src="'.$chatvideo.'" type="video/ogg">
							 				  <source src="'.$chatvideo.'" type="video/3gp">
							 				</video>
							 			</a>
									</div>
								</div>
							</div>
					            <img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.Auth()->user()->photo).'"alt="avatar">
						</div>
	   				';

	   			}
	   			
			}else{
				if(!empty($message->image)){
					$chatimage=url('public/uploads/chat',$message->image);
					
					$msg.='<div class="media">
							<img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
							<div class="msg_body ml-3">
								<div class="bg-light rounded image_sent_outer_wrap py-2 px-3">
									<div class="img_sent_wrap">
										<img src="'.$chatimage.'" title="" alt="">
									</div>
								</div>
							</div>
						</div>';


				}
				if(!empty($message->location)){
	   				$location=explode(",", $message->location);
	   				$mapkey=getStaticMapKey();
	   				$locationurl="https://www.google.co.in/maps/@".$location[0].",".$location[1].",15z";
	   				$locationimage="https://maps.googleapis.com/maps/api/staticmap?center=".$location[0]."%2C".$location[1]."&amp;language=en&amp;size=640x256&amp;zoom=16&amp;scale=1&amp;&markers=color:red%7Clabel:%7C".$location[0].",".$location[1]."&key=".$mapkey."";
	   					$msg.='<div class="media">
							<img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
							<div class="msg_body ml-3">
								<div class="bg-light rounded image_sent_outer_wrap py-2 px-3">
									<div class="img_sent_wrap">
										<a href="'.$locationurl.'" target="_blank">
										  <img class="img-fluid" src="'.$locationimage.'"/>
										</a>
									</div>
								</div>
							</div>
						</div>';

	   			}
				if(!empty($message->msg)){
					$msg.='<div class="media">
							<img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
							<div class="msg_body ml-3">
								<div class="bg-light rounded py-2 px-3">
								<p class="text-small mb-0 sender-text">'.$message->msg.'</p>
								</div>
							</div>
						</div>';
				}
				if(!empty($message->video)){
					$chatvideo=url('public/uploads/chatvideo',$message->video);
					
					$msg.='<div class="media">
							<img class="chat_avathar_img_wrap" src="'.asset('public/uploads/profile/small/'.get_userphoto($message->user_id)).'"alt="avatar">
							<div class="msg_body ml-3">
								<div class="bg-light rounded image_sent_outer_wrap py-2 px-3">
									<div class="img_sent_wrap">
										<a href="'.$chatvideo.'" download>
										  	<video class="embed-responsive" >
											  <source src="'.$chatvideo.'" type="video/mp4">
											  <source src="'.$chatvideo.'" type="video/ogg">
											  <source src="'.$chatvideo.'" type="video/3gp">
											</video>
										</a>
									</div>
								</div>
							</div>
						</div>';
				}
			}
	   	}
	   	return $msg;die;
	}
	public function statuschaged(Request $request){
		$input=$request->all();
		$chat=Chats::where('unique_chats_id',$input['chatid'])->first();
		//echo "<pre>";print_r($chat);die;
		if(!empty($chat)){
			if($chat->user_1 == Auth::user()->id){
				
				$chatmessage= ChatsMessage::where('chat_id',$chat->unique_chats_id)->where('user_id',$chat->user_2)->where('read_status','0')->orderby('created_at','asc')->get();
				foreach ($chatmessage as $key => $message) {
					//echo "<pre>1";print_r($message);die;
					$message->read_status=1;
					$message->save();
				}
				$chat->user_1_read_status=1;
			}else{
				$chatmessage= ChatsMessage::where('chat_id',$chat->unique_chats_id)->where('user_id',$chat->user_1)->where('read_status','0')->orderby('created_at','asc')->get();
				foreach ($chatmessage as $key => $message) {
					//echo "<pre>2";print_r($message->msg);die;
					$message->read_status=1;
					$message->save();
				}
				$chat->user_2_read_status=1;
			}
			//$chat->user_1_read_status=1;
			$chat->save();
			return  "Read status changed";die;
		}else{
			return  "not Matched Chats";die;
		}
	}
	public function deleteconversation(Request $request){
		$input=$request->all();
		$chatmessage= ChatsMessage::where('id',$input['msgid'])->where('user_id',Auth::user()->id)->first();
		if (!empty($chatmessage)) {
			
			if($input['msgtype']=='1'){
				ChatsMessage::destroy($input['msgid']);
			}
			if($input['msgtype']=='2'){
				//$url='public/uploads/chat/';
				$url=public_path('uploads/chat/');
                unlink($url.$chatmessage->image);
                ChatsMessage::destroy($input['msgid']);
            }
			if($input['msgtype']=='3'){
				//$url='public/uploads/chatvideo/';
				$url=public_path('uploads/chatvideo/');
                unlink($url.$chatmessage->video);
                ChatsMessage::destroy($input['msgid']);
			}
			return 1;//success
		}
		return 0;//fail
	}

   	
   	public function sellerchat($newchatid){
		if(Auth::check()){


			$urldata=explode("_",$newchatid);
			/*if(isset($urldata[2]) && $urldata[2]!=''){
				$unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1])."_".getAdsId($urldata[2]);
				$ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0])."_".getAdsId($urldata[2]);
			}else{
				$unique_chats_id=getUserId($urldata[0])."_".getUserId($urldata[1]);
				$ulternative_unique_chats_id=getUserId($urldata[1])."_".getUserId($urldata[0]);
			}*/

			//echo "<pre>1";print_r($newchatid);die;
			$urluserdata=explode("_",$newchatid);
			$adsid = $urluserdata['2'];
			$sellerid = $urluserdata['0'];
			$chatuserid = getUserId($urluserdata['1']);


			$check=Ads::where('uuid',$adsid)->where('seller_id',getUserId($sellerid))->first();
			//echo "<pre>$chatuserid";print_r($check);die;
			if(!empty($check)){
				$unique_chats_id=$chatuserid."_".$check->seller_id."_".$check->id;
	   			$ulternative_unique_chats_id=$check->seller_id."_".$chatuserid."_".$check->id;
	   			
	   			$chat=Chats::where('unique_chats_id',$unique_chats_id)->orwhere('unique_chats_id',$ulternative_unique_chats_id)->first();
	   			if(empty($chat)){
					$chat= Chats::firstOrCreate([
						'unique_chats_id' => $unique_chats_id,
						'ads_id' =>$check->id,
						'user_1'=>$chatuserid,
						'user_2'=>$check->seller_id,
					]);
	   			}

				//echo "<pre>";print_r($newchatid);die;
				return redirect('chat/'.$newchatid);

   			}else{
   				toastr()->warning('Invalid Data!');
   				return view('chat');
   			}
   		}else{
   			toastr()->warning('Login to continue!');
   			return back();
   		}
	}
	
	
	

}
