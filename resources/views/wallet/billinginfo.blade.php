@extends('layouts.home')
@section('styles')

<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
<style type="text/css">
    .common_btn_wrap a .blockbtn {
    background-color: #62040f;
}
</style>
@endsection
@section('content')
<?php 
    $segment1 = Request::segment('1');
    $segment2 = Request::segment('2');
    $segment3 = Request::segment('3');
?>

<!-- Page Title -->
    <div class="container-fluid pl-0 pr-0 page_title_new_bg_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-md-6 pl-0">
                    <div class="profile_breadcum_new_wrap">
                        <nav aria-label="breadcrumb" class="page_title_inner_new_wrap">
                            <ol class="breadcrumb">
                               <!--  <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li> -->
                                <!-- <li class="breadcrumb-item active" aria-current="page"><a href="#">Change Password</a></li> -->
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Profile -->
    <div class="container-fluid pl-0 pr-0 profile_edit_row_outer_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-md-3 pl-0 profile_left_new_col_wrap">
                    <div class="profile_edit_inner_wrap">
                        <div class="profile_edit_wrap profile_water_marker_outer_wrap">
                            <div class="profile_img_wrap">
                                <img src="{{asset('public/uploads/profile/mid/'.$userdetails->photo)}}" alt="avatar" title="avatar">
                            </div>
                            <?php $badge=verified_profile($userdetails->id)?>
                            @if($badge == '1')
                                <div class="profile_water_marker">
                                    <img src="{{asset('public/frontdesign/assets/img/ojaak_watermark.png')}}">
                                </div>
                            @endif
                        </div>
                        <div class="profile_name_wrap profile_info_name_wrap">
                            <h2>{{get_name($userdetails->id)}}</h2>
                            
                            <div class="share_profile_link_outer_wrap">
                                @if($segment2 == 'info')
                                <a href="javascript:void(0)" onclick="setClipboard('{{url('profile/info')}}/{{$userdetails->uuid}}')">Share Profile Link</a>
                                 @endif
                            </div>
                           
                        </div>
                    </div>
                    <div class="profile_nav_wrap">
                        @include('includeContentPage.profilemenuforadmin')
                    </div>

                    <!-- Category Model Popup -->
                    <div id="share_profile_link">
                        Link is copied to clipboard 
                    </div>
                    <div class="profile_nav_wrap profile_info_listing_outer_wrap">
                        <h3>Linked Accounts</h3>
                        @if($userdetails->google_id)
                        <div class="listing_profile_info_wrap">
                            <p>Google</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                        @if($userdetails->facebook_id)
                        <div class="listing_profile_info_wrap">
                            <p>Facebook</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                        @if($userdetails->phone_verified_at)
                        <div class="listing_profile_info_wrap">
                            <p>Phone Number</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                        @if($userdetails->email_verified_at)
                        <div class="listing_profile_info_wrap">
                            <p>Email</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-9 pl-0 profile_right_new_col_wrap mywallet_table_outer_wrap">
                    
                    <div class="billing_outer_bg_wrap admin_view_user_wrap">
                        <div class="row">
                            <div class="col-md-6 pl-0">
                                <h4>Billing Information</h4>
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
                                            <input type="text" name="Customername"  value="{{ (isset($billinfo->username)?$billinfo->username:ucwords(Auth::user()->name)) }}"  class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Email Address</label>
                                            <input type="email" value="{{ (isset($billinfo->email)?$billinfo->email:ucwords(Auth::user()->email)) }}"  name="Emailaddress"class="form-control" disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="pl-0 col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Bussiness Name</label>
                                            <input type="text" name="Businessname"  value="{{isset($billinfo->businessname)?$billinfo->businessname:''}}"  class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>GST No</label>
                                            <input type="text" value="{{isset($billinfo->gst)?$billinfo->gst:''}}"  name="GstNo" class="form-control" disabled="">
                                        </div>
                                    </div>
                                </div>
                                <h4>Billing Address</h4>
                                <div class="row">
                                    <div class="pl-0 col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Address Line 1</label>
                                            <input type="text" value="{{isset($billinfo->addr1)?$billinfo->addr1:''}}" name="Address1" class="form-control" disabled="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fields_common_wrap form-group">
                                            <label>Address Line 2</label>
                                            <input type="text" value="{{isset($billinfo->addr2)?$billinfo->addr2:''}}" name="Address2" class="form-control" disabled="">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="pl-0 col-md-6">
                                        <div class="fields_common_wrap form-group common_select_option contact_form_select_btn_wrap">
                                            <label>State</label>
                                            <select class="form-control" name="State" id="state_form" required disabled=""> <option value="" hidden>Select State</option>
                                            @foreach($states as $ids => $state)
                                                @if(isset($billinfo->state) && $billinfo->state== $state->name )
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
                                            <select class="form-control" name="City" id="city_form" required disabled=""> 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
            
                </div>

            </div>
        </div>  
    </div>
@endsection
@section('scripts')

</script>
@endsection