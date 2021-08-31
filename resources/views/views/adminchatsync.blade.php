@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/newstyle.css') }}">
<style type="text/css">
	.btn:focus {
	  box-shadow: none!important;
	}
	.mCSB_scrollTools .mCSB_draggerRail {
		width: 12px !important;
	}
	.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
		height: 165% !important;
	}
	.mCSB_scrollTools {
	    opacity: 0.75 !important;
	}
	.mCS-minimal.mCSB_scrollTools .mCSB_draggerRail, .mCS-minimal-dark.mCSB_scrollTools .mCSB_draggerRail {
	    background-color: rgba(0,0,0,0.15) !important;
	}
	.mCSB_dragger_bar {
	    background-color: #000 !important;
	    background-color: rgba(0,0,0,0.75) !important;
	}
	.fa {
    position: relative;
	}

	.badge {
	    font-size: 0.8em;
	    font-weight: bold;
	    display: block;
	    position: absolute;
	    top: -.75em;
	    right: 0.1em;
	    width: 2em;
	    height: 2em;
	    line-height: 2em;
	    border-radius: 50%;
	    text-align: center;
	    padding:0px !important;
	    color: #555555;
    	background: transparent;
	}
	.swal-overlay {
		background-color: rgba(43, 165, 137, 0.45);
	}
	#map {
       position: initial;
        overflow: hidden; 
       height: 240px;
       }
     #map #infowindow-content {
       display: inline;
     }
     
     .pac-container { z-index: 100000 !important; }
     #description {
       font-family: Roboto;
       font-size: 15px;
       font-weight: 300;
     }

     #infowindow-content .title1 {
       font-weight: bold;
     }

     #infowindow-content {
       display: none;
     }


     .pac-card {
       margin: 10px 10px 0 0;
       border-radius: 2px 0 0 2px;
       box-sizing: border-box;
       -moz-box-sizing: border-box;
       outline: none;
       box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
       background-color: #fff;
       font-family: Roboto;
     }

     #pac-container {
       padding-bottom: 12px;
       margin-right: 12px;
     }

     .pac-controls {
       display: inline-block;
       padding: 5px 11px;
     }

     .pac-controls label {
       font-family: Roboto;
       font-size: 13px;
       font-weight: 300;
     }

     #autocomplete {
       background-color: #fff;
       font-family: Roboto;
       font-size: 16px;
       font-weight: 300;
       margin-left: 12px;
       padding: 0 11px 0 13px;
       text-overflow: ellipsis;
       width: 525px;
       height: 35px;
     }

     #autocomplete:focus {
       border-color: #4d90fe;
     }

     #title1{
       color: #fff;
       background-color: #4d90fe;
       font-size: 25px;
       font-weight: 500;
       padding: 6px 12px;
     }
     #target {
       width: 345px;
     }
@media(max-width:767px) {
    #autocomplete {
       background-color: #fff;
       font-family: Roboto;
       font-size: 14px;
       font-weight: 300;
       margin-left: 0px;
       padding: 0 11px 0 13px;
       text-overflow: ellipsis;
       width: 230px;
       height: 30px;
       top:15px;
     }
}
</style>
@endsection

@section('content')
<?php
    $urlid = Request::segment('4');
    $usrid = Request::segment('5');
?>

<div class="container-fluid pl-0 pr-0 page_title_new_bg_wrap">
    <div class="box-size">
        <div class="row">
            <div class="col-md-6 pl-0">
                <div class="profile_breadcum_new_wrap">
                    <nav aria-label="breadcrumb" class="page_title_inner_new_wrap">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">My Profile</a></li>
							<li class="breadcrumb-item active" aria-current="page"><a href="#">Messages</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="container-fluid bg-light admin_chat_outer_wrap">
		<div class="box-size">
			<div class="profile-right-side">
				<div class="billing-name-wrap">
					<div class="row">
						<div class="col-md-12 col-lg-3 chat-list-outer-wrap ">
							<div class="chat_title_outer_wrap">
								<div class="chat-conversation">
									<h6>Conversation</h6>
								</div>
								<div class="search_bar_chats">
									<div class="input-group">
								        <span class="input-group-append bg-white border-right-0">
								            <span class="input-group-text bg-transparent">
								                <i class="icon fa fa-search"></i>
								            </span>
								        </span>
								        <input type="text" class="form-control" placeholder="Search Chats" id="search_input" onkeyup="searchFunction()">
								    </div>
								</div>
								<div class="recent_chat_outer_wrap">
								  <select id="selectchatfillter">
								    <option value="3" selected="">All Chats</option>
								    <option value="1">Read</option>
								    <option value="0">Unread</option>
								  </select>
								</div>
							</div>
							<ul id="search_sorting" class="chat_listing_outer_wrap">
									
							</ul>
						</div>
						
						<div class="col-md-12 col-lg-9 pr-0 chat-view-outer-wrap">
							<div class="row chat-view-inner-wrap">
								<div class="col-md-12 col-lg-8 pr-0 chat_msg_col_wrap">
									@if(isset($chat->ads_id) && $chat->ads_id !='')
										<div class="ads_detail_inner_wrap">
											<a href="{{route('adsview.getads',getAdsUuid($chat->ads_id))}}">
												<div class="ads_img_outer_wrap">
													<img src="{{url('public/uploads/ads/')}}/{{get_adsphoto($chat->ads_id)}}" title="" >
												</div>
												<h2>{{get_adsname($chat->ads_id)}}</h2>
												<span>₹ {{get_adsprice($chat->ads_id)}}</span>
											</a>
										</div>
									@else
										<div class="ads_detail_inner_wrap">
										</div>
									@endif
									<div class=" chat-box bg-maxwidth">
										
									</div>
								</div>
								<div class="col-md-12 col-lg-4 pl-0	chat_user_info_outer_wrap pr-0">
									<div class="chat-user-details">
										<div class="user-details">
											<img src="{{asset('public/uploads/profile/small/'.$user->photo)}}"alt="avatar">
										</div>
									</div>
									<div class="user-details-inner-wrap ">
										<h5 style="cursor: pointer;" onclick="userProfileOpen()">{{$user->name}}</h5>
										@if(Auth::check() && !empty($privacy) && $privacy->online == '1')
		                                    @if(Auth::user()->id!=$user->id )
		                                       @if(Cache::has('user-is-online-' . $user->id))
		                                            <h6>Online</h6>
		                                        @else
		                                            <h6 class="offline">Offline</h6>
		                                        @endif
		                                     @endif 
		                                @else
		                                	<h6 class="offline">Offline</h6>  
		                                @endif
									</div>
									<div class="user-information-wrap">
										<h6>Personal Information</h6>
										<div class="row user-information-inner-wrap">
											<div class="col-md-2 chat-user-information-wrap">
												<i class="fa fa-phone" aria-hidden="true"></i>
											</div>
											<div class="col-md-10 chat-user-information-wrap">
												<h5>{{$user->phone_no}}</h5>
											</div>
										</div>
										<div class="row user-information-inner-wrap">
											<div class="col-md-2 chat-user-information-wrap">
												<i class="fa fa-envelope" aria-hidden="true"></i>
											</div>
											<div class="col-md-10 chat-user-information-wrap">
												<h5>{{$user->email}}</h5>
											</div>
										</div>
										<div class="chat_user_option_wrap">
											
											<div class="phone_call-btn_outer_wrap">
												<a href="tel:{{$user->phone_no}}">
													<i class="fa fa-phone" aria-hidden="true"></i> 
												</a>
											</div>
											<div class="close_chat_btn_wraps">
												<a href="{{route('chathistory',$usrid)}}">
													<i class="fa fa-times" aria-hidden="true"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
   

      
@endsection

