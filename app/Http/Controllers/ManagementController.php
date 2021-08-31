<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Uuid;
use App\Contact;
use Illuminate\Support\Facades\Storage;
use Mail;
use App\Mail\ContactUs;
use App\Mail\TrackContactus;
use App\Enquiry;
use Auth;
use App\Setting;
use App\Redeem;
use App\Freepoints;
use App\User;
use Illuminate\Support\Facades\File;
use Prologue\Alerts\Facades\Alert;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;

use App\EnquiryMessage;
use App\Exports\WalletExport;
use Maatwebsite\Excel\Facades\Excel;
class ManagementController extends Controller
{   
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function contact(){

        $trackreq = DB::table('enquiry')
                    ->select('*')->where('mail_id', Auth::user()->email)->get();
        // $user = auth()->user()->email;            
        //dd($trackreq);        

       
        return view('contact.contactus',compact('trackreq'));
    }

    public function trackreq_reopen(Request $request ){
        
        $affected = DB::table('enquiry')
              ->where('tickectid', $request->tickectid)
              ->update(['status' => 0]);
        // dd($request->all());
        return redirect(route('contact'))->with('messages','Reopened Successfully');
    }

    public function gettrack(Request $request ){
        
     
        $trackget = DB::table('enquiry')
                    ->select('*')->where('tickectid', $request->track_id)->get();
        //dd($trackget);
        if(count($trackget) > 0){            
            return view('contact.contactus',compact('trackget'));
        }else{
           return redirect(route('contact'))->with('error','Track List Not Found');
        }
    }



    public function contactus(Request $request){
        $input = $request->all();
        $now = now();
        $adminmail = DB::table('settings')->select('email')->where('id',1)->first();
        //echo "<pre>";print_r($adminmail);die;
        $request->validate([
            'question'      =>'required',
            'name'          =>'required',
            'email'         =>'required|email',
            'description'   =>'required',
            'mobileno'      =>'required',
            'attachments'   =>'mimes:xls,pdf,docx,jpeg,jpg,png',
            
        ]);
        $now = now();
        $random = Str::random(6);
        $ticket=$random.$now->milli;
        $attachments = '';      
        $file = ''; 
        $uuid=Uuid::generate(4);       
        $destinationPath = public_path('uploads/attachments/');
        if(isset($input['attachments'])){
            $file = $request->file('attachments');
            $filename=$uuid.'.'.$file->getClientOriginalExtension();
            $file->move($destinationPath,$filename);
            $attachments =$filename;
            $document = $destinationPath.'/'.$attachments;
        }

        //echo "<pre>";print_r($attachments);die;   
        //$ticket=$now->timestamp.$now->milli;
        $data = Enquiry::create(['tickectid'=>$ticket,'help'=>$input['question'],'name'=>$input['name'],'mail_id'=>$input['email'],'ad_id'=>$input['adid'],'description'=>$input['description'],'mobileno'=>$input['mobileno'],'attachments'=>$attachments,'status'=>0,
        ]);
        $mail['path']=url("/contact/ticket/").'/'.$ticket;
        $mail['tickectid']=$data['tickectid'];
        $trackmail = Mail::to($input['email'])->send(new TrackContactus($mail));                    
        /*For sending mail*/
        //echo "<pre>";print_r($data);die;
        if(isset($attachments) && $attachments != ''){
            $data['document']=$document;
            Mail::to($adminmail->email)
            ->send(new ContactUs($data));   
        }else{
            Mail::to($adminmail->email)
            ->send(new ContactUs($data));                           
        }               
        toastr()->success('Your response has been recorded !');
        return back();
    }
    public function ticket($id){
        
        $check=Enquiry::where('tickectid',$id)->where('status',0)->first();
        if(!empty($check)){
            $message=EnquiryMessage::where('tickectid',$check->tickectid)->orderby('id','desc')->get();
            return view('contact.ticket',compact('message','check'));
        }else{
            toastr()->warning('Invalid Ticket / Expired Ticket !');
            return redirect('/');
        }
    }
    public function ticketmessagesave(Request $request){
        
        $input = $request->all();
        //echo "<pre>";print_r($input);die;
        $id=$input['id'];
        $request->validate([
            'message'      =>'required',
        ]);
        $check=Enquiry::where('tickectid',$input['id'])->first();
        if(!empty($check)){
            EnquiryMessage::create(['tickectid' => $input['id'],'message' =>$input['message'],'type'=>1]);
            toastr()->success('Message Sent!');
            return redirect()->route('contact.ticket',$id);
        }else{
            toastr()->warning('Invalid Ticket!');
            return redirect('/');
        }
    }

