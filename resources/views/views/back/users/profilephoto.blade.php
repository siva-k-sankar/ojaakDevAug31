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
@endsection
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Profile Photo verification</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          	<div class="box  box-info">
	            <div class="box-header">
	              <h3 class="box-title">Un Verified Profile Photo</h3>
	            </div>
	            <!-- /.box-header -->
		            <div class="box-body">
		              	<table id="user_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" style="width:100%">
			                <thead>
				                <tr>
				                	<th>User Name</th>
				                  	<th>Email</th>
				                  	<th>Action</th>
				                </tr>
			                </thead>

		              	</table>
		            </div>
		            <!-- /.box-body -->
	          	</div>
	          	<!-- /.box -->
	          	
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
                "url":"<?php echo url('admin/user/profilephoto/get'); ?>",
                "type": "POST",
                "data": function(d) {
                    d.fromdate = $('#fromdate').val();
                    d.todate = $('#todate').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
	                "data": "name"
	            }, {
	                "data": "email"
	            }, {
	                "data": "action"
	            },
           	],
            "order": [
                [0, 'desc']
            ],
        });
    });
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection