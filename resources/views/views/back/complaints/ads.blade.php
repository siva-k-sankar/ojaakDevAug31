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
@endsection
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Complaints</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          	<div class="box  box-info">
	            <div class="box-header">
	              <h3 class="box-title">Ads</h3>
	            </div>
	            <!-- /.box-header -->
		            <div class="box-body">
		              	<table id="user_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
			                <thead>
				                <tr>
				                	<th>User</th>
				                  	<th>Reported ads</th>
				                  	<th>Reason</th>
				                  	<th>Comments</th>
				                  	<th>Date</th>
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
            //"ordering": false,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"dom": 'Bfrtip',
	        "buttons": [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ],
            "ajax": {
                "url":"<?php echo url('admin/complaints/ads/get'); ?>",
                "type": "post",
                "data": function(d) {
                    d.fromdate = $('#fromdate').val();
                    d.todate = $('#todate').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
	                "data": "user"
	            }, {
	                "data": "report"
	            }, {
	                "data": "reason"
	            }, {
	                "data": "comments"
	            },{
	                "data": "date"
	            },{
	                "data": "action"
	            },
           	],
            "order": [
                [0, 'desc']
            ],
            createdRow: function(row, data, index) {
            	//console.log("a",data['status']);
			    $('td', row).eq(4).addClass('td-actions'); // 6 is index of column
			    if ( data['status'] == "" ) {
		    		$(row).css("opacity", "0.2");
			    }
			},
        });
    });
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection