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
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Mail Updation</a></li>
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
                                <a href="{{route('usertransaction')}}">Redeem Earnings</a>
                            </div>
                        </div>
                    </div>
                    <div class="profile_nav_wrap">
                        @include('includeContentPage.profilemenu')
                    </div>
                </div>
                <div class="col-md-9 profile_right_col">
                    <div class="profile_form_fields_outer_wrap">
                        <form role="form" action="{{route('profile.mail.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    @if(Auth::user()->email !="")
                                        <div class="fields_common_wrap form-group">
                                            <label>Current Mail Id Is : {{ Auth::user()->email }}</label>
                                        </div>
                                    @endif
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group" id="mail-form" style="display:none;">
                                        <label>Enter Mail  Address</label>
                                        <input type="text" type="email" name="mail" required value="" required class="form-control">
                                    </div>
                                </div>  
                            </div>
                            <div class="row ">
                                <div class="col-md-4 common_btn_wrap contact_submit_btn_wrap">
                                    <button id="add" type="button" class="" >Edit</button>
                                    <button type="submit" id="update"class="">Update Mail</button>
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
        $("#mail-form").hide();
        $("#update").hide();
        $("#add").click(function(e){
            $("#mail-form").show();
            $("#update").show();
            $("#add").hide();
            e.preventDefault();
        });
    });
       
</script>

@endsection