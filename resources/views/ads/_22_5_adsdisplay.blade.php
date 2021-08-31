@extends('layouts.home')
@section('styles')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/newstyle.css') }}">
	<style type="text/css">

	</style>
@endsection
@section('content')
<!-- Page Title -->
	<div class="container-fluid pl-0 pr-0 page_title_bg_wrap">
		<div class="box-size">
			<div class="row">
				<div class="col-md-6 pl-0 pr-0">
					<nav aria-label="breadcrumb" class="page_title_inner_wrap">
						<ol class="breadcrumb">
						    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
						    <li class="breadcrumb-item active" aria-current="page"><a href="#">
						    	@if(isset($ads->categoryName))
						    		{{$ads->categoryName}} / {{$ads->subCategoryName}}
						    	@endif
						</a></li>
						</ol>
					</nav>
				</div>
				<div class="col-md-6 pl-0 pr-0">
					@if(Auth::check())
					 	@if(Auth::user()->role_id == 2 && Auth::user()->email_verified_at != null && Auth::user()->phone_verified_at != null)
					 		@if($ads->seller_id != Auth::user()->id)

					 			@if(isset($ads->viewpoint) && $ads->viewpoint == '1'  && $ads->alreadyviewed=='0' && $ads->point_expire_date > date('Y-m-d H:i:s') && $ads->ads_expire_date > date('Y-m-d H:i:s'))
									<!-- <div class="circle_outer_wrap">
										<div class="circle_inner_wrap" data-progress="0">
										  <div class="quad1"></div>
										  <div class="quad2"></div>
										  <div class="quad3"></div>
										  <div class="quad4"></div>
										</div>
									</div> -->
								@endif
							@endif
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>


	<div class="container-fluid pl-0 pr-0  bg-light chat_new_outer_wrap">
		<div class="box-size">
			<div class="ads_listing_mobile_search_wrap">
    		</div> 

			<div class="profile-right-side">
				<input type="hidden" id="viewed_ads_id" value="{{$ads->id}}">
				<div class="row">
					<div class="col-md-7">
						<div class="adsdetails-outer-wrap">
							<div class="row">
								
								@php $showfeat = 0; @endphp
								@if($ads->featureadsexp!='' && $ads->featureadsexp > date("Y-m-d h:m:s"))
									<div class="ads_status_wrap pending_ad_wrap" style="border-radius: 0px;"><p>Platinum</p></div>
								@php $showfeat = 1; @endphp
								@endif
								<?php /* @elseif($ads->type =='0')
									<div class="ads_status_wrap pending_ad_wrap" style="border-radius: 0px;"><p>Free</p></div>
									*/
									?>
								@if($ads->expireDDD !=''  && $ads->expireDDD > date("Y-m-d h:m:s"))
									@php 
										$addcss = '';
										$addclass = '';
										if($showfeat == 1){
											$addcss = 'left: 150px;';
											$addclass = 'feature_sec_apper_wrap';
										}
									@endphp
									<div class="ads_status_wrap pending_ad_wrap {{$addclass}} " style="border-radius: 0px;{{$addcss}}"><p>Featured</p></div>
								@endif
								<div class="col-md-9 ad-name-wrap">
									<h5>{{ ucwords($ads->title)}}</h5>
									<h6>
										<i class="fa fa-rupee"></i> {{formatMoney($ads->price)}} 
										
									</h6>
								</div>
								<div class="col-md-3 favourite-icon-wrap">
									<div class="share_drop_wrap ">
										<div class="fb-share-button1">
				                            <a href="JavaScript:void(0);" class="">
												<i class="fa fa-share-alt" aria-hidden="true"></i>
											</a>
										</div>
			                            <div class="share_category_listing">
			                                <ul>
			                                    <li>
		                                            <div class="share_icon_img">
		                                            	<a  target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}" title="Facebook Share">
		                                            		<i class="fa fa-facebook-official"  aria-hidden="true" ></i>
		                                            	</a>
		                                            </div>
		                                        </li>
		                                        <li>
		                                            <div class="share_icon_img">
		                                            	<a  target="_blank" href="https://twitter.com/intent/tweet?url={{url()->current()}}" title="Twitter Share">
		                                                	<i class="fa fa-twitter-square" aria-hidden="true" ></i>
		                                            	</a>
		                                            </div>
		                                        </li>
		                                        <li>
		                                            <div class="share_icon_img">
		                                            	<a  target="_blank" onclick="setClipboard('{{url()->current()}}')" title="Clipboard">
		                                                	<i class="fa fa-clone" aria-hidden="true" ></i>
		                                            	</a>
		                                            </div>
		                                        </li>
	                                    	</ul>
			                            </div>
			                            <div id="share_profile_link">
					                        Link is copied to clipboard 
					                    </div>
			                        </div>
									<h6>
										@if(Auth::check())
											@if(!empty($ads->fav_uuid))
												<i class="fa fa-heart heart_wrap" onclick="fav('{{$ads->uuid}}','{{$ads->id}}')"  id="favourite-{{$ads->id}}"aria-hidden="true"></i>
											@elseif($ads->seller_id == auth()->user()->id)
												
											@else
												<i class="fa fa-heart-o heart_wrap" onclick="fav('{{$ads->uuid}}','{{$ads->id}}')"  id="favourite-{{$ads->id}}"aria-hidden="true"></i>
											@endif
											<!-- <i class="fa fa-heart-o heart_wrap" onclick="fav('{{$ads->uuid}}','{{$ads->id}}')"  id="favourite-{{$ads->id}}"aria-hidden="true"></i> -->
										@else
										    <i class="fa fa-heart-o heart_wrap" onclick="fav('{{$ads->uuid}}','{{$ads->id}}')"  id="favourite-{{$ads->id}}"aria-hidden="true"></i>
										@endif
									</h6>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 ad-id-wrap">
									<h6>AD ID {{$ads->ads_ep_id}}</h6>
								</div>
								<div class="col-md-6 ad-report-wrap">


									<p style="float: right;text-decoration: none !important;color: #00000070;font-weight: 500;">Posted On :									

									@if(Auth::check())
										@if(Auth::user()->id==$ads->seller_id)
											<span>{{date("d-M-Y h:i A", strtotime($ads->created_at))}}</span>
										@else
											<span>{{ads_display_time_elapsed_string($ads->created_at)}}</span>
										@endif	
									@else
										<span>{{ads_display_time_elapsed_string($ads->created_at)}}</span>
									@endif
								</p>
								</div>
							</div>
							<div class="new_ads_detail_slider_outer_wrap">
								
								<div class="product_img_slider_outer_wrap">
									
									<div class='product_slider'>

										@foreach($adsimg as $key => $img)
									  	<img src="{{asset('public/uploads/ads/'.$img->image)}}" title="product_img" alt="producct_img">
									  	@endforeach
									</div>
									<div class='product_slider_nav'>
										@foreach($adsimg as $key => $img)
									  	<img src="{{asset('public/uploads/ads/'.$img->image)}}" title="product_img" alt="producct_img">
									  	@endforeach
									</div>
								</div>
							</div>
							<div class="product_description_outer_wrap">
								<div class="description_inner_wrap">
									<h2>Description</h2>
									{!! ucwords($ads->description) !!}
									<div class="view_more_des">
										<!-- <a href="#">View More</a> -->
									</div>
								</div>
								<div class="product_details_wrap">
									<h2>Details</h2>
									<ul>
										@if(!empty($customfields))
											{!! $customfields !!}
				                        @else
			                    			<li>
												<h3>{{ ucwords($ads->title)}}</h3>
											</li>
			                    		@endif
									</ul>
									<div class="view_more_des">
										<!-- <a href="#">View More</a> -->
									</div>
								</div>

									
								
							<div class="row">
								<div class="col-md-6 ad-id-wrap">
								</div>
								<div class="col-md-6 ad-report-wrap">
									@if(Auth::check())
										@if($ads->seller_id != auth()->user()->id)
											<a href="javascript:void(0);" class="ad-report-inner-wrap" id="reportad" onclick="reportad()">REPORT THIS AD</a>
										@endif
									@else
										<a href="javascript:void(0);" class="ad-report-inner-wrap" id="reportad" onclick="reportad()">REPORT THIS AD</a>
									@endif
								</div>
							</div>

							</div>
						</div>
					</div>
					<div class="col-md-5">
						<div class="adsdetails-outer-wrap">
							<div class="row posted-header-wrap">
								<h6>Posted By</h6>
							</div>
							<div class="row profile_img_inner_wrap">
								<div class="col-md-12 pl-0 pr-0">
									<div class="profile_info_inner_wrap ">
										<div class="profile_water_marker_outer_wrap ads_water_marker">
											<div class="profile_img_wrap">
												<img src="{{asset('public/uploads/profile/original/'.$ads->photo)}}" title="avatar" alt="avatar">
											</div>

											<?php $badge=verified_profile($ads->seller_id)?>
											@if($badge == '1')
												<div class="profile_water_marker">
					                                <img src="{{asset('public/frontdesign/assets/img/ojaak_profile_watermark.png')}}">
					                            </div>
				                            @endif
				                        </div>
										<div class="profile_name_inner_wrap">
											@if(Auth::check())
												<a href="{{route('view.profile',getUserUuid($ads->seller_id))}}" style="color: #000 !important;" ><h2   style="cursor: pointer;">@if($ads->name!='')
														{{$ads->name}}
													@else
														{{get_name($ads->seller_id)}}
													@endif</h2>
												</a>
												
											@else
											<h2  onclick="viewprofile()" style="cursor: pointer;">@if($ads->name!='')
													{{$ads->name}}
												@else
													{{get_name($ads->seller_id)}}
												@endif</h2>

											@endif
											@if(Auth::check() && !empty($privacy) && $privacy->online == '1')
			                                    @if(Auth::user()->id!=$ads->seller_id )
			                                       @if(Cache::has('user-is-online-' . $ads->seller_id))
			                                            <h6>Online</h6>
			                                        @else
			                                            <h6 class="offline">Offline</h6>
			                                        @endif
			                                     @endif 
			                                @else
			                                	<h6 class="offline">Offline</h6>  
			                                @endif
											<p>Member since {{$month}} {{$year}}</p>
										</div>
										<span class=" rating_ads_detail"> <a href="#customer_review_outer_row_wrap" style="color: #fff !important;font-size: 12px !important;font-weight: 800;">{{number_format((float)userstarrating($ads->seller_id), 1, '.', '')}}<i class="fa fa-star"></i></a></span>
									</div>
								</div>
							</div>
							<div class="new_ads_expire_detail">
								@if(Auth::check())
									@if($ads->seller_id == Auth::user()->id )
										<button type="button" onclick="republish('{{$ads->id}}')" class="republish_btn_wrap" >Republish</button>
										@if($ads->ads_expire_date !='' || $ads->ads_expire_date !=null)
											<p>AD Expired Date  : {{date("d M Y h:i A", strtotime($ads->ads_expire_date))}}</p>
										@else
											<p>AD Expired Date  : None</p>
										@endif
										@if($ads->ads_expire_date !='' || $ads->ads_expire_date !=null)
										@php
											//echo "<pre>";print_r(date("Y-m-d h:m:s", strtotime($ads->ads_expire_date)));
											//echo "<pre>";print_r(date("Y-m-d h:m:s"));
										@endphp
											@if(strtotime($ads->ads_expire_date) >  strtotime(date("d-m-Y h:s:i")))
												@if($ads->status==0)
													<p>AD Status  : Pending</p>
												@elseif($ads->status==1)
													<p>AD Status  : Approved</p>
												@elseif($ads->status==2)
													<p>AD Status  : Rejected</p>
												@elseif($ads->status==3)
													<p>AD Status  : Sold</p>
												@elseif($ads->status==4)
													<p>AD Status  : Blocked</p>
												@else
													<p>AD Status  : Deleted</p>
												@endif
											@else
												<p>AD Status  : Expired</p>
											@endif

										@else
											<p>AD Status  : Pending</p>
										@endif

										@if($ads->point_expire_date !='' || $ads->point_expire_date !=null)
											<p>Point Expired Date  : {{date("d M Y h:i A", strtotime($ads->point_expire_date))}}</p>
										@else
											<p>Point Expired Date  : None</p>
										@endif
										<p>AD Plan Name : {{getpaidplanname($ads->plan_id)}}</p>
										<p>AD Point Balance : {{number_format((float)AdsBalanceChecker($ads->id), 2, '.', '')}}</p>
									@endif
								@endif
							</div>
							<div class="row new_ads_detail_call_btn_wrap">
								<div class="col-md-12 pl-0 pr-0 single_ad_btn_outer_wrap ">
									@if(Auth::check() && Auth::user()->role_id == 2)
										@if(Auth::user()->id != $ads->seller_id)
										<a href="#" class="btn btn-success" data-toggle="modal" data-target="#safetytips"><i class="fa fa-comments" aria-hidden="true"></i> Chat Now</a>
										@endif
									@endif
									@if($ads->seller_information == 1 && Auth::check() && Auth::user()->role_id == 2)
										@if(Auth::user()->id != $ads->seller_id) 
										<a href="tel:{{ $ads->phone_no}}" class="btn btn-success" ><i class="fa fa-phone" aria-hidden="true"></i> Call Now</a>
										@endif
									@endif
									@if(Auth::check())
										@if(Auth::user()->id != $ads->seller_id && Auth::user()->role_id == 2)
										<a href="#" class="btn btn-success" data-toggle="modal" data-target="#customer_review_modal"><i class="fa fa-bullhorn" aria-hidden="true"></i> Review Now</a>
										@endif
									@endif
									@if(Auth::check())
										@if(Auth::user()->id != $ads->seller_id && Auth::user()->role_id == 2)
											<a href="#" class="btn btn-success" data-toggle="modal" data-target="#make_offer_model_wrap"><i class="fa fa-handshake-o" aria-hidden="true"></i> Make Offer</a>
										@endif
									@endif
									<!-- @if(Auth::check())
										<a href="{{route('view.profile',getUserUuid($ads->seller_id))}}" class="btn btn-success"><i class="fa fa-eye" aria-hidden="true"></i> View Profile</a>
									@else
										<a href="javascript:void(0);" onclick="viewprofile()" class="btn btn-success"><i class="fa fa-eye" aria-hidden="true"></i> View Profile</a>
									@endif -->
								</div>
							</div>
							
							<div class="row Location-wrap">
								<h6>Location : {{ strtoupper(get_areaname($ads->area_id)) }}</h6>
								@if(Auth::check())
								<a target="_blank" href="https://www.google.com/maps/@<?php echo("$ads->latitude,$ads->longitude");?>,15.96z">
									<img class="img-fluid" src="https://maps.googleapis.com/maps/api/staticmap?center={{$ads->latitude}}%2C{{$ads->longitude}}&zoom=13&format=png&maptype=roadmap&style=element:geometry%7Ccolor:0xebe3cd&style=element:labels.text.fill%7Ccolor:0x523735&style=element:labels.text.stroke%7Ccolor:0xf5f1e6&style=feature:administrative%7Celement:geometry.stroke%7Ccolor:0xc9b2a6&style=feature:administrative.land_parcel%7Celement:geometry.stroke%7Ccolor:0xdcd2be&style=feature:administrative.land_parcel%7Celement:labels.text.fill%7Ccolor:0xae9e90&style=feature:landscape.natural%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:poi%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0x93817c&style=feature:poi.park%7Celement:geometry.fill%7Ccolor:0xa5b076&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x447530&style=feature:road%7Celement:geometry%7Ccolor:0xf5f1e6&style=feature:road.arterial%7Celement:geometry%7Ccolor:0xfdfcf8&style=feature:road.highway%7Celement:geometry%7Ccolor:0xf8c967&style=feature:road.highway%7Celement:geometry.stroke%7Ccolor:0xe9bc62&style=feature:road.highway.controlled_access%7Celement:geometry%7Ccolor:0xe98d58&style=feature:road.highway.controlled_access%7Celement:geometry.stroke%7Ccolor:0xdb8555&style=feature:road.local%7Celement:labels.text.fill%7Ccolor:0x806b63&style=feature:transit.line%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:transit.line%7Celement:labels.text.fill%7Ccolor:0x8f7d77&style=feature:transit.line%7Celement:labels.text.stroke%7Ccolor:0xebe3cd&style=feature:transit.station%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:water%7Celement:geometry.fill%7Ccolor:0xb9d3c2&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x92998d&size=640x280&markers=color:red%7Clabel:%7C{{$ads->latitude}},{{$ads->longitude}}&key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4">
								</a>
								@else
									<a  href="javascript:void(0);" class="circle_map">
										<img class="img-fluid " src="https://maps.googleapis.com/maps/api/staticmap?center={{$ads->latitude}}%2C{{$ads->longitude}}&zoom=13&format=png&maptype=roadmap&style=element:geometry%7Ccolor:0xebe3cd&style=element:labels.text.fill%7Ccolor:0x523735&style=element:labels.text.stroke%7Ccolor:0xf5f1e6&style=feature:administrative%7Celement:geometry.stroke%7Ccolor:0xc9b2a6&style=feature:administrative.land_parcel%7Celement:geometry.stroke%7Ccolor:0xdcd2be&style=feature:administrative.land_parcel%7Celement:labels.text.fill%7Ccolor:0xae9e90&style=feature:landscape.natural%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:poi%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0x93817c&style=feature:poi.park%7Celement:geometry.fill%7Ccolor:0xa5b076&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x447530&style=feature:road%7Celement:geometry%7Ccolor:0xf5f1e6&style=feature:road.arterial%7Celement:geometry%7Ccolor:0xfdfcf8&style=feature:road.highway%7Celement:geometry%7Ccolor:0xf8c967&style=feature:road.highway%7Celement:geometry.stroke%7Ccolor:0xe9bc62&style=feature:road.highway.controlled_access%7Celement:geometry%7Ccolor:0xe98d58&style=feature:road.highway.controlled_access%7Celement:geometry.stroke%7Ccolor:0xdb8555&style=feature:road.local%7Celement:labels.text.fill%7Ccolor:0x806b63&style=feature:transit.line%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:transit.line%7Celement:labels.text.fill%7Ccolor:0x8f7d77&style=feature:transit.line%7Celement:labels.text.stroke%7Ccolor:0xebe3cd&style=feature:transit.station%7Celement:geometry%7Ccolor:0xdfd2ae&style=feature:water%7Celement:geometry.fill%7Ccolor:0xb9d3c2&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x92998d&size=640x280&key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4">
									</a>
								@endif
								
								
								<div class="adver_promotion">
									<div class="adver_img_wrap">

									@if(Auth::check())
										@if($ads->seller_id == Auth::user()->id )
											@if($freeAd == '1')
												<a href="{{url('buyPremium')}}/{{$ads->uuid}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_premium2.jpg')}}">
												</a>
												<a href="{{url('showpackage')}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_feature.jpg')}}">
												</a>
												<a href="{{url('availableplatinamAd')}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_platinum.jpg')}}">
												</a>
											@endif
											@if($premiumAd == '1')
												<a href="{{url('showpackage')}}/{{$ads->uuid}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_feature.jpg')}}">
												</a>
												<a href="{{url('availableplatinamAd')}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_platinum.jpg')}}">
												</a>
											@endif
											@if($featuredAd == '1')
												<a href="{{url('buyPremium')}}/{{$ads->uuid}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_premium2.jpg')}}">
												</a>
												<a href="{{url('showpackage')}}/{{$ads->uuid}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_feature.jpg')}}">
												</a>
												<a href="{{url('availableplatinamAd')}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_platinum.jpg')}}">
												</a>
											@endif
											@if($platinamAd == '1')
												<a href="{{url('buyPremium')}}/{{$ads->uuid}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_premium2.jpg')}}">
												</a>
												<a href="{{url('showpackage')}}/{{$ads->uuid}}">
													<img src="{{asset('public/frontdesign/assets/img/promotion_feature.jpg')}}">
												</a>
											@endif
										@endif
									@endif



									</div>
								</div>
							</div>
						</div>
						@if(Auth::check())
							@if($ads->seller_id == Auth::user()->id )
								<div class="adsdetails-outer-wrap">
									<ul class="list-group">
									  	<li class="list-group-item " data-toggle="modal" data-target="#favourite_model"  style="cursor: pointer;">Favourite : {{$details['favcount']}}</li>
									  	<li class="list-group-item" data-toggle="modal" data-target="#viewadsmodal"  style="cursor: pointer;">Viewed: {{$details['viewcount']}}</li>
									  	<li class="list-group-item" data-toggle="modal" data-target="#offersmademodal"  style="cursor: pointer;">Offers made: {{$details['offersmade']}}</li>
									</ul>
								</div>
							@endif 
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>




<!-- Related Ads -->
<div class="container-fluid">
	<div class="box-size">
		@if(isset($relatedads) && !empty($relatedads))
		<div class="related_ads_outer_wrap">
			<h2>Related Ads</h2>
			<div id="products" class='related_ads_slider'>
				<?php $i=0;?>
				

				@foreach($relatedads as $ad)
					<?php $i++;?>
			  	<div class="item">
                    <div class="thumbnail card">
                    	@if(Auth::check())
								@if(!empty($ad->favv) && $ad->favv == '1')
									<i class="fa fa-heart heart_wrap" onclick="fav('{{$ad->uuid}}','{{$ad->id}}')"  id="favourite-{{$ad->id}}"aria-hidden="true"></i>
								@elseif($ad->seller_id != auth()->user()->id)
									<i class="fa fa-heart-o heart_wrap" onclick="fav('{{$ad->uuid}}','{{$ad->id}}')"  id="favourite-{{$ad->id}}"aria-hidden="true"></i>
								@endif
						@else
						    <i class="fa fa-heart-o heart_wrap" onclick="fav('{{$ad->uuid}}','{{$ad->id}}')"  id="favourite-{{$ad->id}}"aria-hidden="true"></i>
						@endif
						<a href="{{ route('adsview.getads',$ad->uuid) }}">
                        <div class="img-event">
                            <img class="group list-group-image img-fluid" src="{{asset('public/uploads/ads/'.$ad->image)}}" alt="" />

                            
                        </div>
                        <!-- @if($ad->featureadsexp!='' && $ad->featureadsexp > date("Y-m-d h:m:s"))
					       <div class="ads_status_wrap pending_ad_wrap"><p>Featured</p></div>
						@endif -->
                        <div class="caption card-body">
                        	
	                            <h4 class="group card-title inner list-group-item-heading">
	                                ₹ {{$ad->price}}</h4>
	                            <p class="group inner list-group-item-text">
	                                <span>{{ ucwords(str_limit($ad->title,18))}}</span><br>
	                                <span>{{ strtoupper(get_cityname($ad->cities,80)) }}</span>
	                            </p>
                        	
                            <div class="row bottom_card_wrap">
                                <div class="col-xs-12 col-md-6 col-sm-4 pl-0 pr-0">
                                    <p class="day_wrap">{{time_elapsed_string($ad->approved_date)}}</p>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-8 pl-0 pr-0">
                                    <!-- <a class="view_earn_wrap @if( $setting->ads_view_point > $ad->point || $ad->point_expire_date < date('Y-m-d H:i:s')) free_ads_color_wrap @endif" href="#">View to Earn</a> -->
                                    @if(Auth::check())
                                    	@if($ad->seller_id != auth()->user()->id)
                                    		@if($ad->alreadyviwed=='0' && $ad->point_expire_date > date('Y-m-d H:i:s') && $ad->ads_expire_date > date('Y-m-d H:i:s') && $setting->ads_view_point <= $ad->point && $ad->point != null)
                                                    <span class="view_earn_wrap">View to Earn</span>
                                             @endif
	                                    	<!-- @if($ad->alreadyviwed=='1')
	                                    		<span class="view_earn_wrap  free_ads_color_wrap ">View to Earn</span>
	                                    	@else
	                                    		<span class="view_earn_wrap @if( $setting->ads_view_point > $ad->point || $ad->point_expire_date < date('Y-m-d H:i:s')) free_ads_color_wrap @endif">View to Earn</span>
	                                    	@endif -->
	                                    @endif
                                    @else
                                    	@if($ad->alreadyviwed=='0' && $ad->point_expire_date > date('Y-m-d H:i:s') && $ad->ads_expire_date > date('Y-m-d H:i:s') && $setting->ads_view_point <= $ad->point && $ad->point != null)
                                                    <span class="view_earn_wrap">View to Earn</span>
                                         @endif
                                    	<!-- <span class="view_earn_wrap @if( $setting->ads_view_point > $ad->point || $ad->point_expire_date < date('Y-m-d H:i:s')) free_ads_color_wrap @endif">View to Earn</span> -->
                                    @endif
                                </div>
                            </div>
                        </div>
                    	</a>
                    </div>
                </div>
                @endforeach
		    </div>
		    @if($i==0)
		      	<p>No related Ads</p>
		    @endif
		</div>
		@endif
	</div>
