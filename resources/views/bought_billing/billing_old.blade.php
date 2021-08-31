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
                    <div class="billing_outer_bg_wrap">
                        <h3>Billing Information</h3>

                        <div class="billing_form_outer_wrap">
                            @if (isset($errors) and $errors->any())
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><strong>{{ __('Oops ! An error has occurred.') }}</strong></h5>
                                    <ul class="list list-check">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form role="form" action="{{route('ads.billing.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                                <h4>Customer Information</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="common_select_option post_selection_field_wrap form-group">
                                            <label>Do you have a GST Number?</label>
                                            <select class="form-control" name="gstquestion" id="gstquestion">
                                                <option value="" hidden>Select Option</option>
                                                <option value="yes" @if($billinfo->gstquestion=='yes') Selected @endif >Yes</option>
                                                <option value="no" @if($billinfo->gstquestion=='no') Selected @endif >No</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Email</label>
                                             <input type="email" value="{{ (($billinfo->email!='')?$billinfo->email:ucwords(Auth::user()->email)) }}"  name="email"class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Customer Named</label>
                                             <input type="text" name="customername"  value="{{ (($billinfo->username!='')?$billinfo->username:ucwords(Auth::user()->name)) }}"  class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Business Name</label>
                                            <input type="text" name="business"  value="{{$billinfo->businessname}}"  class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="gst_wrap">
                                        <div class="fields_common_wrap form-group">
                                            <label>GST Number</label>
                                            <input type="text" value="{{$billinfo->gst}}"  name="gst" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <h4>Customer Address</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Address Line 1</label>
                                            <input type="text" value="{{$billinfo->addr1}}" name="address1" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Address Line 2</label>
                                            <input type="text" value="{{$billinfo->addr2}}"  name="address2" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>State</label>
                                            <input type="text" value="{{$billinfo->state}}" name="state" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>City</label>
                                            <input type="text" value="{{$billinfo->city}}" name="city" class="form-control" >
                                        </div>
                                    </div>
                                </div> 
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                       <div class=" common_btn_wrap contact_submit_btn_wrap">
                                            <button type="submit" class="">Save Changes</button>
                                        </div> 
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    
@endsection
@section('scripts')
<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
<script type="text/javascript">
    if ($('#active_content').length > 0){
        $(window).on("load",function(){
            $("#active_content, #sold_content").mCustomScrollbar({
                autoHideScrollbar: true,
                alwaysShowScrollbar: 1,
                theme: "minimal",
            });
        });
    }
    $(document).ready(function(){
        $("select#gstquestion").change(function(){
            var gstquestion = $(this).children("option:selected").val();
            if(gstquestion=="yes"){
               $("#gst_wrap").show();
            }
            if(gstquestion=="no" || gstquestion==""){
               $("#gst_wrap").hide();
            }
        });

        var gstquestions =$("#gstquestion").val();
            if(gstquestions=="yes"){
               $("#gst_wrap").show();
            }
            if(gstquestions=="no" || gstquestions==""){
               $("#gst_wrap").hide();
            }
    });
</script>

@endsection