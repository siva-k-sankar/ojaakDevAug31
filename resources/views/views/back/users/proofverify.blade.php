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
.zoom {
  
  transition: transform .2s;
  margin: 0 auto;
}

.zoom:hover {
  -ms-transform: scale(3.5); /* IE 9 */
  -webkit-transform: scale(3.5); /* Safari 3-8 */
  transform: scale(3.5); 
}
</style>


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
	              <h3 class="box-title">Unverified Proof List</h3>
	            </div>
	            <!-- /.box-header -->
		            <div class="box-body">
		              	<table id="unproofverify" class="table table-bordered table-striped ">
			                <thead>
				                <tr>
				                	<th>User Name</th>
				                  	<th>Proof Category</th>
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
        <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          	<div class="box  box-info">
	            <div class="box-header">
	              <h3 class="box-title">Proof List</h3>
	            </div>
	            <!-- /.box-header -->
		            <div class="box-body">
		              	<table id="proofverify" class="table table-bordered table-striped ">
			                <thead>
				                <tr>
				                	<th>User Name</th>
				                  	<th>Proof Category</th>
				                  	<th>Image</th>
				                  	<th>Verified User</th>
									<th>Status</th>
				                  	<th>Comments</th>
				                  	<th>Verified Date</th>
				                  	<th>Created date</th>
				                  	<th>Re-Check</th>
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
	$(document).ready(function(){
  		$('#unproofverify').DataTable({
        	"processing": true,
            "serverSide": true,
            //"ordering": false,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"dom": 'Bfrtip',
	        "buttons": [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ],
            "ajax": {
                "url":"<?php echo url('admin/users/proof_verification/unverifieddata'); ?>",
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
	                "data": "proof"
	            }, {
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

  	$(document).ready(function(){
  		$('#proofverify').DataTable({
	        "processing": true,
            "serverSide": true,
            //"ordering": false,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"dom": 'Bfrtip',
	        "buttons": [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ],
            "ajax": {
                "url":"<?php echo url('admin/users/proof_verification/verifieddata'); ?>",
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
	                "data": "proof"
	            }, {
	                "data": "proofimage"
	            },{
	                "data": "verified_by"
	            }, {
	                "data": "verified"
	            },{
	                "data": "comments"
	            }, {
	                "data": "verified_date"
	            },{
	                "data": "created_at"
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
  	function status(id){
            swal({
            title: "Are you sure?",
            text: "Do you want to make this Proof Recheck!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                document.getElementById('status-'+id).submit();
              } 
            });
    }
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection