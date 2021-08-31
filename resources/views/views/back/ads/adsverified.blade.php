@extends('back.layouts.app')
@section('styles')
<style type="text/css">
	.scroll::-webkit-scrollbar {
        width: 2px;
}
.scroll::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
}
 
.scroll::-webkit-scrollbar-thumb {
    background-color: #605ca8;
    outline: 1px solid slategrey;
}
.scroll-image{
	height: 300px !important; overflow: auto; white-space: nowrap;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.css" />
@endsection
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> User Ads Verification</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-6">
	          	<div class="box ">
		            <!-- Add the bg color to the header using any of the bg-* classes -->
		            <div class="box-header bg-purple">
		              	<center>
		              	<h3 class="box-title ">Ads Details</h3>
		                </center>
		            </div>
		            <div class="box-footer no-padding">
			            <ul class="nav nav-stacked ">
			            	<li><a href="{{route('admin.users.view',getUserUuid($ads->seller_id))}}">Ad Posted By <span class="pull-right   ">{{get_name($ads->seller_id)}} [ Userid : {{$ads->seller_id}} ]</span></a></li>
			            	<li><a href="#">Ad Type <span class="pull-right   ">@if($ads->type =='0') Free @else Premium @endif</span></a></li>
			            	<li><a href="#">Title <span class="pull-right   ">{{$ads->title}}</span></a></li>
			            	<!-- <li><a href="#">Ads Primary id <span class="pull-right   ">{{$ads->id}}</span></a></li> -->
			            	<li><a href="#">Display Ads id <span class="pull-right   ">{{$ads->ads_ep_id}}</span></a></li>
			                <li><a href="#">Description </a>
			                	<h5 class="text-justify" style="padding-left: 10px;padding-right: 10px;">{!! $ads->description !!}</h5>
			                </li>
			                <li><a href="#">Price <span class="pull-right ">{{$ads->price}}</span></a></li>
			               <!--  <li><a href="#">Tags <span class="pull-right ">
			                	@foreach($tags as $key=>$tag)
			                		<span class="bg-green badge">{{$tag}}</span>
			                	@endforeach
			                </span></a></li> -->
			                <li><a href="#">Area <span class="pull-right ">{{get_areaname($ads->area_id)}}</span></a></li>
			                <li><a href="#">City <span class="pull-right ">{{get_cityname($ads->cities)}}</span></a></li>
			                <li><a href="#">State <span class="pull-right ">{{get_state($ads->cities)}}</span></a></li>
			                <li><a href="#">Images</a>
			                	<div class="scroll scroll-image">
									@if(!empty($adsimage))
			                			@foreach($adsimage as $key =>$image)
			                				<center>
			                					<img class="img-responsive  img-thumbnail " style="margin: 5px;"src="{{asset('public/uploads/ads/'.$image->image)}}"  alt="Proof Image">
			                				</center>
			                			@endforeach
			                		@endif
			                	</div>
			                </li>
			            </ul>
		            </div>
		        </div>
		        <!-- /.widget-user -->
	        </div>
	        
	        <!-- /.col -->
	        <div class="col-xs-6">
	          	<div class="box ">
		            <!-- Add the bg color to the header using any of the bg-* classes -->
		            <div class="box-header bg-purple">
		            	<center>
		              	<h3 class="box-title ">Custom Fields Values</h3>
		                </center>
		            </div>
		            <div class="box-footer no-padding">
		            	<ul class="nav nav-stacked">
		            		@if(!empty($postvalues))
			                	@forelse($postvalues as $key =>$field)
			                		<li><a href="#">{{get_fieldname($field->field_id)}}<span class="pull-right   ">{{get_fielddata($field->field_id,$field->value)}}</span></a></li>
			                		@empty
			                		<li><a href="#">No Customfield</a></li>
			                	@endforelse
			               @endif
			            </ul>
		            </div>
		        </div>
		        @if(!$reasons->isEmpty())
		        <div class="box ">
		            <div class="box-header  bg-purple">
		            	<center>
		              		<h3 class="box-title widget-user-username">Rejected Reason's</h3>
		              	</center>
		            </div>
            		<!-- /.box-header -->
		            <div class="box-body table-responsive">
		              <table class="table table-striped">
		                	<tbody>
		                		<tr>
		                  			<th style="width: 50px">#</th>
					                <th>Reasons</th>
					                <th>Rejected By</th>
					                <th style="width: 30%">Rejected Date</th>
					            </tr>
		                		@foreach($reasons as $key =>$reason)
		                		<tr>
		                  			<td>{{++$key}}</td>
					                <td>{{$reason->reason}}</td>
					                <td>{{getUserEmail($reason->rejected_by)}}</td>
					                <td>{{$reason->created_at}}</td>
					            </tr>
					            @endforeach
		              		</tbody>
		          		</table>
		            </div>
            			<!-- /.box-body -->
          		</div>
          		@endif
		        <!-- /.widget-user -->
		        <div class="box ">
		            <!-- Add the bg color to the header using any of the bg-* classes -->
		            <div class="box-header bg-purple">
		            	<center>
		              	<h3 class=" box-title ">Actions</h3>
		                </center>
		            </div>
		            <div class="box-body">
		            	<div class="col-md-12 col-sm-6 col-xs-12 " id='actionform'>
		            		<center>
		            			<a class="btn bg-orange btn-app" href="{{ URL::previous() }}">
	                				<i class="fa fa-undo"></i> Back
	              				</a>
		            			<a class="btn bg-navy btn-app" href="{{route('admin.ads.edit',$ads->uuid)}}">
	                				<i class="fa fa-pencil-square-o"></i> Edit
	              				</a>
	              				@if($ads->status==0)
					          	<button type="button" onclick="verifyads('{{$ads->id}}')" class="btn bg-olive btn-app"><i class="fa fa-check-square-o"></i> Approved</button>
					          	@endif
					          	@if($ads->status==1 || $ads->status==4)
					          	<button type="button" id="block" class="btn bg-red btn-app"><i class="fa fa-times-circle"></i>@if($ads->status==4) Un @endif Blocked</button>
					          	<!-- <form action="{{route('admin.ads.block',$ads->id)}}" method="POST" id="block-ads-{{$ads->id}}" style="display:none;">
                                            @csrf
                                </form> -->
                                @endif
					          	<form action="{{route('admin.ads.verified',$ads->id)}}" method="POST" id="verify-ads-{{$ads->id}}" style="display:none;">
                                            @csrf
                                </form>
                                @if($ads->status==0)
	              				<a class="btn bg-maroon btn-app" id="rejectedbtn">
	                				<i class="fa fa-window-close-o"></i> Rejected
	              				</a>
	              				@endif
              				</center>
				        </div>
				        <div class="col-md-12 col-sm-6 col-xs-12 " id="reasonform" style="display: none;">
		            		<center>
				                	<form class="form"action="{{route('admin.ads.reject')}}" method="POST" id="rej-ads-{{$ads->id}}" style="padding:10px;">
	                                            @csrf
	                                    <input type="hidden" id="id" name="id" value="{{$ads->id}}">
	                                   	<div class="form-group"  id="other">
						                  	<label>Reason</label>
						                  	<textarea type="textarea" class="form-control" rows="5" name="reason" id="reason" maxlength="800" required></textarea>
						                  	<!-- <input > -->
						                </div>
						                <div class="form-group"  id="other">
						                  	<a href="{{ URL::previous() }}" class="btn bg-purple margin" id="back">Back</a>
			                				<button type="button" onclick="rejectads('{{$ads->id}}')" class="btn bg-maroon margin">Reject</button>
						                </div>
	                                </form>
              				</center>
				        </div>
				    </div>
		        </div>
		        <div class="box box-danger " id="block-form" style="display:none;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Block / Unblocked Reason</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="post" action="{{route('admin.ads.block',$ads->id)}}" id="adsblock">
                        @csrf
                        <input type="hidden" name="id" value="{{$ads->id}}">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="3" name="reason" id="block_reason"placeholder="Enter ..." required=""></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cancel"class="btn bg-navy btn-flat">Cancel</button>
                            <button type="button" class="btn bg-maroon btn-flat" onclick="block()">Block / Unblock</button>
                        </div>
                      <!-- /.box-footer -->
                    </form>
                </div>
		        <div class="box" style="min-height: 225px;">
                    <div class="box-header bg-purple">
		              	<center>
		              	<h3 class="box-title ">Ads Details</h3>
		                </center>
		            </div>
		            <div class="box-footer no-padding">
			            <ul class="nav nav-stacked ">
			            	<li><a href="#">Ad Expired Date <span class="pull-right   ">{{$ads->ads_expire_date}}</span></a></li>
			            	<li><a href="#">Ad Point Expired Date <span class="pull-right   ">{{$ads->point_expire_date}}</span></a></li>
			            	<li><a href="#">Ad Point Balance <span class="pull-right   ">{{$ads->point}}</span></a></li>
			            	<li ><a href="javascript:void(0);" class="btn bg-maroon btn-flat margin change_val"  >Change Validity / Points</a></li>
			            </ul>
		            </div>
                </div>
                <div class="box add_val" style="display: none !important;" >
                    <div class="box-header bg-purple">
		              	<center>
		              	<h3 class="box-title ">Add New Validity / Points</h3>
		                </center>
		            </div>
		            <div class="box-body no-padding">
			            <form class="form"action="{{route('admin.ads.addvalidity')}}" method="POST" id="addvalidity" style="padding:10px;">
                                        @csrf
                                <input type="hidden" id="id" name="id" value="{{$ads->id}}">
								
								<div class="form-group"  id="other">
				                  	<label>Ad Expired Date :</label>
				                  	<input type="text" class="form-control pull-right datepicker" name="Adexpiredate" value="{{$ads->ads_expire_date}}">
				                  	<!-- <input > -->
				                </div>
				                <div class="form-group"  id="other">
				                  	<label>Ad Point Expired Date :</label>
				                  	<input type="text" class="form-control pull-right datepicker" name="Adpointexpiredate" value="{{$ads->point_expire_date}}">
				                  	<!-- <input > -->
				                </div>
				                <div class="form-group"  id="other">
				                  	<label>Ad Point Balance :</label>
				                  	<input type="number" step="0.01"  min="0"class="form-control pull-right " name="Adpointbalance" value="{{$ads->point}}">
				                  	<!-- <input > -->
				                </div>
				                <div class="form-group"  id="other">
				                  	<a href="#" class="btn bg-purple margin close_val" id="back">close</a>
	                				<button type="submit" onclick="" class="btn bg-maroon margin">Add Validity / Points</button>
				                </div>
                            </form>
          			</div>
                </div>
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-purple">
                        <h3 class="widget-user-username">Blocked / Unblocked Reason </h3>
                    </div>
                    <div class="box-body ">
                        <button type="button" class="btn  btn-primary" data-target="#modal-default" data-toggle="modal">view all</button>
                    </div>
                </div>
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-purple">
                        <h3 class="widget-user-username">Ad Viewed History </h3>
                    </div>
                    <div class="box-body ">
                        <button type="button" class="btn  btn-primary" data-target="#ad_point_admin_history" data-toggle="modal">view all</button>
                    </div>
                </div>
		        
		        <!-- /.widget-user -->
	        </div>
	        
	        <!-- /.col -->
	    </div>
	    <!-- /.row -->


	     <div class="row">
	        <div class="col-md-12 col-sm-6 col-xs-12 ">
                <div class="box-header bg-purple">
                    <center>
                        <h3 class="box-title">Chat with User</h3>
                        <input type="hidden" name= "email" value="">
                    </center>
                </div>
                <div class="box-footer">


	     		<div class="row">
	        		<div class="col-md-6">
					<!-- general form elements -->
						<div class="box box-info">
						<div class="box-header with-border">
						  <h3 class="box-title">Complaint By</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						    <div class="box-body" style="min-height: 100px;">
						        <div class="row">
						            <div class="col-md-6">
						                <div class="form-group">
						                	<select name="complaintbyuser" id="complaintbyuser" class="form-control">		        
							        		<option value="">Complaint By</option>		
						                	@if(!empty($reportingAdsUsers))
							                    @foreach($reportingAdsUsers as $user)
							                    	<option value="{{$user->uuid}}">{{$user->user_name}}</option>
							                    @endforeach
						                    @endif
							        	</select>
						                </div>
						                
						            </div>
						        </div>

						    </div>
						</div>
						<!-- /.box -->
					</div>

	        		<div class="col-md-6">
					<!-- general form elements -->
						<div class="box box-info">
						<div class="box-header with-border">
					  		<h3 class="box-title">Complaint Details</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						    <div class="box-body" style="min-height: 100px;max-height: 200px;overflow-y: scroll;">
						        <div class="row">
						            <div class="col-md-6">
						            	<div class="showdata">
						            		
						            	</div>					                
						            </div>
						        </div>

						    </div>
						</div>
						<!-- /.box -->
					</div>
				</div>

	    		<div class="row">
	        		<div class="col-md-6">
					<!-- general form elements -->
						<div class="box box-info">
						<div class="box-header with-border">
					  		<h3 class="box-title">Complaint Given User</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						    <div class="box-body" style="min-height: 100px;">
						        <div class="row">
						            <div class="col-md-12">
										<div class="form-group">
											<textarea class="form-control" placeholder="Message to user" name="complaintgivenusermsg" id="complaintgivenusermsg"></textarea>
										</div>			                
						            </div>
						        </div>
						    </div>
						    <div class="box-footer text-right">
								<button class="btn btn-primary" id="complaintgivenusersend" disabled="disabled">Send Message</button>						    	
						    </div>
						</div>
						<!-- /.box -->
					</div>
	        		<div class="col-md-6">
					<!-- general form elements -->
						<div class="box box-info">
						<div class="box-header with-border">
					  		<h3 class="box-title">Ad posted user</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						    <div class="box-body" style="min-height: 100px;">
						        <div class="row">
						            <div class="col-md-12">
										<div class="form-group">
											<textarea class="form-control" placeholder="Comment" name="fakeusermsg" id="fakeusermsg"></textarea>
										</div>			                
						            </div>
						        </div>
						    </div>
						    <div class="box-footer text-right">
								<button class="btn btn-primary" id="fakeusersend" disabled="disabled">Send Message</button>						    	
						    </div>
						</div>
						<!-- /.box -->
					</div>
				</div>

                </div>
	        </div>
	    </div>
	</section>
    <!-- /.content -->
    <div class="modal fade" id="modal-default" style="display: none;">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Blocked / Unblocked Reason</h4>
                      </div>
                      <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                              <th>Reason</th>
                              <th>Action Taken</th>
                              <th>Type</th>
                              <th>Date</th>
                            </tr>
                            @if(!$blockreason->isEmpty())
                            @foreach($blockreason as $key => $reason)
                            <tr>
                                <td>{{$reason->reason}}</td>
                                <td>{{getUserEmail($reason->blocked_by)}}</td>
                                <td>{{$reason->status}}</td>
                                <td>{{$reason->created_at}}</td>
                            </tr>
                             @endforeach
                            @else
                                <tr><td colspan="3">No Blocked Reason</td></tr>
                            @endif    
                            </tbody>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
</div>
<!-- Ads point history -->
	<div class="modal fade" id="ad_point_admin_history" style="display: none;">
		<div class="modal-dialog modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
					<h4 class="modal-title">Ad Viewed History</h4>
				</div>
				<div class="modal-body">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th>S.no</th>
								<th>Name</th>
								<th>Viewed At</th>
							</tr>
							@if(!$adpointhistory->isEmpty())
							@foreach($adpointhistory as $key => $view)
							 
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td><a href="{{route('admin.users.view',$view->uuid)}}" >{{$view->ad_view_user}}</a></td>
								<td>{{$view->viewed_at}}</td>
							</tr>
							 
							@endforeach
							@else
							<tr><td colspan="3">No Person Viewd</td></tr>
							@endif    
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script>
	//Date picker
	
	$('.datepicker').datetimepicker({
        
    });
    
    $(".change_val").click(function(e){
       	e.preventDefault();
        $(".add_val").show();
    });
    $(".close_val").click(function(e){
       	e.preventDefault();
        $(".add_val").hide();
    });
    
		 $('#block').click(function(){
            $('#block-form').show();
        });
		 $('#cancel').click(function(){
           $('#block-form').hide();
        });
		function block(id){
			var reason= $('#block_reason');
        	if (reason.val() != null && reason.val() != '') { 
        		swal({
		            title: "Are you sure?",
					text: "You won't be able to revert this!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
		            }).then((willDelete) => {
					  if (willDelete) {
					  	document.getElementById('adsblock').submit();
					    swal("Ads blocked / Unblocked !", {
					      icon: "success",
					    });
					  }
				});
    		} else {  
               swal("Reason Required!", "", "error", { button: "close",});  
            }
        	      
                
		}
        function rejectads(id){
        	var reason= $('#reason');
        	if (reason.val() != null && reason.val() != '') {  
                	swal({
			            title: "Are you sure?",
						text: "You won't be able to revert this!",
						icon: "warning",
						buttons: true,
						dangerMode: true,
			            }).then((willDelete) => {
						  if (willDelete) {
						  	document.getElementById('rej-ads-'+id).submit();
						    swal("Ads Rejected!", {
						      icon: "success",
						    });
						  } else {
						    swal("Your file is safe!");
						  }
					});      
                } else {  
                   swal("Reason Required!", "", "error", { button: "close",});  
                }
		}

		function verifyads(id){
            
            swal({
            title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
            }).then((willDelete) => {
			  if (willDelete) {
			  	document.getElementById('verify-ads-'+id).submit();
			    swal("Ads Approved!", {
			      icon: "success",
			    });
			  } else {
			    swal("Your file is safe!");
			  }
			});
		}

		$(document).ready(function(){
			maxLength = $("textarea#reason").attr("maxlength");
	        $("textarea#reason").after("<div><span id='remainingLengthTempId'>"
	                  + maxLength + "</span> Character(s) Remaining</div>");

	        $("textarea#reason").bind("keyup change", function(){checkMaxLength(this.id,  maxLength); } );

	        function checkMaxLength(textareaID, maxLength){

	            currentLengthInTextarea = $("#"+textareaID).val().length;
	            $(remainingLengthTempId).text(parseInt(maxLength) - parseInt(currentLengthInTextarea));

	            if (currentLengthInTextarea > (maxLength)) {

	                // Trim the field current length over the maxlength.
	                $("textarea#reason").val($("textarea#reason").val().slice(0, maxLength));
	                $(remainingLengthTempId).text(0);

	            }
	        }

	       	$("#rejectedbtn").click(function(e){
	            $("#actionform").hide();
	            $("#reasonform").show();
	            e.preventDefault();
	        });
	        $("#back").click(function(e){
	            $("#actionform").show();
	            $("#reasonform").hide();
	            e.preventDefault();
	        });

			$("#complaintbyuser").change(function(e){
				var useruuid = $(this).val();
				if($(this).val() != ''){
	            	$.ajax({
	                    type: "post",
	                    url: "<?php echo url('admin/getcomplaintdetails'); ?>",
                    	data: "useruuid="+$(this).val()+"&_token={{ csrf_token() }}"+"&adid={{$ads->id}}",
	                    success: function(data){
	                    	$("#complaintgivenusersend").removeAttr("disabled"); 
	                    	$("#complaintgivenusersend").attr("data-userid",useruuid);
	                    	$("#fakeusersend").removeAttr("disabled");   
	                    	$(".showdata").html(data);      
	                    }
	                });
            	}
			});


			$("#complaintgivenusersend").click(function(e){
				//alert($(this).attr('data-userid'));
				var useruuid = $(this).attr('data-userid');
            	$.ajax({
                    type: "post",
                    url: "<?php echo url('admin/adminchattouser'); ?>",
                	data: "typemessage="+$("#complaintgivenusermsg").val()+"&_token={{ csrf_token() }}"+"&adsid={{$ads->id}}"+"&useruuid="+useruuid,
                    success: function(data){
                    	if(data == 1){
                    		$("#complaintgivenusermsg").val('');
	                    	swal("Message Sent!", {icon: "success",});     
	                	}else{
	                    	swal("Message Not Sent!", {icon: "success",});  
	                	}                   
                    }
                });
	        });

			$("#fakeusersend").click(function(e){
				//alert($(this).attr('data-userid'));
            	$.ajax({
                    type: "post",
                    url: "<?php echo url('admin/adminchattouser'); ?>",
                	data: "typemessage="+$("#fakeusermsg").val()+"&_token={{ csrf_token() }}"+"&adsid={{$ads->id}}",
                    success: function(data){
                    	if(data == 1){
                    		$("#fakeusermsg").val('');
	                    	swal("Message Sent!", {icon: "success",});     
	                	}else{
	                    	swal("Message Not Sent!", {icon: "success",});  
	                	}        
                    }
                });
	        });

        });
    </script>
@endsection