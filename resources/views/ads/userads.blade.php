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
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Ads Management</a></li>
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
                <div class="col-md-9 profile_right_new_col_wrap">
                    <div class="ads_manage_search_filter_wrap">
                        <input type="text" id="ads_mana_search_id" onkeyup="search_ads_filter_wrap()" placeholder="Search by Ad Title" title="Search Ad">
                    </div>
                    <div class="manage_ads_outer_wrap">
                        <div class="wallet_nav_outer_wrap">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="active_ads_content_tab" data-toggle="tab" href="#active_content" role="tab" aria-controls="active_content" aria-selected="true">Active Ads</a>
                                    <a class="nav-item nav-link" id="sold_ads_content_tab" data-toggle="tab" href="#sold_content" role="tab" aria-controls="sold_content" aria-selected="false">Sold Ads</a>
                                    <a class="nav-item nav-link" id="favourite_ads_content_tab" data-toggle="tab" href="#favourite_content" role="tab" aria-controls="favourite_content" aria-selected="false">Favourite Ads</a>
                                    <a class="nav-item nav-link" id="inactive_ads_content_tab" data-toggle="tab" href="#inactive_content" role="tab" aria-controls="inactive_content" aria-selected="false">Inactive Ads</a>
                                    <a class="nav-item nav-link" id="promote_ads_content_tab" data-toggle="tab" href="#promote_content" role="tab" aria-controls="promote_content" aria-selected="false">Promote Ads</a>
                                </div>
                            </nav>
                        </div>
                        
                        <div class="tab-content py-3 px-3 px-sm-0" id="ads_management_scroll_id">
                            <div class="tab-pane fade show active" id="active_content" role="tabpanel" aria-labelledby="active_ads_content_tab">
                               
                            </div>
                            <!-- end of active ads -->
                            <div class="tab-pane fade" id="sold_content" role="tabpanel" aria-labelledby="sold_ads_content_tab">
                                
                            </div>
                            <!-- end of sold ads -->
                            <div class="tab-pane fade" id="favourite_content" role="tabpanel" aria-labelledby="favourite_ads_content_tab">
                                
                            </div>
                            <!-- end of favourite ads -->
                            <div class="tab-pane fade" id="inactive_content" role="tabpanel" aria-labelledby="inactive_ads_content_tab">
                                
                            </div>
                            <!-- end of inactive ads -->
                            <div class="tab-pane fade" id="promote_content" role="tabpanel" aria-labelledby="promote_ads_content_tab">
                               <div class="ads_management_promote_outer_wrap">
                                    <form method="post" class="needs-validation" action="{{route('ads.user.promote.save')}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col">
                                                <div class="common_select_option post_selection_field_wrap form-group">
                                                    <label for="ads">Select Ads</label>
                                                    <select class="form-control" id="ads" name="ads" style="width: 100% !important">
                                                        <option value="" hidden>Select Ads</option>
                                                        @foreach($ads as $ad)
                                                        <option value="{{$ad->uuid}}">{{$ad->title}}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('ads')
                                                        <span class="help-block" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="common_select_option post_selection_field_wrap form-group">
                                                    <label for="plan">Select Plans</label>
                                                    <select class="form-control" id="plan" name="plan" style="width: 100% !important">
                                                        <option value="" hidden>Select Plans</option>
                                                        @foreach($Plans as $plan)
                                                        <option value="{{$plan->id}}">
                                                            @if($plan->type==1)
                                                                Platinum No {{$plan->feature_plan_id}}
                                                            @else
                                                                Featured 
                                                            @endif
                                                             - {{$plan->expire_date}}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('plan')
                                                        <span class="help-block" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 common_btn_wrap contact_submit_btn_wrap">
                                                <button type="submit" class="btn btn-primary">Promote</button>
                                            </div>
                                        </div>
                                    </form>
                                    <hr class="mx-2">
                                    <div class="row">
                                        <div class="col-lg-6 ads_plan_common_wrap">
                                            <div class="border text-center p-2">
                                                <h3>Platinum Ads</h3>
                                            </div>
                                            <div class="ads_plan_listing" id="feature_ads_plan_listing"><?php $i=0;?>
                                                @foreach($feature as $fe)
                                                    <?php ++$i;?>
                                                    <p class="">Ads Name : {{ucwords(get_adsname($fe->ads_id))}}</p>
                                                    <p class="">Ads Position: {{$fe->id}}</p> 
                                                    <p class=""> Expire Date : {{date("j M Y , h:i A", strtotime($fe->expire_date))}}</p>
                                                    <hr>              
                                                @endforeach
                                                @if($i==0)
                                                    <p class=" ">No Platinum Ads</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6 ads_plan_common_wrap">
                                            <div class="border text-center p-2">
                                                <h3>Featured Ads</h3>
                                            </div>
                                            <div class="ads_plan_listing" id="top_ads_plan_listing">
                                                <?php $i=0;?>
                                                @foreach($top as $top)
                                                <?php ++$i;?>
                                                    <p class="">Ads Name : {{ucwords(get_adsname($top->ads_id))}}</p><p class=" "> Expire Date : {{date("j M Y , h:i A", strtotime($top->expire_date))}}</p> 
                                                     <hr>            
                                                @endforeach
                                                @if($i==0)
                                                    <p class=" ">No Featured Ads</p>
                                                @endif
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <!-- end of inactive ads -->
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
        
