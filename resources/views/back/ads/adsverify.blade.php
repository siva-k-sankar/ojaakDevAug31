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
        <h1>Ads</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          	<div class="box  box-info">
	            <div class="box-header">
	              <h3 class="box-title">Ads verification</h3>
	            </div>
	            <!-- /.box-header -->
		            <div class="box-body">
		              	<table id="adsverify" class="table table-bordered table-striped ">
			                <thead>
				                <tr>
				                	<th>Title</th>
				                	<th>Display Ads id</th>
				                  	<th>Ads Posted By</th>
				                  	<th>Status</th>
				                  	<th>Approved By</th>
				                  	<th>Verified Date</th>
				                  	<th>created Date</th>
				                  	<th>Type</th>
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
	$(document).ready(function(){
  		$('#adsverify').DataTable({
        	"processing": true,
            "serverSide": true,
            //"ordering": false,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"dom": 'Bfrtip',
	        "buttons": [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ],
            "ajax": {
                "url":"<?php echo url('admin/ads/verification/get/'); ?>",
                "type": "POST",
                "data": function(d) {
                    d.fromdate = $('#fromdate').val();
                    d.todate = $('#todate').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
	                "data": "title"
	            },{
	                "data": "userdisplayadsid"
	            }, {
	                "data": "user"
	            }, {
	                "data": "status"
	            }, {
	                "data": "admin"
	            },{
	                "data": "verify"
	            },{
	                "data": "create"
	            },{
	                "data": "type"
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

	setTimeout(function(){
        var searchText = getUrlParameter("Search");
        //alert(searchText)
        var table = $("#adsverify").DataTable();
        table.search(searchText).draw();
	}, 1000);

  	});

  	var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = window.location.search.substring(1),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        /*if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
	        }*/
         	return sParameterName;
	    }
	};


  	
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection