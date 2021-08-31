<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PaytmWallet;
//use App\EventRegistration;

use App\TopAds;
use App\PaidAds;
use App\PlansPurchase;
use App\AdsFeatures;
use App\Setting;
use App\User;
use App\Freepoints;

use DB;
use Uuid;
use DateTime;
use Auth;
use Carbon\Carbon;
class PaytmController extends Controller
{
    
    /**
     * Redirect the user to the Payment Gateway.
     *
     * @return Response
     */
    public function register()
    {
        return view('event');
    }


    /**
     * Redirect the user to the Payment Gateway.
     *
     * @return Response
     */
    public function order(Request $request)
    {

        //echo "<pre>ee";print_r($request->all());die;

        $input = $request->all();
        $order_id = time().rand(100,100000);
        $amount = $input['amount'];
        $details=base64_decode($request->details);
        session()->put('planuuid', $request->planuuid);
        session()->put('days', $request->days);
        session()->put('validity', $request->id);
        session()->put('amount', $request->amount);
        session()->put('details', $details);

        //EventRegistration::create($input);


        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $order_id,
          'user' => Auth::user()->id,
          'mobile_number' => Auth::user()->phone_no,
          'email' => Auth::user()->email,
          'amount' => $amount,
          'callback_url' => url('payment/status')
        ]);
        return $payment->receive();

        
    }


    /**
     * Obtain the payment information.
     *
     * @return Object
     */
    public function paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response();
        //echo "<pre>ee";print_r($response);die;


        if($transaction->isSuccessful()){
            //$response = $transaction->response();
            $order_id = $transaction->getOrderId();

            $planuuid = session()->get('planuuid');
            $days = session()->get('days');
            $validity = session()->get('validity');
            $amount = session()->get('amount');
            $details = session()->get('details');
            $plans=TopAds::where('status','1')->where('type',1)->where('uuid',$planuuid)->first();
            //echo "<pre>";print_r($plans);die;
                if(!empty($plans)){


                    $uuid = Uuid::generate(4);
                    $day=base64_decode($days);
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
                                'payment_method'=>"PayTM",
                                'order_id'=>$order_id,
                                'payment_id'=>$transaction->getTransactionId(),
                                'payment'=>$amount,
                                'available_amt'=>$amount,
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
                    //$arr = array('msg' => 'Payment successfully credited', 'status' => true);
                    
                    session()->forget('planuuid');
                    session()->forget('days');
                    session()->forget('validity');
                    session()->forget('amount');
                    return redirect('razor-thank-you');
                    //return response()->json(['success'=>true,'message'=>'You Have Purchased Featured Plan'], 200);
                    /*$plantxt='You Have Purchased Featured Plan';
                    toastr()->success($plantxt);
                    return redirect()->route('welcome');*/
                    
                }


        }else{
            return redirect('payment-canceled');
        }
    }    

    public function walletwithplatinam(Request $request)
    {

        //echo "<pre>ee";print_r($request->all());die;

        $input = $request->all();
        $order_id = time().rand(100,100000);
        $amount = $input['amount'];
        $details=base64_decode($request->details);
        session()->put('planuuid', $request->planuuid);
        session()->put('days', $request->days);
        session()->put('validity', $request->id);
        session()->put('amount', $request->amount);
        session()->put('details', $details);

        //EventRegistration::create($input);


        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $order_id,
          'user' => Auth::user()->id,
          'mobile_number' => Auth::user()->phone_no,
          'email' => Auth::user()->email,
          'amount' => $amount,
          'callback_url' => url('paymentCallbackWalletPlatinam')
        ]);
        return $payment->receive();

        
    }


    /**
     * Obtain the payment information.
     *
     * @return Object
     */
    public function paymentCallbackWalletPlatinam()
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response();
        //echo "<pre>ee";print_r($response);die;


        if($transaction->isSuccessful()){
            //$response = $transaction->response();
            $order_id = $transaction->getOrderId();

            $planuuid = session()->get('planuuid');
            $days = session()->get('days');
            $validity = session()->get('validity');
            $amount = session()->get('amount');
            $details = session()->get('details');
            $plans=TopAds::where('status','1')->where('type',1)->where('uuid',$planuuid)->first();
            //echo "<pre>";print_r($plans);die;
                if(!empty($plans)){


                    $uuid = Uuid::generate(4);
                    $day=base64_decode($days);
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
                                'payment_method'=>"PayTM",
                                'order_id'=>$order_id,
                                'payment_id'=>$transaction->getTransactionId(),
                                'payment'=>$amount,
                                'available_amt'=>$amount,
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
                        $freepoint->description="Plan purchased, payment id : ".$transaction->getTransactionId();
                        $freepoint->point=$userWallet;
                        $freepoint->ads_id=NULL;
                        $freepoint->status=0;
                        $freepoint->used=1;
                        $freepoint->expire_date=null;
                        $freepoint->save();
                    }
                    //$arr = array('msg' => 'Payment successfully credited', 'status' => true);
                    
                    session()->forget('planuuid');
                    session()->forget('days');
                    session()->forget('validity');
                    session()->forget('amount');
                    return redirect('razor-thank-you');
                    //return response()->json(['success'=>true,'message'=>'You Have Purchased Featured Plan'], 200);
                    /*$plantxt='You Have Purchased Featured Plan';
                    toastr()->success($plantxt);
                    return redirect()->route('welcome');*/
                    
                }


        }else{
            return redirect('payment-canceled');
        }
    }    

    public function paytmerrormesg(Request $request)
    {
        echo "<pre>ee";print_r($request->all());die;
    }

    /**
     * Redirect the user to the Payment Gateway.
     *
     * @return Response
     */
    /*public function order()
    {
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $order->id,
          'user' => $user->id,
          'mobile_number' => $user->phonenumber,
          'email' => $user->email,
          'amount' => $order->amount,
          'callback_url' => 'http://example.com/payment/status'
        ]);
        return $payment->receive();
    }*/

    /**
     * Obtain the payment information.
     *
     * @return Object
     */
    /*public function paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');
        
        $response = $transaction->response() // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm
        
        if($transaction->isSuccessful()){
          //Transaction Successful
        }else if($transaction->isFailed()){
          //Transaction Failed
        }else if($transaction->isOpen()){
          //Transaction Open/Processing
        }
        $transaction->getResponseMessage(); //Get Response Message If Available
        //get important parameters via public methods
        $transaction->getOrderId(); // Get order id
        $transaction->getTransactionId(); // Get transaction id
    }  */

    /**
    * Obtain the transaction status/information.
    *
    * @return Object
    */
    /*public function statusCheck(){
        $status = PaytmWallet::with('status');
        $status->prepare(['order' => $order->id]);
        $status->check();
        
        $response = $status->response() // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=txn-status-api-description
        
        if($status->isSuccessful()){
          //Transaction Successful
        }else if($status->isFailed()){
          //Transaction Failed
        }else if($status->isOpen()){
          //Transaction Open/Processing
        }
        $status->getResponseMessage(); //Get Response Message If Available
        //get important parameters via public methods
        $status->getOrderId(); // Get order id
        $status->getTransactionId(); // Get transaction id
    }  */

    /**
    * Initiate refund.
    *
    * @return Object
    */
    /*public function refund(){
        $refundStatus = PaytmWallet::with('refund_status');
        $refundStatus->prepare([
            'order' => $order->id,
            'reference' => "refund-order-4", // provide reference number (the same which you have entered for initiating refund)
        ]);
        $refundStatus->check();
        
        $response = $refundStatus->response() // To get raw response as array
        
        if($refundStatus->isSuccessful()){
          //Refund Successful
        }else if($refundStatus->isFailed()){
          //Refund Failed
        }else if($refundStatus->isOpen()){
          //Refund Open/Processing
        }else if($refundStatus->isPending()){
          //Refund Pending
        }
    }*/



    public function packagesbuypaytm(Request $request){

        $input = $request->all();
        // echo '<pre>';print_r( $payment );die;
        $order_id = time().rand(1000,10000000);
        $amount = $request->totalAmount;

        session()->put('totalAmount', $request->totalAmount);
        session()->put('tproduct_id', $request->tproduct_id);
        session()->put('tdays', $request->tdays);
        session()->put('ttotalAmount', $request->ttotalAmount);
        session()->put('tplanuuid', $request->tplanuuid);

        //EventRegistration::create($input);


        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $order_id,
          'user' => Auth::user()->id,
          'mobile_number' => Auth::user()->phone_no,
          'email' => Auth::user()->email,
          'amount' => $amount,
          'callback_url' => url('packagepaymentsuccess')
        ]);
        return $payment->receive();

    }

    public function packagesbuypaytmCallback()
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response();
        //echo "<pre>ee";print_r($response);die;


        if($transaction->isSuccessful()){
            //$response = $transaction->response();
            $order_id = $transaction->getOrderId();

            $totalAmount = session()->get('totalAmount');

            $tproduct_id = session()->get('tproduct_id');
            $tdays = session()->get('tdays');
            $ttotalAmount = session()->get('ttotalAmount');
            $tplanuuid = session()->get('tplanuuid');
            //$plans=TopAds::where('status','1')->where('type',1)->where('uuid',$planuuid)->first();

                if(!empty($tproduct_id) && !empty($tplanuuid)){
                    //echo "TTTT";die;
                    $uuid = Uuid::generate(4);
                    $day=base64_decode($tdays);
                    $tdate = strtotime("$day day", strtotime("now"));
                    $date=date("Y-m-d H:i:s", $tdate);
                    $purchase1=PlansPurchase::Create([
                                        'uuid' => $uuid,
                                        'user_id' => Auth::id(),
                                        'feature_plan_id' => '1',
                                        'ads_limit' => '1',
                                        'type' => '2',
                                        'expire_date'=>$date,
                                        'ads_count'=> '1',
                                        'payment_method'=>"PayTM",
                                        'order_id'=>$order_id,
                                        'payment_id'=>$transaction->getTransactionId(),
                                        'payment'=>$ttotalAmount,
                                        'available_amt'=>$ttotalAmount,
                                    ]);
                    if(!empty($purchase1)){
                        $PurchasedPlanBill=Purchase_Bill_Address($purchase1->id);
                    }


                }
                

            session()->forget('totalAmount');
            session()->forget('tproduct_id');
            session()->forget('tdays');
            session()->forget('ttotalAmount');
            session()->forget('tplanuuid');

            return redirect('razor-thank-you');


        }else{
            return redirect('payment-canceled');
        }
    }    

    public function adspaytmpayment(Request $request){
        //echo '<pre>';print_r( $request->all() );die;
        $input = $request->all();
        //echo '<pre>';print_r( $input );die;
        $order_id = time().rand(1000,10000000);
        $amount = $request->amount;

        session()->put('amount', $request->amount);
        session()->put('adscount', $request->adscount);
        session()->put('planuuid', $request->planuuid);
        session()->put('details', $request->details);

        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $order_id,
          'user' => Auth::user()->id,
          'mobile_number' => Auth::user()->phone_no,
          'email' => Auth::user()->email,
          'amount' => $amount,
          'callback_url' => url('adpostpaymentsuccess')
        ]);
        return $payment->receive();
    }

    public function adpostpaymentsuccessCallback($value='')
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response();
        //echo "<pre>ee";print_r($response);die;


        if($transaction->isSuccessful()){
            //$response = $transaction->response();
            $order_id = $transaction->getOrderId();

            $planuuid = session()->get('planuuid');
            $adscount = session()->get('adscount');
            $amount = session()->get('amount');
            $details = session()->get('details');
            
            $details_id=base64_decode($details);

            //$plans=PaidAds::where('status','1')->where('uuid',$planuuid)->first();
            $plans=DB::table('premiumplansdetails')->select('premiumadsplans.id as planid','premiumplansdetails.quantity','premiumplansdetails.validity as plandays')->leftJoin('premiumadsplans', 'premiumplansdetails.plan_id', '=', 'premiumadsplans.id')->where('premiumplansdetails.id',$details_id)->where('premiumadsplans.uuid',$planuuid)->first();

            if(!empty($plans)){

                $adcount=base64_decode($adscount);
                $uuid = Uuid::generate(4);
                // $date = new DateTime('now');
                // $date->modify('+90 day'); 
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
                'payment_method'=>"PayTm",
                'order_id'=>$order_id,
                'payment_id'=>$transaction->getTransactionId(),
                'payment'=>$amount,
                'available_amt'=>$amount,
                ]);
                
                if(!empty($purchase)){
                    session()->forget('successtxt');
                    $PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
                    if (session()->has('adsid')) {
                            $adsid =session()->get('adsid');
                            //echo"<pre>";print_r($purchase); die;
                            $purchasePost=PurchaseToPost($purchase->uuid,$adsid);
                            if($purchasePost==1){
                                session()->forget('adsid');
                                session()->put('successtxt','You Have Purchased Paid Plan And Ads Successfully Posted!');
                                session()->put('adsmovepage','success');
                                session()->put('adsmovepage_adsid',$adsid);
                                //return response()->json(['success'=>true,'message'=>'You Have Purchased Paid Plan And Ads Successfully Posted!'], 200);
                            }
                            
                        }
                }

                session()->forget('planuuid');
                session()->forget('adscount');
                session()->forget('amount');
                session()->forget('details');

                return redirect('razor-thank-you');
            }

        }else{
            return redirect('payment-canceled');
        }
    }



    public function adspaytmpaymentforplatinam(Request $request){
        //echo '<pre>';print_r( $request->all() );die;
        $input = $request->all();
        //echo '<pre>';print_r( $input );die;
        $order_id = time().rand(1000,10000000);
        $amount = $request->amount;

        session()->put('amount', $request->amount);
        session()->put('adscount', $request->adscount);
        session()->put('planuuid', $request->planuuid);
        session()->put('details', $request->details);
        session()->put('adUuid', $request->adUuid);

        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $order_id,
          'user' => Auth::user()->id,
          'mobile_number' => Auth::user()->phone_no,
          'email' => Auth::user()->email,
          'amount' => $amount,
          'callback_url' => url('adpostpaymentsuccessPlatinamCallback')
        ]);
        return $payment->receive();
    }

    public function adpostpaymentsuccessPlatinamCallback($value='')
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response();
        //echo "<pre>ee";print_r($response);die;


        if($transaction->isSuccessful()){
            //$response = $transaction->response();
            $order_id = $transaction->getOrderId();

            $planuuid = session()->get('planuuid');
            $adscount = session()->get('adscount');
            $amount = session()->get('amount');
            $details = session()->get('details');
            $adUuid = session()->get('adUuid');
            
            $details_id=base64_decode($details);

            //$plans=PaidAds::where('status','1')->where('uuid',$planuuid)->first();
            $plans=DB::table('premiumplansdetails')->select('premiumadsplans.id as planid','premiumplansdetails.quantity','premiumplansdetails.validity as plandays')->leftJoin('premiumadsplans', 'premiumplansdetails.plan_id', '=', 'premiumadsplans.id')->where('premiumplansdetails.id',$details_id)->where('premiumadsplans.uuid',$planuuid)->first();

            if(!empty($plans)){

                $adcount=base64_decode($adscount);
                $uuid = Uuid::generate(4);
                // $date = new DateTime('now');
                // $date->modify('+90 day'); 
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
                'payment_method'=>"PayTm",
                'order_id'=>$order_id,
                'payment_id'=>$transaction->getTransactionId(),
                'payment'=>$amount,
                'available_amt'=>$amount,
                ]);
                
                if(!empty($purchase)){
                    session()->forget('successtxt');
                    $PurchasedPlanBill=Purchase_Bill_Address($purchase->id);
                    $purchasePost=PurchaseToPostPlatinam($purchase->uuid,$adUuid);
                }

                session()->forget('planuuid');
                session()->forget('adscount');
                session()->forget('amount');
                session()->forget('details');
                session()->forget('adUuid');

                return redirect('razor-thank-you');
            }

        }else{
            return redirect('payment-canceled');
        }
    }

}