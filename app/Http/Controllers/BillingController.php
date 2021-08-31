<?php
 namespace App\Http\Controllers;
 use Illuminate\Http\Request;
 use Validator,Redirect,Response,File;
 use Carbon\Carbon;
 use DB;
 use Auth;
 use PDF;
 use App\PlansPurchase;
 use App\PurchaseBilling;
 use App\BillingInformation;
 use App\Ads;
class BillingController extends Controller
{
    public function index(Request $request)
    {   $currentdate=date('Y-m-d H:s:i');
        
        $activeplans=PlansPurchase::where('user_id',Auth::user()->id)->orderby('id','desc')->whereDate('expire_date','>=', $currentdate)->get();

        $expiredplans=PlansPurchase::where('user_id',Auth::user()->id)->orderby('id','desc')->whereDate('expire_date','<', $currentdate)->get();
        //echo"<pre>";print_r($expiredplans);die;
        return view('bought_billing.boughtpackage',compact('activeplans','expiredplans'));
    }
    public function invoice(Request $request)
    {	
        return view('bought_billing.invoice');
    }
    public function getinvoicedetails($req)
    {
        $input = $req;
        if(!empty($input)){

            $fromdate=$input['invoice_from_date_pick'];
            $todate=$input['invoice_to_date_pick'];
            if($todate==""){
              $todate=date('Y-m-d');  
            }
        }else{
            $fromdate='';
            $todate='';
            
        }

        //$plans=DB::table('plans_purchase')->where('user_id',Auth::user()->id)->where('payment','!=',0)->where('refund_orderi_d',null)->orderby('plans_purchase.id','desc');
        $plans=DB::table('plans_purchase')->where('user_id',Auth::user()->id)->where('payment','!=',0)->orderby('plans_purchase.id','desc');
        if($fromdate!='' && $todate!=''){
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = date('Y-m-d', strtotime($todate));
            //echo"<pre>";print_r($fromdate);die;
            $plans=$plans->whereDate('created_at','>=', $fromdate);
            $plans=$plans->whereDate('created_at','<=', $todate);
        }
           
        return $plans;
    }
    public function invoicedetails(Request $request)
    {   $input=$request->all();
        $count = $this->getinvoicedetails($input);
        $totalCount = count($count->get());

        $getData = $this->getinvoicedetails($input);
        if(isset($_REQUEST['length']) && $_REQUEST['length'] != -1){
            $getData->offset($_REQUEST['start']);
            $getData->limit($_REQUEST['length']);
        }
        $data['invoicedetails'] = $getData->get();
        
        //echo "<pre>";print_r($data);die;
        $datas = array();
        $row = array();
        foreach ($data['invoicedetails'] as $plan) {
            $action='<div class="action_btn_wrap">
                        <a target="_blank"  href="'.route('ads.invoicePrintPDF',[$plan->uuid,'preview']).'">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <a href="'.route('ads.invoicePrintPDF',[$plan->uuid,'download']).'">
                            <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>
                        </a>
                    </div>';
            $refundAction = "-";
            if($plan->refund_orderi_d !=''){
                $refundAction='<div class="action_btn_wrap">
                        <a target="_blank"  href="'.route('ads.refundPrintPDF',[$plan->uuid,'preview']).'">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <a href="'.route('ads.refundPrintPDF',[$plan->uuid,'download']).'">
                            <i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>
                        </a>
                    </div>';
            }
            $row = array("orderid"=>"#OPID".$plan->id,"amount"=>$plan->payment,"refund"=>$refundAction,"date"=>date("d-M-Y",strtotime($plan->created_at)),"action"=>$action);
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
    public function billing(Request $request)
    {   
        $billinfo = BillingInformation::updateOrCreate(
            ['user_id' =>Auth::user()->id],
            ['user_id' =>Auth::user()->id,]
        );
        $states= DB::table('states')->where('status','1')->get();
        return view('bought_billing.billing',compact('billinfo','states'));
    }
    public function billingUpdate(Request $request)
    {   
        $input=$request->all();
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
        $billing = BillingInformation::updateOrCreate(
            ['user_id' =>Auth::user()->id],
            ['user_id' =>Auth::user()->id,'username' => ucwords($input['Customername']),'email' => ucwords($input['Emailaddress']), 'businessname' => ucwords($input['Businessname']), 'gst' => $input['GstNo'],'gstquestion' => "", 'addr1' => ucwords($input['Address1']), 'addr2' => ucwords($input['Address2']), 'state' => ucwords($input['State']), 'city' => ucwords($input['City'])]
        );
        
        toastr()->success('Billing Information Save Successfully! ');
        if (session()->has('adsid')) {
            $adsid =session()->get('adsid');
            toastr()->success('Select plan to Purchase! ');
            return redirect()->route('plans');
            //toastr()->success('Proceed to Posting! ');
           // return redirect()->route('ads.prevent');
            
        }
        return redirect()->route('ads.billing');
    }

    public function refundPrintPDF($id,$option)
    {
        $purchase=PlansPurchase::where('user_id',Auth::user()->id)->where('uuid',$id)->first();
        //echo"<pre>";print_r($purchase);die;
        if(!empty($purchase)){
            $bill=PurchaseBilling::where('plan_id',$purchase->id)->first();
            if(!empty($bill)){
                $plans_purchase_refund_details = DB::table('plans_purchase_refund')->where('plan_purchase_id',$purchase->id)->get();
                // echo"<pre>";print_r($plans_purchase_refund_details);die;
                $refund_amount = 0;
                $refundedDate = 0;
                if(!empty($plans_purchase_refund_details)){
                    foreach ($plans_purchase_refund_details as $key => $pprd) {
                        $refund_amount += $pprd->refund_amount;
                        $refundedDate = $pprd->created_at;
                    }
                }

                if($purchase->type==0){
                    if($purchase->plan_id==1){
                        $Information="Free Posting Plan";
                    }else {
                        $Information="Premium Ads Plan";
                    }
                }else if($purchase->type==1){
                    $Information="Platimum Ads Plan";
                }else {
                    $Information="Featured Ads Plan";
                }
                $purpayment=number_format((float)$refund_amount, 2, '.', '');
                //$purpayment=$purchase->payment;
                $gstAmountValue = ((9/100)*$refund_amount);
                $gstAmountValueTotal = ((18/100)*$refund_amount);
                $paymentGSTdeduct = ($refund_amount - $gstAmountValueTotal);
                //echo"<pre>";print_r($payment);die;
                $data = [
                'invoiceidno' => $purchase->id,
                'invoiceid' => "OPID".$purchase->id,
                'created_at' => $purchase->created_at,
                'expire' => $purchase->expire_date,
                'payment' => $refund_amount,
                'businessname' => $bill->businessname,
                'ads_limit' => $purchase->ads_limit,
                'addr1' => $bill->addr1,
                'addr2' => $bill->addr2,
                'gst' => $bill->gst,
                'state' => $bill->state,  
                'city' => $bill->city,  
                'information' => $Information,
                'refund_information' => "Refunded",
                'customername' => $bill->username, 
                'email' => $bill->email, 
                'totalinvoiceamut' =>  getIndianCurrency($purpayment),
                'refundOrderId' => $purchase->refund_orderi_d,
                'refundedAt' => $refundedDate,
                'refund_amount' => $refund_amount,
                'gstAmountValue' =>$gstAmountValue,
                'paymentGSTdeduct' =>$paymentGSTdeduct
            ];
            if($option=='download'){// 1=download,2=preview
                $pdf = PDF::loadView('bought_billing.refundPdf', $data);
                $pdf->setPaper('A4', 'landscape'); // portrait,landscape
                $pdfname='Invoice_'."OPID_".$purchase->id.'.pdf'; 
               /* $pdfname='invoice_'.str_pad($purchase->id, 7, "0", STR_PAD_LEFT).'.pdf';*/ 
                return $pdf->download($pdfname);
            }
            if($option=='preview'){// 1=download,2=preview
                $pdf = PDF::loadView('bought_billing.refundPdf', $data);
                $pdf->setPaper('A4', 'landscape'); // portrait,landscape
                $pdfname='Invoice_'."OPID_".$purchase->id.'.pdf'; 
                //$pdfname='invoice_'.str_pad($purchase->id, 7, "0", STR_PAD_LEFT).'.pdf'; 
                return $pdf->stream('invoice.pdf');
            }
            return back();
        }else{
            toastr()->warning('Invaild Purchase Invoice !');
            //return redirect()->route('ads.billing');
            return back();
        }
            
        }else{
            toastr()->warning('Invaild Invoice !');
            return redirect()->route('ads.invoice');
        }
       
    }

    public function invoicePrintPDF($id,$option)
    {
        $purchase=PlansPurchase::where('user_id',Auth::user()->id)->where('uuid',$id)->first();
        if(!empty($purchase)){
            $bill=PurchaseBilling::where('plan_id',$purchase->id)->first();
            if(!empty($bill)){
                //$plans_purchase_refund_details = DB::table('plans_purchase_refund')->where('plan_purchase_id',$purchase->id)->get();
                // echo"<pre>";print_r($plans_purchase_refund_details);die;
                /*$refund_amount = 0;
                $refundedDate = 0;
                if(!empty($plans_purchase_refund_details)){
                    foreach ($plans_purchase_refund_details as $key => $pprd) {
                        $refund_amount += $pprd->refund_amount;
                        $refundedDate = $pprd->created_at;
                    }
                }*/

                if($purchase->type==0){
                    if($purchase->plan_id==1){
                        $Information="Free Posting Plan";
                    }else {
                        $Information="Premium Ads Plan";
                    }
                }else if($purchase->type==1){
                    $Information="Platimum Ads Plan";
                }else {
                    $Information="Featured Ads Plan";
                }
                $purpayment=number_format((float)$purchase->payment, 2, '.', '');
                //$purpayment=$purchase->payment;
                $gstAmountValue = ((9/100)*$purchase->payment);
                $gstAmountValueTotal = ((18/100)*$purchase->payment);
                $paymentGSTdeduct = ($purchase->payment - $gstAmountValueTotal);
                //echo"<pre>";print_r($payment);die;
                $data = [
                'invoiceid' => "OPID".$purchase->id,
                'created_at' => $purchase->created_at,
                'expire' => $purchase->expire_date,
                'payment' => $purchase->payment,
                'businessname' => $bill->businessname,
                'ads_limit' => $purchase->ads_limit,
                'addr1' => $bill->addr1,
                'addr2' => $bill->addr2,
                'gst' => $bill->gst,
                'state' => $bill->state,  
                'city' => $bill->city,  
                'information' => $Information,
                'customername' => $bill->username, 
                'email' => $bill->email, 
                'totalinvoiceamut' =>  getIndianCurrency($purpayment),
                //'refundOrderId' => $purchase->refund_orderi_d,
                //'refundedAt' => $refundedDate,
                //'refund_amount' => $refund_amount,
                'gstAmountValue' =>$gstAmountValue,
                'paymentGSTdeduct' =>$paymentGSTdeduct
            ];
            if($option=='download'){// 1=download,2=preview
                $pdf = PDF::loadView('bought_billing.invoicePdf', $data);
                $pdf->setPaper('A4', 'landscape'); // portrait,landscape
                $pdfname='Invoice_'."OPID_".$purchase->id.'.pdf'; 
               /* $pdfname='invoice_'.str_pad($purchase->id, 7, "0", STR_PAD_LEFT).'.pdf';*/ 
                return $pdf->download($pdfname);
            }
            if($option=='preview'){// 1=download,2=preview
                $pdf = PDF::loadView('bought_billing.invoicePdf', $data);
                $pdf->setPaper('A4', 'landscape'); // portrait,landscape
                $pdfname='Invoice_'."OPID_".$purchase->id.'.pdf'; 
                //$pdfname='invoice_'.str_pad($purchase->id, 7, "0", STR_PAD_LEFT).'.pdf'; 
                return $pdf->stream('invoice.pdf');
            }
            return back();
        }else{
            toastr()->warning('Invaild Purchase Invoice !');
            //return redirect()->route('ads.billing');
            return back();
        }
            
        }else{
            toastr()->warning('Invaild Invoice !');
            return redirect()->route('ads.invoice');
        }
       
    }
    public function planadslist(Request $request)
    {
        $input=$request->all();
        $details='';
        if($input['representdetails'] == 0){

            $ads = DB::table('ads')->where('seller_id',Auth::id())->where('purchase_id',$input['id'])->where('plan_id',$input['planid'])->get();
            if(!$ads->isEmpty()){ 
                foreach ($ads as $key => $value) {
                   $details.="<li class='list-group-item'><a href='".route('adsview.getads',$value->uuid)."' style='text-decoration:none'>$value->title</a></li>";
                }
            }else{
                $details.="<li class='list-group-item'>No Used Ads</li>";
            } 

        }elseif($input['representdetails'] == 1){

            $ads = DB::table('featureads_list')->where('user_id',Auth::id())->where('plan_id',$input['id'])->get();
            if(!$ads->isEmpty()){ 
                foreach ($ads as $key => $value) {
                   $details.="<li class='list-group-item'><a href='".route('adsview.getads',getAdsUuid($value->ads_id))."' style='text-decoration:none'>".getAdsTitle($value->ads_id)."</a></li>";
                }
            }else{
                $details.="<li class='list-group-item'>No Used Ads</li>";
            }

        }else{
            
            $ads = DB::table('toplists_ads')->where('user_id',Auth::id())->where('plan_id',$input['id'])->get();
            if(!$ads->isEmpty()){ 
                foreach ($ads as $key => $value) {
                   $details.="<li class='list-group-item'><a href='".route('adsview.getads',getAdsUuid($value->ads_id))."' style='text-decoration:none'>".getAdsTitle($value->ads_id)."</a></li>";
                }
            }else{
                $details.="<li class='list-group-item'>No Used Ads</li>";
            } 

        }
        
        echo $details;die;
    }

    public function adminviewplanadslist(Request $request)
    {
        $input=$request->all();
        $details='';
        if($input['representdetails'] == 0){

            $ads = DB::table('ads')->where('seller_id',$input['sellerId'])->where('purchase_id',$input['id'])->where('plan_id',$input['planid'])->get();
            if(!$ads->isEmpty()){ 
                foreach ($ads as $key => $value) {
                   $details.="<li class='list-group-item'><a href='".route('adsview.getads',$value->uuid)."' style='text-decoration:none'>$value->title</a></li>";
                }
            }else{
                $details.="<li class='list-group-item'>No Used Ads</li>";
            } 

        }elseif($input['representdetails'] == 1){

            $ads = DB::table('featureads_list')->where('user_id',$input['sellerId'])->where('plan_id',$input['id'])->get();
            if(!$ads->isEmpty()){ 
                foreach ($ads as $key => $value) {
                   $details.="<li class='list-group-item'><a href='".route('adsview.getads',getAdsUuid($value->ads_id))."' style='text-decoration:none'>".getAdsTitle($value->ads_id)."</a></li>";
                }
            }else{
                $details.="<li class='list-group-item'>No Used Ads</li>";
            }

        }else{
            
            $ads = DB::table('toplists_ads')->where('user_id',$input['sellerId'])->where('plan_id',$input['id'])->get();
            if(!$ads->isEmpty()){ 
                foreach ($ads as $key => $value) {
                   $details.="<li class='list-group-item'><a href='".route('adsview.getads',getAdsUuid($value->ads_id))."' style='text-decoration:none'>".getAdsTitle($value->ads_id)."</a></li>";
                }
            }else{
                $details.="<li class='list-group-item'>No Used Ads</li>";
            } 

        }
        
        echo $details;die;
    }
}