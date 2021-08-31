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
                <div class="col-md-9 single_billing_outer_wrap profile_right_col">
                    <div class="manage_ads_outer_wrap">
                        <div class="single_billing_title_wrap">
                            <h3>Purchased Packages</h3>
                        </div>
                        <div id="bought_information_scroll_id">
                         @if(isset($plans) && $plans!='' && ! $plans->isEmpty())
                            @foreach($plans as $plan)    
                            <div class="single_billing_product">
                                <div class="row">
                                    <div class="col-lg-8 single_billing_col_left">
                                        <h4>Package: @if($plan->type == '0')
                                                    @if($plan->plan_id == '1')
                                                        Free
                                                    @else
                                                        Paid
                                                    @endif
                                                @elseif($plan->type == '1')
                                                    Featured 
                                                @elseif($plan->type == '2') 
                                                    Toplist
                                                @else
                                                    Pearl
                                                @endif 


                                                Ad for  {{ceil((strtotime($plan->expire_date) - strtotime($plan->created_at)) / (60 * 60 * 24))}} Days</h4>
                                        <div class="billing_info_wrap">
                                            <p>
                                                <strong>Date of Activation:</strong>
                                                <span>{{date("d/m/Y",strtotime($plan->created_at))}}</span>
                                            </p>
                                            <p>
                                                <strong>Expire on:</strong>
                                                <span>{{date("d/m/Y",strtotime($plan->expire_date))}}</span>
                                            </p>
                                            <p>
                                                <strong>Order ID:</strong>
                                                <span>#<?php echo str_pad($plan->id, 7, "0", STR_PAD_LEFT); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 single_billing_col_right">
                                        <div class="single_billing_available_wrap">
                                            <span>{{$plan->ads_count}}</span>
                                            <h4>Available</h4>
                                        </div>
                                        <div class="use_purchase_wrap">
                                            <span class="used_inner_wrap">{{$plan->ads_limit - $plan->ads_count}} used</span>
                                            <span>{{$plan->ads_limit}} Purchased</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 1st one end -->
                            @endforeach
                            @else
                                <div class="single_billing_product">
                                    <h3>No Purchase Packages</h3>
                                </div>
                            @endif
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
    $(window).on("load",function(){
            $("#active_content, #sold_content").mCustomScrollbar({
                autoHideScrollbar: false,
                alwaysShowScrollbar: 1,
                theme: "dark",
            });
        });
    
</script>

@endsection