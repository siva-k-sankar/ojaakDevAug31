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
        <h1>Users</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          	<div class="box  box-info">
		        <div class="box-header with-border">
	              <h3 class="box-title ">Users</h3>
					<h3 class="box-title text-right " style="float: right;">
						<a class="btn btn-primary " href="{{route('admin.addsubadmin')}}">Add Sub Admin</a>
					</h3>
		        </div>
	            <!-- /.box-header -->
		            <div class="box-body">
		              	<table id="roles_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
			                <thead>
				                <tr>
				                	<th>S.No</th>
				                	<th>Name</th>
				                	<th>Email</th>
				                	<th>Role</th>
				                	<th>Status</th>
				                	<th>Action</th>
				                </tr>
			                </thead>
			                <tbody>
			                	@if(!empty($users))
				                	@foreach($users as $user)
					                <tr>
					                	<td>{{$user->id}}</td>
					                	<td>{{$user->name}}</td>
					                	<td>{{$user->email}}</td>
					                	<td>{{$user->role_name}}</td>
					                	<td>@if($user->status == 3) Deactive @else Active @endif</td>
					                	<td>
					                		<center>
								            <a style="margin:3px;" href="{{url('admin/role_access/edit_subadmin/')}}/{{$user->uuid}}" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a>
								            <a style="margin:3px;" href="javascript:void(0);" onclick="inactiveuser({{$user->id}})" class=" btn bg-blue"><i class="fa  fa-clone"></i></a>
								            <form id="inactive-{{$user->id}}" action="{{route('admin.status_subadmin')}}" method="post">
								            	@csrf
								            	<input type="hidden" name="userid" value="{{$user->uuid}}">
								            </form>
								            </center>
								        </td>
					                </tr>
					                @endforeach
				                @endif
			                </tbody>

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
	function inactiveuser(id){
		swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              	if (willDelete) {
                	document.getElementById('inactive-'+id).submit();
              	}
            });
	}
	$(document).ready(function() {

		$('#roles_table').DataTable({"ordering": false});
        /*$('#roles_table').DataTable({
            "processing": true,
            "serverSide": true,
            //"ordering": false,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"dom": 'Bfrtip',
	        "buttons": [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ],
            "ajax": {
                "url":"<?php echo url('admin/complaints/user/get'); ?>",
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
        });*/
    });
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection