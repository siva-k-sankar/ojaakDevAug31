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
    $urlid = Request::segment('2');
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
                            <li class="breadcrumb-item"><a href="#">My Profile</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">Messages</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="container-fluid bg-light chat_new_outer_wrap">
		<div class="box-size">
			<div class="profile-right-side">
				<div class="billing-name-wrap">
					<div class="row">
						<div class="col-md-12 col-lg-3 chat-list-outer-wrap">
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
									<div class=" chat-box bg-white" id="chat_msg_body">
										
									</div>
									
									<div class="chatting-area">
										<div class="row">
											<div class="col-md-1 pl-0 pr-0 col-sm-1">
												<div class="toggle_btn_wrap">
													<i class="arrow_btn_wrap fa fa-angle-up" aria-hidden="true"></i>
												</div>
												<div class="quick_msg_ouiter_wrap">
													<div class="msg_suggestion_outer_wrap">
														<div class="msg_suggestion_inner_wrap">
															<h2 class="quick_message">Is it available</h2>
															<h2 class="quick_message">Hello</h2>
															<h2 class="quick_message">Make an offer</h2>
															<h2 class="quick_message">What's the lowest price?</h2>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-1 pr-0 col-sm-1">
												<div class="chat-options">
													<button id="attachement" type="submit" class="btn"> <i class="fa fa-plus" aria-hidden="true"></i></button>
													<div class="attachementbox">
														<div class=" px-2 m-1 pt-2">
															<input type="file" name="chatimage" id="chatimage" class="inputfile" accept="image/*">
															<label for="chatimage">
																<span class="fa fa-camera m-1 "></span>
																<span class="h6">
																	<span>Share Image</span>
																</span>
															</label>
														</div>
														<div class=" px-2 m-1">
															<input type="file" name="chatvideo" id="chatvideo" class="inputfile" accept="video/*">
															<label for="chatvideo">
																<span class="fa fa-video-camera m-1"></span>
																<span class="h6">
																	<span>Share Video</span>
																</span>
															</label>
														</div>
														<div  class="share_location_outer_wrap px-2 pb-2 m-1" data-toggle="modal" data-target=".maplocation">
															<span class="fa fa fa-map-marker m-1"></span>
															<span class="h6">
																<span>Share location</span>
															</span>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-7 pl-0 col-sm-7">
												<div class="chat-area">
													<input type="text" class="message-wrap" id="typemessage" placeholder="Type Your Message">
												</div>
											</div>
											<div class="col-md-3 col-sm-3">
												<div class="chat-send">
													<button type="button" class="btn" id="sendmessage"> <h6>SEND</h6></button>
												</div>
											</div>
										</div>
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
		                                @if($blockedUsers == 1)
		                                	<h3 style="margin: 0 auto;">User Blocked</h3>  
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
											<div class="report_new_user_wrap" title="Report Abuse">
												<a href="javascript:void(0);" onclick="reportAbuse()" >
													<i class="fa fa-flag" aria-hidden="true"></i>
												</a>
												<form role="form" action="#" method="POST" id='report-user-forms' style="display: none;">
													<input type="hidden" name="uuuid" id="uuuid" value="{{$user->uuid}}" />
													<input type="hidden" name="commentss" id="commentss" value="Report Abuse" />
													<input type="hidden" name="reportuser" id="reportuser" value="Report Abuse" />
												</form>
											</div>

		                                @if($blockedUsers == 1)

											<div class="phone_call-btn_outer_wrap" title="Un Block User">
												<a href="javascript:void(0);" onclick="unBlockUser()">
													<i class="fa fa-user-plus" aria-hidden="true"></i> 
												</a>
												<form role="form" action="{{route('setting.privacy.manageusersunblockchat')}}" method="POST" id='unblock-user-form'>
													@csrf
														<input type="hidden" name="user" value="{{$user->id}}" />
														<input type="hidden" name="chat" value="chat" />
												</form>
											</div>
										@else
											<div class="phone_call-btn_outer_wrap" title="Block User">
												<a href="javascript:void(0);" onclick="blockUser()">
													<i class="fa fa-user-times" aria-hidden="true"></i> 
												</a>
												<form role="form" action="{{route('setting.privacy.manageusersblock')}}" method="POST" id='block-user-form'>
													@csrf
														<input type="hidden" name="user" value="{{$user->id}}" />
														<input type="hidden" name="chat" value="chat" />
												</form>
											</div>
		                                @endif
											<div class="phone_call-btn_outer_wrap" title="Call">
												<a href="tel:{{$user->phone_no}}">
													<i class="fa fa-phone" aria-hidden="true"></i> 
												</a>
											</div>
											<div class="close_chat_btn_wraps" title="Close">
												<a href="{{route('chat')}}">
													<i class="fa fa-times" aria-hidden="true"></i>
												</a>
											</div>
										</div>
									</div>
									<div class="search_indivi_msg_wrap">
										<input type="text" id="individual_msg" onkeyup="search_chat_msg()" placeholder="Find message.." title="Find message">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<div class="modal fade" id="safetytips" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-centered" id="exampleModalLongTitle"><center>TIPS FOR A SAFE DEAL</center></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                        <i class="fa fa-2x fa-credit-card"></i>
                    </div>
                    <div class="col-md-10">
                        <h5 class="sub_titles">Do not enter UPI PIN while receiving money</h5>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                        <i class="fa fa-2x fa-thumbs-up" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-10">
                        <h5 class="sub_titles">Never give money or product in advance</h5>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                        <i class="fa fa-2x fa-comments-o" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-10">
                        <h5 class="sub_titles">Keep discussions in OJAAK chat only</h5>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                       <i class="fa fa-2x fa-flag-o" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-10">
                        <h5 class="sub_titles">Report Suspicious users to OJAAK</h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>    



<div class="modal fade maplocation" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Location</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body px-0 py-0">
			<input id="autocomplete" name="map" class="controls" type="text" placeholder="Search Places">
      		<div id="map"></div>
		</div>
    	<div class="modal-footer d-flex justify-content-center">
    		<button type="button" id="chatlocation" class="btn btn-primary">Share Location</button>
    	</div>
    </div>
  </div>
</div>      
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

	var userurlredirect="{{url('profile/info/')}}/{{$user->uuid}}";
	
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
    	swal({
			title: "Are you sure?",
			text: "Once Blocked, you will not be able to receive message form this user!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$("#block-user-form").submit();
					//window.location.href = "http://www.w3schools.com";
				} else {
					swal("This chat is safe!");
				}
		});
        
    }

    function unBlockUser() {
    	swal({
			title: "Are you sure?",
			text: "Do you want to unblock this user?",
			icon: "warning",
			buttons: true,
			dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					$("#unblock-user-form").submit();
				} else {
					swal("This chat is safe!");
				}
		});
        
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
		
		// jQuery.event.special.touchstart = {
		//   setup: function( _, ns, handle ) {
		//       this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
		//       this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
		//       this.addEventListener("wheel", handle, { passive: !ns.includes("noPreventDefault") });
		//   }
		// };

	    setTimeout(function(){
			readmessage();
			statuschanged();
	        userdata();
	    },1000); 

		// setInterval(function(){
		// 	 readmessage();
		// 	 statuschanged();
		// 	},100000); 
		/*setInterval(function(){
			statuschanged();
			},100000); */
		
	});
	$("#typemessage").keyup(function(event) { 
	        if (event.keyCode === 13) { 
	        	sendmessage();
	        } 
	}); 
	$("#sendmessage").click(function(event) { 
	    setTimeout(function(){
	            	sendmessage();
	        	},2000); 
	});

	/**/
    $(".quick_message").click(function(event) { 
        //alert ($(this).html());
        //console.log($(this).html());
        var typemessage = $(this).html();
	    var _token = "{{ csrf_token() }}";
	    var urlid="{{$urlid}}";
	    if(typemessage!=""){
	        $.ajax({
	            type: "post",
	            url: "<?php echo url('chat/sync/SaveChat/'); ?>",
	            data: "typemessage="+typemessage+"&urlid="+urlid+"&_token="+_token,
	            beforeSend:function(){
					$('.ajax-loader').css("visibility", "visible");
				},
	            success: function(data){
	            	$('#typemessage').val('');
	            	$('.ajax-loader').css("visibility", "hidden");
	            	readmessage();
	            	statuschanged();
	       //      	setTimeout(function(){
			     //    	$(".chat-box").mCustomScrollbar("scrollTo","bottom");
			    	// },1000)
	            }
	       });
	         
	    }else{
	        swal("Message Empty!", { icon: "warning", });
	    }
    });
	function sendmessage(){
	   	var typemessage = $('#typemessage').val();
	    var _token = "{{ csrf_token() }}";
	    var urlid="{{$urlid}}";
	    if(typemessage!=""){
	        $.ajax({
	            type: "post",
	            url: "<?php echo url('chat/sync/SaveChat/'); ?>",
	            data: "typemessage="+typemessage+"&urlid="+urlid+"&_token="+_token,
	            beforeSend:function(){
					$('.ajax-loader').css("visibility", "visible");
				},
	            success: function(data){
	            	$('#typemessage').val('');
	            	$('.ajax-loader').css("visibility", "hidden");

	            	readmessage();
	            	statuschanged();
	       //      	setTimeout(function(){
			     //    	$(".chat-box").mCustomScrollbar("scrollTo","bottom");
			    	// },1000)
	            }
	       });
	         
	    }else{
	        swal("Message Empty!", { icon: "warning", });
	    }
	}
	function readmessage(){
			//$(".chat-box #mCSB_2_container").html(data);
			var _token = "{{ csrf_token() }}";
			var urlid="{{$urlid}}";
			$.ajax({
			    type: "post",
			    url: "<?php echo url('chat/sync/GetChat/'); ?>",
			    data: "urlid="+urlid+"&_token="+_token,
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
				    url: "<?php echo url('/chat/userdata'); ?>",
				    data:"message_status="+msg_status,
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
		//userdata();
	    setInterval(function(){  
	        userdata();
	        readmessage();
	    }, 30000);
	    $( "#selectchatfillter" ).change(function() {

		  	localStorage.setItem("message_status",$(this).val());
		  	userdata();
		});
	    
	});
</script>
<script>
$(document).ready(function(){
	$(document).on('change', '#chatimage', function(){
		$(".attachementbox" ).toggleClass( "active" );
		var name = document.getElementById("chatimage").files[0].name;
		var form_data = new FormData();
		var ext = name.split('.').pop().toLowerCase();
		if(jQuery.inArray(ext, ['png','jpg','jpeg']) == -1) 
		{	
			swal("Image formats supported by chat include jpg, jpeg and png only ", { icon: "warning", });die;
		}
		var oFReader = new FileReader();
		oFReader.readAsDataURL(document.getElementById("chatimage").files[0]);
		var f = document.getElementById("chatimage").files[0];
		var fsize = f.size||f.fileSize;
		if(fsize > 500000) // 500 KB for bytes.
		{
			swal("The file exceeds the maximum size of 2Mb", { icon: "warning", });die;
		}else{
			form_data.append("chatimage", document.getElementById('chatimage').files[0]);
			form_data.append("urlid", "{{$urlid}}");
			$.ajax({
				url:"<?php echo url('chat/sync/SaveChat/'); ?>",
				method:"POST",
				data: form_data,
				contentType: false,
				cache: false,
				processData: false,
				beforeSend:function(){
					$('.ajax-loader').css("visibility", "visible");
				}, 
				success:function(data)
				{
					$('.ajax-loader').css("visibility", "hidden");
	            	readmessage();
	            	statuschanged();
					// setTimeout(function(){
			  //       	$(".chat-box").mCustomScrollbar("scrollTo","bottom");
			  //   	},1000)
				}
			});
		}
	});
	$(document).on('change', '#chatvideo', function(){
		$(".attachementbox" ).toggleClass( "active" );
		var name = document.getElementById("chatvideo").files[0].name;
		var form_data = new FormData();
		var ext = name.split('.').pop().toLowerCase();
		if(jQuery.inArray(ext, ['ogg','mp4','3gp']) == -1) 
		{
			swal("Video formats supported by chat include ogg, mp4 and 3gp only", { icon: "warning", });die;
		}
		var oFReader = new FileReader();
		oFReader.readAsDataURL(document.getElementById("chatvideo").files[0]);
		var f = document.getElementById("chatvideo").files[0];
		var fsize = f.size||f.fileSize;
		if(fsize > 2097152) // 2 mb for bytes.
		{
			swal("The file exceeds the maximum size of 2Mb", { icon: "warning", });die;
		}else{
			form_data.append("chatvideo", document.getElementById('chatvideo').files[0]);
			form_data.append("urlid", "{{$urlid}}");
			$.ajax({
				url:"<?php echo url('chat/sync/SaveChat/'); ?>",
				method:"POST",
				data: form_data,
				contentType: false,
				cache: false,
				processData: false,
				beforeSend:function(){
					$('.ajax-loader').css("visibility", "visible");
				}, 
				success:function(data)
				{
					$('.ajax-loader').css("visibility", "hidden");
	            	readmessage();
	            	statuschanged();
					// setTimeout(function(){
			  //       	$(".chat-box").mCustomScrollbar("scrollTo","bottom");
			  //   	},1000)
				}
			});
		}
	});
	$(document).on('click', '#chatlocation', function(){
		$(".attachementbox" ).toggleClass( "active" );
		$(".maplocation").modal('hide');
		var form_data = new FormData();
		form_data.append("latitude", document.getElementById('latitude').value);
		form_data.append("longitude", document.getElementById('longitude').value);
		form_data.append("location", document.getElementById('latitude').value);
		form_data.append("urlid", "{{$urlid}}");
		$.ajax({
				url:"<?php echo url('chat/sync/SaveChat/'); ?>",
				method:"POST",
				data: form_data,
				contentType: false,
				cache: false,
				processData: false,
				beforeSend:function(){
					$('.ajax-loader').css("visibility", "visible");
				}, 
				success:function(data)
				{
					$('.ajax-loader').css("visibility", "hidden");
	            	readmessage();
	            	statuschanged();
					// setTimeout(function(){
			  //       	$(".chat-box").mCustomScrollbar("scrollTo","bottom");
			  //   	},1000); 
				}
		});
		
	});
	
	$(document).on('click', '.msg_delete_indiv', function(){
		var msgid = $(this).attr('data-chatmessage');
		var msgtype = $(this).attr('data-chattype');
		var chatMid=".chat_M_"+msgid;
		swal({
			title: "Are you sure?",
			text: "Once deleted, you will not be able to recover this Conversation !",
			icon: "warning",
			buttons: true,
			dangerMode: true,
			})
			.then((willDelete) => {
			if (willDelete) {
				
				var form_data = new FormData();
				form_data.append("msgid", msgid);
				form_data.append("msgtype", msgtype);
				form_data.append("_token", "{{ csrf_token() }}");
				$.ajax({
						url:"<?php echo url('chat/deleteconversation'); ?>",
						method:"POST",
						data: form_data,
						contentType: false,
						cache: false,
						processData: false,
						success:function(data)
						{	
							if(data==1){
								$(chatMid).attr('style','display:none');
								toastr.success('Conversation deleted!');
							}else{
								toastr.warning('Conversation not deleted yet!');
							}
						
						}
				});
			} else {
				swal("Your Conversation is safe!");
			}
		});
		
		
	});

});
</script>
<script>
    

    var latitudeg=13.0827;
    var longitudeg=80.2707;
    var zoomlevel=12;

    createInputOfLatLang(latitudeg,longitudeg);
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
        initAutocomplete();
    }
    
    function showPosition(position) {
        $("#addresshow").attr("disabled","disabled");
        var google;
        latitudeg=position.coords.latitude;
        longitudeg=position.coords.longitude;
        /*console.log('lat'+latitudeg)
        console.log('lng'+longitudeg)*/
        zoomlevel=16;
        createInputOfLatLang(latitudeg,longitudeg);
    }

    function initAutocomplete() {
        $("#addresshow").attr("disabled","disabled");

        var mapProp= {
            center:new google.maps.LatLng(latitudeg,longitudeg),
            zoom:zoomlevel,
            mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
            },
            disableDefaultUI: true, // a way to quickly hide all controls
            mapTypeControl: true,
            scaleControl: true,
            zoomControl: true,
            zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE 
            },
            
        };
        var map=new google.maps.Map(document.getElementById("map"),mapProp);


        var input = document.getElementById('autocomplete');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
            map.fitBounds(bounds);
            var bound = bounds.getCenter();
            var data=JSON.stringify(bound);
            var parsedata = JSON.parse(data);
            createInputOfLatLang(parsedata.lat,parsedata.lng);
        });

        map.addListener('click', function(e) {
            //alert(e.latLng.lat() + ", " + e.latLng.lng());
            placeMarker(e.latLng, map);
            createInputOfLatLang(e.latLng.lat(),e.latLng.lng());
        });
        

        var marker;
        function placeMarker(location) {
            if (marker) {
                //if marker already was created change positon
                marker.setPosition(location);
            } else {
                //create a marker
                marker = new google.maps.Marker({          
                    position: location,
                    map: map,
                    draggable: false
                });
            }
        }

    }

    function createInputOfLatLang(latitude,longitude) {
        $("#addresshow").attr("disabled","disabled");
        $( "#latitude" ).remove();
        $( "#longitude" ).remove();    
        $( ".user-details-inner-wrap" ).prepend( '<input type="hidden" id="latitude" name="latitude" value="'+ latitude +'"><input type="hidden" id="longitude" name="longitude" value="'+longitude+'">' );
    }
</script>
<script type="text/javascript">
		$(window).on("load",function(){
            $(".chat_listing_outer_wrap, .chat-box").mCustomScrollbar({
            	// autoHideScrollbar: true,
            	// alwaysShowScrollbar: 1,
            	// theme: "minimal",
            	autoHideScrollbar: false,
            	theme: "rounded-dark",
            });
   
			toastr.options.preventDuplicates = false;
			toastr.options.progressBar = true;
			
			// setInterval(function(){
			// 	$(".chat-box").mCustomScrollbar("update");
	  //   	},1500);

        }); 

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4
&libraries=places&callback=initAutocomplete"
         async defer></script>
@endsection