</div>

<!-- <div class="container-fluid py-4">
	<div class="box-size">
		<h2>Customer Review</h2>
		<div class="jumbotron ">
		@if(Auth::check())
			@if(Auth::user()->id != $ads->seller_id)	
				<form action="{{route('adsreviews')}}" method="post" class="form">
			  		@csrf
				    <div class="form-row justify-content-center">
				      <div class="col-md-8">
				      	<input type="hidden" class="form-control" id="id" autocomplete="off"  name="id" value="{{$ads->uuid}}">
				        <input type="text" class="form-control" id="reviewMessage" autocomplete="off"placeholder="Enter Review" name="reviewMessage" required="">
				      </div>
				      <div class="col-md-2 text-center common_btn_wrap ">
				        <button type="submit" class="btn   btn-block ">Send</button>
				      </div>
				    </div>
		  		</form>
		  	@endif
  		@endif
  		<div class="py-4">
  			@if(!$usersreviews->isEmpty())
				@foreach($usersreviews as $msg)
	  			<div class="alert alert-success alert-dismissible">
	  				<strong>{{get_name($msg->review_from_user_id)}} :</strong> {{$msg->review}}.
					<button type="button" class="close" >
				    	<span aria-hidden="true"><small>{{time_elapsed_string($msg->created_at)}}</small></span>
				  	</button>
				</div>
				@endforeach
			@else
			<div class="alert alert-info alert-dismissible">
				<strong>No Review</strong> 
			</div>
			@endif
  		</div>
  		</div>
  	</div>
</div> -->	

<!-- Customer Review -->
<div class="container-fluid py-4">
	<div class="box-size">
		<div class="customer_review_outer_row_wrap" id="customer_review_outer_row_wrap">
			<h2>Customer review for seller</h2>
			@if(!$usersrating->isEmpty())
				@foreach($usersrating as $ky=>$msg)
				<div class="customer_review_single_wrap">
					<div class="customer_img_outer_wrap">
						<div class="customer_img_inner_wrap">
							<img src="{{asset('public/uploads/profile/small/'.get_userphoto($msg->rating_from_user_id))}}" title="avatar" alt="avatar">
						</div>
						<h3>{{get_name($msg->rating_from_user_id)}}</h3>
					</div>
					<div class="star_rating pt-2">
						@for($i=1;$i<6;$i++)
							@if($i<=$msg->rating)
								<span class="fa fa-star active"></span>
							@else
								<span class="fa fa-star "></span>
							@endif
						@endfor
						<p>Reviewed on {{date('d F Y', strtotime($msg->updated_at))}}</p>
					</div>
					<div class="customer_msg_wrap">
               			@php $keys_update = 0; @endphp
						@foreach($usersreviews as $keyss=>$review)
							@if($review->review_from_user_id == $msg->rating_from_user_id)
                                    @if($keys_update == 0)
										<p>{{$review->review}}</p>
                                    @else
										<p>Update : {{$review->review}}</p>
                                    @endif
           				@php $keys_update++; @endphp
							@endif
						@endforeach
               	@php $keys_update = 0; @endphp
					</div>
				</div>
				@endforeach
			@else
			<div class="alert alert-info alert-dismissible">
				<strong>No Reviewed</strong> 
			</div>
			@endif
		</div>
	</div>
</div>

<div class="modal" id="favourite_model">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Ads Liked Viewer</h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
			</div>
			<div class="model_close_btn_wrap close_chat_btn_wrap">
                <a href="javascript:void(0)" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="table-responsive" style="overflow-x:auto; white-space:nowrap;">
					<table class="table" style="width: 100%; white-space:nowrap;">
						<thead class="thead-light">
							<th>S.no</th>
							<th>Name</th>
							<th>Liked at</th>
							<th>Chat now</th>
						</thead>
						@if(Auth::check())
							@if(!$favuser->isEmpty())
								@foreach($favuser as $favusers)
								@php

								$urlid=getUserUuid(Auth::user()->id)."_".getUserUuid($favusers->user_id)."_".$ads->uuid;
								@endphp
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td> {{$favusers->name}}</td>
										<td>{{$favusers->created_at}}</td>
										<td><a href="{{url('chat/seller',$urlid)}}" style="float:right" target="_blank"> Chat </a></td>
							  		</tr>
							  	@endforeach
						  	@else
						  		<tr>No Liked</tr>
						  	@endif
					  	@endif
					</table>
				</div>
		    </div>
		</div>
	</div>
</div>

<div class="modal" id="viewadsmodal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Ads Viewer</h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
			</div>
			<div class="model_close_btn_wrap close_chat_btn_wrap">
                <a href="javascript:void(0)" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="table-responsive" style="overflow-x:auto; white-space:nowrap;">
					<table class="table" style="width: 100%; white-space:nowrap;">
						<thead class="thead-light">
							<th>S.no</th>
							<th>Name</th>
							<th>Viewed at</th>
							<th>Chat now</th>
						</thead>
						@if(Auth::check())
							@if(!$viewdetails->isEmpty())
								@foreach($viewdetails as $viewdetail)
								@php

								$urlid=getUserUuid(Auth::user()->id)."_".getUserUuid($viewdetail->user_id)."_".$ads->uuid;
								@endphp
								@if($viewdetail->view_chat == 1)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{get_name($viewdetail->user_id)}} </td>
										<td>{{$viewdetail->created_at}}</td>
										<td><a href="{{url('chat/seller',$urlid)}}" style="float:right" target="_blank"> Chat </a></td>
									</tr>
								@endif
								@endforeach
						  	@else
						  		<tr>No Viewer</tr>
						  	@endif
					  	@endif
					</table>
				</div>
		    </div>
		</div>
	</div>
</div>

<div class="modal" id="offersmademodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Made Offers</h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
			</div>
			<div class="model_close_btn_wrap close_chat_btn_wrap">
                <a href="javascript:void(0)" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="table-responsive" style="overflow-x:auto; white-space:nowrap;">
					<table class="table" style="width: 100%; white-space:nowrap;">
						<thead class="thead-light">
							<th>S.no</th>
							<th>Name</th>
							<th>Offer amount</th>
							<th>Offer made at</th>
							<th>Chat now</th>
						</thead>
						@if(Auth::check())
							@if(!$offersmade->isEmpty())
								@foreach($offersmade as $offersmade)
								@php

								$urlid=getUserUuid(Auth::user()->id)."_".getUserUuid($offersmade->user_id)."_".$ads->uuid;
								@endphp
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{get_name($offersmade->user_id)}} </td>
										<td><i class="fa fa-rupee"></i> {{ $offersmade->amount }}</td>
										<td>{{$offersmade->created_at}}</td>
										<td><a href="{{url('chat/seller',$urlid)}}" style="float:right" target="_blank"> Chat </a></td>
									</tr>
								@endforeach
						  	@else
						  		<tr>No Viewer</tr>
						  	@endif
					  	@endif
					</table>
				</div>
		    </div>
		</div>
	</div>
</div>

