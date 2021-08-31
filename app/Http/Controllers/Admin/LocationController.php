<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Uuid;
use Image;
use DB;
use Carbon\Carbon;
use App\Ads;
use Illuminate\Support\Str;
use App\Countries;
use App\States;
use App\Cities;
use App\City_requests;
class LocationController extends Controller
{
    public function index(Request $request)
    {
        return view('back.location.country.country');

    }
    public function treeview(Request $request)
    {
        $countries=Countries::get();
        $states=States::get();
        $cities=Cities::get();
        return view('back.location.treeview',compact('countries','states','cities'));

    }
    public function createcountry(Request $request)
    {
        return view('back.location.country.createcountry');

    }
    public function getCountryDetails()
    {
        $county = DB::table('countries')
                     ->select('name','sortname','status','uuid','id')->orderby('id','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $county->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("countries.name","like","%".$searchValue."%")
                ->orWhere("countries.sortname","like","%".$searchValue."%");
            });          
        }
        return $county;
    }
    public function getcountry(Request $request){
        $count = $this->getCountryDetails();
        $totalCount = count($count->get());

        $getData = $this->getCountryDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['country'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['country'] as $country) {

            if($country->status==1){
                $status='Active';
            }else{
               $status='In Active';
            }

            $statusform =  route('admin.location.statuscountry',$country->id);
            $edit =  route('admin.location.editcountry',$country->uuid);
            $states =  route('admin.location.state',$country->uuid);
            $action = '<center><a style="margin:3px;" href="'.$states.'" class=" btn  bg-green">States</a><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$country->id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a></center>
                <form action="'.$statusform.'" method="POST" id="status-cate-'.$country->id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>
                ';
            $row = array("name"=>$country->name,"sort"=>$country->sortname,"status"=>$status,"action"=>$action);
            
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
    public function addcountry(Request $request)
    {   
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'name'          =>'required', 
            'sortname'          =>'required',
            
        ]);
        //echo"<pre>";print_r($input);die;
        $check=Countries::where('name',$input['name'])->where('sortname',$input['sortname'])->first();
        if(!empty($check)){
            toastr()->error('Country already exists');
            return back();
        }else{
            
            $create=Countries::Create([

                'name'=>ucfirst($input['name']),
                'sortname'=>strtoupper($input['sortname']),
                'uuid'=>$uuid,
                
            ]);
            if(!empty($create)){
                toastr()->success('Country Created  Successfully');
                return redirect()->route('admin.location.country');
            }

        }
    }
    public function countryedit($id)
    {   
        $data= DB::table('countries')->where('uuid',$id)->first();
        if(empty($data)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            return view('back.location.country.editcountry',compact('data'));
        }
    }
    public function countryupdate(Request $request)
    {   
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'name'          =>'required', 
            'sortname'          =>'required',
            
        ]);
        
        $check=Countries::where('uuid',$input['id'])->first();
        if(!empty($check)){
			$check->name = ucfirst($input['name']);
            $check->sortname = strtoupper($input['sortname']);
            $check->save();
            toastr()->success(' Country Updated successfully!');
            return redirect()->route('admin.location.country');
        }else{
           
            toastr()->error(' Request Invalid !');
            return back();
        }
    }
    public function countrydelete($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= DB::table('countries')->where('id',$id)->first();
        //echo"<pre>";print_r($check);die;
        if(!empty($check)){
            
            $res=Countries::destroy($id);
            toastr()->success(' Country deleted successfully!');
            return back();
        }
        toastr()->error(' Data not found');
        return back();
    }
    public function stateindex($id)
    {   
        $parentdata= DB::table('countries')->where('uuid',$id)->first();
        if(!empty($parentdata)){
            return view('back.location.states.state',compact('id'));
        }else{
            toastr()->warning(' illegal Access !');
            return redirect()->route('admin.location.country');
        }
    }
    public function createstate($id)
    {   //echo"<pre>";print_r($id);die;
    	$parentdata= DB::table('countries')->where('uuid',$id)->first();
        if(!empty($parentdata)){
            return view('back.location.states.createstate',compact('id'));
        }else{
            toastr()->warning(' illegal Access !');
            return redirect()->route('admin.location.country');
        }
    }
    public function getstate($id){
    	$countryuuid=$id;
        $check=DB::table('countries')->where('uuid',$id)->first();
        $countryid=$check->id;
        //echo"<pre>";print_r($countryid);die;
        $unverifiedproofdata=DB::table('states')
        ->select('name','status','uuid','id')->where('country_id',$countryid)->orderby('id','desc')->get();
        //echo"<pre>";print_r($unverifiedproofdata);die;
        $final_resp = array();
        $i=0;
        foreach ($unverifiedproofdata as $key => $value) {
            $resp = array();
            $j=0;
             $resp[] = $i+1;
            foreach ($value as $key1 => $value1) {
                if($j==3){
                	$id=$value1;
                }else if($j==1){
                      if($value1==1)
                      {
                        $resp[] ='Active';
                      }else{
                        $resp[] ='InActive';
                      }  

                }else if($j==2){
                    $uuid=$value1;
                }else{
                   $resp[] = $value1; 
                }
                $j++;

            }
            $status =  route('admin.location.statusstate',$id);
            $delete =  route('admin.location.deletestate',$id);
            $edit =  route('admin.location.editstate',[$countryuuid,$uuid]);
            $cities =  route('admin.location.cities',$uuid);
            $resp[] = '<center><a style="margin:3px;" href="'.$cities.'" class=" btn  bg-green">Cities</a><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a></center>
                
                <form action="'.$status.'" method="POST" id="status-cate-'.$id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>
                ';
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
    }
    public function addstate(Request $request)
    {   
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'name'          =>'required', 
            
            
        ]);
        //echo"<pre>";print_r($input);die;
        $country=Countries::where('uuid',$input['country-uuid'])->first();
        if(empty($country)){
            toastr()->error('No country Selected');
            return back();
        }
        $name=ucfirst($input['name']);
        $state=States::where('name',$name)->first();
        if(!empty($state)){
            toastr()->error('State already exists');
            return back();
        }else{
            
            $create=States::Create([
			    'name'=>ucfirst($input['name']),
			    'country_id'=>$country->id,
                'uuid'=>$uuid,
            ]);
            if(!empty($create)){
                toastr()->success('State Created  Successfully');
                return redirect()->route('admin.location.state',$input['country-uuid']);
            }

        }
    }
    public function stateedit($countryid,$id)
    {   
        $subdata= DB::table('states')->where('uuid',$id)->first();
        $parentdata= DB::table('countries')->where('uuid',$countryid)->first();
        $id=$countryid;
        if(empty($subdata) || empty($parentdata)){
            toastr()->warning(' illegal Access !');
            return redirect()->route('admin.location.country');
        }else{
            return view('back.location.states.editstate',compact('subdata','countryid'));
        }
    }
    public function stateupdate(Request $request)
    {   
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'name'          =>'required', 
            
            
        ]);
        
        $check=States::where('uuid',$input['id'])->first();
        $country=Countries::where('id',$check->country_id)->first();
        //echo"<pre>";print_r($country->uuid);die;
        if(!empty($check)){
			$check->name = ucfirst($input['name']);
            $check->save();
            toastr()->success(' State Updated successfully!');
            return redirect()->route('admin.location.state',$country->uuid);
        }else{
           
            toastr()->error(' Request Invalid !');
            return back();
        }
    }
    public function statedelete($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= DB::table('states')->where('id',$id)->first();
        //echo"<pre>";print_r($check);die;
        if(!empty($check)){
            
            $res=States::destroy($id);
            toastr()->success(' State deleted successfully!');
            return back();
        }
        toastr()->error(' Data not found');
        return back();
    }
    public function citiesindex($id)
    {   
        $parentdata= DB::table('states')->where('uuid',$id)->first();
        if(!empty($parentdata)){
            $data= DB::table('countries')->where('id',$parentdata->country_id)->first();
            return view('back.location.cities.cities',compact('id','data'));
        }else{
            toastr()->warning(' illegal Access !');
            return redirect()->route('admin.location.country');
        }
    }
    public function createcities($id)
    {   //echo"<pre>";print_r($id);die;
    	$parentdata= DB::table('states')->where('uuid',$id)->first();
        if(!empty($parentdata)){
            $data= DB::table('countries')->where('id',$parentdata->country_id)->first();
            return view('back.location.cities.createcities',compact('id','data'));
        }else{
            toastr()->warning(' illegal Access !');
            return redirect()->route('admin.location.country');
        }
    }
    public function getcities($id){
    	$countryuuid=$id;
        $check=DB::table('states')->where('uuid',$id)->first();
        $countryid=$check->id;
        //echo"<pre>";print_r($countryid);die;
        $unverifiedproofdata=DB::table('cities')
        ->select('name','status','uuid','id')->where('state_id',$countryid)->orderby('id','desc')->get();
        //echo"<pre>";print_r($unverifiedproofdata);die;
        $final_resp = array();
        $i=0;
        foreach ($unverifiedproofdata as $key => $value) {
            $resp = array();
            $j=0;
             $resp[] = $i+1;
            foreach ($value as $key1 => $value1) {
                if($j==3){
                	$id=$value1;
                }else if($j==1){
                      if($value1==1)
                      {
                        $resp[] ='Active';
                      }else{
                        $resp[] ='InActive';
                      }  

                }else if($j==2){
                    $uuid=$value1;
                }else{
                   $resp[] = $value1; 
                }
                $j++;

            }
            $status =  route('admin.location.statuscities',$id);
            $delete =  route('admin.location.deletecities',$id);
            $edit =  route('admin.location.editcities',[$countryuuid,$uuid]);
            $resp[] = '<center><a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$id.')"><i class="fa   fa-clone"></i></button></center>
                
                <form action="'.$status.'" method="POST" id="status-cate-'.$id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>
                ';
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
    }
    public function addcities(Request $request)
    {   
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'name'          =>'required', 
            
            
        ]);
        //echo"<pre>";print_r($input);die;
        $country=States::where('uuid',$input['country-uuid'])->first();
        if(empty($country)){
            toastr()->error('No Cities Selected');
            return back();
        }
        $name=ucfirst($input['name']);
        $state=Cities::where('name',$name)->first();
        if(!empty($state)){
            toastr()->error('Cities already exists');
            return back();
        }else{
            
            $create=Cities::Create([
			    'name'=>ucfirst($input['name']),
			    'state_id'=>$country->id,
                'uuid'=>$uuid,
            ]);
            if(!empty($create)){
                toastr()->success('Cities Created  Successfully');
                return redirect()->route('admin.location.cities',$input['country-uuid']);
            }

        }
    }
    public function citiesedit($countryid,$id)
    {   
        $subdata= DB::table('cities')->where('uuid',$id)->first();
        $parentdata= DB::table('states')->where('uuid',$countryid)->first();
        $id=$countryid;
        if(empty($subdata) || empty($parentdata)){
            toastr()->warning(' illegal Access !');
            return redirect()->route('admin.location.country');
        }else{
            return view('back.location.cities.editcities',compact('subdata','countryid'));
        }
    }
    public function citiesupdate(Request $request)
    {   
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'name'          =>'required', 
            
            
        ]);
        
        $check=Cities::where('uuid',$input['id'])->first();
        $state=States::where('id',$check->state_id)->first();
        if(!empty($check)){
			$check->name = ucfirst($input['name']);
            $check->save();
            toastr()->success(' State Updated successfully!');
            return redirect()->route('admin.location.cities',$state->uuid);
        }else{
           
            toastr()->error(' Request Invalid !');
            return back();
        }
    }
    public function citiesdelete($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= DB::table('cities')->where('id',$id)->first();
        //echo"<pre>";print_r($check);die;
        if(!empty($check)){
            
            $res=Cities::destroy($id);
            toastr()->success(' Cities deleted successfully!');
            return back();
        }
        toastr()->error(' Data not found');
        return back();
    }
    public function citiesstatus($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= Cities::where('id',$id)->first();
        //echo"<pre>";print_r($check->status);die;

        if(!empty($check)){
            
            if($check->status=="0"){
                $check->status="1";
                $check->save();
            }else{
                $check->status="0";
                $check->save();
            }
            
            toastr()->success('Sub Categories Status Changed successfully!');
            return back();
        }
        
    }
    public function countrystatus($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= Countries::where('id',$id)->first();
        //echo"<pre>";print_r($check->status);die;

        if(!empty($check)){
            
            if($check->status=="0"){
                $check->status="1";
                $check->save();
            }else{
                $check->status="0";
                $check->save();
            }
            
            toastr()->success('Sub Categories Status Changed successfully!');
            return back();
        }
        
    }
    public function statestatus($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= States::where('id',$id)->first();
        //echo"<pre>";print_r($check->status);die;

        if(!empty($check)){
            
            if($check->status=="0"){
                $check->status="1";
                $check->save();
            }else{
                $check->status="0";
                $check->save();
            }
            
            toastr()->success('Sub Categories Status Changed successfully!');
            return back();
        }
        
    }
    public function getRequestDetails()
    {
        $city_request = DB::table('city_request')
                     ->select('*')->orderBy('status','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $city_request->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("city_request.name","like","%".$searchValue."%");
            });          
        }
        return $city_request;
    }
    public function getrequest(Request $request){
        $count = $this->getRequestDetails();
        $totalCount = count($count->get());

        $getData = $this->getRequestDetails();
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);

        }
        $data['city'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['city'] as $city) {
            $citystatus='';
            $action='';
            if($city->status==1){
                $citystatus='Approved';
                $action = '<center><a style="margin:3px;" href="#" class=" btn bg-green"><i class="fa  fa-check-square-o"></i></a></center>';
            }else if($city->status==0){
                $citystatus='Pending';
                $edit =  route('admin.location.viewrequest',$city->uuid);
                $action = '<center><a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a></center>';
            }else{
                $citystatus='Reject';
                $action='<center><a style="margin:3px;" href="#" class=" btn bg-red"><i class="fa  fa-pencil-square-o"></i></a></center>';
            }

            $row = array("name"=>$city->name,"user"=>get_name($city->user_id),"status"=>$citystatus,"action"=>$action);
            
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
    public function getrequests(Request $request){
        $city_request=DB::table('city_request')
        ->select('name','user_id','status','uuid','id')->orderby('created_at','desc')->get();
        //echo"<pre>";print_r($unverifiedproofdata);die;
        $final_resp = array();
        $i=0;
        foreach ($city_request as $key => $value) {
            $resp = array();
            $j=0;
            $change="";
             $resp[] = $i+1;
            foreach ($value as $key1 => $value1) {
                if($j==4){
                    $id=$value1;
                }else if($j==2){
                      if($value1==1)
                      {
                        $resp[] ='Approved';
                        $change='Approved';
                      }else if($value1==0){
                        $resp[] ='Pending';
                        $change='Pending';
                      }else{
                        $resp[] ='Reject';
                        $change='Reject';
                      }  

                }else if($j==3){
                    $uuid=$value1;
                }else if($j==1){
                     $resp[] = get_name($value1);
                }else{
                   $resp[] = $value1; 
                }
                $j++;

            }
            if($change=='Pending'){
                $edit =  route('admin.location.viewrequest',$uuid);
                $resp[] = '<center><a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a></center>';
            }else if($change=='Approved'){
                
                $resp[] = '<center><a style="margin:3px;" href="#" class=" btn bg-green"><i class="fa  fa-check-square-o"></i></a></center>';
            }else{
                $resp[] = '<center><a style="margin:3px;" href="#" class=" btn bg-red"><i class="fa  fa-pencil-square-o"></i></a></center>';
            }
            

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
    }
    public function locationrequest(Request $request)
    {
        return view('back.location.request');

    }
    public function locationview($id)
    {
        
        $check=City_requests::where('uuid',$id)->first();
        if(!empty($check)){
           return view('back.location.requestadd',compact('check')); 
        }else{
            toastr()->warning('Invalid Access');
            return redirect()->route('admin.location.request');
        }
    }
    public function autocompletecountry(Request $request)
    {
        $countries =  DB::table("countries")->pluck("name","id");
        return json_encode($countries);
    }
    public function autocompletestate($id)
    {
        $state =  DB::table("states")->where('country_id',$id)->pluck("name","id");
        //echo"<pre>";print_r($id);die;
        return json_encode($state);
    }
    public function locationadd(Request $request)
    {
        $request->validate([
            'country'        =>'required',
            'post_id'        =>'required', 
            'state'          =>'required',
            'city'           =>'required',
            
        ]);
        $input=$request->all();
        $input['uuid'] = Uuid::generate(4);
        $cities=Cities::where('name',ucfirst($input['city']))->where('state_id',$input['state'])->first();
        if(!empty($cities)){
                toastr()->error('cities already exists');
                $ads=Ads::where('id',$input['post_id'])->first();
                if(!empty($ads)){
                    $ads->cities=$cities->id;
                    $ads->save();
                    toastr()->success('Post Id Updated');
                }
                $cr=City_requests::where('uuid',$input['id'])->first();
                if(!empty($cr)){
                    $cr->status="1";
                    $cr->name=$cities->name;
                    $cr->save();
                }
                return redirect()->route('admin.location.request');
            //return back();
        }else{
            
            $create=Cities::Create([
                'name'=>ucfirst($input['city']),
                'state_id'=>$input['state'],
                'uuid'=>$input['uuid'],
            ]);

            if(!empty($create)){
                toastr()->success('Cities Added  Successfully');
                $ads=Ads::where('id',$input['post_id'])->first();
                if(!empty($ads)){
                    $ads->cities=$create->id;
                    $ads->save();
                    toastr()->success('Post Id Updated');
                }
                $cr=City_requests::where('uuid',$input['id'])->first();
                if(!empty($cr)){
                    $cr->status="1";
                    $cr->name=$create->name;
                    $cr->save();
                }
                return redirect()->route('admin.location.request');

            }

        }


        //echo"<pre>";print_r($input);die;
        //return view('back.location.request');

    }

}
