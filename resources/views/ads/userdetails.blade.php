@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
<style type="text/css">
    .common_btn_wrap a .blockbtn {
    background-color: #62040f;
}
</style>
<style type="text/css">
        .rating {
          float: left;
        }

        /* :not(:checked) is a filter, so that browsers that don’t support :checked don’t 
           follow these rules. Every browser that supports :checked also supports :not(), so
           it doesn’t make the test unnecessarily selective */
        .rating:not(:checked)>input {
          position: absolute;
          top: -9999px;
          clip: rect(0, 0, 0, 0);
        }

        .rating:not(:checked)>label {
          float: right;
          width: 1em;
          padding: 0 .1em;
          overflow: hidden;
          white-space: nowrap;
          cursor: pointer;
          font-size: 140%;
          line-height: 1.2;
          color: #ddd;
          text-shadow: 1px 1px #bbb, 2px 2px #666, .1em .1em .2em rgba(0, 0, 0, .5);
        }

        .user_rating_outerr_wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 4px;
        }

        .rating:not(:checked)>label:before {
          content: '★ ';
        }

        .rating>input:checked~label {
          color: #f70;
          text-shadow: 1px 1px #c60, 2px 2px #940, .1em .1em .2em rgba(0, 0, 0, .5);
        }

        .rating:not(:checked)>label:hover,
        .rating:not(:checked)>label:hover~label {
          color: gold;
          text-shadow: 1px 1px goldenrod, 2px 2px #B57340, .1em .1em .2em rgba(0, 0, 0, .5);
        }

        .rating>input:checked+label:hover,
        .rating>input:checked+label:hover~label,
        .rating>input:checked~label:hover,
        .rating>input:checked~label:hover~label,
        .rating>label:hover~input:checked~label {
          color: #ea0;
          text-shadow: 1px 1px goldenrod, 2px 2px #B57340, .1em .1em .2em rgba(0, 0, 0, .5);
        }

        .rating>label:active {
          position: relative;
          top: 2px;
          left: 2px;
        }

        /* end of Lea's code */

        /*
         * Clearfix from html5 boilerplate
         */

        .clearfix:before,
        .clearfix:after {
          content: " ";
          /* 1 */
          display: table;
          /* 2 */
        }

        .clearfix:after {
          clear: both;
        }

        /*
         * For IE 6/7 only
         * Include this rule to trigger hasLayout and contain floats.
         */

        .clearfix {
          *zoom: 1;
        }

        /* my stuff */
        /*#status,
        button {
          margin: 20px 0;
        }*/

         .box-size {width: 1300px !important; }

        @media(max-width:1280px){
            .box-size {width: 95vw !important; }            
        }
        nav.page_title_inner_wrap ol.breadcrumb { padding: 29px 0; }
        .share_category_listing { z-index: 1; display: none; position: absolute; background: #fff; box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.23); border-radius: 5px; top: 40px; right: 0px;}
        .share_category_listing ul {list-style: none; margin: 0; display: flex; justify-content: flex-start; align-items: flex-start; padding: 14px 14px 10px 14px;     max-width: 128px; min-width: 128px; flex-flow: row wrap; }
        .share_category_listing ul li {display: flex; justify-content: flex-start; align-items: center; flex-direction: column; margin: 1px; flex: 0 0 17%; }
        .share_icon_img {width: 30px;  height: 44px; padding: 4px 8px; border-radius: 30px;     margin: 0 auto;  display: flex; justify-content: center; align-items: center; }
        .share_icon_img a i {font-size: 22px; width: 45px; height: 44px; display: flex; justify-content: center; align-items: center;    color: #366368!important; }
        .share_icon_img img {width: 100%; height: 100%; object-fit: contain; }
        .share_category_listing ul li h4 {font-size: 14px; margin-bottom: 0; margin-top: 5px; text-transform: capitalize; text-align: center;}
        .share_drop_wrap.active .share_category_listing {display: block; }

        .star_rating .fa-star{

            color: grey;
        }
        .star_rating .fa-star.active{
            color: #EEBD01 !important;
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
                                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Profile info</a></li>
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
                        <div class="profile_name_wrap profile_info_name_wrap new_ads_rating_content_wrap">
                            
                            <h2>{{get_name($userdetails->id)}}</h2>
                            @php
                                $roundoff = userstarrating($userdetails->id);
                            @endphp

                            <div class="star_rating pt-2">
                                @for($i=1;$i<6;$i++)
                                    @if($i<=round($roundoff))
                                        <span class="fa fa-star active"></span>
                                    @else
                                        <span class="fa fa-star "></span>
                                    @endif
                                @endfor
                            </div>
                            <!-- <span class=" rating_ads_detail" style="position: relative !important;">{{number_format((float)userstarrating($userdetails->id), 1, '.', '')}}<i class="fa fa-star"></i></span> -->
                            <p>Member Since {{$month}}-{{$year}}</p> 
                            

                        </br>
                            <!-- @if($segment2 == 'info')
                            <div class="user_rating_outerr_wrap" title="Rate this user! ">
                                <form id="ratingForm">
                                    <fieldset class="rating">
                                        <input type="radio" id="star5" class="star_rate" name="rating" value="Perfect" /><label for="star5" title="Perfect">5 stars</label>
                                        <input type="radio" id="star4" class="star_rate" name="rating" value="Good" /><label for="star4" title="Good">4 stars</label>
                                        <input type="radio" id="star3" class="star_rate" name="rating" value="Medium" /><label for="star3" title="Medium">3 stars</label>
                                        <input type="radio" id="star2" class="star_rate" name="rating" value="Poor" /><label for="star2" title="Poor">2 stars</label>
                                        <input type="radio" id="star1" class="star_rate" name="rating" value="Bad" /><label for="star1" title="Bad">1 star</label>
                                    </fieldset>
                                </form> 
                            </div>
                            @endif -->
                            <div class="common_btn_wrap">
                                @if(Auth::check())
                                    @if(Auth::user()->id!=$userdetails->id)
                                    <form action="#" id="followers-form">
                                        <input type="hidden" id="id" value="{{getUserUuid($userdetails->id)}}">
                                        <input type="hidden" id="token" value="{{csrf_token()}}">
                                    <!-- <input type="button" class="{{$prop[1]}} " value="{{$prop[2]}}" id="follow"> -->
                                        @if($segment2 == 'info')
                                            <a href="javascript:void(0);" id="follow">{{$prop[2]}}</a>
                                         @endif   
                                    </form>
                                    @else
                                        <a class="" href="{{route('profile')}}"><i class="fa fa-pencil pr-1" aria-hidden="true"></i>Edit</a>
                                    @endif
                                @else
                                    <!-- <input type="button" class="btn btn-success" value="Follow" id="follow-log"> -->
                                    @if($segment2 == 'info')
                                        <a href="javascript:void(0);" id="follow-log">Follow</a> 
                                     @endif   
                                @endif
                            </div>
                            <div class="common_btn_wrap">
                                @if(Auth::check() && !empty($privacy) && $privacy->online == '1')
                                    @if(Auth::user()->id!=$userdetails->id )
                                        <br>
                                        @if(Cache::has('user-is-online-' . $userdetails->id))
                                            <input type="button" class="btn btn-success" value="Online" id="onlineuser">
                                        @else
                                            <input type="button" class="btn btn-danger" value="Offline" id="offlineuser">
                                        @endif
                                     @endif   
                                @endif

                            </div>
                            
                            <div class="share_profile_link_outer_wrap">
                                @if($segment2 == 'info')
                                <a href="javascript:void(0)" onclick="setClipboard('{{url('profile/info')}}/{{$userdetails->uuid}}')">Share Profile Link</a>
                                 @endif
                            </div>
                           
                        </div>
                    </div>

                    @if($segment2 != 'info')
                    <div class="profile_nav_wrap">
                        @include('includeContentPage.profilemenuforadmin')
                    </div>
                    @endif
                    
                    <!-- Category Model Popup -->
                    <div id="share_profile_link">
                        Link is copied to clipboard 
                    </div>
                    <div class="profile_nav_wrap profile_info_listing_outer_wrap">
                        <h3>Linked Accounts</h3>
                        @if($userdetails->google_id)
                        <div class="listing_profile_info_wrap" >
                            <p style="cursor: default !important;">Google</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                        @if($userdetails->facebook_id)
                        <div class="listing_profile_info_wrap">
                            <p style="cursor: default !important;">Facebook</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                        @if($userdetails->phone_verified_at)
                        <div class="listing_profile_info_wrap">
                            <p style="cursor: default !important;">Phone Number</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                        @if($userdetails->email_verified_at)
                        <div class="listing_profile_info_wrap">
                            <p style="cursor: default !important;">Email</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                    </div>
                    <div class="profile_nav_wrap profile_info_listing_outer_wrap">
                        <h3>Friends</h3>
                        <div class="listing_profile_info_wrap">
                            <p class="showfollowersmodal" data-toggle="modal" data-target="#followers_popup_modal" data-userid="{{$userdetails->uuid}}" style="cursor: pointer;" >Followers</p>
                            <i>{{$fcount['follower']}}</i>
                        </div>
                        <div class="listing_profile_info_wrap">
                            <p  class="showfollowingmodal"data-toggle="modal" data-target="#following_popup_modal"  data-userid="{{$userdetails->uuid}}">Following</p>
                            <i>{{$fcount['following']}}</i>
                        </div>
                        <div class="listing_profile_info_wrap" style="display: none;">
                            <p  class="get_user_reviews" data-toggle="modal" data-target="#reviews_popup_modal"  data-userid="{{$userdetails->uuid}}">Reviews</p>
                        </div>
                        @if($segment2 == 'info')
                            @if(Auth::check())
                                @if(Auth::user()->id!=$userdetails->id)
                                    <a  href="javascript:void(0);" onclick="reportUser()">Report User</a>
                                @endif
                            @endif   
                        @endif
                    </div>
                </div>
                <div class="modal" id="reportuser">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">User Report</h4>
                                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                            </div>
                            <div class="model_close_btn_wrap close_chat_btn_wrap">
                                <a href="javascript:void(0)" data-dismiss="modal">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <form method="post" action="#" enctype="multipart/form-data" id="myform">
                                    <input type="hidden" id="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" id="uuuid" value="{{$userdetails->uuid}}">

                                    <div class="multicheck_btn_outer_wrap">
                                        <div class="multicheck_box_wrap radio_btn_wrap form-group">
                                            <div class="multicheck_outer_wrap">
                                                <label class="multicheck_box_inner_wrap">Spam 
                                                    <input type="radio" class="reportuser" name="reportuser"  value="spam">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="multicheck_box_wrap radio_btn_wrap form-group">
                                            <div class="multicheck_outer_wrap">
                                                <label class="multicheck_box_inner_wrap">Fraud
                                                    <input type="radio" class="reportuser" name="reportuser"  value="fraud">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="multicheck_box_wrap radio_btn_wrap form-group">
                                            <div class="multicheck_outer_wrap">
                                                <label class="multicheck_box_inner_wrap">Inappropriate Profile Picture
                                                    <input type="radio" class="reportuser" name="reportuser"  value="inappropriateProfilePic">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="multicheck_box_wrap radio_btn_wrap form-group">
                                            <div class="multicheck_outer_wrap">
                                                <label class="multicheck_box_inner_wrap">This user is threatening me
                                                    <input type="radio" class="reportuser" name="reportuser"  value="threateningMe">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="multicheck_box_wrap radio_btn_wrap form-group">
                                            <div class="multicheck_outer_wrap">
                                                <label class="multicheck_box_inner_wrap">This user is insulting me
                                                    <input type="radio" class="reportuser" name="reportuser"  value="insultingMe">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control commentss" placeholder="Comment" id="commentss" name="commentss"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check-inline">
                                            <label ><span class="radiotextsty">Attach File</span>
                                            <input type="file" id="file" name="file" /> 
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="text-danger" id="erroruserreport"></div>
                                    </div>
                                    <div class="common_btn_wrap">
                                    <button type="submit"id="reportusersubmit" class="btn btn-primary">Send complaint</button>
                                    </div>
                                </form>
                                <div class="message_box" style="margin:10px 0px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Followers popup model -->
                <div id="followers_popup_modal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="close_chat_btn_wrap button_close_model">
                                <a href="#" data-dismiss="modal">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="modal-body" id='followercontent'>
                                <h2>Followers</h2>
                                <div class="follower_scroll_wrap">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Following popup model -->
                <div id="following_popup_modal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="close_chat_btn_wrap button_close_model">
                                <a href="#" data-dismiss="modal">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="modal-body " id="followingcontent">
                                <h2>Following</h2>
                                <div class="follow_scroll_wrap"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Reviews popup model -->
                <!-- <div id="reviews_popup_modal" class="modal fade" role="dialog">
                    <div class="modal-dialog" style="max-width: 850px">
                        <div class="modal-content">
                            <div class="close_chat_btn_wrap button_close_model">
                                <a href="#" data-dismiss="modal">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="modal-body" id='reviewscontent'>
                                <h2>Reviews</h2>
                                <div class="reviewscontent_contents">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-9 profile_right_new_col_wrap">
                    <div class="profile_info_page_title_wrap">
                        <h4>Published Ads</h4>
                    </div>
                    <div class="profile_info_ads_scroll">
                        <div class="profile_ads_listing_wrap row view-group">
                            @if(isset($ads))
                                @if($segment2 == 'info-all')
                                    @if(empty($ads))
                                           <p class="day_wrap">No ads Found</p>
                                    @endif
                                    @foreach($ads as $ad)
                                    <div class="item col-md-6 col-lg-4 filter cat-1">
                                        <div class="thumbnail card">
                                            <div class="img-event">
                                                <img class="group list-group-image img-fluid" src="{{asset('public/uploads/ads/'.$ad->image)}}" alt="" />
                                            </div>
                                            @if($ad->status==0)
                                                <div class="ads_status_wrap pending_ad_wrap">
                                                    <p>Pending</p>
                                                </div>
                                            @elseif($ad->status==1)
                                                <div class="ads_status_wrap approve_ad_wrap">
                                                    <p>Approved</p>
                                                </div>
                                            @elseif($ad->status==2)
                                                <div class="ads_status_wrap reject_ad_wrap">
                                                    <p>Rejected</p>
                                                </div>
                                            @elseif($ad->status==3)
                                                <div class="ads_status_wrap sold_ad_wrap">
                                                    <p>Sold</p>
                                                </div>
                                            @elseif($ad->status==4)
                                                <div class="ads_status_wrap block_ad_wrap">
                                                    <p>Block</p>
                                                </div>
                                            @elseif($ad->status==6)
                                                <div class="ads_status_wrap inactive_ad_wrap">
                                                    <p>Inactive</p>
                                                </div>
                                            @endif
                                            <div class="caption card-body">
                                                <!-- <a target="_blank" href="{{ route('admin.ads.view',$ad->uuid) }} "> -->
                                                <a target="_blank" href=" {{ route('adsview.getads',$ad->uuid) }}">
                                                <h4 class="group card-title inner list-group-item-heading">
                                                    ₹{{$ad->price}}</h4>
                                                <p class="group inner list-group-item-text">
                                                    <span>{{ ucwords(str_limit($ad->title,18))}}</span><br>
                                                    <span>{{ strtoupper(get_cityname($ad->cities,80)) }}</span>
                                                    @if($ad->status==1 )
                                                        <span>Expired Date  : {{date("d-m-Y H:i ", strtotime($ad->ads_expire_date))}}</span>
                                                    @else
                                                        </br>
                                                        <span>Expired Date  : None </span>
                                                    @endif
                                                </p>
                                                </a>
                                                <div class="row bottom_card_wrap">
                                                    <div class="col-xs-12 col-md-6 col-sm-4 pl-0 pr-0">
                                                        <p class="day_wrap">
                                                            {{time_elapsed_string($ad->approved_date)}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    @if(empty($ads))
                                           <p class="day_wrap">No ads Found</p>
                                    @endif
                                    @foreach($ads as $ad)
                                        <div class="item col-md-6 col-lg-4 filter cat-1">
                                            <div class="thumbnail card">
                                                <div class="img-event">
                                                    <img class="group list-group-image img-fluid" src="{{asset('public/uploads/ads/'.$ad->image)}}" alt="" />
                                                    @if(Auth::check())
                                                        @if($ad->seller_id != auth()->user()->id)
                                                            @if(isset($ad->favv) && $ad->favv == '1')
                                                                <i class="fa fa-heart heart_wrap" onclick="fav('{{$ad->uuid}}','{{$ad->id}}')" id="favourite-{{$ad->id}}" aria-hidden="true"></i>
                                                            @else
                                                                <i class="fa fa-heart-o heart_wrap" onclick="fav('{{$ad->uuid}}','{{$ad->id}}')" id="favourite-{{$ad->id}}" aria-hidden="true"></i>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <i class="fa fa-heart-o heart_wrap" onclick="fav('{{$ad->uuid}}','{{$ad->id}}')"  id="favourite-{{$ad->id}}"aria-hidden="true"></i>
                                                    @endif
                                                </div>
                                                @if($ad->featureadsexp!='' && $ad->featureadsexp > date("Y-m-d h:m:s"))
                                                       <div class="ads_status_wrap pending_ad_wrap"><p>Featured</p></div>
                                                @endif
                                                <div class="caption card-body">
                                                    <a href="{{ route('adsview.getads',$ad->uuid) }}">
                                                    <h4 class="group card-title inner list-group-item-heading">
                                                        ₹{{$ad->price}}</h4>
                                                    <p class="group inner list-group-item-text">
                                                        <span>{{ ucwords(str_limit($ad->title,18))}}</span><br>
                                                        <span>{{ strtoupper(get_cityname($ad->cities,80)) }}</span>
                                                    </p>
                                                    </a>
                                                    <div class="row bottom_card_wrap">
                                                        <div class="col-xs-12 col-md-6 col-sm-4 pl-0 pr-0">
                                                            <p class="day_wrap">
                                                                {{time_elapsed_string($ad->approved_date)}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                <br>
                <div class="col-md-12 col-lg-9 profile_right_new_col_wrap card">
                    <div class="caption card-body">
                        <div class="profile_info_page_title_wrap">
                            <h4>Customer review for seller</h4>
                        </div>
                        <div class="profile_info_ads_scroll">
                            <div class="profile_ads_listing_wrap row view-group user_review_single_wrap"> 
                                <div class="reviewscontent_contents">
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
<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>

@if(Auth::user()->role_id =='2')
<script type="text/javascript">
    var star="{{ userstarratingcheck($userdetails->id,Auth::user()->id)}}";
    if(star!=0){
        var starid='#star'+star;
        $(starid).trigger('click');
    }
</script>
@endif

<script>
$(document).ready(function() {
    
    

    // $(".star_rate").change(function(e) 
    // {
    //     e.preventDefault(); // prevent the default click action from being performed
    //     swal({
    //         title: "Are you sure?",
    //         text: "Rate this user !",
    //         icon: "warning",
    //         buttons: true,
    //         dangerMode: true,
    //         })
    //         .then((willDelete) => {
    //             if (willDelete) {
    //                 var rating = new FormData(); 
    //                 rating.append('user', "{{$userdetails->uuid}}"); 
    //                 rating.append('rating', $(this).val()); 
    //                 rating.append('ratefromuser', "{{Auth::user()->uuid}}"); 
    //                 rating.append('_token', "{{csrf_token()}}"); 
    //                 $.ajax({
    //                     url: "<?php echo url('/ratinguser'); ?>",
    //                     type: "post",
    //                     data: rating, 
    //                     contentType: false, 
    //                     processData: false, 
    //                     success: function(data){  
    //                         swal("Successfully Rated!", {
    //                             icon: "success",
    //                         });
    //                     },
    //                 });
                    
    //             } else {
    //                 location.reload();
    //             }

    //         });
    // });
});
</script>
<script type="text/javascript">
    $(window).on("load",function(){
            $(".profile_info_ads_scroll").mCustomScrollbar({
                autoHideScrollbar: false,
                theme: "dark",
            });
        });
</script>
<script type="text/javascript">
    $(document).ready(function(){


        

            setTimeout(function(){ $(".get_user_reviews").trigger('click'); }, 1000);

            $("#follow").click(function(e){
                var id = $("#id").val();
                var token = $("#token").val();
                $.ajax({
                    type:"get",
                    url:"<?php echo url('/follow')?>",
                    data:"id="+id+"&_token="+token,
                    success: function(data){
                        if(data == 1){
                            $("#follow").html("Unfollow");
                        }else{
                            $("#follow").html("Follow");
                        }
                    }
                });
            });
        });
        $(document).ready(function(){
            $("#follow-log").click(function(e){
                swal("Login to continue!", {
                  icon: "warning",
                });
            });
        });
        function setClipboard(value) {
            var tempInput = document.createElement("input");
            tempInput.style = "position: absolute; left: -1000px; top: 10px";
            tempInput.value = value;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            var x = document.getElementById("share_profile_link");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
        function reportUser() {
            $("#reportuser").modal('show');
        }
    $(document).ready(function(){
        $("#reportusersubmit").click(function(e){
            e.preventDefault();
            var commentss = $('.commentss').val();
            var uuuid = $('#uuuid').val();
            var _token = $('#_token').val();
            var reportuser = $("input[name=reportuser]:checked").val();
            
            /*if ($("input[name=reportuser]:checked").val() && $('#commentss').val() != '') {
                $.ajax({
                    type: "post",
                    url: "<?php echo url('/reportusersubmit'); ?>",
                    data: "reportuser="+reportuser+"&uuuid="+uuuid+"&_token="+_token+"&commentss="+commentss,
                    success: function(data){  
                    $("#reportuser").modal('hide'); 
                        swal("Saved!", {
                        icon: "success",
                    });                     
                        
                    }
                });
            }else{
                alert("Please select atleast one reason!,Comments is mandatory");
            }*/

            var fd = new FormData(); 
            var files = $('#file')[0].files[0]; 
            fd.append('file', files); 
            fd.append('commentss', commentss); 
            fd.append('uuuid', uuuid); 
            fd.append('_token', _token); 
            fd.append('reportuser', reportuser); 
            
            if ($("input[name=reportuser]:checked").val()) {
                if($('#commentss').val() != ''){
                      $.ajax({
                        url: "<?php echo url('/reportusersubmit'); ?>",
                        type: "post",
                        data: fd, 
                        contentType: false, 
                        processData: false, 
                        success: function(data){  
                            $("#reportuser").modal('hide'); 
                                swal("Saved!", {
                                icon: "success",
                            });
                        },
                        error: function(data){
                            if( data.status === 422 ) {
                                var errors = $.parseJSON(data.responseText);
                                var error=errors.errors.file;
                                $('#erroruserreport').html(""+error+"");
                            }
                        },
                    });  
                }else{
                    swal("Comments is mandatory");
                }
                
            }else{
                swal("Please select atleast one reason!");
            }

        });

        $(".showfollowingmodal").click(function(){
            $.ajax({
                url: '{{url("getfollwing")}}/'+$(this).data('userid'),
                type: "get",
                //data:{'uuid':$(this).data('userid')},
                success:function(data) {
                    $(".follow_scroll_wrap").html(data);
                }
            });
        });

        $(".showfollowersmodal").click(function(){
            $.ajax({
                url: '{{url("getfollowers")}}/'+$(this).data('userid'),
                type: "get",
                success:function(data) {
                    $(".follower_scroll_wrap").html(data);
                }
            });
        });
    });

    $(document).on("click", ".getfollowing", function(){
        var thisuuid = $(this).data('useruuid');
        $.ajax({
            url:"<?php echo url('/follow')?>",
            data:"id="+$(this).data('useruuid'),
            type: "get",
            success: function(data){
                if(data == 1){
                    $("#getfollowing"+thisuuid).addClass('unfollowbtn');
                    $("#getfollowing"+thisuuid).html("Unfollow");
                    /*$("#followcount").empty();
                    count=followcount+1;
                    $("#followcount").text(count);*/
                }else{
                    $("#getfollowing"+thisuuid).html("Follow");
                    $("#getfollowing"+thisuuid).removeClass('unfollowbtn');
                    /*$("#followcount").empty();
                    uncount=followcount-1;
                    $("#followcount").text(uncount);*/      
                }
            }
        });
    });

    $(document).on("click", ".get_user_reviews", function(){
        var thisuuid = $(this).data('userid');
        $.ajax({
            url:"<?php echo url('profile/reviews')?>/"+thisuuid,
            //data:"id="+thisuuid,
            type: "get",
            success: function(data){
                if(data != '0'){
                    $(".reviewscontent_contents").html(data);
                }else{
                    $(".reviewscontent_contents").html("<p>No Reviews Found </p>");
                }
            }
        });
    });


    $(document).on("click", ".blockuser", function(){
        var thisuuid = $(this).data('useruuid');
        var statuschanges = $(this).data('statuschanges');
        $.ajax({
            url:"<?php echo url('/blockuser')?>",
            data:"uuuid="+$(this).data('useruuid')+"&statuschanges="+statuschanges,
            type: "get",
            success: function(data){
                if(data == 1){
                    $("#blockuser"+thisuuid).removeClass('blockbtn');
                    $("#blockuser"+thisuuid).html("UnBlock");
                    /*$("#followcount").empty();
                    count=followcount+1;
                    $("#followcount").text(count);*/
                    //$( ".blockuser" ).load(window.location.href + " #followers" );
                    location.reload(true);
                }else{
                    $("#blockuser"+thisuuid).addClass('blockbtn');
                    $("#blockuser"+thisuuid).html("Block");
                    location.reload(true);
                    //$( ".blockuser" ).load(window.location.href + " #followers" );
                    /*$("#followcount").empty();
                    uncount=followcount-1;
                    $("#followcount").text(uncount);*/      
                }
            }
        });
    });

    

</script>
@endsection