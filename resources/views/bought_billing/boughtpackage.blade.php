<?php

use App\Ads;
use App\Parent_categories;
?>
@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
@endsection
@section('content')
<!-- Page Title -->
<div class="container-fluid pl-0 pr-0 page_title_new_bg_wrap">
    <div class="box-size">
        <div class="row">
            <div class="col-md-6 pl-0">
                <div class="profile_breadcum_new_wrap">
                    <nav aria-label="breadcrumb" class="page_title_inner_new_wrap">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">My Profile</a></li>
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
            <div class="col-md-3 pl-0 profile_left_new_col_wrap">
                <div class="profile_edit_inner_wrap">
                    <div class="profile_edit_wrap profile_water_marker_outer_wrap">
                        <div class="profile_img_wrap">
                            <img id="profilepreview" src="{{asset('public/uploads/profile/original/'.Auth()->user()->photo)}}" alt="avatar" title="avatar">
                        </div>
                        <?php $badge = verified_profile(Auth::user()->id) ?>
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
            <div class="col-md-9 single_billing_outer_wrap profile_right_new_col_wrap">
                <div class="manage_ads_outer_wrap">
                    <!-- <div class="single_billing_title_wrap">
                        <h3>Purchased Packages</h3>
                    </div> -->
                    <div class="wallet_nav_outer_wrap">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="active_ads_content_tab" data-toggle="tab" href="#active_content" role="tab" aria-controls="active_content" aria-selected="true">Active Plans</a>
                                <a class="nav-item nav-link" id="sold_ads_content_tab" data-toggle="tab" href="#sold_content" role="tab" aria-controls="sold_content" aria-selected="false">Expired Plans</a>
                            </div>
                        </nav>
                    </div>

                    <div class="tab-content  px-3 px-sm-0" id="ads_management_scroll_id">
                        <div class="tab-pane fade show active" id="active_content" role="tabpanel" aria-labelledby="active_ads_content_tab">
                            @if(isset($activeplans) && $activeplans!='' && ! $activeplans->isEmpty())
                            @foreach($activeplans as $plan) 
                            <?php
                            $getCategName = '';
                            $getAdsCateg = Ads::where(['purchase_id' => $plan1->id])->first();
                            $categID = isset($getAdsCateg->categories) ? $getAdsCateg->categories : '';
                            if ($categID != '') {
                                $getCategData = Parent_categories::find($categID);
                                $getCategName = isset($getCategData->name) ? ucfirst($getCategData->name) : '';
                            }
                            ?>
                            <div class="single_billing_product">
                                <div class="row">
                                    <div class="col-lg-8 single_billing_col_left">
                                        <h4>Package: @if($plan->type == '0')
                                            @if($plan->plan_id == '1')
                                            Free
                                            @else
                                            Premium
                                            @endif
                                            @elseif($plan->type == '1')
                                            Platimum 
                                            @else 
                                            Featured
                                            @endif 


                                            Ad for  {{ceil((strtotime($plan->expire_date) - strtotime($plan->created_at)) / (60 * 60 * 24))}} Days</h4>
                                        <div class="billing_info_wrap">
                                            @if(isset($getCategName) && $getCategName!='')
                                            <p>
                                                <strong>Category:</strong>
                                                <span>{{$getCategName}}</span>
                                            </p>
                                            @endif
                                            <p>
                                                <strong>Date of Activation:</strong>
                                                <span>{{date("d/m/Y H:s A",strtotime($plan->created_at))}}</span>
                                            </p>
                                            <p>
                                                <strong>Expire on:</strong>
                                                <span>{{date("d/m/Y H:s A",strtotime($plan->expire_date))}}</span>
                                            </p>
                                            <p>
                                                <strong>Order ID:</strong>
                                                <span>#OPID{{$plan->id}}</span>
                                                <!-- <span>#<?php echo str_pad($plan->id, 7, "0", STR_PAD_LEFT); ?></span> -->
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 single_billing_col_right">
                                        <div class="single_billing_available_wrap">
                                            <span>{{$plan->ads_count}}</span>
                                            <h4>Available</h4>
                                        </div>
                                        <div class="use_purchase_wrap">
                                            <span class="used_inner_wrap " style="cursor: pointer;" @if($plan->type == '0') onclick="viewadeslist('{{$plan->id}}','{{$plan->plan_id}}','0')" @elseif($plan->type == '1') onclick="viewadeslist('{{$plan->id}}','{{$plan->plan_id}}','1')" @else onclick="viewadeslist('{{$plan->id}}','{{$plan->plan_id}}','2')"  @endif >{{$plan->ads_limit - $plan->ads_count}} used</span>
                                            <span>{{$plan->ads_limit}} Purchased</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 1st one end -->
                            @endforeach
                            @else
                            <div class="single_billing_product">
                                <h3>No Active Plans</h3>
                            </div>
                            @endif

                        </div>
                        <!-- end of active ads -->
                        <div class="tab-pane fade" id="sold_content" role="tabpanel" aria-labelledby="sold_ads_content_tab">

                            @if(isset($expiredplans) && $expiredplans!='' && ! $expiredplans->isEmpty())
                            @foreach($expiredplans as $plan1)   
                            <?php
                            $getCategName = '';
                            $getAdsCateg = Ads::where(['purchase_id' => $plan1->id])->first();
                            $categID = isset($getAdsCateg->categories) ? $getAdsCateg->categories : '';
                            if ($categID != '') {
                                $getCategData = Parent_categories::find($categID);
                                $getCategName = isset($getCategData->name) ? ucfirst($getCategData->name) : '';
                            }
                            ?>
                            <div class="single_billing_product">
                                <div class="row">
                                    <div class="col-lg-8 single_billing_col_left">
                                        <h4>Package: @if($plan1->type == '0')
                                            @if($plan1->plan_id == '1')
                                            Free
                                            @else
                                            Premium
                                            @endif
                                            @elseif($plan1->type == '1')
                                            Platimum 
                                            @else 
                                            Featured
                                            @endif 


                                            Ad for  {{ceil((strtotime($plan1->expire_date) - strtotime($plan1->created_at)) / (60 * 60 * 24))}} Days</h4>
                                        <div class="billing_info_wrap">
                                            @if(isset($getCategName) && $getCategName!='')
                                            <p>
                                                <strong>Category:</strong>
                                                <span>{{$getCategName}}</span>
                                            </p>
                                            @endif
                                            <p>
                                                <strong>Date of Activation:</strong>
                                                <span>{{date("d/m/Y H:s A",strtotime($plan1->created_at))}}</span>
                                            </p>
                                            <p>
                                                <strong>Expire on:</strong>
                                                <span>{{date("d/m/Y H:s A",strtotime($plan1->expire_date))}}</span>
                                            </p>
                                            <p>
                                                <strong>Order ID:</strong>
                                                <span>#OPID{{$plan1->id}}</span>
                                                <!-- <span>#<?php echo str_pad($plan1->id, 7, "0", STR_PAD_LEFT); ?></span> -->
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 single_billing_col_right">
                                        <div class="single_billing_available_wrap">
                                            <span>{{$plan1->ads_count}}</span>
                                            <h4>Expired</h4>
                                        </div>
                                        <div class="use_purchase_wrap">
                                            <span class="used_inner_wrap " style="cursor: pointer;" @if($plan1->type == '0') onclick="viewadeslist('{{$plan1->id}}','{{$plan1->plan_id}}','0')" @elseif($plan1->type == '1') onclick="viewadeslist('{{$plan1->id}}','{{$plan1->plan_id}}','1')" @else onclick="viewadeslist('{{$plan1->id}}','{{$plan1->plan_id}}','2')"  @endif >{{$plan1->ads_limit - $plan1->ads_count}} used</span>
                                            <span>{{$plan1->ads_limit}} Purchased</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 1st one end -->
                            @endforeach
                            @else
                            <div class="single_billing_product">
                                <h3>No Expired Plans</h3>
                            </div>
                            @endif

                        </div>
                        <!-- end of sold ads -->
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
<!-- Button to Open the Modal -->


<!-- The Modal -->
<div class="modal fade" id="usedadsmodal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Used Plan Ads</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <input type="hidden" id="usedadsmodalid" value="0"/>
                <ul class="list-group plan_id_list">

                </ul>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>   
@endsection
@section('scripts')
<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
<script type="text/javascript">
$(window).on("load", function () {
    $("#active_content, #sold_content").mCustomScrollbar({
        autoHideScrollbar: false,
        alwaysShowScrollbar: 1,
        theme: "dark",
    });

});
function viewadeslist(purchaseid, planid, representdetails) {
    var form_data = new FormData();
    form_data.append("id", purchaseid);
    form_data.append("planid", planid);
    form_data.append("representdetails", representdetails);
    form_data.append("_token", "{{ csrf_token() }}");
    $.ajax({
        url: "<?php echo url('planadslist/view'); ?>",
        method: "POST",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('.ajax-loader').css("visibility", "visible");
        },
        success: function (data)
        {
            $('.ajax-loader').css("visibility", "hidden");
            $(".plan_id_list").html(data);
            $("#usedadsmodal").modal('show');
        }
    });



}
</script>
@endsection