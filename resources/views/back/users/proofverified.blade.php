@extends('back.layouts.app')
@section('styles')

@endsection
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>User Proof Verification</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-6">
	          	<div class="box box-widget widget-user-2">
		            <!-- Add the bg color to the header using any of the bg-* classes -->
		            <div class="widget-user-header bg-purple">
		              	<center>
		              	<h3 class="widget-user-username ">User Details</h3>
		                </center>
		            </div>
		            <div class="box-footer no-padding">
			            <ul class="nav nav-stacked">
			            	<li><a href="#">Profile</a></li>
			                <li></br><center><img  src="{{asset('public/uploads/profile/small/'.$userdetails->photo)}}"  alt="User Image"></center></br>
			                </li>
			                <li><a href="#">UserName <span class="pull-right  badge bg-green">{{ucwords($userdetails->name)}}</span></a></li>
			                <li><a href="#">Date of Birth <span class="pull-right badge bg-green">{{$userdetails->dob}}</span></a></li>
			                <li><a href="#">Gender <span class="pull-right badge bg-green">{{ucwords($userdetails->gender)}}</span></a></li>
			                <li><a href="#">Phone No <span class="pull-right badge bg-green">{{$userdetails->phone_no}}</span></a></li>
			                <li><a href="#">Email <span class="pull-right badge bg-green">{{$userdetails->email}}</span></a></li>
			                <li><a href="#">Work Email <span class="pull-right badge bg-green">{{$userdetails->work_mail}}</span></a></li>
			                
			            </ul>
		            </div>
		        </div>
		        <!-- /.widget-user -->
	        </div>
	        
	        <!-- /.col -->
	        <div class="col-xs-6">
	          	<div class="box box-widget widget-user-2">
		            <!-- Add the bg color to the header using any of the bg-* classes -->
		            <div class="widget-user-header bg-purple">
		            	<center>
		              	<h3 class="widget-user-username ">Proof Verification</h3>
		                </center>
		            </div>
		            <div class="box-footer no-padding">
		              	<ul class="nav nav-stacked"  id="veriform">
			                <li><a href="#">{{ (($proofs->proof !='')?get_prooflist($proofs->proof):"-") }}</a></li>
			                <li></br><center><img class="img-responsive pad img-thumbnail" src="{{asset('public/uploads/proof/'.$proofs->image)}}"  alt="Proof Image"></center></br>
			                </li>
			                <li>
			                	</br>
			                	<center>
			                		<a href="{{route('admin.users.proofverify')}}" class="btn bg-purple margin">Back</a>
					                <button type="button" onclick="verifyProof('{{$proofs->id}}')" class="btn bg-olive margin">Verified</button>
					                <form action="{{route('admin.users.proofverified',$proofs->id)}}" method="POST" id="verify-proof-{{$proofs->id}}" style="display:none;">
                                            @csrf
                                    </form>
                                    <button type="button" id="Submit-Confirm" class="btn bg-maroon margin">Reject</button>
					                
					            </center>
			                </li>
		              	</ul>
		              	<ul class="nav nav-stacked" style="display: none;" id="rejform">
			                <li><a href="#">{{ (($proofs->proof !='')?get_prooflist($proofs->proof):"-") }}</a></li>
			                <li>
			                	<center>
				                	<form class="form"action="{{route('admin.users.proofdelete')}}" method="POST" id="del-proof-{{$proofs->id}}" style="padding:10px;">
	                                            @csrf
	                                    <input type="hidden" id="proofid" name="proofid" value="{{$proofs->id}}">
	                                    <div class="form-group">
						                  	<label>Select</label>
						                  	<select class="form-control" id="reason" name="reason">
						                    	<option value="Not Valid Proof">Not Valid Proof</option>
						                    	<option value="Date Of Birth Mismatch">Date Of Birth Mismatch</option>
						                    	<option value="Name Mismatch">Name Mismatch</option>
						                    	<option value="Licence Number Mismatch">Licence Number Mismatch </option>
						                    	<option value="Aadhar Number Mismatch">Aadhar Number Mismatch</option>
						                    	<option value="Voter Id Number Mismatch">Voter Id Number Mismatch</option>
						                    	<option value="PAN No Mismatch">PAN No Mismatch</option>
						                    	<option value="Others">Others</option>
						                  	</select>
						                </div>
	                                
	                                	<div class="form-group"  id="other" style="display: none;">
						                  	<label>Others Reasons</label>
						                  	<textarea class="form-control" rows="2" name="other_reason"></textarea>
						                </div>
	                                </form>
                            	</center>
			                </li>
			                <li>
			                	</br>
			                	<center>
			                		<a href="#" class="btn bg-purple margin" id="back">Back</a>
			                		<button type="button" onclick="deleteProof('{{$proofs->id}}')" class="btn bg-maroon margin">Reject</button>
					                <!-- <form action="{{route('admin.users.proofdelete')}}" method="POST" id="del-proof-{{$proofs->id}}">
                                            @csrf

                                    </form> -->
					            </center>
			                </li>
		              	</ul>
		            </div>
		        </div>
		        <!-- /.widget-user -->
	        </div>
	        
	        <!-- /.col -->
	    </div>
	    <!-- /.row -->
    </section>
    <!-- /.content -->
    
</div>
@endsection

@section('scripts')
<script>
        function deleteProof(id){
            
            swal({
            title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
            }).then((willDelete) => {
			  if (willDelete) {
			  	document.getElementById('del-proof-'+id).submit();
			    swal("Poof has been Rejected!", {
			      icon: "success",
			    });
			  } else {
			    swal("Your file is safe!");
			  }
			});
		}
		function verifyProof(id){
            
            swal({
            title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
            }).then((willDelete) => {
			  if (willDelete) {
			  	document.getElementById('verify-proof-'+id).submit();
			    swal("Poof has been verified!", {
			      icon: "success",
			    });
			  } else {
			    swal("Your file is safe!");
			  }
			});
		}
		
		$(document).ready(function(){
       $("#Submit-Confirm").click(function(e){
            $("#veriform").hide();
            $("#rejform").show();
            e.preventDefault();
        });
        $("#back").click(function(e){
            $("#veriform").show();
            $("#rejform").hide();
            e.preventDefault();
        });
        $("#reason").change(function(e){
        	reason=$("#reason").val();
        	if(reason=="Others"){
        		$("#other").show();
        	}else{
        		$("#other").hide();
        	}
            
            e.preventDefault();
        });
    });
    </script>
@endsection