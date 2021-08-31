<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Redeem;
use DB;
use App\EnquiryMessage;
use App\Enquiry;
use Uuid;

use PaytmWallet;
use App\Setting;
use App\PurchaseBilling;
use Mail;
use App\Mail\ContactReportAdminReply;
use App\Mail\RefundMail;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function reportRedeem(){
    	$sum=0;
    	$redeem=DB::table('redeem')->get();
    	foreach ($redeem as $key => $redeem) {
    		$sum=$sum+$redeem->redeem_amt;
    		
    	}
    	$redeemTotal=number_format((float)$sum, 2, '.', '');
    	//echo "<pre>";print_r($sum);die;
		return view('back.report.redeem',compact('redeemTotal'));
	}
	public function getRedeemdetails($req)
    {   $input = $req;
        $fromdate=$input['fromdate'];
        $todate=$input['todate'];
        if($todate==""){
          $todate=date('m/d/Y');  
        }
        $usersdatas = DB::table('redeem')->select('redeem.*','users.name','users.uuid as useruuid','users.id as userid')->where('users.status',1)->leftjoin('users', function($leftJoin){
            $leftJoin->on('redeem.user_id', '=', 'users.id');
        });

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->Where(function ($query) use ($searchValue) {
                $query->orWhere("users.name","like","%".$searchValue."%");
                $query->orWhere("redeem.paytmOrderId","like","%".$searchValue."%");
                $query->orWhere("redeem.redeem_amt","like","%".$searchValue."%");
                $query->orWhere("redeem.created_at","like","%".$searchValue."%");
                $query->orWhere("users.id","like","%".$searchValue."%");
            });          
        }
        if($fromdate!='' && $todate!=''){
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = date('Y-m-d', strtotime($todate));
            $usersdatas->whereDate('redeem.created_at','>=', $fromdate);
            $usersdatas->whereDate('redeem.created_at','<=', $todate);
        }   
        $usersdatas->orderBy('redeem.id','desc');
        return $usersdatas;
    }
    public function getRedeem(Request $request)
    {   
        $input = $request->all();
    	$count = $this->getRedeemdetails($input);
        $totalCount = count($count->get());

        $getData = $this->getRedeemdetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);
        }
        $data['redeem'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        $i=0;
        $link='';
        foreach ($data['redeem'] as $redeem) {
             $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",$redeem->useruuid).'">'.$redeem->name.' [ Userid : '.$redeem->userid.' ]</a>';
            $row = array("sl"=>++$i,"user"=>$link,"transaction"=>$redeem->paytmOrderId,"amount"=>number_format((float)$redeem->redeem_amt, 2, '.', ''),"createdate"=>$redeem->created_at);
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


    public function refund_plans(Request $request)
    {   
        $input = $request->all();
        //echo "<pre>";print_r($input);die;
        $puuid = $input['planpurchaseUuid'];
        $plans_purchase_details = DB::table('plans_purchase')->where('uuid',$puuid)->first();
        //echo "<pre>";print_r($plans_purchase_details);die;


        if(!empty($plans_purchase_details)){
            if(!empty($input['partial_payment']) && $plans_purchase_details->payment_method == 'PayTM'){
                toastr()->warning('Partial Refund not possible in PayTm');
                return back();
            }
            if($plans_purchase_details->available_amt < $input['refund_amount']){
                toastr()->warning('Amount should be less than available amount');
                return back();

            }

            $refundAmt = 0;
            if(!empty($input['partial_payment'])){
                $refundAmt = $input['refund_amount'];
            }
            $planDelete = 0;
            if(!empty($input['plan_delete'])){
                $planDelete = 1;
            }

            if($plans_purchase_details->payment_method == 'PayTM' || $plans_purchase_details->payment_method == 'PayTm'){
                $returndata = $this->paytmrefund($plans_purchase_details,$refundAmt,$planDelete);
            }else{
                $returndata = $this->razorrefund($plans_purchase_details,$refundAmt,$planDelete);
                if(isset($returndata->error)){
                    toastr()->warning($returndata->error->description);
                    return redirect('admin/report/planpurchase');
                }
            }

            toastr()->success('Refund the amount successfully');
            return redirect('admin/report/planpurchase');
        }

    }

    public function planpurchases(){
        $sum=0;
        //$plans_purchase = DB::table('plans_purchase')->get();
        //echo "<pre>";print_r($plans_purchase);die;
        return view('back.report.planpurchase');
    }
    public function getplanpurchases(Request $request)
    {   
        $input = $request->all();
        $count = $this->getplanpurchasesdetails($input);
        $totalCount = count($count->get());

        $getData = $this->getplanpurchasesdetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);
        }
        $data['plnpurchase'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        $i=0;
        $link='';
        /*'.url('admin/report/purchaserefund').'/'.$plnpurchase->uuid.'*/
        foreach ($data['plnpurchase'] as $plnpurchase) {
            $link='<a class="text-danger"target="_blank" href="'.route("admin.users.view",$plnpurchase->useruuid).'">'.$plnpurchase->name.' [ Userid : '.$plnpurchase->user_id.' ]</a>';
            $refunded_details = '-';
            if($plnpurchase->refund_orderi_d !=''){
                $refund='<center><a class="text-danger" href="javascript:void(0);"  data-toggle="modal"  data-target="#myModal'.$plnpurchase->uuid.'" >Refund</a></center>';
                $refundedyes = 'Yes';
                $refunded_details='<center><a class="text-danger" href="'.url('admin/report/refund_details/').'/'.$plnpurchase->refund_orderi_d.'">View</a></center>';
            }else{
                //onclick="refundalert(\''.$plnpurchase->uuid.'\')"
                $refund='<center><a class="text-danger" href="javascript:void(0);"  data-toggle="modal"  data-target="#myModal'.$plnpurchase->uuid.'" >Refund</a></center>';
                if($plnpurchase->payment_method =="OjaakFree" || $plnpurchase->payment_method =="WalletPoint"){
                    $refund='<center><a class="text-danger" href="javascript:void(0);">-</a></center>';
                }
                $refundedyes = 'No';
            }

            $htmlview = '<div class="modal fade" id="myModal'.$plnpurchase->uuid.'" role="dialog">
                        <div class="modal-dialog">

                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Refund</h4>
                            </div>
                            <div class="modal-body">
                                <form action="'.url("admin/report/refund_plans/").'" method="post">
                                    <input type="hidden" value="'.csrf_token().'" name="_token">
                                    <input type="hidden" value="'.$plnpurchase->uuid.'" name="planpurchaseUuid">
                                    <p>Parchased amount: '.number_format((float)$plnpurchase->available_amt, 2, '.', '').'</p>
                                    <p>Partial Payment <input type="checkbox" id="partial_payment'.$plnpurchase->uuid.'" onclick="partial_payment(\''.$plnpurchase->uuid.'\')" name="partial_payment[]" value="0"></p>
                                    <p>Do you want to delete the plan purchased? <input type="checkbox" class="plan_delete" name="plan_delete[]" id="plan_delete'.$plnpurchase->uuid.'" onclick="plan_delete(\''.$plnpurchase->uuid.'\')" value="0"></p>
                                    <div class="form-group">
                                        <label ><span class="radiotextsty">Amount</span>
                                        <input type="text" name="refund_amount" class="form-control" required value="'.number_format((float)$plnpurchase->available_amt, 2, '.', '').'">
                                    </div>

                                    <input type="submit" class="sub_btn btn btn-success" value="Refund" name="sub_btn">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                            </div>
                          
                        </div>
                    </div>';


             

            $row = array("sl"=>$plnpurchase->id,"user"=>$link,"amount"=>number_format((float)$plnpurchase->payment, 2, '.', ''),"createdate"=>$plnpurchase->created_at,'payment_type'=>$plnpurchase->payment_method,'payment_id'=>$plnpurchase->payment_id,'expire_date'=>$plnpurchase->expire_date,'refund'=>$refund,"refunded"=>$refundedyes,"refunded_details"=>$refunded_details.$htmlview);
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


    public function refund_details($refundid='')
    {
        //echo $refundid;die;
        if(!empty($refundid)){
            $plans_purchase_details = DB::table('plans_purchase')->where('refund_orderi_d',$refundid)->first();
            $plans_purchase_refund_details = DB::table('plans_purchase_refund')->where('plan_purchase_id',$plans_purchase_details->id)->get();
            //$plans_purchase_details = DB::table('plans_purchase')->leftJoin('plans_purchase_refund', 'plans_purchase.id', '=', 'plans_purchase_refund.id')->where('plans_purchase.refund_orderi_d',$refundid)->get();
            //echo '<pre>';print_r( $plans_purchase_refund_details );die;
            if(!empty($plans_purchase_details)){
                
                return view('back.report.refund_details',compact('plans_purchase_details','plans_purchase_refund_details'));
            }else{
                toastr()->success('Refund data not found');
                return redirect('admin/report/planpurchase');
            }
        }else{
            toastr()->success('Refund data not found');
            return redirect('admin/report/planpurchase');
        }
    }


    public function purchaserefund($puuid='')
    {
        //echo $puuid;die;
        $plans_purchase_details = DB::table('plans_purchase')->where('uuid',$puuid)->first();
        // echo '<pre>';print_r( $plans_purchase_details );die;
        if(!empty($plans_purchase_details)){
            if($plans_purchase_details->payment_method == 'PayTM' || $plans_purchase_details->payment_method == 'PayTm'){
                $returndata = $this->paytmrefund($plans_purchase_details);
            }else{
                $returndata = $this->razorrefund($plans_purchase_details);
                //echo "<pre>order";print_r($returndata);die;
            }

            toastr()->success('Refund the amount successfully');
            return redirect('admin/report/planpurchase');
        }
    }



    public function razorrefund($order,$partialAmount,$planDelete)
    {
        if($partialAmount > 0){
            $amount = ($partialAmount*100);
        }else{
            $plans_purchase_details = DB::table('plans_purchase')->where('uuid',$order->uuid)->first();
            $plans_purchase_refund_details = DB::table('plans_purchase_refund')->where('plan_purchase_id',$plans_purchase_details->id)->get()->sum("refund_amount");

            $payment = ($plans_purchase_details->payment - $plans_purchase_refund_details);

            $amount = ($payment*100);
        }

        /*$mail['amount']=$amount;
        $mail['payment_id']="paymentId";
        $mail['refund_id']="asdf";
        $mail['status']="done";
        $trackmail = Mail::to("saravana235@mailinator.com")->send(new RefundMail($mail)); 
        echo "<pre>order";print_r($trackmail);die;*/

        //echo "<pre>order";print_r($amount);die;
        //$amount = 200;
        $settings = Setting::first();
        //echo '<pre>';print_r( $settings );die;

        //$url = 'https://api.razorpay.com/v1/payments/pay_Ea3oTSuZdavRG3/refund';
        $url = 'https://api.razorpay.com/v1/payments/'.$order->payment_id.'/refund';
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
        $results_decode = json_decode($result);
        if(!empty($results_decode) && !isset($results_decode->error)){
            //echo $results_decode->id;

            $plans_purchase_details = DB::table('plans_purchase')->where('uuid',$order->uuid)->first();
            //echo "<pre>order";print_r($plans_purchase_details);die;
            $available_amt = ($plans_purchase_details->available_amt - ($amount/100));
            
            
            $uuid=Uuid::generate(4); 
            $update = DB::table('plans_purchase_refund')->insert(['user_id' => $plans_purchase_details->user_id,'uuid' =>$uuid,'plan_purchase_id' => $plans_purchase_details->id,'payment_id'=>$plans_purchase_details->payment_id,'refund_id'=>$results_decode->id,'refund_amount'=>($amount/100),'created_at'=>date('Y-m-d H:i:s'),'modified_at'=>date('Y-m-d H:i:s')]);
            DB::table('plans_purchase')->where('uuid',$order->uuid)->update(['available_amt'=>$available_amt,'refund_orderi_d'=>$results_decode->id]);

            if($planDelete == 1){
                DB::table('plans_purchase')->where('uuid',$order->uuid)->where('type',$order->type)->update(['ads_limit'=>0,'ads_count'=>0,'refund_orderi_d'=>$results_decode->id,'refund_order_status'=>$results_decode->status,'expire_date'=>null]);

                if($order->type == '1'){
                    DB::table('ads_features')->where('id',$plans_purchase_details->feature_plan_id)->update(['ads_id'=>null,'expire_date'=>null,'user_id'=>null]);
                }
                if($order->type == '2'){
                    DB::table('toplists_ads')->where('plan_id',$plans_purchase_details->id)->delete();
                }
            }

            $user_details = DB::table('users')->where('id',$plans_purchase_details->user_id)->first();
            //echo "<pre>order";print_r($plans_purchase_details);die;

            if(!empty($user_details)){
                //$mail['amount']=$plans_purchase_details->payment;
                $mail['amount']=($amount/100);
                $mail['payment_id']=$plans_purchase_details->payment_id;
                $mail['refund_id']=$results_decode->id;
                $mail['status']=$results_decode->status;
                $trackmail = Mail::to($user_details->email)->send(new RefundMail($mail)); 
            } 

        //Refund Mail

        }
        return $results_decode;

    }
    /*public function razorrefund($order){
        echo "<pre>order";print_r($order);die;
    }*/
    public function paytmrefund($order,$partialAmount=0,$planDelete=0){
        //echo "<pre>order";print_r($order);
        $refund = PaytmWallet::with('refund');
        $refund->prepare([
            'order' => $order->order_id,
            'reference' => "refund-order-".$order->order_id, // provide refund reference for your future reference (should be unique for each order)
            'amount' => $order->payment, // refund amount 
            'transaction' => $order->payment_id, // provide paytm transaction id referring to this order 
        ]);
        $refund->initiate();
        $response = $refund->response(); // To get raw response as array
        //echo "<pre>order";print_r($response);die;

        $plans_purchase_details = DB::table('plans_purchase')->where('uuid',$order->uuid)->first();
            //echo "<pre>order";print_r($plans_purchase_details);die;
            $available_amt = ($plans_purchase_details->available_amt - $response['REFUNDAMOUNT']);
            
            
            $uuid=Uuid::generate(4); 
            $update = DB::table('plans_purchase_refund')->insert(['user_id' => $plans_purchase_details->user_id,'uuid' =>$uuid,'plan_purchase_id' => $plans_purchase_details->id,'payment_id'=>$plans_purchase_details->payment_id,'refund_id'=>$response['REFUNDID'],'refund_amount'=>$response['REFUNDAMOUNT'],'created_at'=>date('Y-m-d H:i:s'),'modified_at'=>date('Y-m-d H:i:s')]);
            DB::table('plans_purchase')->where('uuid',$order->uuid)->update(['available_amt'=>$available_amt,'refund_orderi_d'=>$response['REFUNDID']]);

            if($planDelete == 1){
                
                DB::table('plans_purchase')->where('uuid',$order->uuid)->where('type',$order->type)->update(['ads_limit'=>0,'ads_count'=>0,'refund_orderi_d'=>$response['REFUNDID'],'refund_order_status'=>$response['STATUS'],'expire_date'=>null]);


                if($order->type == '1'){
                    DB::table('ads_features')->where('id',$plans_purchase_details->feature_plan_id)->update(['ads_id'=>null,'expire_date'=>null,'user_id'=>null]);
                }
                if($order->type == '2'){
                    DB::table('toplists_ads')->where('plan_id',$plans_purchase_details->id)->delete();
                }
            }

        /*$user_details = DB::table('users')->where('id',$plans_purchase_details->user_id)->first();
        //echo "<pre>order";print_r($plans_purchase_details);die;

        if(!empty($user_details)){
            $mail['amount']=$plans_purchase_details->payment;
            $mail['payment_id']=$plans_purchase_details->payment_id;
            $mail['refund_id']=$plans_purchase_details->refund_orderi_d;
            $mail['status']=$plans_purchase_details->refund_order_status;
            $trackmail = Mail::to($user_details->email)->send(new RefundMail($mail)); 
        } */


        //echo "<pre>response";print_r($response);die;
        //$this->refundstatuschk($order,'qwer2134');

        if($refund->isSuccessful()){
            //echo "success";
            //Refund Successful
            $this->refundstatuschk($order,$response['REFUNDID'],$planDelete);
        }else if($refund->isFailed()){
            //echo "Failed";
            //$this->refund($order);
        }else if($refund->isOpen()){
            //echo "Processing";
            $this->refundstatuschk($order,$response['REFUNDID'],$planDelete);
        }else if($refund->isPending()){
            //echo "Pending";
            $this->refundstatuschk($order,$response['REFUNDID'],$planDelete);
        }
    }

    public function refundstatuschk($order,$refundId,$planDelete=0){
        $refundStatus = PaytmWallet::with('refund_status');
        $refundStatus->prepare([
            'order' => $order->order_id,
            //'reference' => "refund-order-158641925189643",
            'reference' => "refund-order-".$order->order_id, 
            // provide reference number (the same which you have entered for initiating refund)
        ]);
        $refundStatus->check();
        
        $response = $refundStatus->response(); // To get raw response as array
        //echo "<pre>order";print_r($response);die;



        $plans_purchase_details = DB::table('plans_purchase')->where('uuid',$order->uuid)->first();
        
        DB::table('plans_purchase')->where('uuid',$order->uuid)->where('type',$order->type)->update(['refund_order_status'=>$response['STATUS']]);


            
        $user_details = DB::table('users')->where('id',$plans_purchase_details->user_id)->first();
        //echo "<pre>order";print_r($plans_purchase_details);die;

        if(!empty($user_details)){
            $mail['amount']=$plans_purchase_details->payment;
            $mail['payment_id']=$plans_purchase_details->payment_id;
            $mail['refund_id']=$plans_purchase_details->refund_orderi_d;
            $mail['status']=$plans_purchase_details->refund_order_status;
            $trackmail = Mail::to('saravana235@mailinator.com')->send(new RefundMail($mail)); 
        } 

        //Refund Mail

        return true;
    }


    public function getplanpurchasesdetails($req)
    {   
        $input = $req;
        $fromdate=$input['fromdate'];
        $todate=$input['todate'];
        $usersdatas = DB::table('plans_purchase')->select('plans_purchase.*','users.name','users.uuid as useruuid')
        ->where('users.status',1)
        ->leftjoin('users', function($leftJoin){
            $leftJoin->on('plans_purchase.user_id', '=', 'users.id');
        });

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->Where(function ($query) use ($searchValue) {
                $query->orWhere("users.name","like","%".$searchValue."%");
                $query->orWhere("users.id","like","%".$searchValue."%");
                $query->orWhere("plans_purchase.payment_method","like","%".$searchValue."%");
                $query->orWhere("plans_purchase.payment_id","like","%".$searchValue."%");
                $query->orWhere("plans_purchase.created_at","like","%".$searchValue."%");
                //$orderid=str_replace("OPID","",$searchValue);
                //$query->orWhere("plans_purchase.id","like","%".$orderid."%");
                $query->orWhere("plans_purchase.id","like","%".$searchValue."%");
            });          
        }
        if($fromdate!='' && $todate!=''){
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = date('Y-m-d', strtotime($todate));
            $usersdatas->whereDate('plans_purchase.created_at','>=', $fromdate);
            $usersdatas->whereDate('plans_purchase.created_at','<=', $todate);
        }   
        $usersdatas->orderBy('plans_purchase.id','desc');
        return $usersdatas;
    }


    public function reportContactus(){
       return view('back.report.contactus');
    }
    public function getContactusdetails($req)
    {   $input = $req;
        $fromdate=$input['fromdate'];
        $todate=$input['todate'];
        $usersdatas = DB::table('enquiry')->select('enquiry.*');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->Where(function ($query) use ($searchValue) {
                $query->orWhere("enquiry.tickectid","like","%".$searchValue."%");
                $query->orWhere("enquiry.created_at","like","%".$searchValue."%");
                $query->orWhere("enquiry.name","like","%".$searchValue."%");
                $query->orWhere("enquiry.mail_id","like","%".$searchValue."%");
                $query->orWhere("enquiry.mobileno","like","%".$searchValue."%");
                $query->orWhere("enquiry.ad_id","like","%".$searchValue."%");
                $query->orWhere("enquiry.help","like","%".$searchValue."%");
                $query->orWhere("enquiry.description","like","%".$searchValue."%");
            });          
        }
        if($fromdate!='' && $todate!=''){
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = date('Y-m-d', strtotime($todate));
            $usersdatas->whereDate('enquiry.created_at','>=', $fromdate);
            $usersdatas->whereDate('enquiry.created_at','<=', $todate);
        } 
        $usersdatas->whereIn('enquiry.status',array("0"));
        //$usersdatas->orderBy('enquiry.id','desc');
        //$usersdatas->groupBy('enquiry.status');
        $usersdatas->orderBy('enquiry.status','asc');
        return $usersdatas;
    }

    public function getContactuscompletedetails($req)
    {   $input = $req;
        $fromdate=$input['fromdate'];
        $todate=$input['todate'];
        $usersdatas = DB::table('enquiry')->select('enquiry.*');

        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] !=''){
            $searchValue = $_REQUEST['search']['value'];
            $usersdatas->Where(function ($query) use ($searchValue) {
                $query->orWhere("enquiry.tickectid","like","%".$searchValue."%");
                $query->orWhere("enquiry.created_at","like","%".$searchValue."%");
                $query->orWhere("enquiry.name","like","%".$searchValue."%");
                $query->orWhere("enquiry.mail_id","like","%".$searchValue."%");
                $query->orWhere("enquiry.mobileno","like","%".$searchValue."%");
                $query->orWhere("enquiry.ad_id","like","%".$searchValue."%");
                $query->orWhere("enquiry.help","like","%".$searchValue."%");
                $query->orWhere("enquiry.description","like","%".$searchValue."%");
            });          
        }
        if($fromdate!='' && $todate!=''){
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = date('Y-m-d', strtotime($todate));
            $usersdatas->whereDate('enquiry.created_at','>=', $fromdate);
            $usersdatas->whereDate('enquiry.created_at','<=', $todate);
        } 
        $usersdatas->where('enquiry.status','1');
        //$usersdatas->orderBy('enquiry.id','desc');
        //$usersdatas->groupBy('enquiry.status');
        $usersdatas->orderBy('enquiry.status','asc');
        return $usersdatas;
    }
    public function getContactus(Request $request)
    {   
        $input = $request->all();
        $count = $this->getContactusdetails($input);
        $totalCount = count($count->get());

        $getData = $this->getContactusdetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);
        }
        $data['contactus'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        $i=0;
        $link='';
    
        foreach ($data['contactus'] as $contact) {
            //echo "<pre>";print_r($contact);die;
            //dd($contact);
            $status = 'Pending';
            if($contact->status == '1'){
                $status = 'Completed';
            }
            $action='<a href="'.route("admin.reportContactus.chat",$contact->tickectid).'" class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square "></i></a>'; 
            $row = array("sl"=>++$i,"name"=>$contact->name,"mailid"=>$contact->mail_id,"Ads_id"=>$contact->ad_id,"help"=>$contact->help,"desc"=>$contact->description,"createdate"=>$contact->created_at,"action"=>$action,"ticketno"=>$contact->tickectid,"status"=>$status);
            $datas[] = $row;
            
        }
        // "mobile"=>$contact->mobileno,
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
    public function getCompleteContactus(Request $request)
    {   
        $input = $request->all();
        $count = $this->getContactuscompletedetails($input);
        $totalCount = count($count->get());

        $getData = $this->getContactuscompletedetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);
        }
        $data['contactus'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        $i=0;
        $link='';
        foreach ($data['contactus'] as $contact) {
            //echo "<pre>";print_r($contact);die;
            //dd($contact);
            $status = 'Pending';
            if($contact->status == '1'){
                $status = 'Completed';
            }
            $action='<a href="'.route("admin.reportContactus.chat",$contact->tickectid).'" class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square "></i></a>'; 
            $row = array("sl"=>++$i,"name"=>$contact->name,"mailid"=>$contact->mail_id,"Ads_id"=>$contact->ad_id,"help"=>$contact->help,"desc"=>$contact->description,"createdate"=>$contact->created_at,"action"=>$action,"ticketno"=>$contact->tickectid,"status"=>$status);
            $datas[] = $row;
            
        }
        // "mobile"=>$contact->mobileno,
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
    public function reportContactusChat($id){
        $check=Enquiry::where('tickectid',$id)->first();
        //echo "<pre>";print_r($check);die;
        if(!empty($check)){
            $message=EnquiryMessage::where('tickectid',$check->tickectid)->orderby('id','desc')->get();
            return view('back.report.contactuschat',compact('message','check'));
        }else{
            return back();
        } 
       //return view('back.report.contactuschat');
    }
    public function reportContactusChatSave(Request $request){
        
        $input = $request->all();
        $id=$input['id'];
        $request->validate([
            'message'      =>'required',
        ]);
        $check=Enquiry::where('tickectid',$input['id'])->first();
        //echo "<pre>";print_r($check);die;

        if(!empty($check)){
            EnquiryMessage::create(['tickectid' => $input['id'],'message' =>$input['message'],'type'=>2]);


            $mail['path']=url("/contact/ticket/").'/'.$id;
            $mail['tickectid']=$id;
            $mail['admin_message']=$input['message'];

            $trackmail = Mail::to($check['mail_id'])->send(new ContactReportAdminReply($mail));   


            toastr()->success('Message Sent!');
            return redirect()->route('admin.reportContactus.chat',$id);
        }else{
            toastr()->warning('Invalid Ticket!');
            return redirect('/');
        }
    }
    public function markascomplete(Request $request){
        
        $input = $request->all();
        $check=Enquiry::where('tickectid',$input['ticketId'])->first();
        //echo "<pre>";print_r($check);die;
        if(!empty($check)){
            Enquiry::where('tickectid',$input['ticketId'])->update(['status' => 1]);
            $mail['path']=url("/contact/ticket/").'/'.$input['ticketId'];
            $mail['tickectid']=$input['ticketId'];
            $mail['admin_message']="Your ticket has been closed / completed";
            $trackmail = Mail::to($check['mail_id'])->send(new ContactReportAdminReply($mail));
            //return redirect()->route('admin.reportContactus.chat',$input['ticketId']);
            echo "1";die;
        }else{
            echo "0";die;
        }
    }
    public function reportBillInfo(){
       return view('back.report.billinfo');
    }
    public function getreportBilldetails($req)
    {   $input = $req;
        $usersdatas = DB::table('purchase_billing')->select('purchase_billing.*','plans_purchase.uuid as planuuid','plans_purchase.payment as planpaymemt','users.name as buyername','users.uuid as buyeruuid','users.email as buyeremail','users.phone_no as buyerphone','users.id as buyerid')->leftJoin('plans_purchase', 'plans_purchase.id', '=', 'purchase_billing.plan_id')->leftJoin('users', 'plans_purchase.user_id', '=', 'users.id')->where('users.status',1);
        if($input['orderid']!="" && $input['orderid']!=null){
            
            $usersdatas->where('purchase_billing.plan_id',$input['orderid']);
        }
        if($input['buyerid']!="" && $input['buyerid']!=null){
            
            $usersdatas->where('users.id',$input['buyerid']);
        }
        $usersdatas->orderBy('plans_purchase.id','desc');
        return $usersdatas;
    }
    public function getreportBillInfo(Request $request)
    {   
        $input = $request->all();
        $count = $this->getreportBilldetails($input);
        $totalCount = count($count->get());

        $getData = $this->getreportBilldetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);
        }
        $data['billinfo'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        $i=0;
        $link='';
        foreach ($data['billinfo'] as $billinfo) {
            $action='<a href="'.route('admin.reportBillInfo.view',$billinfo->planuuid).'" class="btn bg-purple btn-flat margin demo"><i class="fa fa-external-link-square "></i></a>'; 
            $row = array("orderid"=>$billinfo->plan_id,"name"=>$billinfo->buyername,"id"=>$billinfo->buyerid,"email"=>$billinfo->buyeremail,"payment"=>$billinfo->planpaymemt,"phone"=>$billinfo->buyerphone,"action"=>$action);
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
    public function reportBillInfoview($purchaseuuid){
        $billinfo = DB::table('purchase_billing')->select('purchase_billing.*','plans_purchase.uuid as planuuid','plans_purchase.payment as planpaymemt')->leftJoin('plans_purchase', 'plans_purchase.id', '=', 'purchase_billing.plan_id')->where('plans_purchase.uuid',$purchaseuuid)->first();
        $states= DB::table('states')->where('status','1')->get();
        if(!empty($billinfo)){
            //echo "<pre>";print_r($billinfo);die;
           return view('back.report.billinfosave',compact('billinfo','states')); 
        }
        toastr()->warning('Invalid Bill Info!');
        return back();
       
    }
    public function reportBillInfosave(Request $request)
    {   
        $input=$request->all();
        //echo"<pre>";print_r($input);die;
        $this->validate($request, [
                'Emailaddress' => 'required',
                'Customername' => 'required',
                //'Businessname' => 'required',
                //'GstNo' => 'required',
                'Address1' => 'required',
                'Address2' => 'required',
                'State' => 'required',
                'City' => 'required',
             ]);
        $billing = PurchaseBilling::updateOrCreate(
            ['id' =>$input['id']],
            ['id' =>$input['id'],'username' => ucwords($input['Customername']),'email' => ucwords($input['Emailaddress']), 'businessname' => ucwords($input['Businessname']), 'gst' => $input['GstNo'],'gstquestion' => "", 'addr1' => ucwords($input['Address1']), 'addr2' => ucwords($input['Address2']), 'state' => ucwords($input['State']), 'city' => ucwords($input['City'])]
        );
        //echo"<pre>";print_r($billing);die;
        toastr()->success('Billing Information Save Successfully! ');
        return back();
        //return redirct()->route('admin.reportBillInfo.view',$billing->plan_id);
    }
}
