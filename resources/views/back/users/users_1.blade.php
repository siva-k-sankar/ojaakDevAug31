@extends('back.layouts.app')
@section('styles')
<style>
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  background: none;
  color: black!important;
  border-radius: 4px;
  border: 1px solid #828282;
}
 
.dataTables_wrapper .dataTables_paginate .paginate_button:active {
  background: none;
  color: black!important;
}
</style>
<style>
    .color-palette {
      height: 35px;
      line-height: 35px;
      text-align: center;
    }

    .color-palette-set {
      margin-bottom: 15px;
    }

    .color-palette span {
      display: none;
      font-size: 12px;
    }

    .color-palette:hover span {
      display: block;
    }

    .color-palette-box h4 {
      position: absolute;
      top: 100%;
      left: 25px;
      margin-top: -40px;
      color: rgba(255, 255, 255, 0.8);
      font-size: 12px;
      display: block;
      z-index: 7;
    }
    th,td{
    	text-align: center;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css" >
@endsection
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Users</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          	<div class="box  box-info">
	            <div class="box-header">
	              <h3 class="box-title">Users</h3>
	            </div>
	            <!-- /.box-header -->
		            <div class="box-body">
		            	<form class="form-inline" >
						  <div class="checkbox">
						    <label><input type="radio" name="userfillter" checked value="1"> ALL</label>
						  </div>
						  <div class="checkbox">
						    <label><input type="radio"name="userfillter" value="2"> Verified User</label>
						  </div>
						  <div class="checkbox">
						    <label><input type="radio" name="userfillter" value="3"> Un Verified User</label>
						  </div>
						</form>
		              	<table id="user_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" style="width:100%">
			                <thead>
				                <tr>
				                	<th>User Name</th>
				                	<th>Primary User ID</th>
				                  	<th>Email</th>
				                  	<th>Phone</th>
				                  	<th>Registerd Date</th>
				                  	<th>Last Activity</th>
				                  	<th>Status</th>
				                  	<th>Action</th>
				                </tr>
			                </thead>

		              	</table>
		            </div>
		            <!-- /.box-body -->
	          	</div>
	          	<!-- /.box -->
	          	<div class="box box-default color-palette-box">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-tag"></i> Color Scheme</h3>
					</div>
					<div class="box-body">
						<div class="row">
							<input type="hidden" name="userfillter1" id="userfillter1" value="1">
							<div class="col-sm-4 col-md-2">
								<h4 class="text-center"style="color: #000000 !important;" >Email Not Verify</h4>

								<div class="color-palette-set">
								<div class=" color-palette" style="background-color: #FFAB91;"></div>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-sm-4 col-md-2">
								<h4 class="text-center" style="color: #000000 !important;">Mobile Not Verify</h4>

								<div class="color-palette-set">
								<div class=" color-palette" style="background-color: #FFECB3;"></div>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-sm-4 col-md-2">
								<h4 class="text-center" style="color: #000000 !important;">Verified User</h4>

								<div class="color-palette-set">
								<div class=" color-palette" style="background-color: #A5D6A7;"></div>
								</div>
							</div>
							<!-- /.col -->

						<!-- /.col -->
						</div>

					</div>
					<!-- /.box-body -->
			      </div>
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
	
	$(document).ready(function() {
		user();
		$('input:radio[name=userfillter]').change(function(){
			 //var table = $('#user_table').DataTable();
			 //table.draw( 'page' );
			 $('#userfillter1').val($(this).val());
			 //uservalue=$(this).val();
			 user();
		});
		function user(){
	        $('#user_table').DataTable({
	            "processing": true,
	            "serverSide": true,
	            "ordering": false,
				"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
				"dom": 'Bfrtip',
		        "buttons": [
		            'copy', 'csv', 'excel', 'pdf', 'print'
		        ],
	            "ajax": {
	                "url":"<?php echo url('admin/user/get'); ?>",
	                "type": "POST",
	                "data": function(d) {
	                    d.fromdate = $('#fromdate').val();
	                    d.todate = $('#todate').val();
	                    d.userfillter = $('#userfillter1').val();
	                    d._token = "{{ csrf_token() }}";
	                }
	            },
	            "columns": [{
		                "data": "name"
		            }, {
		                "data": "userid"
		            }, {
		                "data": "email"
		            }, {
		                "data": "mobile_no"
		            }, {
		                "data": "created_at"
		            }, {
		                "data": "lastlogin"
		            }, {
		                "data": "status"
		            }, {
		                "data": "action"
		            },
	           	],
	            "order": [
	                [0, 'desc']
	            ],
	            "rowCallback": function( row, data ) {

	            	if ( data.email_verify == "" || data.email_verify == null) {
	            		$( row).css('color', '#000000');
	                    $(row).css('background-color', '#FFAB91');
	                }else if ( data.phone_verify == "" || data.phone_verify == null) {
	                    $( row).css('background-color', '#FFECB3');
	                    $( row).css('color', '#000000');
	                }else{
	                	$( row).css('background-color', '#A5D6A7');
	                	$( row).css('color', '#000000');
	                }

	            },
	            "destroy" : true 
	        });
    	}
    });
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src = "https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js" defer ></script>

@endsection