@extends('back.layouts.app')

@section('styles')
<style>
.box-info{
	margin-top: 18px;
	margin-left: 15px;
	width: 97.8%;
}
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header"><h1>FAQ</h1></section>
	    <div class="box box-info">
	        <div class="box-header with-border">
				<h3 class="box-title">
					<a class="btn btn-primary" href="{{route('admin.faq.faqquestion')}}">Add Questions</a>
				</h3>
	        </div>
	        <div class="box-body">
                <table id="faq" class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Questions</th>
                            <th>Answers</th>
                            <th>Created at</th>
                            <th>Updated at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>    
            </div>
	    </div>
</div>
@endsection

@section('scripts')
<script>
	$(document).ready(function() {
        $('#faq').DataTable({
            "processing": true,
            "serverSide": true,
            //"ordering": false,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"dom": 'Bfrtip',
	        "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],
            "ajax": {
                "url":"<?php echo url('admin/faq/get'); ?>",
                "type": "post",
                "data": function(d) {
                	d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
	                "data": "questions"
	            }, {
	                "data": "answers"
	            },{
                    "data": "created_at"
                },{
                    "data": "updated_at"
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
<script>
    function deletecate(id){
            
            swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                document.getElementById('del-cate-'+id).submit();
                swal("categories has been deleted!", {
                  icon: "success",
                });
              } else {
                swal("Your file is safe!");
              }
            });
        }
</script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
@endsection