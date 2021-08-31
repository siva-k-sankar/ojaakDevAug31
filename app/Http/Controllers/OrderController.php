<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PaytmWallet;
use App\EventRegistration;

use App\Imports\LocationsImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class OrderController extends Controller
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
        $this->validate($request, [
            'name' => 'required',
            //'mobile_no' => 'required|numeric|digits:10|unique:event_registration,mobile_no',
            'mobile_no' => 'required|numeric',
            'address' => 'required',
        ]);


        $input = $request->all();
        $input['order_id'] = $request->mobile_no.rand(100,100000);
        $input['fee'] = 50;


        EventRegistration::create($input);


        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $input['order_id'],
          'user' => $request->mobile_no,
          'mobile_number' => $input['order_id'],
          'email' => 'saravana235@mailinator.com',
          'amount' => $input['fee'],
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
        $order_id = $transaction->getOrderId();


        if($transaction->isSuccessful()){
          EventRegistration::where('order_id',$order_id)->update(['status'=>2, 'transaction_id'=>$transaction->getTransactionId()]);
          dd('Payment Successfully Paid.');

        }else if($transaction->isFailed()){
          //EventRegistration::where('order_id',$order_id)->update(['status'=>1, 'transaction_id'=>$transaction->getTransactionId()]);
          EventRegistration::where('order_id',$order_id)->update(['status'=>1, 'transaction_id'=>null]);
          dd('Payment Failed.');
        }
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

    /**
    * @return \Illuminate\Support\Collection
    */
    public function importExportView()
    {
       return view('import');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    /*public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }*/
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import() 
    {

        //echo "<pre>";print_r(request()->all());die;
        Excel::import(new LocationsImport,request()->file('file'));
           die;
        return back();
    }

}