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
        <h1>Role</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          	<div class="box  box-info">
		        <div class="box-header with-border">
	              <h3 class="box-title ">Users</h3>
					<h3 class="box-title text-right " style="float: right;">
						<a class="btn btn-primary " href="{{route('admin.role.save')}}">Add Role</a>
					</h3>
		        </div>
	            <!-- /.box-header -->
		            <div class="box-body">
		              	<table id="roles_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
			                <thead>
				                <tr>
				                	<th>S.No</th>
				                	<th>Role</th>
				                	<th>Slug</th>
				                	<th>Action</th>
				                </tr>
			                </thead>
			                <tbody>
			                	@if(!empty($roles))
			                		<?php $i=0;?>
				                	@foreach($roles as $role)
					                <tr>
					                	<td>{{$role->id}}</td>
					                	<td>{{$role->name}}</td>
					                	<td>{{$role->slug}}</td>
					                	<td><center>
								            <a style="margin:3px;" href="{{url('admin/role_access/editrole')}}/{{$role->uuid}}" class=" btn bg-yellow"><i class="fa  fa-pencil-square-o"></i></a>
								            <a style="margin:3px;" href="javascript:void(0);" onclick="deleterole({{$role->id}})" class=" btn bg-red"><i class="fa  fa-close"></i></a>
								            <form id="deleterole-{{$role->id}}" action="{{route('admin.role.delete')}}" method="post">
								            	@csrf
								            	<input type="hidden" name="roleid" value="{{$role->id}}">
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
	function deleterole(id){
		swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              	if (willDelete) {
                	document.getElementById('deleterole-'+id).submit();
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