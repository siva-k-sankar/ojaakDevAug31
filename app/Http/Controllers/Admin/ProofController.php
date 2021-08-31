<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Role;
use App\User;
use DB;
use App\Setting;
use Auth;
use App\Proof;
use File;
use Mail;
use App\Mail\ProofStatus;
class ProofController extends Controller
{
    //
    public function index(Request $request)
    {
    	
        return view('back.users.proofverify');

		
    }
    public function getUnverifiedDetails()
    {
        $proofs = DB::table('proofs')
                        ->leftjoin('users','users.id','=','proofs.user_id')
                        ->select('proofs.user_id','users.name','proofs.proof','proofs.uuid','users.uuid as useruuid')->orderBy('proofs.user_id', 'desc')->orderBy('proofs.created_at', 'desc')->where('users.status',1);

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $proofs->Where(function ($query) use ($searchValue) {
                $query->orWhere("users.name","like","%".$searchValue."%");
                $query->orWhere("users.id","like","%".$searchValue."%");
            });
                      
        }
        $proofs->Where('proofs.verified','0');
        //echo "<pre>";print_r($proofs);die;
        return $proofs;
    }
    public function getunverified(Request $request){
        $count = $this->getUnverifiedDetails();
        $totalCount = count($count->get());

        $getData = $this->getUnverifiedDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['proof'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['proof'] as $proof) {
           
            $action = '<a href='.route('admin.users.proofview',$proof->uuid).' class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square "></i></a>';

            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",$proof->useruuid).'">'.$proof->name.' [ Userid : '.$proof->user_id.' ]</a>';

            $row = array("name"=>$link,"proof"=>get_prooflist($proof->proof),"action"=>$action);
            
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
    public function getVerifiedDetails()
    {
        $proofs = DB::table('proofs')
                ->leftjoin('users','users.id','=','proofs.user_id')
                ->select('proofs.user_id','proofs.uuid','users.uuid as useruuid','users.name','proofs.proof','proofs.image','proofs.verified_by','proofs.verified','proofs.comments','proofs.verified_date','proofs.created_at')->orderBy('proofs.verified_date', 'desc')->where('users.status',1);

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $proofs->Where(function ($query) use ($searchValue) {
                $query->orWhere("users.name","like","%".$searchValue."%")
                ->orWhere("proofs.comments","like","%".$searchValue."%");
                $query->orWhere("users.id","like","%".$searchValue."%");
            }); 
                     
        }
        $proofs->Where('proofs.verified',"!=",'0');
        //echo "<pre>";print_r($proofs);die;
        return $proofs;
    }
    public function getverified(Request $request){
        $count = $this->getVerifiedDetails();
        $totalCount = count($count->get());

        $getData = $this->getVerifiedDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['proof'] = $getData->get();
        
        
        $datas = array();
        $row = array();
        foreach ($data['proof'] as $proof) {
            //echo "<pre>";print_r($proof->useruuid);die;
            $edit =  url('public/uploads/proof',$proof->image);
            $proofimg='<center><a href="'.$edit.'" class="btn bg-olive btn-flat margin demo"target="_blank"><i class="fa fa-camera "></i></a></center>';
            if($proof->verified==1)
            {
                $verify ='<center><button style="margin:3px;" class=" btn bg-green"><i class="fa fa-check"></i></button></center>';
            }else{
                $verify ='<center><button style="margin:3px;" class=" btn bg-red"><i class="fa fa-times"></i></button>';
            }
            $routeurl =  route('admin.users.proofrecheck',$proof->uuid);
            $id="'".$proof->uuid."'";
            $action = '<center><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$id.')"><i class="fa   fa-clone"></i></button></center>
                
                <form action="'.$routeurl.'" method="POST" id="status-'.$proof->uuid.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';

            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",$proof->useruuid).'">'.$proof->name.' [ Userid : '.$proof->user_id.' ]</a>';
            
            $row = array("name"=>$link,"proof"=>get_prooflist($proof->proof),"proofimage"=>$proofimg,"verified_by"=>get_name($proof->verified_by),"verified"=>$verify,"comments"=>$proof->comments,"verified_date"=>$proof->verified_date,"created_at"=>$proof->created_at,'action'=>$action);
            
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
    /*public function getverifieds(Request $request){
        $verifiedproofdata=DB::table('proofs')
        ->leftjoin('users','users.id','=','proofs.user_id')
        ->select('users.name','proofs.proof','proofs.image','proofs.verified_by','proofs.verified','proofs.comments','proofs.verified_date','proofs.created_at')->where('proofs.verified',"!=",'0')->orderBy('proofs.id', 'desc')->get();
        $final_resp = array();
        $i=1;
        foreach ($verifiedproofdata as $key => $value) {
            $resp = array();
            $j=0;
            $resp[] = $i;
            foreach ($value as $key1 => $value1) {
                if($j==2){
                    $proof_image = $value1;
                    $edit =  url('public/uploads/proof',$proof_image);
                    $resp[] = '<center><a href="'.$edit.'" class="btn bg-olive btn-flat margin demo"target="_blank"><i class="fa fa-camera "></i></a></center>';
                }else if($j==1){
                    $resp[] = get_prooflist($value1);
                }else if($j==3){
                    $resp[] = get_name($value1);
                }else if($j==4){
                    if($value1==1)
                      {
                        $resp[] ='<center><button style="margin:3px;" class=" btn bg-green"><i class="fa fa-check"></i></button></center>';
                      }else{
                        $resp[] ='<center><button style="margin:3px;" class=" btn bg-red"><i class="fa fa-times"></i></button>';
                      }  
                }else if($j==5){
                    if($value1 == "")
                      {
                        $resp[] ='-';
                      }else{
                        $resp[] = $value1;
                      }  
                }else if($j==7){
                    $resp[] = $value1;
                }else{
                    $resp[] = $value1;
                }
                $j++;
               
            }
            
            
            //$view =  '<a href="'.$edit.'" class="btn btn-warning btn-sm waves-effect"><i class="material-icons">local_library</i></a>';
            
            array_push($final_resp, $resp);
            $i++;
        }
        //$data=json_encode($final_resp);
        $response['draw']   = 1;
        $response['recordsTotal']   = $i;
        $response['recordsFiltered']   = $i;
        $response['data'] = $final_resp;
        return $response;
        //echo"<pre>";print_r($response);die;
    }*/
    public function view($proofid)
    {	
    	$proofs= DB::table('proofs')->where('verified','0')->where('uuid',$proofid)->first();
    	if(!empty($proofs))
        {
            $userdetails= DB::table('users')->where('id',$proofs->user_id)->first();
            return view('back.users.proofverified',compact('proofs','userdetails'));
        }else{
           
            return redirect()->route('admin.users.proofverify');
        }
    }
    public function delete(Request $request)
    {   $input=$request->all();
        //echo"<pre>";print_r($input['proofid']);die;
        if(!empty($input['reason'])){
            $reason=$input['reason'];
            if(!empty($input['other_reason'])){
                $reason=$input['other_reason'];
            }

        }else{
            toastr()->error('Invaild Reasons');
            return back();
        }
        $proofs= Proof::where('id',$input['proofid'])->first();
        //echo"<pre>";print_r($proofs);die;
        if(!empty($proofs)){
            $proofs->verified = '2';
            $proofs->verified_by = Auth::id();
            $proofs->comments = $reason;
            $proofs->verified_date = date("Y-m-d H:m:s");
            $proofs->save();
        }
        
        return redirect()->route('admin.users.proofverify');
    }
    public function recheck($proofid)
    {   
        $proofs= Proof::where('uuid',$proofid)->first();
        //echo"<pre>";print_r($proofs);die;
        if(!empty($proofs)){
            $proofs->verified = '0';
            $proofs->comments = "";
            $proofs->verified_by = Auth::id();
            $proofs->verified_date = date("Y-m-d H:m:s");
            $proofs->save();
        }
        return redirect()->route('admin.users.proofverify');
    }
    public function verified($proofid)
    {   
        $proofs= Proof::where('id',$proofid)->first();
        //echo"<pre>";print_r($proofs);die;
        if(!empty($proofs)){
            $proofs->verified = '1';
            $proofs->comments = "";
            $proofs->verified_by = Auth::id();
            $proofs->verified_date = date("Y-m-d H:m:s");
            $proofs->save();
            addpoint($proofs->user_id,'govt_id_point');
            $findEmail=User::where('id', $proofs->user_id)->first();
            if(!empty($findEmail)){
                $data['name']=$findEmail->name;
                $data['proof']=get_prooflist($proofs->proof);
                $sendmail = Mail::to($findEmail->email)->send(new ProofStatus($data));
                toastr()->success('Verified Status Send');
            }
        }

        
        return redirect()->route('admin.users.proofverify');
    }
}
