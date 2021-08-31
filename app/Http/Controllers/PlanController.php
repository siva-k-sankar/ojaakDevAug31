<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Plan;
use App\PlansLimit;
use App\PaidAds;
use App\PlansPurchase;
use Uuid;
use Auth;
use DB;
use App\TopAds;
use DateTime;
use App\AdsFeatures;
use App\Pearls;
use App\TopLists;
use App\Setting;
use App\Premiumadsplan;
use App\Premiumplansdetails;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'], ['except' => ['featureads']]);
    }
    public function index()
    {   
        if (session()->has('catesid')) {
                $cateid =session()->get('catesid');
                $plansPreferCategory=plancategorychoose($cateid);
        }else {
            toastr()->warning('Error Occoured Try To Reposting ! ');
            return redirect()->route('ads.post');
        }
        // $cateid =13;
        // $plansPreferCategory=plancategorychoose($cateid);
    	$plans=Premiumadsplan::where('status',1)->whereIN('id',$plansPreferCategory)->get();
        $plansdetails=Premiumplansdetails::get();
        //echo"<pre>";print_r($plansdetails); die;
        //$plansdetails1=Premiumplansdetails::get();
        $settings = Setting::first();
    	return view('plan.plan',compact('plans','settings','plansdetails'));
    }
    public function order($planid,$details)
    {
        $setting=Setting::first();
        $bill_info=Billing_Information_Check();
        if($bill_info==0){
            toastr()->warning('Please Fillout All Billing Information!');
            return redirect()->route('ads.billing');
        }
        //echo"<pre>";print_r($bill_info); die;
        //$plans=PaidAds::where('status','1')->where('uuid',$planid)->first();
        $details_id=base64_decode($details);
        $plans=DB::table('premiumplansdetails')->select('premiumadsplans.id as id','premiumadsplans.plan_name as plan_name','premiumadsplans.id as planid','premiumplansdetails.quantity','premiumplansdetails.validity as plandays')->leftJoin('premiumadsplans', 'premiumplansdetails.plan_id', '=', 'premiumadsplans.id')->where('premiumplansdetails.id',$details_id)->where('premiumadsplans.uuid',$planid)->first();
        //echo"<pre>";print_r($plans); die;
        if(!empty($plans)){
            
            $PlansPurchasecheck=PlansPurchase::where('type','0')->where('user_id',Auth::id())->where('ads_count','1')->where('plan_id',$plans->id)->first();
            
            if(!empty($PlansPurchasecheck)){
                toastr()->warning('Already You Have Free Plan');
                return redirect('plans');
            }else{
                if($setting->infinitefreelimit =='0'){
                    $count = PlansPurchase::where('type','0')->where('user_id',Auth::id())->where('plan_id',$plans->id)->count();
                    if($setting->freeadslimit <= $count){
                        toastr()->warning('You Exceed your Free Plan!, Buy New Plans');
                        return redirect('plans');
                        
                    }
                } 
            }
            $uuid = Uuid::generate(4);
            // $date = new DateTime('now');
            // $date->modify('+90 day'); 
            // $date = $date->format('Y-m-d H:i:s');
            $date = strtotime("$plans->plandays day", strtotime("now"));
            $date=date("Y-m-d H:i:s", $date);
            
            $purchase=PlansPurchase::Create([
            'uuid' => $uuid,
            'user_id' => Auth::id(),
            'plan_id' => $plans->id,
            'ads_limit' => $plans->quantity,
            'type' => '0',
            'expire_date'=>$date,
            'payment_method'=>"OjaakFree",
            'payment_id'=>"OFP_".uniqid(),
            'ads_count'=> $plans->quantity,
            ]);
            if(!empty($purchase)){
                $PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
                if (session()->has('adsid')) {
                    $adsid =session()->get('adsid');
                    $purchasePost=PurchaseToPost($purchase->uuid,$adsid);
                    session()->forget('adsid');
                    session()->forget('catesid');
                    if($purchasePost==1){
                        session()->put('successtxt','You Have Purchased Free Plan And Ads Successfully Posted!');
                        // return response()->json(['success'=>true,'message'=>'You Have Purchased Paid Plan And Ads Successfully Posted!'], 200);
                    }

                    if($purchasePost==2){
                        session()->put('successtxt','You can post free ads. But ads will not have OJAAK point And Ads Successfully Posted!');
                        // return response()->json(['success'=>true,'message'=>'You Have Purchased Paid Plan And Ads Successfully Posted!'], 200);
                    }
                    $plantxt='You Have Purchased '.$plans->plan_name;
                    toastr()->success($plantxt);



                    if (session()->has('adsuuid')) {
                        $adsuuid =session()->get('adsuuid');
                        return redirect('item/'.$adsuuid);
                    }else{
                        return redirect('user/ads');
                    }
                    //return redirect('')->route('adsview.getads',getAdsUuid($adsid));
                }

                $plantxt='You Have Purchased '.$plans->plan_name;
                toastr()->success($plantxt);
                /*if (session()->has('adsid')) {
                    return redirect()->route('ads.choosePlanPost');
                }else{
                    session()->forget('adsid');
                    //return redirect()->route('welcome');
                    return redirect()->route('adsview.getads',getAdsUuid($adsid));
                }*/
            }
            return redirect()->route('welcome');
            
        }else{
            return back();
        }
        
    }
    public function featureads($planid,$position)
    {   
        if(Auth::check()){
            $plans=TopAds::where('status',1)->where('type',1)->where('uuid',$planid)->first();
            $postion_id=base64_decode($position);
            //echo "<pre>";print_r($plans);die;
            if($postion_id == 1 || $postion_id == 2 || $postion_id == 3 || $postion_id == 4){
                
                if(!empty($plans)){
                    $PlansPurchasecheck=PlansPurchase::where('type','1')->where('feature_plan_id',$postion_id)->whereDate('expire_date','>',date('Y-m-d H:i:s'))->first();
                    if(!empty($PlansPurchasecheck)){
                        $plantxt='This Featured Plan already Purchased';
                        toastr()->warning($plantxt);
                        return redirect()->route('welcome');
                    }
                    $settings = Setting::first();
                    return view('plan.plantoplist',compact('plans','settings','position'));
                }else{
                    toastr()->error('Plan Not Available');
                    return back();
                }

            }else{
                toastr()->error('Error occur');
                return back();
            }
        
        }else{
                toastr()->error('Login to Continue');
                return back();
        }
        
    }
    public function featureadsorder($planid,$days)
    {   
        $plans=TopAds::where('status','1')->where('type',1)->where('uuid',$planid)->first();
        if(!empty($plans)){
            $PlansPurchasecheck=PlansPurchase::where('type','1')->where('feature_plan_id',$plans->id)->whereDate('expire_date','>',date('Y-m-d H:i:s'))->first();
            if(!empty($PlansPurchasecheck)){
                $plantxt='This Featured Plan already Purchased';
                toastr()->warning($plantxt);
                return redirect()->route('welcome');
            }
            $uuid = Uuid::generate(4);
            //$day=base64_decode($days);
            $day=$days;
            /*if($day =='' || $day !='7' || $day!='15' || $day!='30'){
                $plantxt='You Have Not Purchased Featured Plan';
                toastr()->warning($plantxt);
                return back();
            }*/
            $date = new DateTime('now');
            $date->modify("$day day"); 
            $date = $date->format('Y-m-d H:i:s');
            //echo "<pre>ee";print_r($date);die;
            $purchase=PlansPurchase::Create([
            'uuid' => $uuid,
            'user_id' => Auth::id(),
            'feature_plan_id' => $plans->id,
            'ads_limit' => '1',
            'type' => '1',
            'expire_date'=>$date,
            'ads_count'=> '1',
            ]);
            if(!empty($purchase)){
                
                $PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
                    
                if('Platinum plan 1'==$plans->name){
                    $find=1;
                }else if('Platinum plan 2'==$plans->name){
                    $find=2;
                }else if('Platinum plan 3'==$plans->name){
                    $find=3;
                }else{
                    $find=4;
                }
                $featured=AdsFeatures::find($find);
                $featured->user_id=Auth::id();
                $featured->ads_id=null;
                $featured->expire_date=$date;
                $featured->save();
            }
            $plantxt='You Have  Purchased Featured Plan';
            toastr()->success($plantxt);
            return redirect()->route('welcome');
            
        }else{
            $plantxt='You Have Not Purchased Featured Plan';
            toastr()->warning($plantxt);
            return back();
        }
        
    }
    public function showpackage($auuid='')
    {   
        session()->put('promotion_auuid',"");
        if(!empty($auuid)){
           session()->put('promotion_auuid',$auuid);
        }
        $toplisted=TopAds::where('status',1)->where('type',2)->first();
                //echo "<pre>ee";print_r($featured);die;
        //$pearl=TopAds::where('status',1)->where('type',3)->first();
        if(!empty($toplisted) || !empty($pearl)){
            $settings = Setting::first();
            return view('plan.showpackages',compact('toplisted','settings'));
        }else{
            toastr()->error('Plan Not Available');
            return back();
        }
    }
    public function showpackageOrder(Request $request)
    {   
        $input = $request->all();
        if( $input['toplist']==7 || $input['toplist']==15 || $input['toplist']==30 || $input['toplist']==0 ){
            if($input['toplist']!=0){
                $uuid = Uuid::generate(4);
                $day=$input['toplist'];
                $date = new DateTime('now');
                $date->modify("$day day"); 
                $date = $date->format('Y-m-d H:i:s');
                $purchase1=PlansPurchase::Create([
                    'uuid' => $uuid,
                    'user_id' => Auth::id(),
                    'feature_plan_id' => '5',
                    'ads_limit' => '1',
                    'type' => '2',
                    'expire_date'=>$date,
                    'ads_count'=> '1',
                ]);
                
                if(!empty($purchase1)){
                    $PurchasedPlanBill=Purchase_Bill_Address($purchase1->id);
                }

                $plantxt='You Have  Purchased TopListed Plans';
                toastr()->success($plantxt);
            } 
        }else{
            toastr()->error('Plan Not invaild');
            return back();
        }
        
        if( $input['pearl']==7 || $input['pearl']==15 || $input['pearl']==30 || $input['pearl']==0){
            if($input['pearl']!=0){
                $uuid = Uuid::generate(4);
                $day=$input['pearl'];
                $date = new DateTime('now');
                $date->modify("$day day"); 
                $date = $date->format('Y-m-d H:i:s');
                $purchase2=PlansPurchase::Create([
                    'uuid' => $uuid,
                    'user_id' => Auth::id(),
                    'feature_plan_id' => '6',
                    'ads_limit' => '1',
                    'type' => '3',
                    'expire_date'=>$date,
                    'ads_count'=> '1',
                ]);
                if(!empty($purchase2)){
                    $PurchasedPlanBill=Purchase_Bill_Address($purchase2->id);
                }
                $plantxt='You Have  Purchased Pearl Plans';
                toastr()->success($plantxt);
            } 
        }else{
            toastr()->error('Plan Not invaild');
            return back();
        }

        return redirect()->route('welcome');
    }

    public function checkpanwalletamt()
    { 
        $proofs = DB::table('proofs')
                            ->where('user_id',Auth::user()->id)
                            ->where('proof',3)
                            ->where('verified','1')
                            ->first();

        $reedemableamt = Setting::select('minimum_ojaak_point_use_payment')->first();

        //echo"<pre>";print_r($reedemableamt->minimum_ojaak_point_use_payment); 
        //echo"<pre>";print_r(Auth::user()->wallet_point); die;
        $values = "0";
        if($reedemableamt->minimum_ojaak_point_use_payment > Auth::user()->wallet_point){      
            $values = "2";
        }
        if(!isset($proofs->verified)){        
            $values = "1";
        }
        echo trim($values)."@@@".trim($reedemableamt->minimum_ojaak_point_use_payment);die;
    }
    
}