<div class="modal" id="customer_review_modal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Customer review for seller</h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
			</div>
			<div class="model_close_btn_wrap close_chat_btn_wrap">
                <a href="javascript:void(0)" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
			<!-- Modal body -->
			<div class="modal-body">
				
		        <!-- <form method="post" action="" enctype="multipart/form-data" id="myform"> -->
				<div class="rating star-rating star-5">
				  <input type="radio" name="reviewuser" class="reviewuser" value="1"><i></i>
				  <input type="radio" name="reviewuser" class="reviewuser" value="2"><i></i>
				  <input type="radio" name="reviewuser" class="reviewuser" value="3"><i></i>
				  <input type="radio" name="reviewuser" class="reviewuser" value="4"><i></i>
				  <input type="radio" name="reviewuser" class="reviewuser" value="5"><i></i>
				</div>
					<!-- <div class="multicheck_btn_outer_wrap">
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">1 Star
									<input type="radio" class="reviewuser" name="reviewuser" checked  value="1">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">2 Star
									<input type="radio" class="reviewuser" name="reviewuser"  value="2">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">3 Star
									<input type="radio" class="reviewuser" name="reviewuser"  value="3">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">4 Star
									<input type="radio" class="reviewuser" name="reviewuser"  value="4">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">5 Star
									<input type="radio" class="reviewuser" name="reviewuser"  value="5">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
					</div> -->
					<div class="form-group">
						<textarea class="form-control reviewcommentss" placeholder="Review" rows="7" id="reviewcommentss"name="reviewcommentss" ></textarea>
					</div>

					<div class="form-group">
						<div class="text-danger" id="erroradsreport"></div>
					</div>
					<div class="common_btn_wrap">
					<button type="submit" id="reviewssubmit" class="btn ">Send Review</button>
					</div>
				<!-- </form> -->
				<div class="message_box" style="margin:10px 0px;">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="make_offer_model_wrap">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Make an Offer</h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
			</div>
			<div class="model_close_btn_wrap close_chat_btn_wrap">
                <a href="javascript:void(0)" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="make_offer_input_outer_wrap">
					<div class="make_offer_symbol_wrap">₹</div>
					<div class="make_offer_input_inner_wrap">
						<input  type="number" autocomplete="off" placeholder="Enter Amount" id="makeAnOffer">
						<input  type="hidden" id="make_off_ads_id" value="{{$ads->id}}">
					</div>
				</div>
				<div class="common_btn_wrap">
					<button type="submit" class="makeAnOffer">Send</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="reportads">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Ads Report</h4>
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
			</div>
			<div class="model_close_btn_wrap close_chat_btn_wrap">
                <a href="javascript:void(0)" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
			<!-- Modal body -->
			<div class="modal-body">
				
		        <form method="post" action="" enctype="multipart/form-data" id="myform">
					<input type="hidden" id="uuuid" value="{{$ads->uuid}}">
					<div class="multicheck_btn_outer_wrap">
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">Offensive content
									<input type="radio" class="reportads" name="reportads"  value="offensiveContent">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">Fraud
									<input type="radio" class="reportads" name="reportads"  value="fraud">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">Duplicate Ad
									<input type="radio" class="reportads" name="reportads"  value="duplicateAd">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">Product already sold
									<input type="radio" class="reportads" name="reportads"  value="productAlreadySold">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
						<div class="multicheck_box_wrap radio_btn_wrap form-group">
							<div class="multicheck_outer_wrap">
								<label class="multicheck_box_inner_wrap">Other
									<input type="radio" class="reportads" name="reportads"  value="other">
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<textarea class="form-control commentss" placeholder="Comment" id="commentss"name="commentss" ></textarea>
					</div>


					<div class="form-group">
						<div class="form-check-inline">
							<label ><span class="radiotextsty">Attach File</span>
							<input type="file" id="file" name="file" /> 
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="text-danger" id="erroradsreport"></div>
					</div>
					<div class="common_btn_wrap">
					<button type="submit"id="reportadssubmit" class="btn btn-primary">Send complaint</button>
					</div>
				</form>
				<div class="message_box" style="margin:10px 0px;">
				</div>
			</div>
		</div>
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="safetytips" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-centered" id="exampleModalLongTitle"><center>TIPS FOR A SAFE DEAL</center></h3>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="model_close_btn_wrap close_chat_btn_wrap">
                <a href="javascript:void(0)" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
            <div class="modal-body">
            	<div class="container">
                    <div class="row">
                        <div class="col-md-2">
                            <i class="fa fa-2x fa-credit-card"></i>
                        </div>
                        <div class="col-md-10">
                            <h5 class="sub_titles">Do not enter UPI PIN while receiving money</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <i class="fa fa-2x fa-thumbs-up" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-10">
                            <h5 class="sub_titles">Never give money or product in advance</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <i class="fa fa-2x fa-comments-o" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-10">
                            <h5 class="sub_titles">Keep discussions in OJAAK chat only</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                           <i class="fa fa-2x fa-flag-o" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-10">
                            <h5 class="sub_titles">Report Suspicious users to OJAAK</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer common_btn_wrap">
				<a href="{{route('chatuser',[$ads->uuid,getUserUuid($ads->seller_id)])}}" class="btn" role="button">CONTINUE WITH CHAT</a>
            </div>
        </div>
    </div>
</div>
<div id="fb-root"></div>

