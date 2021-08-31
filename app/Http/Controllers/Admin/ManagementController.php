<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Faq_data;
use App\Broadcast;
use DB;
use Auth;
use id;
use Uuid;
class ManagementController extends Controller
{
	public function faq()
	{

		return view('back.management.faq');
	}
	public function faqquestion()
	{
		return view('back.faq.faqquestion');
	}
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $uuid = Uuid::generate(4);
            //echo "<pre>";print_r('xs');die;
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $uuid.'_'.time().'.'.$extension;
        
            $request->file('upload')->move(public_path('uploads/faq'), $fileName);
   
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('public/uploads/faq/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }
    public function insert(Request $request){
        $input=$request->all();
        $uuid = Uuid::generate(4);
        
        $request->validate([
            'questions'=>'required', 
            'answers'  =>'required',
        ]);
        $insert=Faq_data::where('questions',$input['questions'])->first();
        
        if(!empty($insert)) {
            // echo "<pre>";print_r($check);die;
            toastr()->warning('Illegal Access!');
            return back();
            //echo "<pre>";print_r($check);die;
        } else {
            $store= new Faq_data;
            $store->uuid=$uuid;
            $store->questions=$input['questions'];
            $store->answers=$input['answers'];
            $store->save();
            // echo "<pre>";print_r($store);die;

            toastr()->success('Inserted successfully!');
            return redirect()->route('admin.management');
        }
    }
	
    public function getfaqdetails()
    {
        $usersdatas = DB::table('faq')
                    ->select('*')->orderBy('id','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("faq.questions","like","%".$searchValue."%")
                        ->orWhere("faq.answers","like","%".$searchValue."%");
            });          
        }   
        return $usersdatas;
    }
    public function getfaq(Request $request)
    {
    	$count = $this->getfaqdetails();
        $totalCount = count($count->get());

        $getData = $this->getfaqdetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);
        }
        $data['faq'] = $getData->get();
        
        // echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['faq'] as $user) {
            
            $id=($user->id);
            $delete = route('admin.faq.delete',$user->id);
            
            $edit =  route('admin.faq.editfaq',$user->uuid);
            $action= '<center>
            <a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a>
            <button class=" btn btn-danger"onclick="deletecate('.$id.')">
            <i class="fa fa-times"></i></button>
            </center>
            
            <form action="'.$delete.'" method="POST" id="del-cate-'.$id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
            </form>';
             //echo "<pre>";print_r($datas);die;
            $row = array("questions"=>$user->questions,"answers"=>$user->answers,"created_at"=>$user->created_at,"updated_at"=>$user->updated_at,"action"=>$action);
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
    public function editfaq($id)
    {   
        $data= DB::table('faq')->where('uuid',$id)->first();
        //  echo "<pre>";print_r($data);die;
        if(empty($data)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            return view('back.faq.editfaq',compact('data'));
        }
    }
    public function updatefaq(Request $request)
    {
        $input=$request->all();
        //echo "<pre>";print_r($input);die;
        $request->validate([
            'questions'  =>'required',
            'answers'    =>'required',
            
        ]);
        //echo "<pre>";print_r($input);die;
        
        $datafaq=Faq_data::where('id',$input['id'])->first();
        if(empty($datafaq)){
            toastr()->warning(' illegal Access !');
            return back();
        }
        else{
            $datafaq->questions=$input['questions'];
            $datafaq->answers=$input['answers'];
            $datafaq->save();
            
            toastr()->success('Updated successfully!');
            return redirect()->route('admin.management');
        }
    }
    public function deletefaq($id)
    {   //$input=$request->all();
        
        $check=DB::table('faq')->where('id',$id)->first();
        // echo"<pre>";print_r($check);die;
        if(!empty($check)){
            
            $res=Faq_data::destroy($id);
            toastr()->success('Deleted successfully!');
            return back();
        }
        toastr()->error('Data not found');
        return back();
    }

    public function broadcast(Request $request)
    {   
        return view('back.broadcast.broadcast');
    }
    public function broadcastSend(Request $request)
    {   $input=$request->all();
        $request->validate([
            'message'=>'required', 
            'date'=>'date |required'
        ]);
        if($input['date']<=date('Y-m-d')){
            toastr()->warning('Only allows Feature date');
           return back()->withInput();
        }
        Broadcast::create(['message'=>$input['message'],'created_by'=>Auth::user()->id,'date'=>$input['date']]);
        toastr()->success('Broadcast  Saved successfully!');
        return redirect()->route('admin.broadcast');
    }
    public function getbroadcast()
    {
        $usersdatas = DB::table('broadcast')
                    ->select('*')->orderBy('id','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->Where(function ($query) use ($searchValue) {
                $query->orWhere("broadcast.message","like","%".$searchValue."%");
            });          
        }   
        return $usersdatas;
    }
    public function getbroadcastdetails(Request $request)
    {
        $count = $this->getbroadcast();
        $totalCount = count($count->get());

        $getData = $this->getbroadcast();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);
        }
        $data['broadcast'] = $getData->get();
        
        // echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['broadcast'] as $broadcast) {
            
            if($broadcast->status==0){
                $status='Processing';
            }else{
                $status='Sent';
            }
            $action='NO';
            if($broadcast->status==0){
                
                $delete = route('admin.broadcast.delete',$broadcast->id);
                $edit =  route('admin.broadcast.edit',$broadcast->id);
                $action= '<center>
                <a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a>
                <button class=" btn btn-danger"onclick="deletecate('.$broadcast->id.')">
                <i class="fa fa-times"></i></button>
                </center>
                
                <form action="'.$delete.'" method="POST" id="del-cate-'.$broadcast->id.'">
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';
            }
            $row = array("message"=>$broadcast->message,"created_by"=>getUserEmail($broadcast->created_by),"date"=>$broadcast->date,"status"=>$status,'action'=>$action);
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
    public function broadcastEdit($id)
    {   $broad=Broadcast::find($id);
        if(!empty($broad)){
            return view('back.broadcast.editbroadcast',compact('broad'));
        }else{
            toastr()->warning('This Broadcast resource Not found!');
            return back();
        }
        
    }
    public function broadcastUpdate(Request $request)
    {   $input=$request->all();
        $request->validate([
            'id'=>'required',
            'message'=>'required', 
            'date'=>'date |required'
        ]);
        if($input['date']<=date('Y-m-d')){
            toastr()->warning('Only allows Feature date');
           return back()->withInput();
        }
        $broad=Broadcast::find($input['id']);
        if(!empty($broad)){
            $broad->message=$input['message'];
            $broad->date=$input['date'];
            $broad->save();
            toastr()->success('Broadcast  Saved successfully!');
            return redirect()->route('admin.broadcast');
        }else{
            toastr()->warning('This Broadcast resource Not found!');
            return back();
        }
    }
    public function delete($id)
    {   
        
        $check=DB::table('broadcast')->where('id',$id)->first();
        // echo"<pre>";print_r($check);die;
        if(!empty($check)){
            
            $res=Broadcast::destroy($id);
            toastr()->success('This Broadcast Deleted!');
            return redirect()->route('admin.broadcast');
        }
        toastr()->error('Data not found');
        return redirect()->route('admin.broadcast');
    }
}
