<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect,Response;

use App\Payment;
use App\TopAds;
use App\PaidAds;
use App\PlansPurchase;
use App\AdsFeatures;
use App\Setting;
use DB;
use Uuid;
use DateTime;
use Auth;
use App\Premiumadsplan;
use App\Premiumplansdetails;
use App\User;
use App\Freepoints;
use Carbon\Carbon;
use App\FeatureadsLists;
use App\Verification;
use App\Pearls;
use App\TopLists;
use App\Ads;

class RazorpayController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','users'],['except' => ['paidPlanRazorPaySuccess','walletusedwithrzrpayPlatinam']]);
    }
	public function index()
	{
		return view('razorpay');
	}
	public function razorPaySuccess(Request $request){
		//echo '<pre>';print_r( $request->all() );die;
		if(!empty($request->planuuid)){


            $results = $this->get_curl_handle($request->razorpay_payment_id, ($request->totalAmount*100));
            //$ch = $this->get_curl_handle('pay_EMAqMfOGwoAOb6', $request->planuuid);
        	$results_decode = json_decode($results);
            if(!empty($results_decode) && $results_decode->id !=''){
				$plans=TopAds::where('status','1')->where('type',1)->where('uuid',$request->planuuid)->first();
		        if(!empty($plans)){
		        	$details=base64_decode($request->details);
		            $uuid = Uuid::generate(4);
		            $day=base64_decode($request->days);
		            $date = strtotime("$day day", strtotime("now"));
	                $date=date("Y-m-d H:i:s", $date);
		            // $date = new DateTime('now');
		            // $date->modify("$day day"); 
		            // $date = $date->format('Y-m-d H:i:s');
		            //echo "<pre>ee";print_r($date);die;
		            $purchase=PlansPurchase::Create([
					            'uuid' => $uuid,
					            'user_id' => Auth::id(),
					            'feature_plan_id' => $details,
					            'ads_limit' => '1',
					            'type' => '1',
					            'expire_date'=>$date,
					            'payment_method'=>"RazorPay",
					            'payment_id'=>$request->razorpay_payment_id,
					            'payment'=>$request->totalAmount,
					            'available_amt'=>$request->totalAmount,
					            'ads_count'=> '1',
					            ]);

		            if(!empty($purchase)){
		            	$PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
		                $featured=AdsFeatures::find($details);
		                $featured->user_id=Auth::id();
		                $featured->ads_id=null;
		                $featured->expire_date=$date;
		                $featured->save();
		            }
	        		return response()->json(['success'=>true,'message'=>'You Have Purchased Featured Plan'], 200);
		            /*$plantxt='You Have Purchased Featured Plan';
		            toastr()->success($plantxt);
		            return redirect()->route('welcome');*/
		            
		        }
	    	}

		}
		/*$data = [
		       'user_id' => '1',
		       'payment_id' => $request->payment_id,
		       'amount' => $request->amount,
		    ];
		$getId = Payment::insertGetId($data);  */
		$arr = array('msg' => 'Payment successfully credited', 'status' => true);
		//return Response()->json($arr);    
		
        return redirect('razor-thank-you');
	}
	public function thankYou()
	{
    	session()->forget('adsid');
		$plantxt='You Have Purchased Plan!';
		if (session()->has('successtxt')) {
             $plantxt='You Have Purchased Paid Plan And Ads Successfully Posted!';
             session()->forget('successtxt');
        }
        toastr()->success($plantxt);
     	if (session()->has('adsuuid')) {
            $adsuuid =session()->get('adsuuid');
         	session()->forget('adsuuid');
            return redirect('item/'.$adsuuid);
        }

        if (session()->has('adsmovepage')) {
        	$adsid=session()->get('adsmovepage_adsid');
        	session()->forget('adsmovepage_adsid');
        	session()->forget('adsmovepage');
        	return redirect()->route('adsview.getads',getAdsUuid($adsid));
        }else{
        	return redirect()->route('welcome');
        }
        return redirect()->route('welcome');
		//return view('thankyou');
	}

	public function canceled()
	{
		$plantxt='Your payment has been declined by your bank. Please try again or use a different method to complete the payment!';
        toastr()->warning($plantxt);
        if (session()->has('adsid')) {
             return redirect()->route('ads.choosePlanPost');
        }
        return redirect()->route('welcome');
		//return view('thankyou');
	}

    public function get_curl_handle($payment_id, $amount)
    {

        $settings = Setting::first();
        //echo '<pre>';print_r( $settings );die;

        $url = 'https://api.razorpay.com/v1/payments/'.$payment_id.'/capture';
        //$key_id = 'rzp_test_RxvuezbLL1Ve6m';
        //$key_secret = 'F1JDCFdL2mjCaaNBUACL18kG';
        $key_id = $settings->RAZORPAY_KEY;
        $key_secret = $settings->RAZORPAY_SECRET; 
        $fields_string = "amount=$amount";



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id.':'.$key_secret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;

        //echo '<pre>';print_r( $result );die;
        /*//cURL Request
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id.':'.$key_secret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/ca-bundle.crt');*/

    }
    public function paidPlanRazorPaySuccess(Request $request){
		
		if(!empty($request->planuuid)){


            $results = $this->get_curl_handle($request->razorpay_payment_id, ($request->totalAmount*100));
            //$ch = $this->get_curl_handle('pay_EMAqMfOGwoAOb6', $request->planuuid);
        	$results_decode = json_decode($results);
            if(!empty($results_decode) && $results_decode->id !=''){
				$details_id=base64_decode($request->details);
		        	//echo '<pre>';print_r( $details_id );
		        	//echo '<pre>';print_r( $request->planuuid );
				$plans=DB::table('premiumplansdetails')->select('premiumadsplans.id as planid','premiumplansdetails.quantity','premiumplansdetails.validity as plandays')->leftJoin('premiumadsplans', 'premiumplansdetails.plan_id', '=', 'premiumadsplans.id')->where('premiumplansdetails.id',$details_id)->where('premiumadsplans.uuid',$request->planuuid)->first();
		        	//echo '<pre>';print_r( $plans );die;
		        if(!empty($plans)){
		        	//echo '<pre>';print_r( $plans );die;
		        	$adcount=base64_decode($request->adscount);
		            $uuid = Uuid::generate(4);
		            // $date = new DateTime('now');
		            // $date->modify('$plans->plandays day'); 
		            // $date = $date->format('Y-m-d H:i:s');
		            $date = strtotime("$plans->plandays day", strtotime("now"));
	                $date=date("Y-m-d H:i:s", $date);
		            
		            $purchase=PlansPurchase::Create([
		            'uuid' => $uuid,
		            'user_id' => Auth::id(),
		            'plan_id' => $plans->planid,
		            'ads_limit' => $adcount,
		            'type' => '0',
		            'expire_date'=>$date,
		            'ads_count'=> $adcount,
		            'payment_method'=>"RazorPay",
					'payment_id'=>$request->razorpay_payment_id,
					'payment'=>$request->totalAmount,
					'available_amt'=>$request->totalAmount,
		            ]);
		            if(!empty($purchase)){


					    if($request->fullwalletpointused == '1'){
					    	$user = User::find(Auth::id());
				            $userWallet = $user->wallet_point;
				            $user->wallet_point="0";
				            $user->save();

			             	$freepoint= new Freepoints;
					        $freepoint->order_id = generateRandomString();
					        $freepoint->user_id=Auth::id();
					        $freepoint->description="Plan purchased, payment id : ".$request->razorpay_payment_id;
					        $freepoint->point=$userWallet;
					        $freepoint->ads_id=NULL;
					        $freepoint->status=0;
					        $freepoint->used=1;
					        $freepoint->expire_date=null;
					        $freepoint->save();
					    }

		            	session()->forget('successtxt');
		            	
		            	$PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
		            	//if (session()->has('adsid')) {
				            $adsid =session()->get('adsid');
				            $purchasePost=PurchaseToPost($purchase->uuid,$adsid);

				            if($purchasePost==1){
				            	session()->forget('adsid');
				            	session()->put('successtxt','You Have Purchased Paid Plan And Ads Successfully Posted!');
				            	session()->put('adsmovepage','success');
				            	session()->put('adsmovepage_adsid',$adsid);
				            	// return response()->json(['success'=>true,'message'=>'You Have Purchased Paid Plan And Ads Successfully Posted!'], 200);
				            }
		            }
		            return response()->json(['success'=>true,'message'=>'You Have Purchased Paid Plan'], 200);
		        }
	    	}

		}
		
		return $arr = array('msg' => 'Payment successfully credited', 'status' => true);
		//return Response()->json($arr);
        //return redirect('razor-thank-you');
	}

	public function paidPlanRazorPlatinamPaySuccess(Request $request){
		
		if(!empty($request->planuuid)){


            $results = $this->get_curl_handle($request->razorpay_payment_id, ($request->totalAmount*100));
            //$ch = $this->get_curl_handle('pay_EMAqMfOGwoAOb6', $request->planuuid);
        	$results_decode = json_decode($results);
            if(!empty($results_decode) && $results_decode->id !=''){
				$details_id=base64_decode($request->details);
		        	//echo '<pre>';print_r( $details_id );
		        	//echo '<pre>';print_r( $request->planuuid );
				$plans=DB::table('premiumplansdetails')->select('premiumadsplans.id as planid','premiumplansdetails.quantity','premiumplansdetails.validity as plandays')->leftJoin('premiumadsplans', 'premiumplansdetails.plan_id', '=', 'premiumadsplans.id')->where('premiumplansdetails.id',$details_id)->where('premiumadsplans.uuid',$request->planuuid)->first();
		        	//echo '<pre>';print_r( $plans );die;
		        if(!empty($plans)){
		        	//echo '<pre>';print_r( $plans );die;
		        	$adcount=base64_decode($request->adscount);
		            $uuid = Uuid::generate(4);
		            // $date = new DateTime('now');
		            // $date->modify('$plans->plandays day'); 
		            // $date = $date->format('Y-m-d H:i:s');
		            $date = strtotime("$plans->plandays day", strtotime("now"));
	                $date=date("Y-m-d H:i:s", $date);
		            
		            $purchase=PlansPurchase::Create([
		            'uuid' => $uuid,
		            'user_id' => Auth::id(),
		            'plan_id' => $plans->planid,
		            'ads_limit' => $adcount,
		            'type' => '0',
		            'expire_date'=>$date,
		            'ads_count'=> $adcount,
		            'payment_method'=>"RazorPay",
					'payment_id'=>$request->razorpay_payment_id,
					'payment'=>$request->totalAmount,
					'available_amt'=>$request->totalAmount,
		            ]);
		            if(!empty($purchase)){
		            	$PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
			            $purchasePost=PurchaseToPostPlatinam($purchase->uuid,$request->adUuid);				            
		            }
		            return response()->json(['success'=>true,'message'=>'You Have Purchased Paid Plan'], 200);
		        }
	    	}

		}
		
		return $arr = array('msg' => 'Payment successfully credited', 'status' => true);
		//return Response()->json($arr);
        //return redirect('razor-thank-you');
	}

	public function packageRazorPaySuccess(Request $request){
		//echo '<pre>';print_r( $request->all() );die;
		
		if(!empty($request->razorpay_payment_id && !empty($request->totalAmount))){


            $results = $this->get_curl_handle($request->razorpay_payment_id, ($request->totalAmount*100));
            //$ch = $this->get_curl_handle('pay_EMAqMfOGwoAOb6', $request->planuuid);
        	$results_decode = json_decode($results);
            if(!empty($results_decode) && $results_decode->id !=''){
				if(!empty($request->Tproduct_id) && !empty($request->Tplanuuid)){

					$uuid = Uuid::generate(4);
	                $day=base64_decode($request->Tdays);
	                $date = strtotime("$day day", strtotime("now"));
	                $date=date("Y-m-d H:i:s", $date);
	                $purchase=PlansPurchase::Create([
	                    'uuid' => $uuid,
	                    'user_id' => Auth::id(),
	                    'feature_plan_id' => '1',
	                    'ads_limit' => '1',
	                    'type' => '2',
	                    'expire_date'=>$date,
	                    'ads_count'=> '1',
	                    'payment_method'=>"RazorPay",
					    'payment_id'=>$request->razorpay_payment_id,
					    'payment'=>$request->Tamount,
					    'available_amt'=>$request->Tamount,
	                ]);
	                if(!empty($purchase)){
		            	$PurchasedPlanBill=Purchase_Bill_Address($purchase->id);


				        if (session()->has('promotion_auuid')) {

			            	$promotion_auuid =session()->get('promotion_auuid');
				        	$ads=DB::table('ads')->where('seller_id',Auth::id())->where('uuid',$promotion_auuid)->where('status',1)->orderby('id','desc')->first();
					        if(!empty($ads)){
					            $plans=PlansPurchase::where('id',$purchase->id)->where('ads_count','>','0')->whereDate('expire_date','>',date('Y-m-d H:i:s'))->first();
					            if(!empty($plans)){
					                if($plans->type==1){
					                    $featured=AdsFeatures::where('id',$plans->feature_plan_id)->first();
					                    $featured->user_id=Auth::id();
					                    $featured->ads_id=$ads->id;
					                    $featured->expire_date=$plans->expire_date;
					                    $featured->save();
					                    $plans->ads_count=$plans->ads_count-1;
					                    $plans->save();
					                    $featurelist= FeatureadsLists::Create([
					                        'ads_id' => $ads->id,
					                        'user_id' => Auth::id(),
					                        'plan_id' => $plans->id,
					                    ]);
					                    $verify=Verification::where('user_id',Auth::id())->where('verified_id',5)->whereYear('created_at', date("Y"))->whereMonth('created_at', date("m"))->get()->count();
					                    if($verify<3){
					                        addpoint($ads->seller_id,'feature_ads_point',$ads->id);
					                    }


					                    $adsupdate=Ads::where('id',$ads->id)->first();
					                    $adsupdate->ads_expire_date=$plans->expire_date;
					                    $adsupdate->point_expire_date=$plans->expire_date;
					                    $adsupdate->save();


					                }elseif ($plans->type==2) {
					                    $top=TopLists::Create([
					                        'ads_id' => $ads->id,
					                        'user_id' => Auth::id(),
					                        'plan_id' => $plans->id,
					                        'expire_date'=>$plans->expire_date,
					                    ]);
					                    $plans->ads_count=$plans->ads_count-1;
					                    $plans->save();


					                    $adsupdateexpireDate=Ads::where('id',$ads->id)->first();

					                    if(strtotime($adsupdateexpireDate['ads_expire_date']) < strtotime($plans->expire_date)){
					                        $adsupdateexpireDate->ads_expire_date=$plans->expire_date;
					                        $adsupdateexpireDate->point_expire_date=$plans->expire_date;
					                        $adsupdateexpireDate->save();
					                    }

					                }else{
					                    $top=Pearls::Create([
					                        'ads_id' => $ads->id,
					                        'user_id' => Auth::id(),
					                        'category_id' => $ads->categories,
					                        'expire_date'=>$plans->expire_date,
					                    ]);
					                    $plans->ads_count=$plans->ads_count-1;
					                    $plans->save();
					                }
					            }
					        }
				        }
		            }

				}
				
				return response()->json(['success'=>true,'message'=>'You Have Purchased Package Plan'], 200);
		    }
	    }

		$arr = array('msg' => 'Payment successfully credited', 'status' => true);
		//return Response()->json($arr);    
        return redirect('razor-thank-you');
	}

	public function packageRazorPaySuccessWithWallet(Request $request){
		//echo '<pre>';print_r( $request->all() );die;
		
		if(!empty($request->razorpay_payment_id && !empty($request->totalAmount))){


            $results = $this->get_curl_handle($request->razorpay_payment_id, ($request->totalAmount*100));
            //$ch = $this->get_curl_handle('pay_EMAqMfOGwoAOb6', $request->planuuid);
        	$results_decode = json_decode($results);
            if(!empty($results_decode) && $results_decode->id !=''){
				if(!empty($request->Tproduct_id) && !empty($request->Tplanuuid)){

					$uuid = Uuid::generate(4);
	                $day=base64_decode($request->Tdays);
	                $date = strtotime("$day day", strtotime("now"));
	                $date=date("Y-m-d H:i:s", $date);
	                $purchase=PlansPurchase::Create([
	                    'uuid' => $uuid,
	                    'user_id' => Auth::id(),
	                    'feature_plan_id' => '1',
	                    'ads_limit' => '1',
	                    'type' => '2',
	                    'expire_date'=>$date,
	                    'ads_count'=> '1',
	                    'payment_method'=>"RazorPay",
					    'payment_id'=>$request->razorpay_payment_id,
					    'payment'=>$request->Tamount,
					    'available_amt'=>$request->Tamount,
	                ]);
	                if(!empty($purchase)){
		            	$PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
		            }

				}

	            $user = User::find(Auth::id());
	            $userWallet = $user->wallet_point;
	            $user->wallet_point="0";
	            $user->save();

             	$freepoint= new Freepoints;
		        $freepoint->order_id = generateRandomString();
		        $freepoint->user_id=Auth::id();
		        $freepoint->description="Plan purchased, payment id : ".$request->razorpay_payment_id;
		        $freepoint->point=$userWallet;
		        $freepoint->ads_id=NULL;
		        $freepoint->status=0;
		        $freepoint->used=1;
		        $freepoint->expire_date=null;
		        $freepoint->save();
				
				return response()->json(['success'=>true,'message'=>'You Have Purchased Package Plan'], 200);
		    }
	    }

		$arr = array('msg' => 'Payment successfully credited', 'status' => true);
		//return Response()->json($arr);    
        return redirect('razor-thank-you');
	}


	public function walletpointused(Request $request){
		
		if(!empty($request->Tproduct_id) && !empty($request->Tplanuuid)){

			$uuid = Uuid::generate(4);
            $day=base64_decode($request->Tdays);
            $date = strtotime("$day day", strtotime("now"));
            $date=date("Y-m-d H:i:s", $date);
            $uniqidid = "WFP_".uniqid();
            $purchase=PlansPurchase::Create([
                'uuid' => $uuid,
                'user_id' => Auth::id(),
                'feature_plan_id' => '1',
                'ads_limit' => '1',
                'type' => '2',
                'expire_date'=>$date,
                'ads_count'=> '1',
                'payment_method'=>"WalletPoint",
        		'payment_id'=>$uniqidid,
			    'payment'=>$request->Tamount,
			    'available_amt'=>$request->Tamount,
            ]);
            if(!empty($purchase)){
            	$PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
            }

            $user= User::find(Auth::id());
            $user->wallet_point=($user->wallet_point - $request->Tamount);
            $user->save();


	        $freepoint= new Freepoints;
	        $freepoint->order_id = generateRandomString();
	        $freepoint->user_id=Auth::id();
	        $freepoint->description="Plan purchased, payment id : ".$uniqidid;
	        $freepoint->point=$request->Tamount;
	        $freepoint->ads_id=NULL;
	        $freepoint->status=0;
	        $freepoint->used=1;
	        $freepoint->expire_date=null;
	        $freepoint->save();

		}
				
		return response()->json(['success'=>true,'message'=>'You Have Purchased Package Plan'], 200);
	}

	public function getamt(Request $request){
		//echo '<pre>';print_r( $request->all() );die;
		$productId = array();
		if(!empty($request->Tproduct_id)){
			$productId = explode("_",$request->Tproduct_id);

        	$toplisted=TopAds::select($request->Tproduct_id)->where('status',1)->where('type',2)->first();
        	echo $toplisted[$request->Tproduct_id]."@@@".base64_encode($productId[1]);die;

		}
	}

	public function getamtdetails(Request $request){
		//echo '<pre>';print_r( $request->all() );die;


        //$freeplans=Premiumadsplan::where('status',1)->whereIN('id',$plansPreferCategory)->get();
		//echo '<pre>';print_r( base64_decode($request->detailsId) );die;
		if(!empty($request->all())){
			$detailsId = base64_decode($request->detailsId);
	        $plansdetails=Premiumplansdetails::where('id',$detailsId)->first();
			//echo '<pre>';print_r( $plansdetails );die;

			if(!empty($plansdetails)){
	        	echo $plansdetails['price']."@@@".base64_encode($plansdetails['quantity'])."@@@".$request->planuuidid."@@@".$plansdetails['discounts']."@@@".$request->detailsId;die;

			}else{
				echo "0";die;
			}
		}
	}


    public function walletpointusedproductpurchase(Request $request){
		
		if(!empty($request->planuuid)){
				$details_id=base64_decode($request->details);
		        	//echo '<pre>';print_r( $details_id );
		        	//echo '<pre>';print_r( $request->planuuid );
				$plans=DB::table('premiumplansdetails')->select('premiumadsplans.id as planid','premiumplansdetails.quantity','premiumplansdetails.validity as plandays')->leftJoin('premiumadsplans', 'premiumplansdetails.plan_id', '=', 'premiumadsplans.id')->where('premiumplansdetails.id',$details_id)->where('premiumadsplans.uuid',$request->planuuid)->first();
		        	//echo '<pre>';print_r( $plans );die;
		        if(!empty($plans)){
		        	//echo '<pre>';print_r( $plans );die;
		        	$adcount=base64_decode($request->adscount);
		            $uuid = Uuid::generate(4);
		            // $date = new DateTime('now');
		            // $date->modify('$plans->plandays day'); 
		            // $date = $date->format('Y-m-d H:i:s');
		            $date = strtotime("$plans->plandays day", strtotime("now"));
	                $date=date("Y-m-d H:i:s", $date);
		            
            		$uniqidid = "WFP_".uniqid();
		            $purchase=PlansPurchase::Create([
		            'uuid' => $uuid,
		            'user_id' => Auth::id(),
		            'plan_id' => $plans->planid,
		            'ads_limit' => $adcount,
		            'type' => '0',
		            'expire_date'=>$date,
		            'ads_count'=> $adcount,
	                'payment_method'=>"WalletPoint",
	        		'payment_id'=>$uniqidid,
					'payment'=>$request->totalAmount,
					'available_amt'=>$request->totalAmount,
		            ]);
		            if(!empty($purchase)){


			            $user= User::find(Auth::id());
			            $user->wallet_point=($user->wallet_point - $request->totalAmount);
			            $user->save();


				        $freepoint= new Freepoints;
				        $freepoint->order_id = generateRandomString();
				        $freepoint->user_id=Auth::id();
				        $freepoint->description="Plan purchased, payment id : ".$uniqidid;
				        $freepoint->point=$request->totalAmount;
				        $freepoint->ads_id=NULL;
				        $freepoint->status=0;
				        $freepoint->used=1;
				        $freepoint->expire_date=null;
				        $freepoint->save();


		            	session()->forget('successtxt');
		            	
		            	$PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
		            	//if (session()->has('adsid')) {
			            $adsid =session()->get('adsid');
			            $purchasePost=PurchaseToPost($purchase->uuid,$adsid);

			            if($purchasePost==1){
			            	session()->forget('adsid');
			            	session()->put('successtxt','You Have Purchased Paid Plan And Ads Successfully Posted!');
			            	session()->put('adsmovepage','success');
			            	session()->put('adsmovepage_adsid',$adsid);
			            }
		            }
				return $arr = array('msg' => 'Payment successfully credited', 'status' => true);
	    	}

		}
		
		//return Response()->json($arr);
        //return redirect('razor-thank-you');
	}

	public function getamtplatinam(Request $request){
		//echo '<pre>';print_r( $request->all() );
		$productId = array();
		if(!empty($request->planuuid)){
			$productId = explode("_",$request->product_id);
            $toplisted=TopAds::select($request->product_id)->where('status',1)->where('type',1)->where('uuid',$request->planuuid)->first();

        	echo trim($toplisted[$request->product_id])."@@@".base64_encode($productId[1]);die;

		}
	}




	public function walletpointusedforplatinam(Request $request){
		
		if(!empty($request->planuuid)){


			$plans=TopAds::where('status','1')->where('type',1)->where('uuid',$request->planuuid)->first();
			//echo '<pre>';print_r( $plans );die;
	        if(!empty($plans)){
	        	$details=base64_decode($request->details);
	            $uuid = Uuid::generate(4);
	            $day=base64_decode($request->days);
	            $date = strtotime("$day day", strtotime("now"));
                $date=date("Y-m-d H:i:s", $date);
		            
            	$uniqidid = "WFP_".uniqid();
	            $purchase=PlansPurchase::Create([
				            'uuid' => $uuid,
				            'user_id' => Auth::id(),
				            'feature_plan_id' => $details,
				            'ads_limit' => '1',
				            'type' => '1',
				            'expire_date'=>$date,
				            'payment_method'=>"WalletPoint",
				            'payment_id'=>$uniqidid,
				            'payment'=>$request->totalAmount,
				            'available_amt'=>$request->totalAmount,
				            'ads_count'=> '1',
				            ]);

	            if(!empty($purchase)){
	            	$PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
	                $featured=AdsFeatures::find($details);
	                $featured->user_id=Auth::id();
	                $featured->ads_id=null;
	                $featured->expire_date=$date;
	                $featured->save();



		            $user= User::find(Auth::id());
		            $user->wallet_point=($user->wallet_point - $request->totalAmount);
		            $user->save();


			        $freepoint= new Freepoints;
			        $freepoint->order_id = generateRandomString();
			        $freepoint->user_id=Auth::id();
			        $freepoint->description="Plan purchased, payment id : ".$uniqidid;
			        $freepoint->point=$request->totalAmount;
			        $freepoint->ads_id=NULL;
			        $freepoint->status=0;
			        $freepoint->used=1;
			        $freepoint->expire_date=null;
			        $freepoint->save();

	            }
        		return response()->json(['success'=>true,'message'=>'You Have Purchased Featured Plan'], 200);
	            
	        }
	    	

		}
		$arr = array('msg' => 'Payment successfully credited', 'status' => true);
		
        return redirect('razor-thank-you');
	}


	public function walletusedwithrzrpayPlatinam(Request $request){
		//echo '<pre>';print_r( $request->all() );die;
		if(!empty($request->planuuid)){

            $results = $this->get_curl_handle($request->razorpay_payment_id, ($request->totalAmount*100));
            //$ch = $this->get_curl_handle('pay_EMAqMfOGwoAOb6', $request->planuuid);
        	$results_decode = json_decode($results);
            if(!empty($results_decode) && $results_decode->id !=''){
				$plans=TopAds::where('status','1')->where('type',1)->where('uuid',$request->planuuid)->first();
		        if(!empty($plans)){
		        	$details=base64_decode($request->details);
		            $uuid = Uuid::generate(4);
		            $day=base64_decode($request->days);
		            $date = strtotime("$day day", strtotime("now"));
	                $date=date("Y-m-d H:i:s", $date);
		            $purchase=PlansPurchase::Create([
					            'uuid' => $uuid,
					            'user_id' => Auth::id(),
					            'feature_plan_id' => $details,
					            'ads_limit' => '1',
					            'type' => '1',
					            'expire_date'=>$date,
					            'payment_method'=>"RazorPay",
					            'payment_id'=>$request->razorpay_payment_id,
					            'payment'=>$request->totalAmount,
					            'available_amt'=>$request->totalAmount,
					            'ads_count'=> '1',
					            ]);

		            if(!empty($purchase)){
		            	$PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
		                $featured=AdsFeatures::find($details);
		                $featured->user_id=Auth::id();
		                $featured->ads_id=null;
		                $featured->expire_date=$date;
		                $featured->save();



			            $user = User::find(Auth::id());
			            $userWallet = $user->wallet_point;
			            $user->wallet_point="0";
			            $user->save();

		             	$freepoint= new Freepoints;
				        $freepoint->order_id = generateRandomString();
				        $freepoint->user_id=Auth::id();
				        $freepoint->description="Plan purchased, payment id : ".$request->razorpay_payment_id;
				        $freepoint->point=$userWallet;
				        $freepoint->ads_id=NULL;
				        $freepoint->status=0;
				        $freepoint->used=1;
				        $freepoint->expire_date=null;
				        $freepoint->save();

		            }
	        		return response()->json(['success'=>true,'message'=>'You Have Purchased Featured Plan'], 200);
		            
		        }
	    	}

		}
		$arr = array('msg' => 'Payment successfully credited', 'status' => true);
        return redirect('razor-thank-you');
	}

}