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
                <div class="col-md-9 pl-0 profile_right_new_col_wrap mywallet_table_outer_wrap ">
                    <div class="profile_form_fields_outer_wrap billing_outer_bg_wrap admin_view_user_wrap">
                        <div class="table-responsive">
                            @if(!$block_user->isEmpty())
                                <table id="wallet_table_wrap" class="table nowrap table-responsive-lg">
                                    <thead>
                                        <tr>
                                            <th scope="col">SL.No</th>
                                            <th scope="col">User Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($block_user as $key => $blockuser)
                                        <tr>
                                            <td scope="row">{{++$key}}</td>
                                            <td>{{ (($blockuser->block_user_id !='')?get_username($blockuser->block_user_id):"-") }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                    No Blocked User!
                                @endif
                        </div>
                    </div>
            
                </div>

            </div>
        </div>  
    </div>
@endsection
@section('scripts')
@endsection