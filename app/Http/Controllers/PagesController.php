<?php
 namespace App\Http\Controllers;
 use Illuminate\Http\Request;
 use Validator,Redirect,Response,File;
 use Carbon\Carbon;
 use DB;
 use Auth;
 use App\Parent_categories;
 use App\Sub_categories;
 use App\Notification;
 class PagesController extends Controller
    {
        public function aboutus(Request $request)
        {   
            return view('aboutus');
        }
        public function termscondition(Request $request)
        {
            return view('terms_condition');
        }
        public function notification(Request $request)
        {   
            $notification=Notification::where('user_id',Auth::User()->id)->orderBy('id','desc')->get();
            return view('notification',compact('notification'));
        }

        public function notification_delete ($id){
            $check= DB::table('notification')->where('id',$id)->first();
            //echo "<br><pre>";print_r($check);die;
            if(!empty($check)){
                $notificationId = $check->id;
                $res=notification::destroy($notificationId);
                toastr()->success('Notification deleted successfully!');
                return back();
            }
        }

        public function notification_deleteall(Request $request){
            $input=$request->all();
            //echo"<pre>";print_r($input);die;
            $data = explode(",", $input['deletenotifidata']);
            $notifications = Notification::whereIn('id',$data)->get();
            if(!empty($notifications)){
                foreach ($notifications as $key => $notification) {
                    //echo"<pre>";print_r($notification->id);die;
                    DB::table('notification')->where('id',$notification->id)->delete();
                }
                echo "1";die;
            }else{
                echo "0";die;
            }
        }

        public function sitemap(Request $request)
        {
            $popularcategories =   DB::table('parent_categories')
                        ->select('id','name','image','icon')
                        ->where('status',1)->limit(5)
                        ->get()->toArray();
            $parentcate=Parent_categories::where('status',1)->get();
            $subcate=Sub_categories::where('status',1)->get();
            
            $popularcity = DB::table('ads')
                        ->select('cities', DB::raw('count(*) as total'))
                        ->limit(10)
                        ->where('status',1)
                        ->orderBy('total','desc')
                        ->groupBy('cities')
                        ->get()->toArray();

            $sitemapcity = DB::table('ads')
                        ->leftJoin('parent_categories','ads.categories','=','parent_categories.id')
                        ->leftJoin('sub_categories','ads.sub_categories','=','sub_categories.id')
                        ->select('ads.cities', DB::raw('count(ads.cities) as total'), 'parent_categories.id as parent_id' ,'parent_categories.name as parent_name' )
                        /*->select('ads.cities', DB::raw('count(ads.cities) as total'), 'parent_categories.id as parent_id' ,'parent_categories.name as parent_name' , 'sub_categories.id as sub_id' ,'sub_categories.name as sub_name')*/
                        ->where('ads.status',1)
                        ->groupBy('ads.cities')
                        ->groupBy('parent_categories.id')
                        ->groupBy('sub_categories.id')
                        ->orderBy('ads.cities','asc')
                        ->get()->toArray();
                        $sitemapcityid=array();
                        $sitemapcitycomplete=array();
                        
                        foreach ($sitemapcity as $key => $value) {
                            $sitemapcityid[$value->cities]=$value->cities;
                            
                        }

                        
                        $citylimit=1;
                        foreach ($sitemapcityid as $key2 => $value2) {
                            $sitemap =array();
                            foreach ($sitemapcity as $key1 => $value1) {
                                //echo "<br><pre>";print_r($value1->cities);die;
                                
                                if($value1->cities==$value2){
                                    //array_push($sitemap,$value1);
                                    $sitemap[$value1->parent_id] = $value1;
                                } 
                                //echo "<br><pre>";print_r($sitemap);die;
                            }
                            $sitemapcitycomplete[$value2] = $sitemap;
                            if($citylimit==4){
                                goto terminateLoop; 
                            }
                            $citylimit++;
                        }
                        terminateLoop:

            $sitemapstate = DB::table('ads')
                        ->leftJoin('cities','ads.cities','=','cities.id')
                        ->leftJoin('states','states.id','=','cities.state_id')
                        ->leftJoin('parent_categories','ads.categories','=','parent_categories.id')
                        ->leftJoin('sub_categories','ads.sub_categories','=','sub_categories.id')
                        ->select('cities.id','ads.cities', DB::raw('count(ads.cities) as total') , 'states.name as state_name','states.id as state_id', 'parent_categories.id as parent_id' ,'parent_categories.name as parent_name' , 'sub_categories.id as sub_id' ,'sub_categories.name as sub_name')
                        /*->select('ads.cities', DB::raw('count(ads.cities) as total'), 'parent_categories.id as parent_id' ,'parent_categories.name as parent_name' , 'sub_categories.id as sub_id' ,'sub_categories.name as sub_name')*/
                        ->where('ads.status',1)
                        ->groupBy('ads.cities')
                        ->groupBy('states.id')
                        ->groupBy('parent_categories.id')
                        ->groupBy('sub_categories.id')
                        ->orderBy('ads.cities','asc')
                        ->get()->toArray();
                    //echo "<br><pre>";print_r($sitemapstate);
                        $sitemapstateid=array();
                        $sitemapstatecomplete=array();
                        
                        foreach ($sitemapstate as $key => $value) {
                            $sitemapstateid[$value->state_name]=$value->state_id;
                            
                        }

                        //echo "<br><pre>";print_r($sitemapstateid);die;
                        $statelimit=1;
                        foreach ($sitemapstateid as $key2 => $value2) {
                            $sitemap1 =array();
                            foreach ($sitemapstate as $key1 => $value1) {
                                //echo "<br><pre>";print_r($value1->cities);die;
                                
                                if($value1->state_id==$value2){
                                    //array_push($sitemap,$value1);
                                    $sitemap1[$value1->parent_id] = $value1;
                                } 
                                //echo "<br><pre>";print_r($sitemap);die;
                            }
                            $sitemapstatecomplete[$key2] = $sitemap1;
                            if($statelimit==5){
                                goto terminateLoopState; 
                            }
                            $statelimit++;
                        }
                        terminateLoopState:

            /*echo "<pre>";print_r($sitemapstate);            
            echo "<br><pre>";print_r($sitemapstateid);
            echo "<br><pre>";print_r($sitemapstatecomplete);die;*/
            
            return view('sitemap',compact('popularcategories','parentcate','subcate','popularcity','sitemapcitycomplete','sitemapstatecomplete'));
        }
        public function privacypolicy(Request $request)
        {
            return view('privacy_policy');
        }
        public function howitswork(Request $request)
        {
            return view('how_its_work');
        }
        public function help(Request $request)
        {
            return view('help');
        }
        
        
 }