    public function faqdata(){
        
        $userqns = DB::table('faq')
                    ->select('*')->get();

        //$userqns = "hello";           
        //echo "<pre>";print_r($userqns);die;
        return view('faq',compact('userqns'));
    }

    public function faqsearch(Request $request){
        // get the search term
        $search = $request->input('search');

        // search the members table
        $userqns = DB::table('faq')->orWhere('questions', 'LIKE', '%' . $search . '%')
                                   ->orWhere('answers', 'LIKE', '%' . $search . '%')->get();
        //echo "<pre>";print_r($userqns);die;
        return view('faq',compact('userqns','search'));
    }
    public function getfollwing($uuuid)
    {
        $users = DB::table('users')->where('uuid',$uuuid)->first();
        $followingg = DB::table('followers')
                        ->rightJoin('users','users.id','=','followers.following')
                        ->where('user_id',$users->id)
                        ->select('followers.*','users.photo','users.name','users.uuid as useruuid')
                        ->get();
                        $prop='';
                        $btncolor='';
        $followinginfo = '';
        if(count($followingg)>0){
            foreach($followingg as $key => $following){
                if (Auth::check()){
                    if(!empty($following)){
                        $btncolor='unfollowbtn';
                        $prop="Unfollow";
                    }else{
                        $btncolor='';
                        $prop="Follow";                  
                    }
                    $followinginfo .='<div class="follow_outer_wrap">
                                        <div class="follow_img_wrap">
                                            <img src="'.asset('public/uploads/profile/original/'.$following->photo).'" title="follow_img" alt="follow img">
                                        </div>
                                         
                                        <div class="follow_name">
                                            <h4><a  target="_blank"href="'.route("view.profile",$following->useruuid).'">'.$following->name.'</a></h4>
                                        </div>';
                                        
                                    
                    if($users->id == auth()->user()->id){
                            $followinginfo .= '
                                              <div class="chat_follower_btn">
                                            <a href="'.route("chats",$following->useruuid).'">Chat Now</a>
                                        </div>              
                                            <div class="follow_button common_btn_wrap">
                                            <button class="getfollowing '.$btncolor.'" data-useruuid="'.$following->useruuid.'" id="getfollowing'.$following->useruuid.'">'.$prop.'</button>
                                        </div>';
                    }
                    $followinginfo .= '</div>';
                    
                }else{
                    if(!empty($following)){
                        $prop="Unfollow";
                    }else{
                        $prop="Follow";                  
                    }
                    $followinginfo .='<div class="follow_outer_wrap">
                                        <div class="follow_img_wrap">
                                            <img src="'.asset('public/uploads/profile/original/'.$following->photo).'" title="follow_img" alt="follow img">
                                        </div>
                                        <div class="follow_name">
                                            <h4><a  target="_blank"href="'.route("view.profile",$following->useruuid).'">'.$following->name.'</a></h4>
                                        </div>
                                         
                                        <div class="follow_button common_btn_wrap">
                                            
                                        </div>
                                    </div>';
                    
                }
            }
        }else{
            $followinginfo .='<p> No Data Found </p>';
        }
        echo $followinginfo;die;
    }
    public function getfollowers($uuuid)
    {
        $users = DB::table('users')->where('uuid',$uuuid)->first();
        $followers = DB::table('followers')
                       ->leftJoin('users','users.id','=','followers.user_id')
                       ->where('followers.following',$users->id)
                       ->select('followers.*','users.photo','users.name','users.uuid as useruuid')
                       ->get();
        $followersinfo = '';

            $btncolor='';
           $prop='';
        if(count($followers)>0){
            foreach($followers as $key => $followerss){
                if (Auth::check()){
                    $blockedusers = DB::table('customer_manage_user')->where('user_id',auth()->user()->id)->where('block_user_id',$followerss->user_id)->first();
                    if(!empty($blockedusers) && $blockedusers->block_user_id == $followerss->user_id){ 
                        $btncolor=''; 
                        $prop="Unblock";        
                    }else{
                        $btncolor='blockbtn';                 
                        $prop="Block"; 
                    }
                    $followersinfo .='<div class="follow_outer_wrap">
                                        <div class="follow_img_wrap">
                                            <img src="'.asset('public/uploads/profile/original/'.$followerss->photo).'" title="follow_img" alt="follow img">
                                        </div>
                                        <div class="follow_name">
                                            <h4><a  target="_blank"href="'.route("view.profile",$followerss->useruuid).'">'.$followerss->name.'</a></h4>
                                        </div>';
                    if($users->id == auth()->user()->id){
                            $followersinfo .= ' 
                                        <div class="chat_follower_btn">
                                            <a href="'.route("chats",$followerss->useruuid).'">Chat Now</a>
                                        </div> ';
                                        
                                    
                    $followersinfo .= ' 
                                         
                                        <div class="follow_button common_btn_wrap">
                                            <button class="blockuser '.$btncolor.'" data-useruuid="'.$followerss->useruuid.'" data-statuschanges="'.$prop.'"  id="blockuser'.$followerss->useruuid.'">'.$prop.'</button>
                                        </div>';
                    $followersinfo .= '</div>';
                }

                    
                }else{
                        
                    $followersinfo .='<div class="follow_outer_wrap">
                                        <div class="follow_img_wrap">
                                            <img src="'.asset('public/uploads/profile/original/'.$followerss->photo).'" title="follow_img" alt="follow img">
                                        </div>
                                        <div class="follow_name">
                                            <h4><a  target="_blank"href="'.route("view.profile",$followerss->useruuid).'">'.$followerss->name.'</a></h4>
                                        </div>

                                        <div class="follow_button common_btn_wrap">
                                            
                                        </div>
                                    </div>';

                }
                
            }
        }else{
            $followersinfo .='<p> No Data Found </p>';
        }
        echo $followersinfo;die;
    }
    public function walletpassbook(){
       /* $points = DB::table('freepoints')
                       ->select('freepoints.*','ads.uuid as adsuuid')
                       ->leftJoin('ads','ads.id','=','freepoints.ads_id')
                       ->where('freepoints.user_id',auth()->user()->id)
                       ->orderby('freepoints.id','desc')
                       ->get();*/
        $now=date('Y-m-d H:i:s');         
        
        if(Auth::check()){
            /*$pointstable = DB::table('freepoints')->where('user_id',auth()->user()->id)
                                    ->where('status','1')
                                    ->where('used','0')
                                    ->where('expire_date','!=',null)->whereDate('expire_date','>=',$now)->get(); */
            //echo "<pre>";print_r($pointstable);die;
            $reedemableamt = Setting::select('redeemable_amount')->where('id',1)->first();
            //return view('wallet.usertransaction',compact('points','sum'));
            return view('wallet.usertransaction_new',compact('reedemableamt'));
        }else{
            toastr()->error('Login to Continue!');
            return redirect()->route('welcome');
        }
        
    }
    public function walletpassbookDetails($req)
    {   $input = $req;
        $fromdate=$input['fromdate'];
        $todate=$input['todate'];
        if($todate==""){
          $todate=date('Y-m-d');  
        }

        //echo "<pre>";print_r($todate);die;
        $walletdatas = DB::table('freepoints')
                       ->select('freepoints.*','ads.uuid as adsuuid','ads.ads_ep_id as ads_ep_id')
                       ->leftJoin('ads','ads.id','=','freepoints.ads_id')
                       //->where('freepoints.description','!=','Amount Redeemed')
                       ->where('freepoints.user_id',auth()->user()->id);
                       
        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            //echo "<pre>";print_r($searchValue);die;
            $walletdatas->where(function ($query) use ($searchValue) {
                $query->orWhere("freepoints.description","like","%".$searchValue."%");
                $query->orWhere("freepoints.point","like","%".$searchValue."%");
                $query->orWhere("freepoints.created_at","like","%".$searchValue."%");
                $query->orWhere("ads.ads_ep_id","like","%".$searchValue."%");
                $query->orWhere("freepoints.order_id","like","%".$searchValue."%");
                if(strtolower($searchValue) == 'cr' || strtolower($searchValue) == 'cre' || strtolower($searchValue) == 'cred' || strtolower($searchValue) == 'credi' || strtolower($searchValue) == 'credit'){
                    $query->orWhere("freepoints.status","like","%".'1'."%");
                }
                if(strtolower($searchValue) == 'de' || strtolower($searchValue) == 'deb' || strtolower($searchValue) == 'debi' || strtolower($searchValue) == 'debit'){
                    $query->orWhere("freepoints.status","like","%".'0'."%");
                }
                if(strtolower($searchValue) == 'ex' || strtolower($searchValue) == 'exp' || strtolower($searchValue) == 'expi' || strtolower($searchValue) == 'expir' || strtolower($searchValue) == 'expire' || strtolower($searchValue) == 'expired'){
                    $query->orWhere("freepoints.expire_date",'<',date('Y-m-d H:i:s'));
                }
            });
                      
        }
        if($fromdate!='' && $todate!=''){
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = date('Y-m-d', strtotime($todate));
            $walletdatas->whereDate('freepoints.created_at','>=', $fromdate);
            $walletdatas->whereDate('freepoints.created_at','<=', $todate);
        }
       $walletdatas->orderby('freepoints.id','desc');
        

