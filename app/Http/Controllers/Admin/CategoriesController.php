<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Parent_categories;
use App\Sub_categories;
use Uuid;
use Image;
use DB;
use Carbon\Carbon;
use App\Customfield;
use App\Category_field;
use Auth;
class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        return view('back.ads.parent_categories.categories');

    }
    public function treeview(Request $request)
    {
        $parent=Parent_categories::get();
        $sub=Sub_categories::get();
        return view('back.ads.treeview',compact('parent','sub'));

    }
    public function createcategories()
    {
        return view('back.ads.parent_categories.createcategories');

    }
    public function addcategories(Request $request)
    {   
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'name'          =>'required', 
            'desc'          =>'required',
            //'icon'          =>'required',
            'image'     => 'required|mimes:svg,html|max:2048',
        ]);
        //echo"<pre>";print_r($input['desc']);die;
        $slug = Str::slug($input['name'], '-');
        $check=Parent_categories::where('slug',$slug)->first();
        if(!empty($check)){
            toastr()->error('Categories already exists');
            return back();
        }else{
            $image = $request->file('image');
            $imgname = time().$uuid.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/categories/');
            /*$img = Image::make($image->getRealPath());
            //$img->resize(26, 27);
            $img->save($destinationPath.'/'.$imgname);*/
            request()->image->move($destinationPath, $imgname);
            $create=Parent_categories::Create([

                'name'=>$input['name'],
                'uuid'=>$uuid,
                'slug'=>$slug,
                'description'=>$input['desc'],
                /*'icon'=>$input['icon'],
                'image'=>"",*/
                'image'=>$imgname,
            ]);
            if(!empty($create)){
                toastr()->success('Categories Created  Successfully');
                return redirect()->route('admin.ads.categories');
            }

        }
    }
    public function getparentcategories(Request $request){
        $getparentcategories=DB::table('parent_categories')
        ->select('name','slug','description','image','status','uuid','id')->orderby('id','desc')->get();
        //echo"<pre>";print_r($unverifiedproofdata);die;
        $final_resp = array();
        $i=0;
        foreach ($getparentcategories as $key => $value) {
            $resp = array();
            $j=0;
             $resp[] = $i+1;
            foreach ($value as $key1 => $value1) {
                if($j==6){
                    $id=$value1;
                }else if($j==5){
                    $uuid=$value1;
                }else if($j==4){
                      if($value1==1)
                      {
                        $resp[] ='Active';
                      }else{
                        $resp[] ='InActive';
                      }  

                }else if($j==3){
                    $s=url('public/uploads/categories')."/"."$value1";
                   $resp[] ='<center><img src="'.$s.'" width="50"height="50"/></center>';
                   /*$resp[] ='<center><i class="fa '.$value1.'" aria-hidden="true"></i></center>';
*/                }else{
                   $resp[] = $value1; 
                }
                $j++;

            }
            $status =  route('admin.ads.statuscategories',$id);
            $deleteCate =  route('admin.ads.deletecategories',$uuid);
            $edit =  route('admin.ads.editcategories',$uuid);
            $sub =  route('admin.ads.subcategories',$uuid);
            $resp[] = '<center><a style="margin:3px;" href="'.$sub.'" class=" btn  bg-green">Sub Categories</a><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a><button style="margin:3px;" class=" btn bg-red " onclick="cateDelete(\''.trim($uuid).'\')"><i class="fa fa-trash"></i></button></center>
                
                <form action="'.$status.'" method="POST" id="status-cate-'.$id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>
                <form action="'.$deleteCate.'" method="POST" id="delete-cate-'.$uuid.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';
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
    public function parentcategoriesdelete($id)
    {   
        //echo $id;die;
        //$input=$request->all();
        $check= DB::table('parent_categories')->where('uuid',$id)->first();
        if(!empty($check)){
            $categoriesId = $check->id;
            

            $getAdsCategories=DB::table('ads')->select('id','categories')->where('categories',$categoriesId)->get()->toArray();
            $getAdsCategoryId = array_column($getAdsCategories, 'id');
            if(!empty($getAdsCategoryId)){
                toastr()->warning('This category have some ads,so not able to delete this category!');
                return back();
            }else{
                if(!empty($getAdsCategoryId)){
                    DB::table('ads_features')->whereIn('ads_id',$getAdsCategoryId)->update(['ads_id'=>null,'expire_date'=>null]);

                    DB::table('ads_image')->whereIn('ads_id',$getAdsCategoryId)->delete();
                    DB::table('reject_reason')->whereIn('ads_id',$getAdsCategoryId)->delete();
                    DB::table('report_ads')->whereIn('report_ads_id',$getAdsCategoryId)->delete();
                    DB::table('post_values')->whereIn('post_id',$getAdsCategoryId)->delete();
                    DB::table('post_values_temp')->whereIn('post_id',$getAdsCategoryId)->delete();
                    DB::table('ads')->whereIn('id',$getAdsCategoryId)->delete();
                    DB::table('ads_temp')->where('categories',$categoriesId)->delete();
                    DB::table('ads_reviews')->whereIn('ads_id',$getAdsCategoryId)->delete();
                    DB::table('chats')->whereIn('ads_id',$getAdsCategoryId)->delete();
                    DB::table('favourites')->whereIn('ads_id',$getAdsCategoryId)->delete();
                    DB::table('featureads_list')->whereIn('ads_id',$getAdsCategoryId)->delete();
                    DB::table('sub_categories')->where('parent_id',$categoriesId)->delete();
                }
                if(!empty($check->image) && file_exists(public_path('/uploads/categories/'.$check->image)))
                {
                    unlink(public_path('uploads/categories/'.$check->image));
                }
                $res=Parent_categories::destroy($categoriesId);
                toastr()->success(' Categories deleted successfully!');
                return back();
            }
            
        }
        toastr()->error('Categories not found');
        return back();
    }

    public function parentcategoriesstatus($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= Parent_categories::where('id',$id)->first();
        //echo"<pre>";print_r($check->status);die;

        if(!empty($check)){
            
            if($check->status=="0"){
                $check->status="1";
                $check->save();
            }else{
                $check->status="0";
                $check->save();
            }
            
            toastr()->success(' Categories Status Changed successfully!');
            return back();
        }
        
    }
    public function parentcategoriesedit($id)
    {   
        $data= DB::table('parent_categories')->where('uuid',$id)->first();
        if(empty($data)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            return view('back.ads.parent_categories.editcategories',compact('data'));
        }
    }
    public function parentcategoriesupdate(Request $request)
    {   
        $input=$request->all();
        //echo"<pre>";print_r($input);die;
        $uuid = Uuid::generate(4);
        $request->validate([
            'id'=>'required',
            'name'          =>'required', 
            'desc'          =>'required',
            //'icon'          =>'required',
            'image'     => 'mimes:svg,html|max:2048',
        ]);
        $slug = Str::slug($input['name'], '-');
        $check=Parent_categories::where('uuid',$input['id'])->first();
        if(!empty($check)){
            
            $image = $request->file('image');
            if(!empty($image)){
                if(!empty($check->image) && file_exists(public_path('/uploads/categories/'.$check->image)))
                {
                    unlink(public_path('uploads/categories/'.$check->image));
                }
                $imgname = time().$uuid.'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/categories/');
                request()->image->move($destinationPath, $imgname);
                //$img = Image::make($image->getRealPath());
                //$img->resize(26, 27);
                //$img->save($destinationPath.'/'.$imgname);
                $check->image = $imgname;   
            }else{
                $check->image = $check->image;
            }
            
            $check->name = $input['name'];
            $check->description = $input['desc'];
            $check->slug = $slug;
            /*$check->icon = $input['icon'];
            $check->image = "";*/
            $check->save();
            toastr()->success(' Categories Updated successfully!');
            return redirect()->route('admin.ads.categories');
        }else{
           
            toastr()->error(' Request Invalid !');
            return back();
        }
    }
    public function subindex($id)
    {   
        $parentdata= DB::table('parent_categories')->where('uuid',$id)->first();
        if(!empty($parentdata)){
            return view('back.ads.sub_categories.categories',compact('id'));
        }else{
            toastr()->warning(' illegal Access !');
            return redirect()->route('admin.ads.categories');
        }
    }
    public function getsubcategories($id){
        $parentuuid=$id;
        $check=Parent_categories::where('uuid',$id)->first();

        $parentid=$check->id;
        $getsubcategories=DB::table('sub_categories')
        ->select('name','slug','description','tag','status','uuid','id')->where('parent_id',$parentid)->orderby('id','desc')->get();
        $final_resp = array();
        $i=0;
        foreach ($getsubcategories as $key => $value) {
            $resp = array();
            $j=0;
             $resp[] = $i+1;
            foreach ($value as $key1 => $value1) {
                if($j==6){
                    $id=$value1;
                }else if($j==5){
                    $uuid=$value1;
                }else if($j==4){
                      if($value1==1)
                      {
                        $resp[] ='Active';
                      }else{
                        $resp[] ='InActive';
                      }  

                }else{
                   $resp[] = $value1; 
                }
                $j++;

            }
            $status =  route('admin.ads.statussubcategories',$id);
            $edit =  route('admin.ads.editsubcategories',[$parentuuid,$uuid]);
            $custom =  route('admin.ads.customfield',$uuid);
            $brandModels =  route('admin.ads.brandModels',$uuid);
            $resp[] = '<center><a style="margin:3px;" href="'.$brandModels.'" class=" btn btn-sm  btn-info "><i class="fa  fa-plus"></i> Add Brand/Models</a><a style="margin:3px;" href="'.$custom.'" class=" btn btn-sm  btn-success "><i class="fa  fa-plus"></i> Custom Field</a><button style="margin:3px;" class=" btn bg-blue"onclick="status('.$id.')"><i class="fa   fa-clone"></i></button><a style="margin:3px;" href="'.$edit.'" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a></center>
                <form action="'.$status.'" method="POST" id="status-cate-'.$id.'">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                </form>';
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
    public function createsubcategories($id)
    {   $parentdata= DB::table('parent_categories')->where('uuid',$id)->first();
        if(!empty($parentdata)){
            return view('back.ads.sub_categories.createcategories',compact('id'));
        }else{
            toastr()->warning(' illegal Access !');
            return redirect()->route('admin.ads.categories');
        }
    }
    public function addsubcategories(Request $request)
    {   
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'name'          =>'required', 
            'desc'          =>'required',
            
        ]);
        //echo"<pre>";print_r($input);die;
        $parentdata= DB::table('parent_categories')->where('uuid',$input['parentid'])->first();
        $slug = Str::slug($input['name'], '-');
        $check=Sub_categories::where('slug',$slug)->first();
        if(!empty($check)){
            toastr()->error(' Sub Categories already exists');
            return back();
        }else{
            
            $create=Sub_categories::Create([
                'parent_id'=>$parentdata->id,
                'name'=>$input['name'],
                'slug'=>$slug,
                'uuid'=>$uuid,
                'tag' => "",
                'description'=>$input['desc'],
                
            ]);
            if(!empty($create)){
                toastr()->success('Sub Categories Created  Successfully');
                return back();
            }else{
                toastr()->error('Categories Created error ');
                return back();
            }

        }
    }
    public function subcategoriesstatus($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= Sub_categories::where('id',$id)->first();
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
    public function subcategoriesdelete($id)
    {   //$input=$request->all();

        //echo"<pre>";print_r($id);die;

        $check= DB::table('sub_categories')->where('id',$id)->first();
        //echo"<pre>";print_r($check);die;
        if(!empty($check)){
            
            $res=Sub_categories::destroy($id);
            toastr()->success(' Categories deleted successfully!');
            return back();
        }
        toastr()->error(' Data not found');
        return back();
    }
    public function subcategoriesedit($parentid,$id)
    {   
        $subdata= DB::table('sub_categories')->where('uuid',$id)->first();
        $parentdata= DB::table('parent_categories')->where('uuid',$parentid)->first();
        $id=$parentid;
        if(empty($subdata) || empty($parentdata)){
            toastr()->warning(' illegal Access !');
            return redirect()->route('admin.ads.categories');
        }else{
            return view('back.ads.sub_categories.editcategories',compact('subdata','parentid'));
        }
    }
    public function subcategoriesupdate(Request $request)
    {   
        $input=$request->all();
        $request->validate([
            'id'=>'required',
            'name'          =>'required', 
            'desc'          =>'required',
            
            
        ]);
        $slug = Str::slug($input['name'], '-');
        $check=Sub_categories::where('uuid',$input['id'])->first();
        if(!empty($check)){
            $check->name = $input['name'];
            $check->description = $input['desc'];
            $check->tag = "";
            $check->slug = $slug;
            $check->save();
            toastr()->success('Sub Categories Updated successfully!');
            return back();
        }else{
           
            toastr()->error(' Request Invalid !');
            return back();
        }
    }
    public function customfield($id)
    {   
        $data= DB::table('sub_categories')->where('uuid',$id)->first();
        if(!empty($data)){
            $parentdata=DB::table('parent_categories')->where('id',$data->parent_id)->first();
            return view('back.ads.customfield.customfield',compact('id','parentdata'));
        }else{
            toastr()->warning(' illegal Access !');
            return redirect()->back();
        }
    }
    public function createfield($id)
    {   
        $parentdata= DB::table('sub_categories')->where('uuid',$id)->first();
        if(!empty($parentdata)){
            $field=Customfield::where('active',1)->where('subcategory',$parentdata->id)->get();
            return view('back.ads.customfield.createcustomfield',compact('id','field'));
        }else{
            toastr()->warning(' illegal Access !');
            return redirect()->back();
        }
    }
    public function getfield($id){
        $field=Sub_categories::where('uuid',$id)->first();
        //echo"<pre>";print_r($field);die;

        $option=DB::table('category_field')
        ->select('field_id','created_by','updated_at','created_at','uuid','id')->where('category_id',$field->id)->orderby('id','desc')->get();
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
                }else if ($j==1) {
                    $resp[] = get_name($value1); 
                }else if($j==0){
                   $resp[] = get_fieldname($value1); 
                }else{
                    $resp[]=$value1;
                }
                $j++;

            }
            $delete =  route('admin.ads.customfield.delete',$id);
            $edit =  route('admin.ads.customfield.edit',[$field->uuid,$uuid]);
           /* $action = '<a style="margin:3px;" href="'.$edit.'" class=" btn btn-sm bg-yellow"><i class="fa  fa-pencil-square-o"></i></a><button style="margin:3px;" class=" btn btn-sm bg-red"onclick="deletecate('.$id.')"><i class="fa fa-times"></i></button>
                ';*/
            $action = '<a style="margin:3px;" href="'.$edit.'" class=" btn btn-sm bg-yellow"><i class="fa  fa-pencil-square-o"></i></a>';
            $form='<form action="'.$delete.'" method="POST" id="del-custom-'.$id.'">
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
    public function addfield(Request $request)
    {   
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'field'          =>'required', 
            'id'             =>'required'
            
        ]);
        //echo"<pre>";print_r($input);die;
        $parentdata= DB::table('sub_categories')->where('uuid',$input['id'])->first();
        
        if(empty($parentdata)){
            toastr()->error('Data not Found');
            return back();
        }else{
            $data= DB::table('category_field')->where('Category_id',$parentdata->id)->where('field_id',$input['field'])->first();
            if(!empty($data)){
                toastr()->error('Already Found');
                return redirect()->route('admin.ads.customfield',$input['id']);
            }
            $create=Category_field::Create([
                'category_id'=>$parentdata->id,
                'field_id'=>$input['field'],
                'created_by'=>Auth::id(),
                'uuid'=>$uuid,
               
                
            ]);
            if(!empty($create)){
                toastr()->success('Mapped Custom Field');
                return redirect()->route('admin.ads.customfield',$input['id']);
            }else{
                toastr()->error('Mapping error ');
                return back();
            }

        }
    }
    public function fielddelete($id)
    {   
        $check= Category_field::where('id',$id)->first();
        if(!empty($check)){
            $res=Category_field::destroy($id);
            toastr()->success('Field Deleted successfully!');
            return back();
        }else{
            toastr()->error(' Data not found');
            return back();
        }

    }
    public function fieldedit($Field,$id)
    {   $fieldid=$Field;
        $data= DB::table('category_field')->where('uuid',$id)->first();
        if(empty($data)){
            toastr()->warning(' illegal Access !');
            return back();
        }else{
            $field=Customfield::where('active',1)->get();
            return view('back.ads.customfield.editcustomfield',compact('id','field','fieldid','data'));
        }
    }
    public function fieldupdate(Request $request)
    {
        $input=$request->all();
        $uuid = Uuid::generate(4);
        $request->validate([
            'field'          =>'required', 
            'id'             =>'required',
            'pid'             =>'required'
        ]);
        //echo"<pre>";print_r($input);die;
        $field=Category_field::where('uuid',$input['id'])->first();
        $check=Sub_categories::where('uuid',$input['pid'])->first();
        if (empty($field) || empty($check) ) {
            toastr()->error('No data Found!');
            return back();
        } 
        if($field->category_id == $check->id){
            $field->field_id=$input['field'];
            $field->created_by=Auth::id();
            $field->updated_at=Date("Y-m-d H:m:s");
            $field->save();
            toastr()->success('Mapped Record updated Successfully!');
            return redirect()->route('admin.ads.customfield',$input['pid']);
        }else{
            toastr()->warning('Not Matched Records');
            return back();
        }

    }


    public function brandModels($id)
    {   
        //echo "sara".$id;die;
        $sub_categories= DB::table('sub_categories')->where('uuid',$id)->first();
        $brands= DB::table('category_brands')->where('sub_cate_id',$sub_categories->id)->get();
        //echo"<pre>";print_r($sub_categories);

        if(!empty($sub_categories)){
            $parentdata=DB::table('parent_categories')->where('id',$sub_categories->parent_id)->first();
            return view('back.ads.brandModels',compact('id','parentdata','sub_categories','brands'));
        }else{
            toastr()->warning(' illegal Access !');
            return redirect()->back();
        }
    }


    public function brandModelsAdd(Request $request)
    {
        $input=$request->all();
        $request->validate([
            'brandname' =>'required',
        ]);
        if(!empty($input['brandname'])){


            $cateBrands = DB::table('category_brands')->insertGetId([
                'sub_cate_id' => $input['sub_cate_id'],
                'brand' => $input['brandname'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            toastr()->success('Brand added successfully!');
            return redirect('admin/ads/sub_categories/'.$input['sub_cate_uuid'].'/brandModels');
        }else{
            toastr()->warning('Not Matched Records');
            return back();
        }

    }

    
    public function brandModelsAddValues(Request $request)
    {
        
        $input=$request->all();
        //echo"<pre>";print_r($input);die;
        $request->validate([
            'brands' =>'required',
            'modelsname' =>'required',
        ]);
        if(!empty($input['brands'])){
            
            $cateBrandModels = DB::table('category_models')->insertGetId([
                'sub_cate_id' => $input['sub_cate_id'],
                'cate_brand_id' => $input['brands'],
                'model' => $input['modelsname'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            toastr()->success('Brand model added successfully!');
            return redirect('admin/ads/sub_categories/'.$input['sub_cate_uuid'].'/brandModels');
        }else{
            toastr()->warning('Not Matched Records');
            return back();
        }

    }


    public function getsbrandmodels($field)
    {
        
        $category_brands=DB::table('category_brands')
                ->leftJoin('category_models', 'category_brands.id', '=', 'category_models.cate_brand_id')
                ->select('category_brands.sub_cate_id','category_brands.updated_at','category_brands.created_at','category_brands.id as brandId','category_brands.brand','category_models.model')
                ->where('category_brands.sub_cate_id',$field->id)
                ->orderby('category_brands.id','desc');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $category_brands->orWhere(function ($query) use ($searchValue) {
                $query->orWhere("category_brands.brand","like","%".$searchValue."%")
                        ->orWhere("category_models.model","like","%".$searchValue."%");
            });          
        }
        return $category_brands;
    }


    public function getbrandmodels(){

        $id = $_POST['id'];
        $field=Sub_categories::where('uuid',$id)->first();


        $count = $this->getsbrandmodels($field);
        $totalCount = count($count->get());

        $category_brands = $this->getsbrandmodels($field);


        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $category_brands->offset($_REQUEST['start']);
            $category_brands->limit($_REQUEST['length']);

        }
        $option = $category_brands->get();
        
        $datas = array();
        foreach ($option as $brands) {

            /*$action = '<a target="_blank" href='.route("admin.users.view",getUserUuid($user->report_user_id)).' class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square"></i></a>';
            
            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",getUserUuid($user->user_id)).'">'.get_name($user->user_id).' [ Userid : '.$user->user_id.' ]</a>';
            $link1='<a class="text-danger"target="_blank" href="'.route("admin.users.view",getUserUuid($user->report_user_id)).'">'.get_name($user->report_user_id).' [ Userid : '.$user->report_user_id.' ]</a>';*/
            
            $row = array("brand"=>$brands->brand,"model"=>$brands->model,"createdAt"=>$brands->created_at);
            
            $datas[] = $row;
        }

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

}
