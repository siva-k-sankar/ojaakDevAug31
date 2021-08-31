<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Uuid;
use App\Customfield;
use Auth;
use DB;
use App\Customfield_Options;
use App\Parent_categories;
use App\Sub_categories;

class CustomfieldController extends Controller
{
    public function index()
    {   
        return view('back.customfields.customfield');
    }
    public function createfield()
    {   $subcategory=Sub_categories::get();
        //echo"<pre>";print_r($subcategory);die;
        if (!empty($subcategory)) {
            return view('back.customfields.createcustomfield',compact('subcategory'));
        }else{
            toastr()->error('No Sub_categories Found');
            return back();
        }
        

    }
    public function Addfield(Request $request)
    {
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'name'          =>'required', 
            'type'          =>'required',
        ]);
         //'name'          =>'required|regex:/^[a-zA-Z]+$/u', 
		//echo"<pre>";print_r($input);die;
        $check=Customfield::where('name',$input['name'])->where('subcategory',$input['subcategory'])->first();
        if (!empty($check)) {
        	toastr()->error(' Field Already Exists!');
        	return back();
        } else {
			$field= new Customfield;
        	$field->uuid=$uuid;
        	$field->name=$input['name'];
        	$field->type=$input['type'];
            $field->subcategory=$input['subcategory'];
        	if(!empty($input['max'])){
        		$field->max=$input['max'];
        	}
            
            if($input['type']=='checkbox'){
               $field->default=1;
            }else{
                $field->default=$input['default'];
            }

        	//$field->default=$input['default'];
        	$field->required=$input['required'];
        	$field->active=1;
        	$field->created_by=Auth::id();
        	$field->save();
            toastr()->success('Field Created successfully!');
        	return redirect()->route('admin.customfield');
        }

    }
    public function getfield(Request $request){
        
        $fields=DB::table('fields')
        ->select('name','subcategory','type','created_by','updated_at','created_at','active','uuid','id')->orderby('id','desc')->get();
        //echo"<pre>";print_r($plans);die;
        $final_resp = array();
        $i=0;
        foreach ($fields as $key => $value) {
            $resp = array();
            $j=0;
             $resp[] = $i+1;
            foreach ($value as $key1 => $value1) {
                if($j==8){
                    $id=$value1;
                }else if($j==7){
                    $uuid=$value1;
                }else if($j==6){
                      if($value1==1)
                      {
                        $resp[] ='Active';
                      }else{
                        $resp[] ='InActive';
                      }  

                }else if($j==3){
                    $resp[] = get_name($value1);
                   
                }else if($j==2){
                	$resp[] = $value1;
                	$type=$value1;
                }else if($j==1){
                    $resp[] = get_S_Cate_Name($value1);
                }else{
                   $resp[] = $value1; 
                }
                $j++;

            }
            $status =  route('admin.customfield.status',$id);
            $delete =  route('admin.customfield.delete',$id);
            $edit =  route('admin.customfield.edit',$uuid);
            $option =  route('admin.customfield.option',$uuid);
            $action = '<button style="margin:3px;" class=" btn btn-sm bg-blue"onclick="status('.$id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn btn-sm bg-yellow"><i class="fa  fa-pencil-square-o"></i></a>
                ';
            /*$action = '<button style="margin:3px;" class=" btn btn-sm bg-blue"onclick="status('.$id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn btn-sm bg-yellow"><i class="fa  fa-pencil-square-o"></i></a><button style="margin:3px;" class=" btn btn-sm bg-red"onclick="deletecate('.$id.')"><i class="fa fa-times"></i></button>
            ';*/
            $form='<form action="'.$delete.'" method="POST" id="del-plan-'.$id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>
                <form action="'.$status.'" method="POST" id="status-plan-'.$id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';
            if($type=="select" || $type=="radio" || $type=="checkbox_multiple"){
                $resp[]=$action.'<a style="margin:3px;" href="'.$option.'" class=" btn btn-sm bg-green"><i class="fa  fa-cog"></i> Options</a>'.$form;
            }else{
                $resp[]=$action.$form;
            }

            array_push($final_resp, $resp);
            $i++;
        }
        //$data=json_encode($final_resp);
        $response['draw']   = 1;
        $response['recordsTotal']   = $i;
        $response['recordsFiltered']   = $i;
        $response['data'] = $final_resp;
        //echo"<pre>";print_r($response);die;
        return $response;
    
    }
    public function fieldstatus($id)
    {   $check= Customfield::where('id',$id)->first();
        if(!empty($check)){
            if($check->active == 0){
            	$check->created_by = Auth::id();
            	$check->updated_at = Date("Y-m-d H:m:s");
                $check->active = 1;
                $check->save();
            }else{
                $check->active = 0;
                $check->created_by = Auth::id();
                $check->save();
            }
			toastr()->success('Field Status Changed successfully!');
            return back();
        }
    }
    public function fielddelete($id)
    {   
    	$check= Customfield::where('id',$id)->first();
        if(!empty($check)){
            $res=Customfield::destroy($id);
			toastr()->success('Field Deleted successfully!');
            return back();
        }else{
        	toastr()->error(' Data not found');
        	return back();
        }

    }
    public function fieldedit($id)
    {   
        $data= DB::table('fields')->where('uuid',$id)->first();
        if(empty($data)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            return view('back.customfields.editcustomfield',compact('data'));
        }
    }
    public function fieldupdate(Request $request)
    {
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
        	'id'          =>'required', 
            'name'          =>'required', 
            'type'          =>'required',
            // 'max'          =>'required',
            'type'          =>'required',
            'subcategory'   =>'required',
        ]);
		//echo"<pre>";print_r($input);die;
        $field=Customfield::where('uuid',$input['id'])->where('subcategory',$input['subcategory'])->first();
        if (empty($field)) {
        	toastr()->error('No data Found!');
        	return back();
        } else {
			$field->uuid=$uuid;
        	$field->name=$input['name'];
        	$field->type=$input['type'];
            $field->subcategory=$input['subcategory'];
        	if(!empty($input['max'])){
        		$field->max=$input['max'];
        	}
            if($input['type']=='checkbox'){
                if(!empty($input['default'])){
                    $field->default=$input['default'];
                }else{
                    $field->default=1;
                }
            }else{
                $field->default=$input['default'];
            }
        	//$field->default=$input['default'];
        	$field->required=$input['required'];
        	$field->active=1;
        	$field->created_by=Auth::id();
        	$field->updated_at=Date("Y-m-d H:m:s");
        	$field->save();
            toastr()->success('Field Updated successfully!');
        	return redirect()->route('admin.customfield');
        }

    }
    public function option($id)
    {   
    	$field=Customfield::where('uuid',$id)->first();
    	if(!empty($field)){
    		if($field->type=="select" || $field->type=="radio" || $field->type=="checkbox_multiple"){
    			return view('back.customfields.options',compact('field'));
    		}else{
    			toastr()->error('This Field Have No Options !');
    			return back();
			}
		}else{
    		toastr()->error('No data Found!');
    		return back();
    	}
        
    }
    public function createoption($id)
    {
        $field=Customfield::where('uuid',$id)->first();
    	if(!empty($field)){
    		if($field->type=="select" || $field->type=="radio" || $field->type=="checkbox_multiple"){
    			return view('back.customfields.createoptions',compact('field'));
    		}else{
    			toastr()->error('This Field Have No Options !');
    			return back();
			}
		}else{
    		toastr()->error('No data Found!');
    		return back();
    	}

    }
    public function Addoption(Request $request)
    {
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'id'          =>'required', 
            'value'          =>'required',
        ]);
        $field=Customfield::where('uuid',$input['id'])->first();
        if(!empty($field)){
    		if($field->type=="select" || $field->type=="radio" || $field->type=="checkbox_multiple"){
    			$check=Customfield_Options::where('value',$input['value'])->where('field_id',$field->id)->first();
    			if (!empty($check)) {
        			toastr()->error(' Option Already Exists!');
        			return back();
        		}else{
        			$option= new Customfield_Options;
        			$option->uuid=$uuid;
        			$option->field_id=$field->id;
        			$option->value=$input['value'];
                    $option->created_by=Auth::id();
        			$option->save();
        			toastr()->success('Option Created successfully!');
        			return redirect()->route('admin.customfield.option',$input['id']);
        		} 
    		}else{
    			toastr()->error('This Field Have No Options !');
    			return back();
			}
		}else{
    		toastr()->error('No data Found!');
    		return back();
    	}
	}
	public function getoption($id){
        $field=Customfield::where('uuid',$id)->first();
        //echo"<pre>";print_r($field);die;

        $option=DB::table('fields_options')
        ->select('value','created_by','updated_at','created_at','uuid','id')->where('field_id',$field->id)->orderby('id','desc')->get();
        $final_resp = array();
        $i=0;
        foreach ($option as $key => $value) {
            $resp = array();
            $j=0;
             $resp[] = $i+1;
            foreach ($value as $key1 => $value1) {
                if($j==4){
                    $uuid=$value1;
                }else if($j==5){
                	$id=$value1;
                }else if($j==1){
                    $resp[] = get_name($value1);
                   
                }else{
                   $resp[] = $value1; 
                }
                $j++;

            }
            $delete =  route('admin.customfield.option.delete',$id);
            $edit =  route('admin.customfield.option.edit',[$field->uuid,$uuid]);
            $action = '<a style="margin:3px;" href="'.$edit.'" class=" btn btn-sm bg-yellow"><i class="fa  fa-pencil-square-o"></i></a>
                ';
            /*$action = '<a style="margin:3px;" href="'.$edit.'" class=" btn btn-sm bg-yellow"><i class="fa  fa-pencil-square-o"></i></a><button style="margin:3px;" class=" btn btn-sm bg-red"onclick="deletecate('.$id.')"><i class="fa fa-times"></i></button>
            ';*/
            $form='<form action="'.$delete.'" method="POST" id="del-plan-'.$id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';
            
            $resp[]=$action.$form;
            

            array_push($final_resp, $resp);
            $i++;
        }
        //$data=json_encode($final_resp);
        $response['draw']   = 1;
        $response['recordsTotal']   = $i;
        $response['recordsFiltered']   = $i;
        $response['data'] = $final_resp;
        //echo"<pre>";print_r($response);die;

        return $response;
    
    }
    public function optiondelete($id)
    {   
    	$check= Customfield_Options::where('id',$id)->first();
        if(!empty($check)){
            $res=Customfield_Options::destroy($id);
			toastr()->success('Option Deleted successfully!');
            return back();
        }else{
        	toastr()->error(' Data not found');
        	return back();
        }
	}
    public function optionedit($field,$id)
    {   $fieldid=$field;
    	$data= DB::table('fields_options')->where('uuid',$id)->first();
        if(empty($data)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            return view('back.customfields.editoptions',compact('data','fieldid'));
        }
    }
    public function optionupdate(Request $request)
    {
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
        	'id'          =>'required',
        	'field_id' 	=>'required',
            'value'          =>'required', 
            
        ]);
		//echo"<pre>";print_r($input);die;
        $field=Customfield_Options::where('uuid',$input['id'])->first();
        if (empty($field)) {
        	toastr()->error('No data Found!');
        	return back();
        } else {
        	$check=Customfield_Options::where('value',$input['value'])->where('uuid','!=',$input['id'])->first();
        	if(!empty($check)){
        		toastr()->error('Value Already Exists');
        		return back();
        	}
			$field->value=$input['value'];
        	$field->created_by=Auth::id();
        	$field->updated_at=Date("Y-m-d H:m:s");
        	$field->save();
            toastr()->success('Option Updated successfully!');
        	return redirect()->route('admin.customfield.option',$input['field_id']);
        }

    }
}
