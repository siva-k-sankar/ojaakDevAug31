@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/newstyle.css') }}">
<style type="text/css">
.btn:focus {
  box-shadow: none !important;
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
</style>
@endsection

@section('content')
<?php
    $urladsid = Request::segment('3');
    $urlsellerid = Request::segment('4');
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
					<div class="row ">
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
								        <input type="text" class="form-control" id="search_input" onkeyup="searchFunction()" placeholder="Search Chats">
								    </div>
								</div>
								<!-- <div class="recent-chat-wrap">
									<h6>Recent Chats<i class='fa fa-angle-down'></i></h6>
								</div>
								<div class="serch_outer_wrap text-center">
									<div class="bd-example p-2">
										<button type="button" class="btn btn-outline-success active" id="chat_read_all">ALL</button>
										<button type="button" class="btn btn-outline-success" id="chat_read_read">READ</button>
										<button type="button" class="btn btn-outline-success" id="chat_read_unread">UNREAD</button>
									</div>
								</div> -->
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
						
						<div class="col-md-12 col-lg-9 pr-0 pl-0 chat-view-outer-wrap">
							<div class="chat_without_user_wrap">
								<i class="fa fa-user-circle" aria-hidden="true"></i>
								<h3>Select a chat to view conversation</h3>
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
	function reportUser() {
        $("#reportuser").modal('show');
    }
    function ChooseChatToastMsg(){
    	swal({
			title: "Notification!",
			text: "Select a chat to Access ",
			icon: "success",
			button:false,
    	});
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
   				var swalmsg=notify+" New UnRead Chats";
   				swal({
   						title: "Notification!",
						text: swalmsg,
						icon: "success",
						button:false,
                	});
   			}
   		});

	});
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
				    }
				});
			}, 500);
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
            	// autoHideScrollbar: true,
            	// alwaysShowScrollbar: 1,
            	// theme: "minimal",
            	autoHideScrollbar: false,
            	theme: "rounded-dark",
            });
    	});

	    
	</script>
@endsection