@section('scripts')
<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
<script type="text/javascript">
	function statuschanged(){
			var _token = "{{ csrf_token() }}";
			var chatid="{{$chat->unique_chats_id}}";
			$.ajax({
			    type: "post",
			    url: "<?php echo url('chat/readstatuschanges'); ?>",
			    data: "_token="+_token+"&chatid="+chatid,
			    success: function(data){
			    	//$(".msg_card_body").html(data);
			    	
			    }
			});
	}
	var userurlredirect="{{url('profile/info-all/')}}/{{$user->uuid}}";
	
	function userProfileOpen(){
		window.open(userurlredirect, '_blank');
	}
	$(document).ready(function(){
   	
   		$("#chatnotify").click(function(e){
   			var notify=$(".chat_notification").html();
   			if(notify=='0'){
   				swal({
   						title: "Notification!",
						text: "No messages, yet?",
						icon: "success",
						button:false,
                	});
   			}else{
   				var swalmsg=notify+" New UnRead Chats ";
   				swal({
   						title: "Notification!",
						text: swalmsg,
						icon: "success",
						button:false,
                	});
   			}
   		});
   		
	});

	$(document).ready(function(){
		function statuschanged(){
			var _token = "{{ csrf_token() }}";
			var chatid="{{$chat->unique_chats_id}}";
			$.ajax({
			    type: "post",
			    url: "<?php echo url('chat/readstatuschanges'); ?>",
			    data: "_token="+_token+"&chatid="+chatid,
			    success: function(data){
			    	//$(".msg_card_body").html(data);
			    	
			    }
			});
		}
	});
	$('.ajax-loader').css("visibility", "visible");
	setTimeout(function(){ $('.ajax-loader').css("visibility", "hidden"); }, 2000);
	function reportUser() {
        $("#reportuser").modal('show');
    }
    function blockUser() {
        $("#block-user-form").submit();
    }

   function reportAbuse(){
    	var commentss = $('#commentss').val();
        var uuuid = $('#uuuid').val();
        var _token = "{{ csrf_token() }}";
        var reportuser = $('#reportuser').val();
        var fd = new FormData(); 
        fd.append('commentss', commentss); 
        fd.append('uuuid', uuuid); 
        fd.append('_token', _token); 
        fd.append('reportuser', reportuser);
        
        swal({
		  title: "Are you sure?",
		  text: "Report User for Abuse!",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
		  	$.ajax({
                    url: "<?php echo url('/reportusersubmit'); ?>",
                    type: "post",
                    data: fd, 
                    contentType: false, 
                    processData: false, 
                    success: function(data){  
                        swal("Report User! Successfully", {icon: "success",});
                    }
                });
		    } 
		});
    }
	$(document).ready(function(){
		readmessage();
		//statuschanged();
		setInterval(function(){
			 readmessage();
			 statuschanged();
			},100000); 
		
	});
	
	function readmessage(){
			//$(".chat-box #mCSB_2_container").html(data);
			var _token = "{{ csrf_token() }}";
			var urlid="{{$urlid}}";
			$.ajax({
			    type: "post",
			    url: "<?php echo url('profile/chathistory/msg/get/'); ?>",
			    data: "urlid="+urlid+"&_token="+_token+"&userdata={{$usrid}}",
			    beforeSend:function(){
					$('.ajax-loader').css("visibility", "visible");
				},
			    success: function(data){
			    	$(".chat-box #mCSB_2_container").html(data);
			    	// setTimeout(function(){
			    	// 	//$(".chat-box").mCustomScrollbar("update");
			    	// 	$(".chat-box").mCustomScrollbar("scrollTo","last");
			    	// },1000);
			    	setTimeout(function(){$('.ajax-loader').css("visibility", "hidden");},2000);
			    },
		        complete: function () { 
		            setTimeout(function(){
			    		$(".chat-box").mCustomScrollbar("update");
			    		$(".chat-box").mCustomScrollbar("scrollTo","last");
			    	},1000);            
		        }
			});
	}   	
	
	function userdata(){
			var msg_status=localStorage.getItem("message_status");
			setTimeout(function(){
				$.ajax({
				    type: "post",
				    url: "<?php echo url('/profile/chathistory/getchatdata'); ?>",
					    data:"message_status="+msg_status+"&userdata={{getUserId($usrid)}}",
				    beforeSend:function(){
						$('.ajax-loader').css("visibility", "visible");
					},
				    success: function(data){ 
				    	$('.ajax-loader').css("visibility", "hidden");
				    	$(".chat_notification").html(data.notification);
				    	//alert(data.notification)
				    	$(".chat_listing_outer_wrap #mCSB_1_container").html(data.msg);  
				    	var activeid="#chatlist_<?php echo $urlid; ?>";
		        	   $(activeid).addClass('active'); 
		        	   //alert('l')               
				    }
				});
			}, 1000);
	}

	$( window ).ready(function() {
		localStorage.setItem("message_status","3");
		userdata();
	    setInterval(function(){  
	        userdata();
	    }, 100000);
	    $( "#selectchatfillter" ).change(function() {

		  	localStorage.setItem("message_status",$(this).val());
		  	userdata();
		});
	    
	});
</script>

<script type="text/javascript">
		$(window).on("load",function(){
            $(".chat_listing_outer_wrap, .chat-box").mCustomScrollbar({
            	autoHideScrollbar: true,
            	alwaysShowScrollbar: 1,
            	theme: "minimal",
            	// autoHideScrollbar: false,
            	// theme: "dark",
            });
   
			toastr.options.preventDuplicates = false;
			toastr.options.progressBar = true;
			
			// setInterval(function(){
			// 	$(".chat-box").mCustomScrollbar("update");
	  //   	},1500);

        }); 

</script>
@endsection