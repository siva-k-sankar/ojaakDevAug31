@extends('layouts.home')
@section('styles')
<style type="text/css">
    .card-pricing.basic span{
        background-color:#47aa50 !important;
    }
    .card-pricing.basic:hover {
        z-index: 1;
        transform: scale(1.1); 
        border: 2px solid #47aa50!important;
        transition-duration: .3s;
    }
</style>
@endsection
@section('content')

<div class="container-fluid pl-0 pr-0 profile_edit_row_outer_wrap" style="min-height: 415px;">
        <div class="box-size">
            <div class="select_plans_outer_row_wrap select_new_plans_table_outer_box_wrap profile_right_col">

                    @if(isset($plans))
                <!-- New -->
                <div class="manage_ads_outer_wrap">
                    <div class="wallet_nav_outer_wrap">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="free_plans_content_tab" data-toggle="tab" href="#new_plans_content" role="tab" aria-controls="new_plans_content" aria-selected="true">Platinam Ads</a>
                            </div>
                            <div class="back_newplan">
                                <a href="{{URL::previous()}}">
                                   <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                            </div>
                        </nav>
                    </div>
                    <br >
                    <div class="custom-control custom-checkbox new_plan_outer_table_wrap">
                        <ul>
                            <li>Note: No additional "Ad Points" will be given to Platinam ads</li>
                        </ul>
                        <input type="checkbox" class="custom-control-input" id="defaultUnchecked">
                        <label class="custom-control-label" for="defaultUnchecked">Use my wallet point {{ Auth::user()->wallet_point }}</label>
                    </div>
                    
                    <div class="tab-content py-3 px-3 px-sm-0" id="ads_management_scroll_idd">
                        <div class="tab-pane select_plan_scroll_ fade show active" id="new_plans_content" role="tabpanel" aria-labelledby="free_plans_content_tab">

                            <div class="new_plan_outer_table_wrap">

                            <!-- <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="defaultUnchecked">
                                <label class="custom-control-label" for="defaultUnchecked">Use my wallet point {{ Auth::user()->wallet_point }}</label>
                            </div> -->
                            <br>
                                <div class="plans_table_header">
                                    <div>
                                        <h4>Benefits \ Plans</h4>
                                    </div>
                                    <div>
                                        <h4>Platinam 7</h4>
                                    </div>
                                    <div>
                                        <h4>Platinam 15</h4>
                                    </div>
                                    <div>
                                        <h4>Platinam 30</h4>
                                    </div>

                                </div>
                                <div class="plan_table_content">
                                    <div class="plan_content_header">
                                        <h4>Price</h4>
                                        <h4>Reach to customers</h4>
                                        <h4>Plan Validity</h4>
                                        <h4>Remarks</h4>
                                    </div>

                                        <div class="plan_content_header">
                                            <h4 style="font-size: 14px;color: #666;">Rs. {{$plans->validity_7}}</h4>
                                            <h4 style="font-size: 14px;color: #666;">50 Times more</h4>
                                            <h4 style="font-size: 14px;color: #666;">7 Days</h4>
                                            <h4 style="font-size: 14px;color: #666;">Applicable for all category</h4>
                                            <h4 style="border: 0px;"> 
                                                <div class="plans_footer_row">
                                                    <div class="plan_table_btn walletpointdisable"> 
                                                        
                                                        <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpen" data-id="validity_7" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" >Buy </a>
                                                    </div>
                                                    <div class="plan_table_btn walletpointenable" style="display: none;"> 
                                                        
                                                        @if(Auth::user()->wallet_point >= $plans->validity_7)
                                                            <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet" data-id="validity_7" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" data-walletpointused="1">Buy</a>
                                                        @else
                                                            <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet" data-id="validity_7" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" data-walletpointused="0">Buy</a>
                                                        @endif
                                                    </div>

                                                </div>
                                            </h4>
                                        </div><div class="plan_content_header">
                                            <h4 style="font-size: 14px;color: #666;">Rs. {{$plans->validity_15}}</h4>
                                            <h4 style="font-size: 14px;color: #666;">50 Times more</h4>
                                            <h4 style="font-size: 14px;color: #666;">15 Days</h4>
                                            <h4 style="font-size: 14px;color: #666;">Applicable for all category</h4>
                                            <h4 style="border: 0px;"> 
                                                <div class="plans_footer_row">
                                                    <div class="plan_table_btn walletpointdisable"> 
                                                        <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpen" data-id="validity_15" data-planuuid="{{$plans->uuid}}"  data-details="{{$position}}">Buy</a>
                                                    </div>

                                                    <div class="plan_table_btn walletpointenable" style="display: none;"> 
                                                        
                                                        @if(Auth::user()->wallet_point >= $plans->validity_15)
                                                            <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet" data-id="validity_15" data-planuuid="{{$plans->uuid}}"  data-details="{{$position}}" data-walletpointused="1">Buy</a>
                                                        @else
                                                            <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet" data-id="validity_15" data-planuuid="{{$plans->uuid}}"  data-details="{{$position}}" data-walletpointused="0">Buy</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </h4>
                                        </div><div class="plan_content_header">
                                            <h4 style="font-size: 14px;color: #666;">Rs. {{$plans->validity_30}}</h4>
                                            <h4 style="font-size: 14px;color: #666;">50 Times more</h4>
                                            <h4 style="font-size: 14px;color: #666;">30 Days</h4>
                                            <h4 style="font-size: 14px;color: #666;">Applicable for all category</h4>
                                            <h4 style="border: 0px;"> 
                                                <div class="plans_footer_row">
                                                    <div class="plan_table_btn walletpointdisable"> 
                                                        <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpen"  data-id="validity_30" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}">Buy</a>
                                                    </div>

                                                    <div class="plan_table_btn walletpointenable" style="display: none;">
                                                        @if(Auth::user()->wallet_point >= $plans->validity_30)
                                                            <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet"  data-id="validity_30" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" data-walletpointused="1">Buy</a>
                                                        @else
                                                            <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet"  data-id="validity_30" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" data-walletpointused="0">Buy</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        
                    </div>
                </div>
                <!-- End -->
                @endif
            </div>
        </div>  
    </div>
