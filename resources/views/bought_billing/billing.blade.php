@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
@endsection
@section('content')
<!-- Page Title -->
    <div class="container-fluid pl-0 pr-0 page_title_bg_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-md-6">
                    <div class="profile_breadcum_wrap ads_managemnet_title_wrap">
                        <nav aria-label="breadcrumb" class="page_title_inner_wrap">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Bought Package</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="refer_code_wrap">
                        <h2>
                            <strong>Referral code :</strong>
                            <span>OJAAK-{{Auth::user()->referral_code}}</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Profile -->
    <div class="container-fluid pl-0 pr-0 profile_edit_row_outer_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-md-3 pl-0 profile_left_col">
                    <div class="profile_edit_inner_wrap">
                        <div class="profile_edit_wrap profile_water_marker_outer_wrap">
                            <div class="profile_img_wrap">
                                <img id="profilepreview" src="{{asset('public/uploads/profile/original/'.Auth()->user()->photo)}}" alt="avatar" title="avatar">
                            </div>
                            <?php $badge=verified_profile(Auth::user()->id)?>
                            @if($badge == '1')
                                <div class="profile_water_marker">
                                    <img src="{{asset('public/frontdesign/assets/img/ojaak_watermark.png')}}">
                                </div>
                            @endif
                        </div>
                        <div class="profile_name_wrap">
                            <h2 class="pt-1">{{Auth::user()->name}}</h2>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                  <span class="sr-only">70% Complete</span>
                                </div>
                            </div>
                            <p>{{number_format((float)Auth::user()->wallet_point, 2, '.', '')}} points available</p>
                            <div class="common_btn_wrap">
                                <a href="{{route('usertransaction')}}#redeem_content">Redeem Earnings</a>
                            </div>
                        </div>
                    </div>
                    <div class="profile_nav_wrap">
                        @include('includeContentPage.billingmenu')
                    </div>
                </div>
                <div class="col-md-9 profile_right_col">
                    <form id="billing_form" action="{{route('ads.billing.update')}}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="billing_outer_bg_wrap">
                        <div class="row">
                            <div class="col-md-6 pl-0">
                                <h4>Billing Information</h4>
                            </div>
                            <div class="col-md-6 pr-0 billing_info_btn_wrap">
                                <div class="common_btn_wrap">
                                    <!-- <a href="javascript:void(0);" >Save Changes</a> -->
                                     <button type="submit" id="billing_form_save">Save Changes</button>
                                     <!--<button type="submit" id="billing_form_save" disabled="">Save Changes</button>-->
                                </div>
                            </div>
                        </div>  
                        <div class="billing_form_outer_wrap">
                            @if (isset($errors) and $errors->any())
                                <div class="alert alert-danger m-2">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><strong>{{ __('Oops ! An error has occurred.') }}</strong></h5>
                                    <ul class="list list-check">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                                <div class="row">
                                    <div class="pl-0 col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Customer Name</label>
                                            <input type="text" name="Customername"  value="{{ (($billinfo->username!='')?$billinfo->username:ucwords(Auth::user()->name)) }}"  class="form-control btnenable" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Email Address</label>
                                            <input type="email" value="{{ (($billinfo->email!='')?$billinfo->email:ucwords(Auth::user()->email)) }}"  name="Emailaddress"class="form-control btnenable" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="pl-0 col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Bussiness Name</label>
                                            <input type="text" name="Businessname"  value="{{$billinfo->businessname}}"  class="form-control btnenable" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>GST No</label>
                                            <input type="text" value="{{$billinfo->gst}}"  name="GstNo" class="form-control btnenable" >
                                        </div>
                                    </div>
                                </div>
                                <h4>Billing Address</h4>
                                <div class="row">
                                    <div class="pl-0 col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Address Line 1</label>
                                            <input type="text" value="{{$billinfo->addr1}}" name="Address1" class="form-control btnenable" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Address Line 2</label>
                                            <input type="text" value="{{$billinfo->addr2}}" name="Address2" class="form-control btnenable" >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="pl-0 col-md-6">
                                        <div class="fields_common_wrap form-group common_select_option contact_form_select_btn_wrap">
                                            <label>State</label>
                                            <select class="form-control btnenable" name="State" id="state_form" required> <option value="" hidden>Select State</option>
                                            @foreach($states as $ids => $state)
                                                @if($billinfo->state== $state->name )
                                                    <option value="{{$state->name}}" data-id="{{$state->id}}" selected="">{{$state->name}}</option>
                                                @else
                                                    <option value="{{$state->name}}" data-id="{{$state->id}}" >{{$state->name}}</option>
                                                @endif
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        
                                        <div class="fields_common_wrap form-group common_select_option contact_form_select_btn_wrap">
                                            <label>City</label>
                                            <select class="form-control btnenable" name="City" id="city_form" required> 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>  
    </div>
    
@endsection
@section('scripts')
<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>

<script>
 $(document).ready(function(){
        //$('.save').hide();
        /*$("select" ).on('focus change',function() {
          $('.save').show();
        });*/
        $( ".btnenable" ).on('change',function() {
            $('#billing_form_save').removeAttr("disabled");
        });

        $("input").keyup(function(){
          $('#billing_form_save').removeAttr("disabled");
        });
    });   
</script>

<script type="text/javascript">
  // $(document).on('click', '#billing_form_save', function(){
  //       $('#billing_form').submit();
  //   });  
    $( "#state_form" ).change(function() {
            var state_form_value = $('#state_form option:selected').attr('data-id');
            //alert(state_form_value);
            if($(this).val() !=0){
                $('#city_form').html('');
                $.ajax({
                    type: "post",
                    url: "<?php echo url('getcities'); ?>",
                    data: "stateid="+state_form_value,
                    success: function(data){ 
                    let datacities = JSON.parse(data)
                    jQuery.each(datacities, function(key, value) {
                      $('#city_form').append("<option value="+value+">"+value+"</option>");
                    });
                       
                    }
                });
            }
    });

    var stateid=$('#state_form option:selected').attr('data-id');
    
    var cityid="{{$billinfo->city}}";
    if(stateid !=0){
        $.ajax({
            type: "post",
            url: "<?php echo url('getcities'); ?>",
            data: "stateid="+stateid,
            success: function(data){ 
            let datacities = JSON.parse(data)
            jQuery.each(datacities, function(key, value) {

              if(value==cityid){
                $('#city_form').append("<option value="+value+" selected >"+value+"</option>");
                }else{
                $('#city_form').append("<option value="+value+">"+value+"</option>");
              }  
              
            });
               
            }
        });
    }
</script>

@endsection