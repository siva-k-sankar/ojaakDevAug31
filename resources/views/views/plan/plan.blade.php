@extends('layouts.home')
@section('styles')
<style>
    .card-pricing.basic:hover {
        z-index: 1;
        /*transform: scale(1.1); */
        border: 2px solid #47aa50!important;
        transition-duration: .3s;
    }
    .card-pricing.basic {
        z-index: 1;
        /*transform: scale(1.1); */
        border: 2px solid #47aa50!important;
        transition-duration: .3s;
    }
    .card-pricing.basic span{
        background-color:#47aa50 !important;
    }
    .card-pricing .list-unstyled li {
        padding: .5rem 0;
        color: #6c757d;
    }
</style>
@endsection
@section('content')
<div class="container-fluid pl-0 pr-0 product_view_outer_row_wrap">
    <div class="box-size">
        <div class="row  justify-content-center">
            @if($plans->isEmpty())
                <div class="col-md-12">
                    <div class="card card-pricing  text-center px-3 mb-4">
                        
                        <div class="card-body pt-0">
                            <ul class="list-unstyled mb-4">
                                <li>
                                    <div class="bg-transparent card-header pt-4 border-0">
                                        <h1 class="h1 font-weight-normal text-primary text-center mb-0" data-pricing-value="15">Not Available Plans</h1>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                @foreach($plans as $plan)
                    @if($plan->plan_name!='Free Plan')
                    <div class="col-md-4">
                        <div class="card card-pricing basic text-center px-3 mb-4" style="min-height: 324px;">
                            <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom  text-white shadow-sm">{{ucwords($plan->plan_name)}}</span>
                            <div class="bg-transparent card-header pt-4 border-0">
                             </div>
                            <div class="card-body pt-0 common_btn_wrap common_btn_padding ">
                                @foreach($plansdetails as $plansdetail)
                                    @if($plansdetail->plan_id == $plan->id)
                                        <a href="javascript:void(0);" data-amount="{{$plansdetail->price}}" data-adcount="{{base64_encode($plansdetail->quantity)}}"
                                        data-details="{{base64_encode($plansdetail->id)}}" data-planuuid="{{$plan->uuid}}"class="btn btn-block  text-white mb-3 modalOpen" style="text-align: center !important;">
                                            <h6>Ads Limit : {{$plansdetail->quantity}}</h6>
                                            <h6>Price : &#8377; {{$plansdetail->price}}</h6>
                                            <h6>Validity : {{$plansdetail->validity}} Days</h6>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif
            
        </div>
    </div>
</div>

<form method='post' action='{{url("adspaytmpayment")}}' name='paytm_form'  class="hide">
    @csrf
    <div class="paytmform">
        
    </div>
    <button type="submit" style="display: none"></button>
</form>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="display: block;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pay With</h4>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <input type="button" name="paytm" class="btn btn-primary buy_now_paytm" value="Paytm" style="background: #00BAF2;">
            </div>
            <div class="col-md-6 text-center">
                <input type="button" name="paytm" class="btn btn-primary buy_now" value="Razorpay" style="background: #2783f3;">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


@endsection
@section('scripts')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var SITEURL = '{{url("")}}';
    var BillingInformationReDirect = SITEURL + '/billing';
    


    $('body').on('click', '.modalOpen', function(e){
        var bill_info ="{{Billing_Information_Check()}}";
        if(bill_info==0){
            toastr.warning('Please Fillout All Billing Information!');
            window.location.href = BillingInformationReDirect;
            exit();
        }
        var adminchooseamount = "{{$settings['choosepaymentgateway']}}";

        var paymentgateway = 'razorpay';
        <?php
            if($settings['paymentgateway'] == 'paytm'){
        ?>
            var paymentgateway = 'paytm';
        <?php
            }
        ?>
        //alert(paymentgateway);

        //$('#myModal').modal('show');
        var totalAmount = $(this).attr("data-amount");
        var adscount =  $(this).attr("data-adcount");
        var planuuid =  $(this).attr("data-planuuid");
        var details =  $(this).attr("data-details");


        if(paymentgateway == 'paytm'){

            if(parseInt(totalAmount)>=parseInt(adminchooseamount)){                
                $(".paytmform").append('<input type="hidden" name="amount" value="'+totalAmount+'"/>');
                $(".paytmform").append('<input type="hidden" name="adscount" value="'+adscount+'"/>');
                $(".paytmform").append('<input type="hidden" name="planuuid" value="'+planuuid+'"/>');
                $(".paytmform").append('<input type="hidden" name="details" value="'+details+'"/>');
                document.paytm_form.submit();
            }else{
                $(".buy_now").attr("data-amount",totalAmount);
                $(".buy_now").attr("data-adcount",adscount);
                $(".buy_now").attr("data-planuuid",planuuid);
                $(".buy_now").attr("data-details",details);
                $( ".buy_now" ).trigger( "click" );
            }

        }else{

            if(parseInt(totalAmount)>=parseInt(adminchooseamount)){  
                $(".buy_now").attr("data-amount",totalAmount);
                $(".buy_now").attr("data-adcount",adscount);
                $(".buy_now").attr("data-planuuid",planuuid);
                $(".buy_now").attr("data-details",details);
                $( ".buy_now" ).trigger( "click" );
            }else{             
                $(".paytmform").append('<input type="hidden" name="amount" value="'+totalAmount+'"/>');
                $(".paytmform").append('<input type="hidden" name="adscount" value="'+adscount+'"/>');
                $(".paytmform").append('<input type="hidden" name="planuuid" value="'+planuuid+'"/>');
                $(".paytmform").append('<input type="hidden" name="details" value="'+details+'"/>');
                //$( "#foo" ).trigger( "click" );
                document.paytm_form.submit();
            }
        }

    });

    $('body').on('click', '.buy_now', function(e){
        var totalAmount = $(this).attr("data-amount");
        var adscount =  $(this).attr("data-adcount");
        var planuuid =  $(this).attr("data-planuuid");
        var details =  $(this).attr("data-details");
        var options = {
        "key": "{{ $settings['RAZORPAY_KEY'] }}",
        //"key": "rzp_test_RxvuezbLL1Ve6m",
        //"key": "rzp_live_VAEFXPhTYIIqZ0",
        "amount": (totalAmount*100), // 2000 paise = INR 20
        "name": "{{ env('APP_NAME') }}",
        "description": "Payment",
        "image": "{{ asset('public/frontdesign/assets/img/ojaak_logo.png') }}",
        "handler": function (response){
            //console.log(response)
            if(response != null){
                $.ajax({
                   url: SITEURL + '/paidplanpaysuccess',
                   type: 'post',
                   dataType: 'json',
                   data: {
                        razorpay_payment_id: response.razorpay_payment_id , 
                        totalAmount : totalAmount,
                        adscount : adscount,
                        planuuid : planuuid,
                        details : details,
                   },
                   beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                    }, 
                   success: function (msg) {
                       window.location.href = SITEURL + '/razor-thank-you';
                   }
               });
            }
         
        },
        "prefill": {
           "contact": "{{ Auth::user()->phone_no }}",
           "email":   "{{ Auth::user()->email }}",
        },
        "theme": {
           "color": "#47AA50"
        }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
        e.preventDefault();
    });
    /*document.getElementsClass('buy_plan1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
    }*/
    $('body').on('click', '.buy_now_paytm', function(e){
        document.paytm_form.submit();
    });

</script>
@endsection