@endsection
@section('scripts')
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v6.0&appId=2266097777020076&autoLogAppEvents=1"></script>
<script language="JavaScript" type="text/javascript">
	// Timer
	//toastr.options.preventDuplicates = false;
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
                    window.location.reload();
                    if(response=='1'){
                        setTimeout(function(){ 
                            $('.ajax-loader').css("visibility", "hidden");
                            
                        }, 1000);
                    } 
                }
            });
          }
        });
    }
	if ($('.share_drop_wrap').length > 0){
		$(document).ready(function(){
        	$(".share_drop_wrap h3").click(function(){
            	$(".home_search_outer_wrap").removeClass("active");
	        });
	    });
	}
	if ($('.share_drop_wrap').length > 0){
		$(document).ready(function(){
        // Show hide popover
	        $(".share_drop_wrap a").click(function(){
	            $(".share_drop_wrap").toggleClass("active");
	        });
   		 });
	    $(document).on("click", function(event){
	        var $trigger = $(".share_drop_wrap");
	        if($trigger !== event.target && !$trigger.has(event.target).length){
	            $(".share_drop_wrap").removeClass("active");
	        }            
	    });
	}
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
	if ($('.circle_outer_wrap').length > 0){
		window.onload = function(){
		// var progInc = setInterval(incrementProg, 1000);
		// 	function incrementProg(){
		// 		progressbar = document.querySelector('div[data-progress]');    
		// 		progress = progressbar.getAttribute('data-progress');
		// 		progressbar.setAttribute('data-progress', parseInt(progress,10)+1);
		// 		setPie();
		// 			if (progress == 9){
		// 				clearInterval(progInc);
		// 				viewadpoint();
		// 			}
		// 	}
		// }

		function setPie(){
		var progressbar = document.querySelector('div[data-progress]');
		var quad1 = document.querySelector('.quad1'), 
		quad2 = document.querySelector('.quad2'),
		quad3 = document.querySelector('.quad3'),
		quad4 = document.querySelector('.quad4'); 
		var progress = progressbar.getAttribute('data-progress');
		if(progress <= 2.5){
		quad1.setAttribute('style', 'transform: skew(' + progress * (-90/2.5) + 'deg)');
		}
		else if(progress > 2.5 && progress <=5.0){
		quad1.setAttribute('style', 'transform: skew(-90deg)');
		quad2.setAttribute('style', 'transform: skewY(' + (progress-2.5) * (90/2.5) + 'deg)');
		}
		else if(progress > 5.0 && progress <=7.5){
		quad1.setAttribute('style', 'transform: skew(-90deg)');
		quad2.setAttribute('style', 'transform: skewY(90deg)');
		quad3.setAttribute('style', 'transform: skew(' + (progress-5.0) * (-90/2.5) + 'deg)');
		}  
		else if(progress > 7.5 && progress <=10){
		quad1.setAttribute('style', 'transform: skew(-90deg)');
		quad2.setAttribute('style', 'transform: skewY(90deg)');
		quad3.setAttribute('style', 'transform: skew(-90deg)');
		quad4.setAttribute('style', 'transform: skewY(' + (progress-7.5) * (90/2.5) + 'deg)');
		}  
		}
	}
	function reportad() {
		if('{{ Auth::id()}}'){
			$("#reportads").modal('show');
		}else{
			$("#login").modal('show');
		}
    }
    function viewprofile(){
    	$("#login").modal('show');
    }	
	$(document).ready(function(){


		$(".makeAnOffer").click(function(e){
			//alert($("#makeAnOffer").val());
            e.preventDefault();
        	if ($("#makeAnOffer").val() != '') {

    		 	var fd = new FormData();

	            var makeAnOffer = $("#makeAnOffer").val();
	            var make_off_ads_id = $("#make_off_ads_id").val();
	            var _token = "{{ csrf_token() }}";

                fd.append('makeAnOffer', makeAnOffer); 
                fd.append('make_off_ads_id', make_off_ads_id);
                fd.append('_token', _token); 

	            $.ajax({
	                url: "<?php echo url('/makeAnOffer'); ?>",
	                type: "post",
	                data: fd, 
	                contentType: false, 
	                processData: false, 
				    beforeSend:function(){
						$('.ajax-loader').css("visibility", "visible");
					},
	                success: function(data){
				    	$('.ajax-loader').css("visibility", "hidden");
				    	return false;
	                	//location.reload(); 
	                },
	                error: function(data){
	                	if( data.status === 422 ) {
	                		var errors = $.parseJSON(data.responseText);
	                		var error=errors.errors.file;
	                    	$('#erroradsreport').html(""+error+"");
						}
	                },
				});
            }else{
            	swal("Amount is mandatory");
                //alert("Please select atleast one reason!,Comments is mandatory");
            }
            

        });

		$("#reportadssubmit").click(function(e){
            e.preventDefault();
            if ($("input[name=reportads]:checked").val()) {
            	if ($('#commentss').val() != '') {

                var fd = new FormData(); 
                var files = $('#file')[0].files[0]; 

	            var commentss = $('.commentss').val();
	            var uuuid = $('#uuuid').val();
	            var _token = "{{ csrf_token() }}";
	            var reportads = $("input[name=reportads]:checked").val();

                fd.append('file', files); 
                fd.append('commentss', commentss); 
                fd.append('uuuid', uuuid); 
                fd.append('_token', _token); 
                fd.append('reportads', reportads); 
                
            	$.ajax({
                    url: "<?php echo url('/reportadssubmit'); ?>",
                    type: "post",
                    data: fd, 
                    contentType: false, 
                    processData: false, 
                    success: function(data){
                   	$("#reportads").modal('hide'); 
	                    	swal("Saved!", {
	                  		icon: "success",
	                	}); 
	                	location.reload(); 
                    },
                    error: function(data){
                    	if( data.status === 422 ) {
                    		var errors = $.parseJSON(data.responseText);
                    		var error=errors.errors.file;
	                    	$('#erroradsreport').html(""+error+"");
						}
                    },
				});
	            }else{
	            	swal("Comments is mandatory");
	                //alert("Please select atleast one reason!,Comments is mandatory");
	            }
            }else{
            	swal("Please select atleast one reason!");
                //alert("Please select atleast one reason!,Comments is mandatory");
            }

        });

		$("#reviewssubmit").click(function(e){
            e.preventDefault();
            if ($("input[name=reviewuser]:checked").val()) {
            	if ($('#reviewcommentss').val() != '') {

                
                var commentss = $('.reviewcommentss').val();
	            var _token = "{{ csrf_token() }}";
	            var reportads = $("input[name=reviewuser]:checked").val();

	            var fd = new FormData(); 
                fd.append('commentss', commentss); 
                fd.append('_token', _token); 
                fd.append('starrate', reportads); 
                fd.append('seller_id', "{{$ads->seller_id}}");

            	$.ajax({
                    url: "<?php echo url('/Userajaxreview'); ?>",
                    type: "post",
                    data: fd, 
                    contentType: false, 
                    processData: false, 
                    success: function(data){
                   	$("#customer_review_modal").modal('hide'); 
	                    	swal("Saved!", {
	                  		icon: "success",
	                	}); 
	                	location.reload(); 
                    },
                    error: function(data){
                    	if( data.status === 422 ) {
                    		var errors = $.parseJSON(data.responseText);
                    		var error=errors.errors.file;
	                    	$('#erroradsreport').html(""+error+"");
						}
                    },
				});
	            }else{
	            	swal("Review is mandatory");
	                //alert("Please select atleast one reason!,Comments is mandatory");
	            }
            }else{
            	swal("Please select atleast one star!");
                //alert("Please select atleast one reason!,Comments is mandatory");
            }

        });

    });
   

	function viewadpoint() {
		if('{{Auth::check()}}'){
			var ads_id = $('#viewed_ads_id').val();
				$.ajax({
				  	type:"post",
				  	url:"<?php echo url('/viewedads')?>",
					data:"ads_id="+ads_id,
					success: function(data){
	                   	//alert(data)
	                   	toastr.info(data)  
	                },
				});
		}
		
    }
</script>
<script language="JavaScript" type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>
<script type="text/javascript">
	const shareButton = document.querySelector('.fb-share-button1 a');
	shareButton.addEventListener("click", async () => {
	  try {
	    await navigator.share({ title: "OJAAK Listing", url: "{{url()->current()}}" });
	    console.log("Data was shared successfully");
	  } catch (err) {
	    console.error("Share failed:", err.message);
	  }
	});
</script>
<script type="text/javascript">
    $(document).ready(function () {

    if(!localStorage.getItem("query")===true  ){
        localStorage.setItem("query","");
    }
    setTimeout(function(){
        if(localStorage.getItem("query")!=''){
            $("#searchbox").val(localStorage.getItem("query"));
        }
    }, 200);
    
    
    
    $( "h4.panel-title" ).click(function() {
          $(this).toggleClass( "active" );
        });
    
    $(document).on('click', '.selectcategory', function(){
        var categoryId = $(this).attr('data-category_id');
        localStorage.setItem("categoryId", categoryId);
        window.location.href = "{{url('items')}}";
    });
    
    $(document).on('click', '.category_drop_wrap h3', function(){
        $(".searchbox").val(""); 
        $("#listbox").hide();
    });

    $(document).on('keyup', '.searchbox', function(){
        if($(".searchbox").val().length >= '2'){
            var query = $(this).val();
            $.ajax({
                url:"{{ url('search') }}",
                type:"GET",
                data:{'q':query},
                success:function (data) {
                    $('#listbox').html(data);
                }
            });
        }
    });
    
    $(document).on('click', '.locationinput', function(){
        $("#currentlocationList").show();
    });
    
    $(document).on("click", function(event){
        var $trigger = $(".home_search_outer_wrap");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            // $(".home_search_outer_wrap").removeClass("active");
            $("#listbox").hide();
        }            
    });

    $(document).on('keyup', '.locationinput', function(){
        $("#currentlocationList").hide();
        $('#locationList').html('');
        if($(".locationinput").val().length >= '2'){
            // the text typed in the input field is assigned to a variable 
            var query = $(this).val();
            // call to an ajax function
            $.ajax({
                // assign a controller function to perform search action - route name is search
                url:"{{ url('searchlocation') }}",
                // since we are getting data methos is assigned as GET
                type:"GET",
                // data are sent the server
                data:{'q':query},
                // if search is succcessfully done, this callback function is called
                success:function (data) {
                    // print the search results in the div called country_list(id)
                    $('#locationList').html(data);
                }
            })
            // end of ajax call
        }
    });

    

    $(document).on('click', '.li_product_data', function(){
        //var value = $(this).text();
        var value = $(this).attr('data-cate-search');
        var categoryId = $(this).attr('data-categoryId');
        var subCategoryId = $(this).attr('data-subCategoryId');
        $("#categoryId").val(categoryId);
        $("#subCategoryId").val(subCategoryId);

        localStorage.setItem("categoryId", categoryId);
        localStorage.setItem("subCategoryId", subCategoryId);

        // assign the value to the search box
        $('.searchbox').val(value);
        // after click is done, search results segment is made empty
        $('#listbox').html("");
        localStorage.setItem("query",$("#searchbox").val());
        //window.location.href = "{{url('items')}}";
    });
    
    $(document).on('keyup', '.locationinput', function(){
        if($(".locationinput").val().length <= '1'){
        //console.log('val emp',$("#country").val());
            $("#locationList").hide();
        }else{
            $("#locationList").show();                        
        }
    });
    

    jQuery(window).ready(function() {
        setTimeout(function(){  location() }, 1000);
    });

    function location(){
        navigator.geolocation.watchPosition(
            function(position) {
                $(".currentAddress").html(localStorage.getItem("currentAddress"));
                },
            function(error) {
                if (error.code == error.PERMISSION_DENIED){
                    
                    $('.currentAddress').html('Location blocked. Check browser/phone settings.');
                    $('#currentlocationList ul h3').addClass('current_location_addr_denied');
                    $('#currentlocationList ul h3').removeClass('current_location_addr');
                }
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
    }

    function showPosition(position) {
        latitudeg=position.coords.latitude;
        longitudeg=position.coords.longitude;
        localStorage.setItem("lng",longitudeg);
        localStorage.setItem("lat",latitudeg);
        getcity(latitudeg,longitudeg)
        $(".currentAddress").html(localStorage.getItem("currentAddress"));
    }

    function getcity(latitudeg,longitudeg){

        var _token = "{{ csrf_token() }}";
        $.ajax({
            type: "post",
            url: "<?php echo url('getcity'); ?>",
            data: "latitude="+latitudeg+"&longitude="+longitudeg+"&_token="+_token,
            success: function(data){ 
               
                $.each( data, function( key, value ) {
                    if(key=='cityName'){
                        localStorage.setItem("currentAddress",value);
                        //$("#locationinput").val(value);
                        $(".currentAddress").html(value);
                    }

                    if(key=='state'){
                        localStorage.setItem("currentstate",value);
                    }

                    if(key=='cityId'){
                        localStorage.setItem("currentAddressID",value);
                    }
                               
                });
            }
        });

    }

    $(document).on('click', '.current_location_addr', function(){
        $('#currentlocationList').hide();
        localStorage.setItem("cityId",localStorage.getItem("currentAddressID"));
        localStorage.setItem("cityName",localStorage.getItem("currentAddress"));
        localStorage.setItem("state",localStorage.getItem("currentstate"));
        $("#locationinput").val(localStorage.getItem("currentAddress"));
    });

    $(document).on('click', '.li_locationaddr', function(){
            var cityId = $(this).attr('data-cityid');
            var cityName = $(this).attr('data-cityname');
            var stateId = $(this).attr('data-stateid');
            var areaId = $(this).attr('data-areaid');
            $("#cityId").val(cityId);
            localStorage.setItem("cityId", cityId);
            localStorage.setItem("cityName", cityName);
            localStorage.setItem("state", stateId);
            localStorage.setItem("areaId", areaId);
            
            $('.locationinput').val(cityName);

            // after click is done, search results segment is made empty
            $('#locationList').html("");
            $('#currentlocationList').hide();
    });
    $(document).on('click', '.headerSearch', function(){
        if($('#locationinput').val().length >= '1' || $('#searchbox').val().length >= '1'){
            localStorage.setItem("query",$('#searchbox').val());
            window.location.href = "{{url('items')}}";
        }else{
            toastr.warning('Fill details to search');
        }    
    });
    $(document).on('click', '.current_location_addr_denied', function(){
        swal({
            title: "Geolocation is blocked!",
            text: "Looks like your geolocation permissions are blocked. Please, provide geolocation access in your browser settings and get the nearest ads.",
            icon: "warning",
            //button: "Aww yiss!",
        }); 
    });
   
});
$(document).ready(function(){
    // Check Radio-box
    $(".rating input:radio").attr("checked", false);

    $('.rating input').click(function () {
        $(".rating span").removeClass('checked');
        $(this).parent().addClass('checked');
    });

    $('input:radio').change(
      function(){
        var userRating = this.value;
        //alert(userRating);
    }); 
});

$(document).ready(function () {
    
        $searchdata=$(".ads_listing_search_outer_wrap").html();
        if( isMobile.any() ){
            $(".ads_listing_search_outer_wrap").html('');
            $(".ads_listing_mobile_search_wrap").html($searchdata);
            
        }else{
            $(".ads_listing_mobile_search_wrap").html('');
            $(".ads_listing_search_outer_wrap").html($searchdata);
            
        }

    $("#searchbox").keydown(function (e) {
        if($("#searchbox").val().length >= '2'){  
            if (e.keyCode == 13) {
                localStorage.setItem("query",$("#searchbox").val());
                window.location.href = "{{url('items')}}";
            }
        }
    });
});

</script>
@endsection