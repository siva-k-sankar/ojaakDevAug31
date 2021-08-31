@extends('back.layouts.app')

@section('styles')
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript" defer></script>
<style>
.box-info{
	margin-top: 18px;
	margin-left: 15px;
	width: 97.8%;
}
.dt-buttons button{
    background: #09d26d !important;
    color: #fff !important;
    padding: 8px 45px;
    border-radius: 3px;
    border: none !important;
    -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -ms-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
}
.dt-buttons button:hover{
    background: #02363d !important;
    -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -ms-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
}
/* Right aligning the export button */
.dt-buttons {
    float: right; // Add !important for right aligned on mobiles
}
.dataTables_wrapper .dataTables_filter {
    float: left;
}
</style>


@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header"><h1>Contact Us Report</h1></section>
	    <div class="box box-info">
	        <div class="box-header with-border">
                <div class="row text-center">
                    <div class="col-xs-3">
                        <label>From Date </label>
                        <input id="from_date_pick">
                    </div>
                    <div class="col-xs-3">
                        <label>To Date </label>
                        <input id="to_date_pick">
                    </div>
                    <div class="col-xs-3">
                        <button type="button" class="filterleaddata btn btn-success">Filter</button>
                    </div>
                    <div class="col-xs-3">
                        
                    </div>
                </div>
			</div>
	        <div class="box-body table-responsive" >
                <table id="contactus" class="table table-bordered dt-responsive ">
                    <thead>
                        <tr>
                            <th>S.L</th>
                            <th>Ticket No</th>
                            <th>Name</th>
                            <th>Email Id</th>
                            <!-- <th>Mobile no</th> -->
                            <th>Ads Id</th>
                            <th>Help</th>
                            <th>Description</th>
                            <th>Created at</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>    
            </div>
            <div class="box-body table-responsive" >
                <table id="contactus_status" class="table table-bordered dt-responsive ">
                    <thead>
                        <tr>
                            <th>S.L</th>
                            <th>Ticket No</th>
                            <th>Name</th>
                            <th>Email Id</th>
                            <!-- <th>Mobile no</th> -->
                            <th>Ads Id</th>
                            <th>Help</th>
                            <th>Description</th>
                            <th>Created at</th>
                            <th>Status</th>
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
    
    // Wallet date picker
    var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    //alert(today)
     if ($('#from_date_pick').length > 0){
        $('#from_date_pick').datepicker({
            uiLibrary: 'bootstrap',
            format: 'mm/dd/yyyy',
        });
    }
     if ($('#to_date_pick').length > 0){
        $('#to_date_pick').datepicker({
            uiLibrary: 'bootstrap',
            format: 'mm/dd/yyyy',
        });
    }
	$(document).ready(function() {

        $(".filterleaddata").click(function(){    
        //alert($("#location").val());
            redrawDatatable();
            redrawDatatable1();
        });
        function redrawDatatable(){
            var table = $('#contactus').DataTable(); 
            table.draw( 'page' );
        }
        function redrawDatatable1(){
            var table = $('#contactus_status').DataTable(); 
            table.draw( 'page' );
        }
        $('#contactus').DataTable({
             responsive: true,
            "oLanguage": {
                "sEmptyTable": "No data available"
            },
            "processing": true,
            "serverSide": true,
            "ordering": false,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: 'fBrtip<"clear">',
            "columnDefs": [{
              className: "dt-right",
              "targets": [0], // First column
            }],
            "buttons": [{
                extend: 'collection',
                text: 'Export',
                buttons: [
                    { extend: 'copyHtml5', title: 'OJAAK ContactUs'},
                    { extend: 'excelHtml5', title: 'OJAAK ContactUs' },
                    { extend: 'csvHtml5', title: 'OJAAK ContactUs'},
                    { extend: 'pdfHtml5', title: 'OJAAK ContactUs',orientation: 'landscape',pageSize: 'LEGAL' },
                    'print',
                ]
            }
            ],
            "ajax": {
                "url":"<?php echo url('admin/report/contactus/get'); ?>",
                "type": "post",
                "data": function(d) {
                	 d.fromdate = $('#from_date_pick').val();
                    d.todate = $('#to_date_pick').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
	                "data": "sl"
	            }, {
                    "data": "ticketno"
                }, {
	                "data": "name"
	            },{
                    "data": "mailid"
                },
                // {
                //     "data": "mobile"
                // }, 
                {
                    "data": "Ads_id"
                },{
                    "data": "help"
                },{
                    "data": "desc"
                },{
                    "data": "createdate"
                },{
                    "data": "status"
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
        // Status Complete
        $('#contactus_status').DataTable({
             responsive: true,
            "oLanguage": {
                "sEmptyTable": "No data available"
            },
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: 'fBrtip<"clear">',
            "columnDefs": [{
              className: "dt-right",
              "targets": [0], // First column
            }],
            "buttons": [{
                extend: 'collection',
                text: 'Export',
                buttons: [
                    { extend: 'copyHtml5', title: 'OJAAK ContactUs'},
                    { extend: 'excelHtml5', title: 'OJAAK ContactUs' },
                    { extend: 'csvHtml5', title: 'OJAAK ContactUs'},
                    { extend: 'pdfHtml5', title: 'OJAAK ContactUs',orientation: 'landscape',pageSize: 'LEGAL' },
                    'print',
                ]
            }
            ],
            "ajax": {
                "url":"<?php echo url('admin/report/contactus/statuscomplete'); ?>",
                "type": "post",
                "data": function(d) {
                     d.fromdate = $('#from_date_pick').val();
                    d.todate = $('#to_date_pick').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
                    "data": "sl"
                }, {
                    "data": "ticketno"
                }, {
                    "data": "name"
                },{
                    "data": "mailid"
                },
                // {
                //     "data": "mobile"
                // }, 
                {
                    "data": "Ads_id"
                },{
                    "data": "help"
                },{
                    "data": "desc"
                },{
                    "data": "createdate"
                },{
                    "data": "status"
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
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css
" defer>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" defer>
<script src="https://code.jquery.com/jquery-3.3.1.js" defer></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" defer></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" defer></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" defer></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js" defer></script>
@endsection