@extends('layouts.home')

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
                                <li class="breadcrumb-item" aria-current="page"><a href="{{url('/profile')}}">Profile</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Mobile</a></li>
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
                        @include('includeContentPage.profilemenu')
                    </div>
                </div>
                <div class="col-md-9 profile_right_col">
                    <div class="profile_form_fields_outer_wrap">
                        <form role="form" action="{{ route('profile.mobile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    @if(Auth::user()->phone_no !="")
                                        <div class="fields_common_wrap form-group">
                                            <label>Current Mobile Number Is: {{ Auth::user()->phone_no }}</label>
                                        </div>
                                    @else
                                        <div class="fields_common_wrap form-group">
                                            <label>No Mobile Number</label>
                                        </div>
                                    @endif
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group" id="mobile-form" style="display:none;">
                                        <label>Enter Mobile No</label>
                                        <input type="text" id="mobile"class="form-control" name="mobile" pattern="[0-9]{10}" title="Enter 10 digit" required>
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group" id="otp-form"  style="display:none;">
                                        <label>Enter OTP</label>
                                        <input type="text" id="otp" name="otp" class="form-control" pattern="[0-9]{6}" title="ENTER 6 DIGIT OTP" required>
                                    </div>
                                </div>  
                            </div>
                            <div class="row ">
                                <div class="col-md-4 common_btn_wrap contact_submit_btn_wrap">
                                   <button id="sendotpbtn" type="button" class=""style="display:none;">Send OTP</button>
                                    <button id="add" type="button" class="" >Edit</button>
                                    <button  id="submit"type="submit" class="" style="display:none;">Update</button>
                                </div>  
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>  
    </div>
    
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $("#mobile-form").hide();
        $("#submit").hide();
        $("#sendotpbtn").hide();
        
        $("#sendotpbtn").click(function(e){
            e.preventDefault();
            if($("#mobile").val()!=''){
                $("#otp-form").show();
                $("#submit").show();
                $("#sendotpbtn").hide();
            }else{
                swal("Mobile Number Empty!");
            }
        });
        $("#add").click(function(e){
            $("#mobile-form").show();
            $("#sendotpbtn").show();
            $("#add").hide();
            e.preventDefault();
        });
    });
       
</script>

@endsection