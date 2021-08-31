@extends('layouts.home')
@section('styles')
<style>
    .card-pricing.basic:hover {
        z-index: 1;
        transform: scale(1.1); 
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
    .isDisabled {
      color: currentColor;
      cursor: not-allowed;
      opacity: 0.5;
      text-decoration: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
@endsection
@section('content')
<!-- Select plans -->

    <?php $existPlans=0;?>
    @foreach($plans as $plan)
        @if($plan->plan_id !=1 )
        <?php $existPlans++;?>
        @endif
    @endforeach

    <div class="container-fluid pl-0 pr-0 profile_edit_row_outer_wrap" style="min-height: 415px;">
        <div class="box-size">
            <div class="select_plans_outer_row_wrap select_new_plans_table_outer_box_wrap profile_right_col">
                <!-- New -->
                <div class="manage_ads_outer_wrap">
                    <div class="wallet_nav_outer_wrap">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="free_plans_content_tab" data-toggle="tab" href="#new_plans_content" role="tab" aria-controls="new_plans_content" aria-selected="true">New Plans</a>
                                
                                <a class="nav-item nav-link" id="already_bought_tab" data-toggle="tab" href="#already_bought_content" role="tab" aria-controls="already_bought_content" aria-selected="false">Already Bought plan</a>
                            </div>
                            <div class="back_newplan">
                                <a href="{{URL::previous()}}">
                                   <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                            </div>
                        </nav>
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
                                        <h4>Ad Benefits</h4>
                                    </div>

                                    @foreach($freeplans as $freeplan)
                                        @if($freeplan->plan_name != 'Free Plan')
                                            @foreach($plansdetails as $plansdetail)
                                                @if($plansdetail->plan_id == $freeplan->id)
                                                        @php //echo "<pre>";print_r($freeplan); @endphp
                                                        @php //echo "<pre>";print_r($plansdetail);@endphp
                                                    <div>
                                                        <h4>{{$freeplan->plan_name}} <span>{{$plansdetail->quantity}} Ad</span></h4>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif

                                    @endforeach
                                </div>
                                <div class="plan_table_content">
                                    <div class="plan_content_header">
                                        <h4>Price</h4>
                                        <h4>Ad Points</h4>
                                        <h4>No. of Ad Views</h4>
                                        <h4>Points Validity</h4>
                                        <h4>Validity for Ads Quantity</h4>
                                        <h4>Remarks</h4>
                                    </div>

                                        @foreach($freeplans as $freeplan)
                                            @if($freeplan->plan_name == 'Free Plan')
                                            @else
                                                @foreach($plansdetails as $plansdetail)
                                                    @if($plansdetail->plan_id == $freeplan->id)
                                                    <div class="plan_content_header">
                                                        @php //echo "<pre>";print_r($freeplan); @endphp
                                                        @php //echo "<pre>";print_r($plansdetail);die; @endphp
                                                        <h4 style="font-size: 14px;color: #666;">{{$plansdetail->price}}</h4>
                                                        <h4 style="font-size: 14px;color: #666;">{{$freeplan->ads_points}} Points / Ad</h4>
                                                        <h4 style="font-size: 14px;color: #666;">{{($freeplan->ads_points/$ads_view_point)}} Views / Ad</h4>
                                                        <h4 style="font-size: 14px;color: #666;">{{$freeplan->validity}} Days</h4>
                                                        <h4 style="font-size: 14px;color: #666;">{{$plansdetail->validity}} Days</h4>

                                                        @if($plansdetail->plan_id == 2)
                                                            <h4 style="font-size: 14px;color: #666;">Applicable only for Automobile Category</h4>
                                                        @else
                                                            <h4 style="font-size: 14px;color: #666;">-</h4>
                                                        @endif
                                                        <h4 style="border: 0px;"> 
                                                            <div class="plans_footer_row">
                                                                <div class="plan_table_btn walletpointenable" style="display: none;"> 
                                                                @if(Auth::user()->wallet_point >= $plansdetail->price)
                                                                    <a href="javascript:void(0);"
                                                                    data-details="{{base64_encode($plansdetail->id)}}" data-planuuid="{{$freeplan->uuid}}" class="btn btn-block  text-white mb-3 modalOpenForWallet" style="text-align: center !important;" data-walletpointused="1">Buy
                                                                    </a>
                                                                @else
                                                                    <a href="javascript:void(0);"
                                                                    data-details="{{base64_encode($plansdetail->id)}}" data-planuuid="{{$freeplan->uuid}}" class="btn btn-block  text-white mb-3 modalOpenForWallet" style="text-align: center !important;" data-walletpointused="0">Buy
                                                                    </a>
                                                                @endif
                                                                </div>
                                                                <div class="plan_table_btn walletpointdisable"> 
                                                                    
                                                                    <a href="javascript:void(0);"
                                                                    data-details="{{base64_encode($plansdetail->id)}}" data-planuuid="{{$freeplan->uuid}}" class="btn btn-block  text-white mb-3 modalOpen" style="text-align: center !important;">Buy
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </h4>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        
                        <div class="tab-pane select_plan_scroll fade" id="already_bought_content" role="tabpanel" aria-labelledby="already_bought_tab">
                            @if($existPlans!=0)
                                <div class="select_plan_listing_outer_wrap">
                                    <ul>
                                        <li>
                                            <?php $j=0;?>
                                            @foreach($plans as $plan)
                                                @if($plan->plan_id !=1 )
                                                <?php $j++;?>

                                                

                                                <a href="javascript:void(0);" class="checkPlanAvail" onclick="confirmplan('{{route('ads.selectPlanPostPlatinam',[$plan->uuid,$adUuid])}}');"><p>{{getpaidplanname($plan->plan_id)}} </p>
                                                    <p style="float: right;">Invoice No: #OPID{{$plan->id}}</p>
                                                    <span >Purchased Ads : {{$plan->ads_limit}}</span>
                                                    <span class="p-2">|</span>
                                                    <span >Ads Balance : {{$plan->ads_count}}</span>
                                                    <span class="p-2">|</span>
                                                    <span>Expire Date & Time : {{date("d-M-Y h:i A",strtotime($plan->expire_date))}}</span>
                                                </a>
                                                <!-- <a href="{{route('ads.selectPlanPost',$plan->uuid)}}" class="btn  btn-block" onclick="">{{getpaidplanname($plan->plan_id)}} ({{$plan->ads_count}})</a> -->
                                                @endif
                                            @endforeach
                                            @if($j==0)
                                                <a href="javascript:void(0);">No Plans</a>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        
                    </div>
                </div>
                <!-- End -->

            </div>
        </div>  
    </div>

<form method='post' action='{{url("adspaytmpaymentforplatinam")}}' name='paytm_form' class="hide">
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
    var adUuid = '{{$adUuid}}';

    function confirmplan(url){
        swal({
          title: "Are you sure!",
          text: "",
          icon: "warning",
          buttons: [
            'No, cancel it!',
            'Yes, I am sure!'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) {
              window.location.href = url;
          } else {
            $(".checkPlanAvail").removeClass('isDisabled');
          }
        });
    }
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


        var planuuidid =  $(this).attr("data-planuuid");
        var details =  $(this).attr("data-details");

        $.ajax({
           url: SITEURL + '/getamtdetails',
           type: 'post',
           dataType: 'html',
           data: {
            detailsId : details,
            planuuidid : planuuidid,
           },
            beforeSend:function(){
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function (suc_data) {
                $('.ajax-loader').css("visibility", "hidden");
                var resp = suc_data.split("@@@");
                console.log(resp);
                //return false;
                if($.trim(resp[0]) == "0"){
                    swal("Something went wrong !", {
                        icon: "warning",
                    });
                    return false;
                }else{
                    var totalAmount = $.trim(resp[0]);
                    var adscount =  $.trim(resp[1]);
                    var planuuid =  $.trim(resp[2]);
                    var discounts = $.trim(resp[3]);
                    var details =  $.trim(resp[4]);
                }

                if(paymentgateway == 'paytm'){

                    if(parseInt(totalAmount)>=parseInt(adminchooseamount)){                
                        $(".paytmform").append('<input type="hidden" name="amount" value="'+totalAmount+'"/>');
                        $(".paytmform").append('<input type="hidden" name="adscount" value="'+adscount+'"/>');
                        $(".paytmform").append('<input type="hidden" name="planuuid" value="'+planuuid+'"/>');
                        $(".paytmform").append('<input type="hidden" name="details" value="'+details+'"/>');
                        $(".paytmform").append('<input type="hidden" name="adUuid" value="'+adUuid+'"/>');
                        document.paytm_form.submit();
                    }else{
                        $(".buy_now").attr("data-discounts",discounts);
                        $(".buy_now").attr("data-amount",totalAmount);
                        $(".buy_now").attr("data-adcount",adscount);
                        $(".buy_now").attr("data-planuuid",planuuid);
                        $(".buy_now").attr("data-details",details);
                        $( ".buy_now" ).trigger( "click" );
                    }

                }else{

                    if(parseInt(totalAmount)>=parseInt(adminchooseamount)){  
                        $(".buy_now").attr("data-discounts",discounts);
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
                        $(".paytmform").append('<input type="hidden" name="adUuid" value="'+adUuid+'"/>');
                        //$( "#foo" ).trigger( "click" );
                        document.paytm_form.submit();
                    }
                }

            }
       });

    });

    $('body').on('click', '.buy_now', function(e){
        var discounts = $(this).attr("data-discounts");
        var totalAmount = $(this).attr("data-amount");
        if(discounts!=''){
            totalAmount = totalAmount - discounts;
        }
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
                   url: SITEURL + '/paidplanpayPlatinamsuccess',
                   type: 'post',
                   dataType: 'json',
                   data: {
                        razorpay_payment_id: response.razorpay_payment_id , 
                        totalAmount : totalAmount,
                        adscount : adscount,
                        planuuid : planuuid,
                        details : details,
                        discounts : discounts,
                        adUuid : adUuid,
                        _token : "{{ csrf_token() }}",
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

<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
<script type="text/javascript">
    
    $(window).on("load",function(){
        $(".select_plan_scroll").mCustomScrollbar({
            autoHideScrollbar: false,
            theme: "dark",
        });
    });
    
    $( document ).ready(function() {
        $('.checkPlanAvail').on("click",function(){
            console.log( "ready!" );
            $(this).addClass('isDisabled');
        });



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
                            /*swal("Please verify your pan card,to redeem your amount!", {
                                icon: "warning",
                            });*/
                            swal({
                              title: "Please verify your pan card,to redeem your amount!",
                              text: "",
                              icon: "warning",
                              buttons: [
                                'No, cancel it!',
                                'Yes, I am sure!'
                              ],
                              dangerMode: true,
                            }).then(function(isConfirm) {
                              if (isConfirm) {
                                  window.location.href = SITEURL + '/profile#pan_card';
                              } else {
                                swal("Cancelled", "You are not using wallet amount", "error");
                              }
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

        $('body').on('click', '.modalOpenForWallet', function(e){
            walletpointused = $(this).attr("data-walletpointused");
            //alert(walletpointused);return false;
            if(walletpointused == '1'){

                var planuuid =  $(this).attr("data-planuuid");
                var details =  $(this).attr("data-details");
                $.ajax({
                    url: SITEURL + '/getamtdetails',
                    type: 'post',
                    dataType: 'html',
                    data: {
                    detailsId : details,
                    planuuidid : planuuid,
                    },
                    beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success: function (suc_data) {
                        $('.ajax-loader').css("visibility", "hidden");
                        var resp = suc_data.split("@@@");
                        console.log(resp);
                        //return false;
                        if($.trim(resp[0]) == "0"){
                            swal("Something went wrong !", {
                                icon: "warning",
                            });
                            return false;
                        }else{
                            var totalAmount = $.trim(resp[0]);
                            var adscount =  $.trim(resp[1]);
                            var planuuid =  $.trim(resp[2]);
                            var discounts = $.trim(resp[3]);
                            var details =  $.trim(resp[4]);
                        }

                        $.ajax({
                            url: SITEURL + '/walletpointusedproductpurchase',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                totalAmount : totalAmount,
                                adscount : adscount,
                                planuuid : planuuid,
                                details : details,
                                discounts : discounts,
                                _token : "{{ csrf_token() }}",
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
                var planuuid =  $(this).attr("data-planuuid");
                var details =  $(this).attr("data-details");
                $.ajax({
                    url: SITEURL + '/getamtdetails',
                    type: 'post',
                    dataType: 'html',
                    data: {
                    detailsId : details,
                    planuuidid : planuuid,
                    },
                    beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success: function (suc_data) {
                        $('.ajax-loader').css("visibility", "hidden");
                        var resp = suc_data.split("@@@");
                        console.log(resp);
                        //return false;
                        if($.trim(resp[0]) == "0"){
                            swal("Something went wrong !", {
                                icon: "warning",
                            });
                            return false;
                        }else{
                            var totalAmount = $.trim(resp[0]);
                            var adscount =  $.trim(resp[1]);
                            var planuuid =  $.trim(resp[2]);
                            var discounts = $.trim(resp[3]);
                            var details =  $.trim(resp[4]);
                        }


                        if(discounts!=''){
                            totalAmount = totalAmount - discounts;
                        }

                        var user_wallet_point ="{{round(Auth::user()->wallet_point)}}";
                        totalAmount = totalAmount - user_wallet_point;


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
                                            discounts : discounts,
                                            fullwalletpointused : "1",
                                            _token : "{{ csrf_token() }}",
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



                    }
                });
            } 
        });

    });

    function confirmFreePlan(ev) {
        ev.preventDefault();
        var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
        console.log(urlToRedirect); // verify if this is the right URL
        

        $.ajax({
           url: SITEURL + '/getavailablefreeads',
           type: 'post',
           dataType: 'html',
           data: {
           },
            beforeSend:function(){
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function (suc_data) {
                $('.ajax-loader').css("visibility", "hidden");
                //console.log(suc_data);return false;
                if($.trim(suc_data) == '1'){
                    swal({
                      title: "Are you sure?",
                      text: "Do you want to post free ads?",
                      icon: "warning",
                      buttons: true,
                      dangerMode: true,
                    })
                    .then((willDelete) => {
                      if (willDelete) {
                        window.location.href = urlToRedirect;
                      }
                    });
                }else{
                    swal({
                      title: "Are you sure?",
                      text: "You can post free ads. But ads will not have OJAAK point. Do you want to proceed?",
                      icon: "warning",
                      buttons: true,
                      dangerMode: true,
                    })
                    .then((willDelete) => {
                      if (willDelete) {
                        window.location.href = urlToRedirect;
                      }
                    });
                }

                console.log(suc_data);

            }
       });
    }

</script>
@endsection
