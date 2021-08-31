@extends('layouts.home')
@section('styles')
@endsection
@section('content')


<div class="container-fluid pl-0 pr-0 profile_edit_row_outer_wrap" style="min-height: 415px;">
        <div class="box-size">
            <div class="select_plans_outer_row_wrap select_new_plans_table_outer_box_wrap profile_right_col">

                <!-- New -->
                <div class="manage_ads_outer_wrap">
                    <div class="wallet_nav_outer_wrap">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="free_plans_content_tab" data-toggle="tab" href="#new_plans_content" role="tab" aria-controls="new_plans_content" aria-selected="true">Featured Ads</a>
                            </div>
                            <div class="back_newplan">
                                <a href="{{url('')}}">
                                   <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                </a>
                            </div>
                        </nav>
                    </div>
                    <br >
                    <div class="custom-control custom-checkbox new_plan_outer_table_wrap">

                        <div class="plan_main_title_wrap">
                            <div class="row">
                                <div class="col-md-8 col-12 pl-0 pr-0 plan_main_title_left_col">
                                    <h3>Top Listed Plans </h3>
                                    <ul>
                                        <li>Note: No additional "Ad Points" will be given to Featured ads</li>
                                        <li>View all ads top list based on the user's search locations</li>
                                    </ul>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="defaultUnchecked">
                                        <label class="custom-control-label" for="defaultUnchecked">Use my wallet point {{ Auth::user()->wallet_point }}</label>
                                    </div>
                                    <ul>
                                    </ul>

                                </div>
                                <!-- <div class="col-md-4 col-12 pl-0 pr-0 plan_main_title_right_col">
                                    <a href="javascript:void(0);">See Example</a>
                                </div> -->
                            </div>
                        </div>

                    </div>

                    
                    <div class="tab-content py-3 px-3 px-sm-0" id="ads_management_scroll_idd">
                        <div class="tab-pane select_plan_scroll_ fade show active" id="new_plans_content" role="tabpanel" aria-labelledby="free_plans_content_tab">

                            <div class="new_plan_outer_table_wrap show_package_table_wrap">

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
                                        <h4>Featured 7</h4>
                                    </div>
                                    <div>
                                        <h4>Featured 15</h4>
                                    </div>
                                    <div>
                                        <h4>Featured 30</h4>
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
                                            <h4 style="font-size: 14px;color: #666;">Rs. {{$toplisted->validity_7}}</h4>
                                            <h4 style="font-size: 14px;color: #666;">4 Times more</h4>
                                            <h4 style="font-size: 14px;color: #666;">7 Days</h4>
                                            <h4 style="font-size: 14px;color: #666;">Applicable for all category</h4>
                                            
                                             <div class="plans_footer_row">
                                                <div class=" walletpointdisable">
                                                    <div class=" common_btn_wrap contact_submit_btn_wrap ">
                                                        <button type="button" class="btn btn-block modalOpen" data-amount="{{$toplisted->validity_7}}"  data-id="validity_7" data-planuuid="{{$toplisted->uuid}}" data-days="{{base64_encode('7')}}" >Buy</button>
                                                    </div>
                                                </div>

                                                <div class=" walletpointenable" style="display: none;">
                                                @if(Auth::user()->wallet_point >= $toplisted->validity_7)
                                                    <div class=" common_btn_wrap contact_submit_btn_wrap">
                                                        <button type="button" class="btn btn-block modalOpenForWallet"data-amount="{{$toplisted->validity_7}}"  data-id="validity_7" data-planuuid="{{$toplisted->uuid}}" data-days="{{base64_encode('7')}}" data-walletpointused="1">Buy</button>
                                                    </div>
                                                @else
                                                    <div class=" common_btn_wrap contact_submit_btn_wrap">
                                                        <button type="button" class="btn btn-block modalOpenForWallet" data-amount="{{$toplisted->validity_7}}"  data-id="validity_7" data-planuuid="{{$toplisted->uuid}}" data-days="{{base64_encode('7')}}" data-walletpointused="0">Buy</button>
                                                    </div>
                                                @endif
                                                </div>

                                            </div>
                                        </div>

                                        <div class="plan_content_header">
                                            <h4 style="font-size: 14px;color: #666;">Rs. {{$toplisted->validity_15}}</h4>
                                            <h4 style="font-size: 14px;color: #666;">4 Times more</h4>
                                            <h4 style="font-size: 14px;color: #666;">15 Days</h4>
                                            <h4 style="font-size: 14px;color: #666;">Applicable for all category</h4>
                                            <div class="plans_footer_row">
                                                <div class=" walletpointdisable">
                                                    <div class=" common_btn_wrap contact_submit_btn_wrap ">
                                                        <button type="button" class="btn btn-block modalOpen" data-amount="{{$toplisted->validity_15}}"  data-id="validity_15" data-planuuid="{{$toplisted->uuid}}" data-days="{{base64_encode('15')}}">Buy</button>
                                                    </div>
                                                </div>
                                                <div class=" walletpointenable" style="display: none;">

                                                @if(Auth::user()->wallet_point >= $toplisted->validity_15)

                                                    <div class=" common_btn_wrap contact_submit_btn_wrap">
                                                        <button type="button" class="btn btn-block modalOpenForWallet" data-amount="{{$toplisted->validity_15}}"  data-id="validity_15" data-planuuid="{{$toplisted->uuid}}" data-days="{{base64_encode('15')}}" data-walletpointused="1">Buy</button>
                                                    </div>
                                                    @else

                                                    <div class=" common_btn_wrap contact_submit_btn_wrap">
                                                        <button type="button" class="btn btn-block modalOpenForWallet" data-amount="{{$toplisted->validity_15}}"  data-id="validity_15" data-planuuid="{{$toplisted->uuid}}" data-days="{{base64_encode('15')}}" data-walletpointused="0">Buy</button>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="plan_content_header">
                                            <h4 style="font-size: 14px;color: #666;">Rs. {{$toplisted->validity_30}}</h4>
                                            <h4 style="font-size: 14px;color: #666;">4 Times more</h4>
                                            <h4 style="font-size: 14px;color: #666;">30 Days</h4>
                                            <h4 style="font-size: 14px;color: #666;">Applicable for all category</h4>
                                                <div class="plans_footer_row">
                                                    <div class=" walletpointdisable">
                                                        <div class=" common_btn_wrap contact_submit_btn_wrap ">
                                                            <button type="button" class="btn btn-block modalOpen"  data-amount="{{$toplisted->validity_30}}" data-id="validity_30" data-planuuid="{{$toplisted->uuid}}" data-days="{{base64_encode('30')}}" >Buy</button>
                                                        </div>
                                                    </div>
                                                    <div class=" walletpointenable" style="display: none;">
                                                    @if(Auth::user()->wallet_point >= $toplisted->validity_30)
                                                        <div class=" common_btn_wrap contact_submit_btn_wrap">
                                                            <button type="button" class="btn btn-block modalOpenForWallet" data-amount="{{$toplisted->validity_30}}" data-id="validity_30" data-planuuid="{{$toplisted->uuid}}" data-days="{{base64_encode('30')}}" data-walletpointused="1" >Buy</button>
                                                        </div>
                                                    @else
                                                        <div class=" common_btn_wrap contact_submit_btn_wrap">
                                                            <button type="button" class="btn btn-block modalOpenForWallet" data-amount="{{$toplisted->validity_30}}" data-id="validity_30" data-planuuid="{{$toplisted->uuid}}" data-days="{{base64_encode('30')}}" data-walletpointused="0" >Buy</button>
                                                        </div>
                                                    @endif

                                                    </div>

                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        
                    </div>
                </div>
                <!-- End -->

            </div>
        </div>  
    </div>



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
                <input type="button" name="razorpay" class="btn btn-primary buy" value="Razorpay" style="background: #2783f3;">
            </div>
            <form method='post' action='{{url("packagesbuypaytm")}}' name='paytm_form'  class="hide">
                @csrf
                <div class="paytmform">
                    
                </div>
                <button type="submit" style="display: none"></button>
            </form>
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
<script type="text/javascript">


$(document).ready(function(){
        var totalAmount=0;
        var ttotalAmount = 0;
        var tproduct_id ='';
        var tdays ='';
        var tplanuuid='';
        var SITEURL = '{{url("")}}';
        var BillingInformationReDirect = SITEURL + '/billing';
        var twalletpointused = 0;
        
        

        /*$('input[name="toplist"]').click(function(){
            if($(this).prop("checked") == true){
                ttotalAmount = $(this).attr("data-amount");
                tproduct_id =  $(this).attr("data-id");
                tdays =  $(this).attr("data-days");
                tplanuuid =  $(this).attr("data-planuuid");
                twalletpointused = $(this).attr("data-walletpointused");
                //alert(tplanuuid);
                //alert($(this).val());
            }
        });*/


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

        $('input:radio').change(function (){
            
            totalAmount=ttotalAmount;
            //alert(totalAmount);
            
        });
        
        $('body').on('click', '.buy', function(e){
            $.ajax({
               url: SITEURL + '/getamt',
               type: 'post',
               dataType: 'html',
               data: {
                Tproduct_id : tproduct_id,
                tplanuuid : tplanuuid,
               },
                beforeSend:function(){
                    $('.ajax-loader').css("visibility", "visible");
                },
                success: function (suc_data) {
                    $('.ajax-loader').css("visibility", "hidden");
                    var res = suc_data.split("@@@");
                    totalAmount = $.trim(res[0]);

                    if(totalAmount !=0 ){
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
                                       url: SITEURL + '/packagespaysuccess',
                                       type: 'post',
                                       dataType: 'json',
                                       data: {
                                        razorpay_payment_id: response.razorpay_payment_id, 
                                        totalAmount : totalAmount,
                                        Tproduct_id : tproduct_id,
                                        Tdays : res[1],
                                        Tamount : totalAmount,
                                        Tplanuuid : tplanuuid,
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
                    alert('Select atleast one plan');return false;
                }

                }
           });


            
            
        });

        $('body').on('click', '.modalOpen', function(e){
            var totalAmount = $(this).data('amount');

            ttotalAmount = $(this).attr("data-amount");
            tproduct_id =  $(this).attr("data-id");
            tdays =  $(this).attr("data-days");
            tplanuuid =  $(this).attr("data-planuuid");
            twalletpointused = $(this).attr("data-walletpointused");

            //$('#myModal').modal('show');
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
            //alert(paymentgateway);return false;

            if(paymentgateway == 'paytm'){

                if(parseInt(totalAmount)>=parseInt(adminchooseamount)){
                    if(totalAmount !=0 ){
                        if(ttotalAmount !=0 || ptotalAmount !=0){
                            document.paytm_form.submit();
                        }
                    }else{
                        alert('Select atleast one plan');return false;
                    }


                    $.ajax({
                       url: SITEURL + '/getamt',
                       type: 'post',
                       dataType: 'html',
                       data: {
                        Tproduct_id : tproduct_id,
                        tplanuuid : tplanuuid,
                       },
                        beforeSend:function(){
                            $('.ajax-loader').css("visibility", "visible");
                        },
                        success: function (suc_data) {
                            $('.ajax-loader').css("visibility", "hidden");
                            var res = suc_data.split("@@@");
                            totalAmount = res[0];
                                    
                            $(".paytmform").append('<input type="hidden" name="totalAmount" value="'+totalAmount+'"/>');
                            $(".paytmform").append('<input type="hidden" name="tproduct_id" value="'+tproduct_id+'"/>');
                            $(".paytmform").append('<input type="hidden" name="tdays" value="'+res[1]+'"/>');
                            $(".paytmform").append('<input type="hidden" name="ttotalAmount" value="'+totalAmount+'"/>');
                            $(".paytmform").append('<input type="hidden" name="tplanuuid" value="'+tplanuuid+'"/>');
                            //$( "#foo" ).trigger( "click" );
                            document.paytm_form.submit();
                                

                        }
                   });


                }else{ 
                    $( ".buy" ).trigger( "click" );
                    /*$(".buy_now").attr("data-amount",totalAmount);
                    $(".buy_now").attr("data-id",product_id);
                    $(".buy_now").attr("data-days",days);
                    $(".buy_now").attr("data-planuuid",planuuid);
                    $( ".buy_now" ).trigger( "click" );*/
                }

            }else{
                if(parseInt(totalAmount)>=parseInt(adminchooseamount)){ 
                    $( ".buy" ).trigger( "click" );
                    /*$(".buy_now").attr("data-amount",totalAmount);
                    $(".buy_now").attr("data-id",product_id);
                    $(".buy_now").attr("data-days",days);
                    $(".buy_now").attr("data-planuuid",planuuid);
                    $( ".buy_now" ).trigger( "click" );*/
                }else{         
                    if(totalAmount !=0 ){
                        if(ttotalAmount !=0 || ptotalAmount !=0){
                            document.paytm_form.submit();
                        }
                    }else{
                        alert('Select atleast one plan');return false;
                    }

                    $.ajax({
                       url: SITEURL + '/getamt',
                       type: 'post',
                       dataType: 'html',
                       data: {
                        Tproduct_id : tproduct_id,
                        tplanuuid : tplanuuid,
                       },
                        beforeSend:function(){
                            $('.ajax-loader').css("visibility", "visible");
                        },
                        success: function (suc_data) {
                            $('.ajax-loader').css("visibility", "hidden");
                            var res = suc_data.split("@@@");
                            totalAmount = res[0];
                                    
                            $(".paytmform").append('<input type="hidden" name="totalAmount" value="'+totalAmount+'"/>');
                            $(".paytmform").append('<input type="hidden" name="tproduct_id" value="'+tproduct_id+'"/>');
                            $(".paytmform").append('<input type="hidden" name="tdays" value="'+res[1]+'"/>');
                            $(".paytmform").append('<input type="hidden" name="ttotalAmount" value="'+totalAmount+'"/>');
                            $(".paytmform").append('<input type="hidden" name="tplanuuid" value="'+tplanuuid+'"/>');
                            document.paytm_form.submit();
                                

                        }
                   });

                }
            }
            
        });


        $('body').on('click', '.modalOpenForWallet', function(e){
            var totalAmount = $(this).data('amount');

            ttotalAmount = $(this).attr("data-amount");
            tproduct_id =  $(this).attr("data-id");
            tdays =  $(this).attr("data-days");
            tplanuuid =  $(this).attr("data-planuuid");
            twalletpointused = $(this).attr("data-walletpointused");
            //$('#myModal').modal('show');
            //alert(twalletpointused);
             if(totalAmount == 0 ){
                if(ttotalAmount == 0 ){
                    alert('Select atleast one plan');return false;
                }
            }


            var user_wallet_points ="{{round(Auth::user()->wallet_point)}}";

            twalletpointused = 0;
            if(user_wallet_points >= totalAmount){
                twalletpointused = 1;
            }

            if(twalletpointused == '1'){
                var bill_info ="{{Billing_Information_Check()}}";
                if(bill_info==0){
                    toastr.warning('Please Fillout All Billing Information!');
                    window.location.href = BillingInformationReDirect;
                    exit();
                }

                $.ajax({
                    url: SITEURL + '/getamt',
                    type: 'post',
                    dataType: 'html',
                    data: {
                        Tproduct_id : tproduct_id,
                        tplanuuid : tplanuuid,
                    },
                    beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success: function (suc_data) {
                        $('.ajax-loader').css("visibility", "hidden");
                        var res = suc_data.split("@@@");
                        totalAmount = res[0];

                        $.ajax({
                            url: SITEURL + '/walletpointused',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                totalAmount : totalAmount,
                                Tproduct_id : tproduct_id,
                                Tdays : res[1],
                                Tamount : totalAmount,
                                Tplanuuid : tplanuuid,
                                Twalletpointused:twalletpointused
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
                    url: SITEURL + '/getamt',
                    type: 'post',
                    dataType: 'html',
                    data: {
                        Tproduct_id : tproduct_id,
                        tplanuuid : tplanuuid,
                    },
                    beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                    },
                    success: function (suc_data) {
                        $('.ajax-loader').css("visibility", "hidden");
                        var res = suc_data.split("@@@");
                        totalAmount = res[0];

                        var user_wallet_point ="{{round(Auth::user()->wallet_point)}}";
                        totalAmount = totalAmount - user_wallet_point;
                        var options = {
                            "key": "{{ $settings['RAZORPAY_KEY'] }}",
                            "amount": (totalAmount*100), // 2000 paise = INR 20
                            "name": "{{ env('APP_NAME') }}",
                            "description": "Payment",
                            "image": "{{ asset('public/frontdesign/assets/img/ojaak_logo.png') }}",
                            "handler": function (response){
                                //console.log(response)
                                if(response != null){
                                    $.ajax({
                                       url: SITEURL + '/packagespaysuccesswithwallet',
                                       type: 'post',
                                       dataType: 'json',
                                       data: {
                                        razorpay_payment_id: response.razorpay_payment_id, 
                                        totalAmount : totalAmount,
                                        Tproduct_id : tproduct_id,
                                        Tdays : res[1],
                                        Tamount : totalAmount,
                                        Tplanuuid : tplanuuid,
                                        Tfullwalletpointused:1
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
                   });


                
                   

            }
            
        });

        $('body').on('click', '.buy_now_paytm', function(e){
            if(totalAmount !=0 ){
                if(ttotalAmount !=0 ){
                    document.paytm_form.submit();
                }
            }else{
                alert('Select atleast one plan')
            }

        });


 });
</script>
@endsection