<!-- 
<div class="container-fluid pl-0 pr-0 mt-3 product_view_outer_row_wrap">
    <div class="box-size">
        <div class="row justify-content-center">
            @if(isset($plans))
                <div class="col-md-3">
                    <div class="card card-pricing basic text-center px-3 mb-4">
                        <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom  text-white shadow-sm">{{ucwords($plans->name)}}</span>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="defaultUnchecked">
                            <label class="custom-control-label" for="defaultUnchecked">Use my wallet point {{ Auth::user()->wallet_point }}</label>
                        </div>
                        <div class="bg-transparent card-header pt-4 border-0">
                         </div>
                        <div class="card-body pt-0 common_btn_wrap common_btn_padding">
                            <a href="javascript:void(0);" class="btn btn-block btn-primary text-white mb-3 buy_now" data-amount="{{$plans->validity_7}}" data-id="validity_7" data-planuuid="{{$plans->uuid}}" data-days="{{base64_encode('7')}}" >7 Days- &#8377; {{$plans->validity_7}}</a>
                            <a href="javascript:void(0);" class="btn btn-block btn-primary text-white mb-3 buy_now" data-amount="{{$plans->validity_15}}" data-id="validity_15" data-planuuid="{{$plans->uuid}}" data-days="{{base64_encode('15')}}" >15 Days- &#8377;  {{$plans->validity_15}}</a>
                            <a href="javascript:void(0);" class="btn btn-block btn-primary text-white mb-3 buy_now" data-amount="{{$plans->validity_30}}" data-id="validity_30" data-planuuid="{{$plans->uuid}}" data-days="{{base64_encode('30')}}" >30 Days- &#8377; {{$plans->validity_30}}</a>
                        </div> 

                        <div class="card-body pt-0 common_btn_wrap common_btn_padding walletpointdisable">

                            <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpen" data-id="validity_7" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" >7 Days- &#8377; {{$plans->validity_7}}</a>
                            <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpen" data-id="validity_15" data-planuuid="{{$plans->uuid}}"  data-details="{{$position}}">15 Days- &#8377;  {{$plans->validity_15}}</a>
                            <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpen"  data-id="validity_30" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}">30 Days- &#8377; {{$plans->validity_30}}</a>

                        </div>
                        <div class="card-body pt-0 common_btn_wrap common_btn_padding walletpointenable" style="display: none;">

                            @if(Auth::user()->wallet_point >= $plans->validity_7)
                                <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet" data-id="validity_7" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" data-walletpointused="1">7 Days- &#8377; {{$plans->validity_7}}</a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet" data-id="validity_7" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" data-walletpointused="0">7 Days- &#8377; {{$plans->validity_7}}</a>
                            @endif

                            @if(Auth::user()->wallet_point >= $plans->validity_15)
                                <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet" data-id="validity_15" data-planuuid="{{$plans->uuid}}"  data-details="{{$position}}" data-walletpointused="1">15 Days- &#8377;  {{$plans->validity_15}}</a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet" data-id="validity_15" data-planuuid="{{$plans->uuid}}"  data-details="{{$position}}" data-walletpointused="0">15 Days- &#8377;  {{$plans->validity_15}}</a>
                            @endif
                            @if(Auth::user()->wallet_point >= $plans->validity_30)
                                <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet"  data-id="validity_30" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" data-walletpointused="1">30 Days- &#8377; {{$plans->validity_30}}</a>
                            @else
                                <a href="javascript:void(0);" class="btn btn-block  text-white mb-3 modalOpenForWallet"  data-id="validity_30" data-planuuid="{{$plans->uuid}}" data-details="{{$position}}" data-walletpointused="0">30 Days- &#8377; {{$plans->validity_30}}</a>
                            @endif

                            
                            
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
 -->