@endsection
@section('scripts')
<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
<script>
    $( window ).ready(function() {
    setTimeout(function(){ 
            activeads();
            soldads();
            inactiveads();
            favads();

        }, 1000);
    });
    function activeads(){
        $.ajax({
                url: "<?php echo url('user/ads/active/item'); ?>",
                type: "get",
                contentType: false, 
                processData: false, 
                success: function(data){
                    if(data.trim() == '' || data == ''){
                        $('#active_content #mCSB_1_container').html('<p class="px-2">No Active Ads</p>');
                    }else{
                        $('#active_content #mCSB_1_container').html(data);
                    }
                    
                }
            });
    }
    function soldads(){
        $.ajax({
                url: "<?php echo url('/user/ads/sold/item'); ?>",
                type: "get",
                contentType: false, 
                processData: false, 
                success: function(data){
                    if(data.trim() == '' || data == '' ){
                        $('#sold_content #mCSB_2_container').html('<p class="px-2">No Sold Ads</p>');
                    }else{
                        $('#sold_content #mCSB_2_container').html(data);
                    }
                }
            });
    }
    function inactiveads(){
        $.ajax({
                url: "<?php echo url('/user/ads/inactive/item'); ?>",
                type: "get",
                contentType: false, 
                processData: false, 
                success: function(data){  
                    if(data.trim() == '' || data == ''){
                        $('#inactive_content #mCSB_4_container').html('<p class="px-2">No Inactive Ads</p>');
                    }else{
                        $('#inactive_content #mCSB_4_container').html(data);
                    }
                }
            });
    }
    function favads(){
        $.ajax({
                url: "<?php echo url('/user/ads/favourite/item'); ?>",
                type: "get",
                contentType: false, 
                processData: false, 
                success: function(data){
                    if(data.trim() == '' || data == ''){
                        $('#favourite_content #mCSB_3_container').html('<p class="px-2">No Favourite Ads</p>');
                    }else{
                        $('#favourite_content #mCSB_3_container').html(data);
                    }  
                    
                }
            });
    }


    function deleteadsbtn(id){
        var fd = new FormData(); 
        var _token = "{{ csrf_token() }}";
        var adsid = id;

        fd.append('adsid', adsid); 
        fd.append('_token', _token);  
        swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {
            $('.ajax-loader').css("visibility", "visible");
            $.ajax({
                url: "<?php echo url('/user/ads/free/delete'); ?>",
                type: "post",
                data:fd,
                contentType: false, 
                processData: false, 
                success: function(data1){
                    var response=data1.trim();
                    if(response=='1'){
                        setTimeout(function(){ 
                            $('.ajax-loader').css("visibility", "hidden");
                            activeads();
                            soldads();
                            inactiveads();
                            favads();
                        }, 1000);
                    } 
                }
            });
          }
        });
    }
    function soldbtn(id){
        var fd = new FormData(); 
        var _token = "{{ csrf_token() }}";
        var adsid = id;

        fd.append('adsid', adsid); 
        fd.append('_token', _token);  
        swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {
            $('.ajax-loader').css("visibility", "visible");
            $.ajax({
                url: "<?php echo url('/user/ads/all/status/'); ?>",
                type: "post",
                data:fd,
                contentType: false, 
                processData: false, 
                success: function(data1){
                    var response=data1.trim();
                    if(response=='1'){
                        setTimeout(function(){ 
                            $('.ajax-loader').css("visibility", "hidden");
                            activeads();
                            soldads();
                            inactiveads();
                            favads();
                        }, 1000);
                    } 
                }
            });
          }
        });
    }

    function republish(id){
        var fd = new FormData(); 
        var _token = "{{ csrf_token() }}";
        var adsid = id;

        fd.append('adsid', adsid); 
        fd.append('_token', _token);  
        swal({
        title: "Are you sure, do you want to republish?",
        text: "This ad will consider as Free ad!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        }).then((willDelete) => {
          if (willDelete) {
            $('.ajax-loader').css("visibility", "visible");
            $.ajax({
                url: "<?php echo url('/user/ads/republish/'); ?>",
                type: "post",
                data:fd,
                contentType: false, 
                processData: false, 
                success: function(data1){
                    var response=data1.trim();
                    if(response=='1'){
                        setTimeout(function(){ 
                            $('.ajax-loader').css("visibility", "hidden");
                            activeads();
                            soldads();
                            inactiveads();
                            favads();
                        }, 1000);
                    } 
                }
            });
          }
        });
    }
        /*function sold(id){
            
            swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                document.getElementById('sold-'+id).submit();
                
              } else {
                swal("Your file is safe!");
              }
            });
        }*/
        function inactive(id){
            // alert(id);           
            swal({
            title: "Are you sure?",
            text: "Do you want to make this ad inactive!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                document.getElementById('inactive-'+id).submit();
              } 
            });
        }
        function inactiveinvalidaccess(){
            swal({
              title: "Warning!",
              text: "Access blocked. It is based on Ads status",
              icon: "warning",
              button: "Close",
              dangerMode: true,
            });
        }
        $(window).on("load",function(){
            $("#active_content, #sold_content, #favourite_content, #inactive_content, #feature_ads_plan_listing, #top_ads_plan_listing, #pearls_ads_plan_listing").mCustomScrollbar({
                autoHideScrollbar: false,
                theme: "dark",
            });
        });


    
</script>
<script type="text/javascript">
    $(function(){
        var hash = window.location.hash;
        hash && $('a[href="' + hash + '"]').tab('show');
        
        $('.nav-tabs a').click(function (e) {
                $(this).tab('show');
                var scrollurl = $('body').scrollTop() || $('html').scrollTop();
                window.location.hash = this.hash;
                $('html,body').scrollTop(scrollurl);
        });
    });
</script>
@endsection