        return $walletdatas;
    }
    public function Ajaxwalletpassbook(Request $request){
        $input = $request->all();
        $count = $this->walletpassbookDetails($input);
        $totalCount = count($count->get());
        //$totalCount = 12;

        $getData = $this->walletpassbookDetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['wallet'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['wallet'] as $wallet) {
            $point='';
            $status='';
            $ads='';
            if($wallet->ads_id){
                $url=url('item');
                $ads="<a href='$url/$wallet->adsuuid' target='_blank'>$wallet->description (AD ID : $wallet->ads_ep_id)</a>";
            }else{
                $ads="$wallet->description";
            }
            if($wallet->point){
                $point=number_format((float)$wallet->point, 2, '.', '');
            }
            if($wallet->status == '1'){
                $status="Credited";
            }else if($wallet->status == '2'){
                $status="Expired";
            }else{
                $status="Debited";
            }
            if($wallet->description == 'Amount Redeemed'){
                $status="Debited";
            }

            $row = array("title"=>$ads,"point"=>$point,"status"=>$status,"expire"=>(($wallet->expire_date != '')?date("d-M-Y",strtotime($wallet->expire_date)):"-"),"date"=>date("d-M-Y",strtotime($wallet->created_at)),"orderId"=>$wallet->order_id);
            
            $datas[] = $row;
        }
            //echo "<pre>";print_r($datas);die;

        $output = array(
            "draw"=> $_POST['draw'],
            "recordsTotal" => $totalCount, 
            "recordsFiltered" => $totalCount, 
            "data" => $datas,
        );
        //output to json format
        echo json_encode($output);die;
    }


    public function walletuserpassbookDetails($req)
    {   
        

        $input = $req;
        $fromdate=isset($input['fromdate'])?$input['fromdate']:null;
        $todate=isset($input['todate'])?$input['todate']:null;
        if($todate==""){
          $todate=date('Y-m-d');  
        }

        //echo "<pre>";print_r($todate);die;
        $walletdatas = DB::table('freepoints')
                       ->select('freepoints.*','ads.uuid as adsuuid','ads.ads_ep_id as ads_ep_id')
                       ->leftJoin('ads','ads.id','=','freepoints.ads_id')
                       ->where('freepoints.description','!=','Amount Redeemed')
                       ->where('freepoints.user_id',$req['useruuid']);
                       
        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            //echo "<pre>";print_r($searchValue);die;
            $walletdatas->where(function ($query) use ($searchValue) {
                $query->orWhere("freepoints.description","like","%".$searchValue."%");
                $query->orWhere("freepoints.point","like","%".$searchValue."%");
                $query->orWhere("freepoints.created_at","like","%".$searchValue."%");
                if($searchValue =='Credit' || $searchValue =='credit'){
                    $query->orWhere("freepoints.status","like","%".'1'."%");
                }
                if($searchValue =='Debit' || $searchValue =='debit'){
                    $query->orWhere("freepoints.status","like","%".'0'."%");
                }
            });
                      
        }
        if($fromdate!='' && $todate!=''){
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = date('Y-m-d', strtotime($todate));
            $walletdatas->whereDate('freepoints.created_at','>=', $fromdate);
            $walletdatas->whereDate('freepoints.created_at','<=', $todate);
        }
       $walletdatas->orderby('freepoints.id','desc');
        

        return $walletdatas;
    }
    public function Ajaxuserwalletpassbook(Request $request){
        $input = $request->all();
        $count = $this->walletuserpassbookDetails($input);
        $totalCount = count($count->get());
        //$totalCount = 12;

        $getData = $this->walletuserpassbookDetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['wallet'] = $getData->get();
        
        $datas = array();
        $row = array();
        foreach ($data['wallet'] as $wallet) {
            $point='';
            $status='';
            $ads='';
            if($wallet->ads_id){
                $url=url('item');
                $ads="<a href='$url/$wallet->adsuuid' target='_blank'>$wallet->description (AD ID : $wallet->ads_ep_id)</a>";
            }else{
                $ads="$wallet->description";
            }
            if($wallet->point){
                $point=number_format((float)$wallet->point, 2, '.', '');
            }
            if($wallet->status == '1'){
                $status="Credit";
            }else if($wallet->status == '2'){
                $status="Expired";
            }else{
                $status="Debit";
            }
            if($wallet->description == 'Amount Redeemed'){
                $status="Debit";
            }

            $row = array("title"=>$ads,"point"=>$point,"status"=>$status,"expire"=>(($wallet->expire_date != '')?date("d-M-Y",strtotime($wallet->expire_date)):"-"),"date"=>date("d-M-Y",strtotime($wallet->created_at)),"orderId"=>$wallet->order_id);
            
            $datas[] = $row;

        }

        $output = array(
            "draw"=> $_POST['draw'],
            "recordsTotal" => $totalCount, 
            "recordsFiltered" => $totalCount, 
            "data" => $datas,
        );
        //output to json format
        echo json_encode($output);die;
    }

    public function walletredeemDetails($req)
    {   $input = $req;
        $fromdate=$input['fromdate'];
        $todate=$input['todate'];
        if($todate==""){
          $todate=date('Y-m-d');  
        }

        //echo "<pre>";print_r($todate);die;
        $walletdatas = DB::table('redeem')
                       ->select('redeem.*')
                       ->where('redeem.user_id',auth()->user()->id);
                       
        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            //echo "<pre>";print_r($searchValue);die;
            $walletdatas->where(function ($query) use ($searchValue) {
                $query->orWhere("redeem.redeem_amt","like","%".$searchValue."%");
                $query->orWhere("redeem.paytmOrderId","like","%".$searchValue."%");
                $query->orWhere("redeem.created_at","like","%".$searchValue."%");
                
            });
                      
        }
        if($fromdate!='' && $todate!=''){
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = date('Y-m-d', strtotime($todate));
            $walletdatas->whereDate('redeem.created_at','>=', $fromdate);
            $walletdatas->whereDate('redeem.created_at','<=', $todate);
        }
       $walletdatas->orderby('redeem.id','desc');
        

        return $walletdatas;
    }
    public function Ajaxwalletredeem(Request $request){
        $input = $request->all();
        $count = $this->walletredeemDetails($input);
        $totalCount = count($count->get());
        //$totalCount = 12;

        $getData = $this->walletredeemDetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['wallet'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['wallet'] as $wallet) {
            $point='';
            $status='Debit';
            $title='Amount Redeemed';
            
            if($wallet->redeem_amt){
                $point=number_format((float)$wallet->redeem_amt, 2, '.', '');
            }

            $row = array("title"=>$title,"trnid"=>$wallet->paytmOrderId,"point"=>$point,"status"=>$status,"date"=>$wallet->created_at);
            
            $datas[] = $row;
        }
            //echo "<pre>";print_r($datas);die;

        $output = array(
            "draw"=> $_POST['draw'],
            "recordsTotal" => $totalCount, 
            "recordsFiltered" => $totalCount, 
            "data" => $datas,
        );
        //output to json format
        echo json_encode($output);die;
    }

    //display the points in wallet
    public function userwalletpoint(Request $request){
        if (Auth::check()){
            $input = $request->all();
            $freepoints = DB::table('freepoints')->where('user_id',auth()->user()->id)
                                    ->where('status','1')
                                    ->where('used','0')->get(); 
            $val = array();
            //echo"<pre>";print_r($freepoints);die;
            foreach ($freepoints as $key => $point){
                //echo"<pre>";print_r($point->point);die;
                $val[] = $point->point;
            }
            $sum  ='   ';
            $sum .= array_sum($val);
            //echo"<pre>";print_r($sum);die;
            echo $sum;exit();
        }
    }

    //display the amount in redeempage
    public function walletredeem(){
        $walletdatas = DB::table('freepoints')
                       ->select('freepoints.*','ads.uuid as adsuuid','ads.ads_ep_id as ads_ep_id')
                       ->leftJoin('ads','ads.id','=','freepoints.ads_id')
                       ->where('freepoints.description','=','Amount Redeemed')
                       ->where('freepoints.user_id',auth()->user()->id)->count();
        //echo"<pre>";print_r($walletdatas);die;
        if($walletdatas==0){
            toastr()->warning('You Have Not Yet Redeem History!');
            return back();
        }               
        return view('wallet.userredeem');
    }
    public function redeemamountchecking(Request $request){
        $input = $request->all();
        $redeemamount=0;
        $now=date('Y-m-d H:i:s');  
        $pointstable = DB::table('freepoints')->where('user_id',auth()->user()->id)
                                    ->where('status','1')
                                    ->where('used','0')
                                    ->where('expire_date','!=',null)->whereDate('expire_date','>=',$now)->orderby('id','asc')->get(); 
        foreach ($pointstable as $key => $value) {

            $redeemamount=$redeemamount+(float)$value->point;
            
        }
        return  $redeemamount;
    }
    //function for the redeem button
    public function redeemamount(Request $request){
        $input = $request->all();

        //echo"<pre>";print_r($input);die;  
        $date = new DateTime('now');
        $date->modify("180 day"); 
        $date = $date->format('Y-m-d H:i:s'); 
        $now=date('Y-m-d H:i:s');  
        //$reddemid=explode(',', $input['Redeemtoken']);

        $reedemableamt = Setting::select('redeemable_amount')->where('id',1)->first();
        $pointstables = DB::table('freepoints')->where('user_id',auth()->user()->id)
                                    ->where('status','1')
                                    ->where('used','0')
                                    //->whereIn('id',$reddemid)
                                    ->where('expire_date','!=',null)
                                    ->whereDate('expire_date','>=',$now)->orderby('expire_date','asc')->get();
        $redeemamount=$input['sum'];
        if($redeemamount > auth()->user()->wallet_point){
            echo "6";die;
        }
        // foreach ($pointstable as $key => $value) {
        //     $redeemamount=$redeemamount+(float)$value->point;
        // }

       
        //echo"<pre>";print_r($pointstable); die;


        ////testing
        /*$enterAmt = $input['sum'];
        foreach ($pointstable as $key => $point) {
            // $freepoint = Freepoints::find($point->id);
            // $freepoint->available_point=$freepoint->point;
            // $freepoint->save();
            // 10- 8 = 2


            // 10 - 12 = -2
            // 10 - 12 = 

            $freepoint = Freepoints::find($point->id);
            if($key == 0){
                if($freepoint['available_point'] > $enterAmt){
                    $amountt = $freepoint['available_point'] - $enterAmt;
                }else{
                    $amountt = $enterAmt - $freepoint['available_point'];                    
                }
            }else{
                $amountt = $freepoint['available_point'] - $amountAfter;                
            }

            if($amountt <= 0){
                $freepoint->available_point=0;
                $freepoint->save();    
                $amountAfter = $amountt;
                break;            
            }else{
                $freepoint->available_point=$amountt;
                $freepoint->save();
                $amountAfter = $amountt;   
                if($amountAfter <= 0){
                    break;   
                }             
            }
            echo"<pre>1";print_r($amountAfter); 

           //  if($amountt < 0){
           //      echo "string".$amountt;
           //      //$freepoint->status='0';
           //      $freepoint->available_point=$amountt;
           //      //$freepoint->used='1';
           //      $freepoint->save();
           //      break;
           // }   
        }
        echo"<pre>";print_r($redeemamount);die;  */

        //////testing


        if ($redeemamount >= $reedemableamt->redeemable_amount && $redeemamount > 0){

            /*$pointamount = 0;
            $pointamountids = []; 

            foreach ($pointstables as $key => $point) {
                if($point->point == $redeemamount){
                    $pointamountids[] = $point->id;
                    break;
                }
            }
            if(empty($pointamountids)){
                foreach ($pointstables as $key => $point) {
                    $pointamount += $point->point;
                    $pointamountids[] = $point->id;
                    if($redeemamount == $pointamount){
                        break;
                    }
                    if($redeemamount < $pointamount){
                        echo "5@@@".$pointamount;die;
                    }
                }
            }


            $pointstable = DB::table('freepoints')->where('user_id',auth()->user()->id)
                            ->whereIn('id',$pointamountids)->get();*/

            $proofs = DB::table('proofs')
                                ->where('user_id',Auth::user()->id)
                                ->where('proof',3)
                                ->where('verified','1')
                                ->first();

            if(!isset($proofs->verified)){        
                    echo"2";exit();
            }
            if($proofs->verified == '1')
            {
                $beneficiary = User::find(Auth::user()->id);
                if(isset($beneficiary->phone_no) && $beneficiary->phone_no !=''){
                    $redeemWallet = redeemWallet($beneficiary->phone_no,$redeemamount);
                    if($redeemWallet['statusCode'] == 'DE_001' || $redeemWallet['statusCode'] == 'DE_101'){
                       
                       $updatefreepoint = DB::table('freepoints')->insert(['user_id' => $proofs->user_id,'description' => 'Amount Redeemed','status' => '0','used'=>'1','point'=>$redeemamount,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s"),'order_id' => generateRandomString()]);


                        /*foreach ($pointstable as $key => $point) {
                           $freepoint = Freepoints::find($point->id);
                           $freepoint->status='0';
                           $freepoint->used='1';
                           $freepoint->save();
                        }*/

                        $user= User::find(Auth::user()->id);
                        $userwallet=$user->wallet_point - $redeemamount;
                        $user->wallet_point=$userwallet;
                        $user->save();
                        $redeem= Redeem::create(['user_id'=>$proofs->user_id,'redeem_amt'=>$redeemamount,'mid'=>$redeemWallet['result']['mid'],'orderId'=>$redeemWallet['result']['orderId'],'paytmOrderId'=>$redeemWallet['result']['paytmOrderId'],'commissionAmount'=>$redeemWallet['result']['commissionAmount'],'tax'=>$redeemWallet['result']['tax']]);
                            echo"1";exit();
                    }else{
                        echo"4";exit();
                    }
                }else{
                    echo"3";exit();
                }              
            }else{
                echo"2";exit();
            }
        }else{
            echo"0";exit();
        }
        //echo"<pre>";print_r($id);die;                            
        //echo"<pre>";print_r($input);die;    
    }

    //function for the redeem button
    public function redeemamount1(Request $request){
        $input = $request->all();
        //echo"<pre>";print_r(round(microtime(true) * 1000));die;
        //echo"<pre>";print_r($input);die;
        $proofs = DB::table('proofs')
                            ->where('user_id',$input['user_id'])
                            ->where('proof',3)
                            ->where('verified','1')
                            ->first();

        $sum=Auth::user()->wallet_point;
        $reedemableamt = Setting::select('redeemable_amount')->where('id',1)->first();

        $floatval = (float)$sum;//restrict wallet amount

       
        //echo"<pre>re";print_r($input['redeemamount']);die;
        if (($input['redeemamount'] !='') && ($floatval >= $reedemableamt->redeemable_amount) && ($floatval >= $input['redeemamount']))
        {
           
            if(!isset($proofs->verified)){            
                    echo"2";exit();
            }
            //echo"<pre>";print_r($input['redeemamount']);die;
            if($proofs->verified == '1')
            {
                $beneficiary = User::find(Auth::user()->id);
                //echo '<pre>';print_r( $beneficiary->phone_no );die;
                if(isset($beneficiary->phone_no) && $beneficiary->phone_no !=''){
                    $redeemWallet = redeemWallet($beneficiary->phone_no,$input['redeemamount']);
                    //echo '<pre>';print_r( $redeemWallet );die;
                    // $redeemWalletCheckStatus = redeemWalletCheckStatus();
                    // echo '<pre>';print_r( $redeemWalletCheckStatus );die;

                    if($redeemWallet['statusCode'] == 'DE_001' || $redeemWallet['statusCode'] == 'DE_101'){
                            $updatefreepoint = DB::table('freepoints')->insert(['user_id' => $proofs->user_id,'description' => 'Amount Redeemed','status' => '0','used'=>'1','point'=>$input['redeemamount'],'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]);

                            $user= User::find(Auth::user()->id);
                            $user->wallet_point=$user->wallet_point-$input['redeemamount'];
                            $user->save();
                            //echo"<pre>";print_r($sum);die;
                            $redeem= Redeem::create(['user_id'=>$proofs->user_id,'redeem_amt'=>$input['redeemamount'],'mid'=>$redeemWallet['result']['mid'],'orderId'=>$redeemWallet['result']['orderId'],'paytmOrderId'=>$redeemWallet['result']['paytmOrderId'],'commissionAmount'=>$redeemWallet['result']['commissionAmount'],'tax'=>$redeemWallet['result']['tax']]);
                            /* $update = DB::table('freepoints')->where('user_id',$proofs->user_id)->update(['status' => '0','used'=>'1']);*/
                            echo"1";exit();
                    }else{
                        echo"4";exit();
                    }
                }else{
                    echo"3";exit();
                }              
            }
        }else{
            echo"0";exit();
        }
    }



    //send amount to friends
    public function sendwallettouser(){
        
        $freepoints = DB::table('freepoints')->where('user_id',auth()->user()->id)
                     ->where('status','1')
                     ->where('used','0')->get();    
        $val = array();
        //echo"<pre>";print_r($freepoints);die;
        foreach ($freepoints as $key => $point){
            //echo"<pre>";print_r($point->point);die;
            $val[] = $point->point;
        }
        $sum  ='   ';
        $sum .= array_sum($val);
        //echo"<pre>";print_r($sum);die;
        return view('wallet.sendwallettouser',compact('sum'));
    }

    public function getuserdetails(Request $request){
        $input = $request->all();

        if(is_numeric($request->get('userinfo'))){
            $result=DB::table('users')->where('phone_no', $input['userinfo'])->first();
        }else{
            $result=DB::table('users')->where('email', $input['userinfo'])->first();
        }

        if(empty($result)){
            //toastr()->success('No data found!');
            echo"0";exit();
        }else if(isset($result->id) && $result->id == auth()->user()->id){
            echo"1";exit();
        }else{
            echo trim($result->uuid).'@@@'.trim(ucfirst($result->name));exit();
        }
    }


    //function for the redeem button
    public function sendamounttofriends(Request $request){
        $input = $request->all();
        $freepoints = DB::table('freepoints')
                        ->where('user_id',auth()->user()->id)
                        ->where('status','1')
                        ->where('used','0')->get(); 
        $val = array();
        foreach ($freepoints as $key => $point){
            $val[] = $point->point;
        }
        $sum  = array_sum($val);
        if($sum > 0){
            $users = DB::table('users')->where('uuid',$input['touser'])->first();
            $curentusers = DB::table('users')->where('uuid',$input['fromuser'])->first();
            //echo '<pre>';print_r( $users );die;
            //$redeem= Redeem::create(['user_id'=>$users->id,'redeem_amt'=>$sum]);

            $update = DB::table('freepoints')->where('user_id',$curentusers->id)->where('status','1')->where('used','0')->update(['status' => '0','used'=>'1']);

            $update = DB::table('freepoints')->insert(['user_id' => $users->id,'description' => 'Amount transferred from '.$curentusers->name,'status' => '1','used'=>'0','point'=>$sum]);

            $update = DB::table('freepoints')->insert(['user_id' => $curentusers->id,'description' => 'Amount transferred to '.$users->name,'status' => '0','used'=>'1','point'=>$sum]);

            echo"1";exit(); 
        }else{
            echo"0";exit();
        }
    }

    public function clearCache()
    {
        $errorFound = false;
        
        if (session()->has('curr')) {
            session()->forget('curr');
        }
        
        // Removing all Objects Cache
        try {
            $exitCode = Artisan::call('cache:clear');
        } catch (\Exception $e) {
            Alert::error($e->getMessage())->flash();
            $errorFound = true;
        }
        
        // Some time of pause
        sleep(2);
        
        // Removing all Views Cache
        try {
            $exitCode = Artisan::call('view:clear');
        } catch (\Exception $e) {
            Alert::error($e->getMessage())->flash();
            $errorFound = true;
        }
        
        // Some time of pause
        sleep(1);
        
        // Removing all Logs
        try {
            File::delete(File::glob(storage_path('logs') . '/laravel*.log'));
        } catch (\Exception $e) {
            Alert::error($e->getMessage())->flash();
            $errorFound = true;
        }
        
        
        // Check if error occurred

        if (!$errorFound) {
            $message = trans("admin::messages.The cache was successfully dumped.");
            echo $message;
        }
        
        return redirect()->back();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    /*public function walletexport($userid,$year,$month) 
    {   
        $user=User::where('uuid',$userid)->first();
        if(!empty($user)){
            $year=base64_decode($year);
            $month=base64_decode($month);
            return Excel::download(new WalletExport($userid,$year,$month), 'WalletReport.csv');
        }else{
            toastr()->error('Invalid Export Request!'); 
            return redirct()->route('welcome'); 
        }
        
    }*/

}