<!-- Trigger the modal with a button -->
<!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->

<form method='post' action='{{url("payment")}}' name='paytm_form'  class="hide">
    @csrf
    <div class="paytmform">
        
    </div>
    <button type="submit" style="display: none"></button>
</form>
<form method='post' action='{{url("walletwithplatinam")}}' name='walletwithplatinam'  class="hide">
    @csrf
    <div class="paytmformwithplatinam">
        
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

        //$('#myModal').modal('show');
        var product_id =  $(this).attr("data-id");
        var planuuid =  $(this).attr("data-planuuid");
        var details =  $(this).attr("data-details");

        $.ajax({
           url: SITEURL + '/getamtplatinam',
           type: 'post',
           dataType: 'html',
           data: {
            product_id : product_id,
            planuuid : planuuid,
           },
            beforeSend:function(){
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function (suc_data) {
                $('.ajax-loader').css("visibility", "hidden");
                var res = suc_data.split("@@@");
                totalAmount = $.trim(res[0]);
                if(paymentgateway == 'paytm'){

                    if(parseInt(totalAmount)>=parseInt(adminchooseamount)){                
                        $(".paytmform").append('<input type="hidden" name="amount" value="'+totalAmount+'"/>');
                        $(".paytmform").append('<input type="hidden" name="id" value="'+product_id+'"/>');
                        $(".paytmform").append('<input type="hidden" name="days" value="'+$.trim(res[1])+'"/>');
                        $(".paytmform").append('<input type="hidden" name="planuuid" value="'+planuuid+'"/>');
                        $(".paytmform").append('<input type="hidden" name="details" value="'+details+'"/>');
                        //$( "#foo" ).trigger( "click" );
                        document.paytm_form.submit();
                    }else{
                        $(".buy_now").attr("data-amount",totalAmount);
                        $(".buy_now").attr("data-id",product_id);
                        $(".buy_now").attr("data-days",$.trim(res[1]));
                        $(".buy_now").attr("data-planuuid",planuuid);
                        $(".buy_now").attr("data-details",details);
                        $( ".buy_now" ).trigger( "click" );
                    }

                }else{
                    if(parseInt(totalAmount)>=parseInt(adminchooseamount)){  
                        $(".buy_now").attr("data-amount",totalAmount);
                        $(".buy_now").attr("data-id",product_id);
                        $(".buy_now").attr("data-days",$.trim(res[1]));
                        $(".buy_now").attr("data-planuuid",planuuid);
                        $(".buy_now").attr("data-details",details);
                        $( ".buy_now" ).trigger( "click" );
                    }else{           
                        $(".paytmform").append('<input type="hidden" name="amount" value="'+totalAmount+'"/>');
                        $(".paytmform").append('<input type="hidden" name="id" value="'+product_id+'"/>');
                        $(".paytmform").append('<input type="hidden" name="days" value="'+$.trim(res[1])+'"/>');
                        $(".paytmform").append('<input type="hidden" name="planuuid" value="'+planuuid+'"/>');
                        $(".paytmform").append('<input type="hidden" name="details" value="'+details+'"/>');
                        //$( "#foo" ).trigger( "click" );
                        document.paytm_form.submit();
                    }
                }
                    

            }
        });

        

    });

    $('body').on('click', '.modalOpenForWallet', function(e){
        var bill_info ="{{Billing_Information_Check()}}";
        if(bill_info==0){
            toastr.warning('Please Fillout All Billing Information!');
            window.location.href = BillingInformationReDirect;
            exit();
        }
        
        var product_id =  $(this).attr("data-id");
        var planuuid =  $(this).attr("data-planuuid");
        var details =  $(this).attr("data-details");

        walletpointused = $(this).attr("data-walletpointused");

        if(walletpointused == '1'){
            $.ajax({
               url: SITEURL + '/getamtplatinam',
               type: 'post',
               dataType: 'html',
               data: {
                product_id : product_id,
                planuuid : planuuid,
               },
                beforeSend:function(){
                    $('.ajax-loader').css("visibility", "visible");
                },
                success: function (suc_data) {
                    $('.ajax-loader').css("visibility", "hidden");
                    var res = suc_data.split("@@@");
                    totalAmount = $.trim(res[0]);
                    $.ajax({
                        url: SITEURL + '/walletpointusedforplatinam',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            totalAmount : totalAmount,
                            product_id : product_id,
                            days : $.trim(res[1]),
                            amount : totalAmount,
                            planuuid : planuuid,
                            walletpointused:walletpointused,
                            details:details
                        },
                        beforeSend:function(){
                            $('.ajax-loader').css("visibility", "visible");
                        },
                        success: function (msg) {
                            $('.ajax-loader').css("visibility", "hidden");
                            window.location.href = SITEURL + '/razor-thank-you';
                        }
                   });
                }
            });



        }else{

            $.ajax({
               url: SITEURL + '/getamtplatinam',
               type: 'post',
               dataType: 'html',
               data: {
                product_id : product_id,
                planuuid : planuuid,
               },
                beforeSend:function(){
                    $('.ajax-loader').css("visibility", "visible");
                },
                success: function (suc_data) {
                    $('.ajax-loader').css("visibility", "hidden");
                    var res = suc_data.split("@@@");
                    totalAmount = $.trim(res[0]);
                    console.log("totalAmount",totalAmount);
                    

                    if(paymentgateway == 'paytm'){
                        
                        var user_wallet_point ="{{round(Auth::user()->wallet_point)}}";
                        totalAmount = totalAmount - user_wallet_point;
                        console.log("totalAmount else",totalAmount);

                        if(parseInt(totalAmount)>=parseInt(adminchooseamount)){                
                            $(".paytmformwithplatinam").append('<input type="hidden" name="amount" value="'+totalAmount+'"/>');
                            $(".paytmformwithplatinam").append('<input type="hidden" name="id" value="'+product_id+'"/>');
                            $(".paytmformwithplatinam").append('<input type="hidden" name="days" value="'+$.trim(res[1])+'"/>');
                            $(".paytmformwithplatinam").append('<input type="hidden" name="planuuid" value="'+planuuid+'"/>');
                            $(".paytmformwithplatinam").append('<input type="hidden" name="details" value="'+details+'"/>');
                            //$( "#foo" ).trigger( "click" );
                            document.walletwithplatinam.submit();
                        }else{
                            var user_wallet_point ="{{round(Auth::user()->wallet_point)}}";
                            totalAmount = totalAmount - user_wallet_point;
                            console.log("totalAmount",totalAmount);
                            var options = {
                                "key": "{{ $settings['RAZORPAY_KEY'] }}",
                                "amount": (totalAmount*100), // 2000 paise = INR 20
                                "name": "{{ env('APP_NAME') }}",
                                "description": "Payment",
                                "image": "{{ asset('public/frontdesign/assets/img/ojaak_logo.png') }}",
                                "handler": function (response){
                                    console.log("response",response);
                                    if(response != null){
                                        $.ajax({
                                           url: SITEURL + '/walletusedwithrzrpayPlatinam',
                                           type: 'post',
                                           dataType: 'json',
                                           data: {
                                            razorpay_payment_id: response.razorpay_payment_id, 
                                            totalAmount : totalAmount,
                                            product_id : product_id,
                                            days : $.trim(res[1]),
                                            amount : totalAmount,
                                            planuuid : planuuid,
                                            walletpointused:walletpointused,
                                            details:details
                                           },
                                            beforeSend:function(){
                                                $('.ajax-loader').css("visibility", "visible");
                                            },
                                            success: function (msg) {
                                                $('.ajax-loader').css("visibility", "hidden");
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
                        }

                    }else{
                        if(parseInt(totalAmount)>=parseInt(adminchooseamount)){  
                            
                            var user_wallet_point ="{{round(Auth::user()->wallet_point)}}";
                            totalAmount = totalAmount - user_wallet_point;
                            console.log("totalAmount else",totalAmount);
                            var options = {
                                "key": "{{ $settings['RAZORPAY_KEY'] }}",
                                "amount": (totalAmount*100), // 2000 paise = INR 20
                                "name": "{{ env('APP_NAME') }}",
                                "description": "Payment",
                                "image": "{{ asset('public/frontdesign/assets/img/ojaak_logo.png') }}",
                                "handler": function (response){
                                    console.log("response",response);
                                    if(response != null){
                                        $.ajax({
                                           url: SITEURL + '/walletusedwithrzrpayPlatinam',
                                           type: 'post',
                                           dataType: 'json',
                                           data: {
                                                razorpay_payment_id: response.razorpay_payment_id, 
                                                totalAmount : totalAmount,
                                                product_id : product_id,
                                                days : $.trim(res[1]),
                                                amount : totalAmount,
                                                planuuid : planuuid,
                                                walletpointused:walletpointused,
                                                details:details
                                            },
                                            beforeSend:function(){
                                                $('.ajax-loader').css("visibility", "visible");
                                            },
                                            success: function (msg) {
                                                $('.ajax-loader').css("visibility", "hidden");
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
                        }else{           

                            var user_wallet_point ="{{round(Auth::user()->wallet_point)}}";
                            totalAmount = totalAmount - user_wallet_point;
                            console.log("totalAmount else",totalAmount);

                            $(".paytmformwithplatinam").append('<input type="hidden" name="amount" value="'+totalAmount+'"/>');
                            $(".paytmformwithplatinam").append('<input type="hidden" name="id" value="'+product_id+'"/>');
                            $(".paytmformwithplatinam").append('<input type="hidden" name="days" value="'+res[1]+'"/>');
                            $(".paytmformwithplatinam").append('<input type="hidden" name="planuuid" value="'+planuuid+'"/>');
                            $(".paytmformwithplatinam").append('<input type="hidden" name="details" value="'+details+'"/>');
                            //$( "#foo" ).trigger( "click" );
                            document.walletwithplatinam.submit();
                        }
                    }
                }
            });


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

        //$('#myModal').modal('show');

        
    });

    
    $('body').on('click', '.buy_now', function(e){
        var totalAmount = $(this).attr("data-amount");
        var product_id =  $(this).attr("data-id");
        var days =  $(this).attr("data-days");
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
            console.log(response)
            if(response != null){
                $.ajax({
                    url: SITEURL + '/paysuccess',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        razorpay_payment_id: response.razorpay_payment_id, 
                        totalAmount : totalAmount ,product_id : product_id,
                        product_id : product_id,
                        days : days,
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


    $('body').on('click', '.buy_now_paytm', function(e){
        document.paytm_form.submit();
        /*var totalAmount = $(this).attr("data-amount");
        var product_id =  $(this).attr("data-id");
        var days =  $(this).attr("data-days");
        var planuuid =  $(this).attr("data-planuuid");
        alert(totalAmount);


        $.ajax({
           url: SITEURL + '/payment',
           type: 'post',
           dataType: 'json',
           data: {
             totalAmount : totalAmount ,product_id : product_id,
             product_id : product_id,
             days : days,
             planuuid : planuuid,
           }, 
           success: function (msg) {
               window.location.href = SITEURL + '/razor-thank-you';
           }
       });*/


    });

    /*document.getElementsClass('buy_plan1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
    }*/


    $(document).ready(function(){

        $('#defaultUnchecked').click(function(){
            if($(this).prop("checked") == true){
                $.ajax({
                    url: SITEURL + '/checkpanwalletamt',
                    type: 'post',
                    dataType: 'html',
                    data: {
                        dataa: "",
                    },
                    beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success: function (data) {
                        $('.ajax-loader').css("visibility", "hidden");
                        var res = data.split("@@@");
                        if($.trim(res[0]) == '0'){
                            $(".walletpointdisable").hide();
                            $(".walletpointenable").show();
                            //alert(res);
                        }else if($.trim(res[0]) == '1'){
                            $("#defaultUnchecked").prop("checked",false);
                            swal("Please verify your pan card,to redeem your amount!", {
                                icon: "warning",
                            });
                        }else if($.trim(res[0]) == '2'){
                            $("#defaultUnchecked").prop("checked",false);
                            swal("Wallet amount sholud be greater than "+res[1]+" !", {
                                icon: "warning",
                            });
                        }else{
                            $("#defaultUnchecked").prop("checked",false);
                            swal("Something went wrong !", {
                                icon: "warning",
                            });
                        }
                    }
               });
            }else{
                $(".walletpointdisable").show();
                $(".walletpointenable").hide();
            }
        });
    });
</script>
@endsection