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
    <section class="content-header"><h1>Bill info Edit</h1></section>
        <div class="box box-info">
            <div class="box-header with-border">
                <div class="row text-center">
                    <div class="col-xs-2">
                        <label>Enter Order Id </label>
                    </div>
                    <div class="col-xs-2">
                        <input id="orderid" name="orderid" type="number" min="0" onkeydown="javascript: return event.keyCode == 69 ? false : true">
                    </div>
                     <div class="col-xs-2">
                        <label>Enter Buyer Id </label>
                    </div>
                    <div class="col-xs-2">
                        <input id="buyerid" name="buyerid" type="number" min="0" onkeydown="javascript: return event.keyCode == 69 ? false : true">
                    </div>
                    <!-- <div class="col-xs-2">
                        <button type="button" class="filterleaddata btn btn-success">Filter</button>
                    </div> -->
                    
                </div>
            </div>
            <div class="box-body">
                <table id="billinfo" class="table table-bordered display">
                    <thead>
                        <tr>
                            <th>Order Id (Prefix: OPID )</th>
                            <th>Buyer ID</th>
                            <th>Buyer Name</th>
                            <th>Buyer Email</th>
                            <th>Buyer Phone</th>
                            <th>Bill Amount</th>
                            <th >Action</th>
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
        $( "#orderid" ).focus();
        $("#orderid").keyup(function(){    
        //alert($("#location").val());
            redrawDatatable();
        });
        $("#buyerid").keyup(function(){    
        //alert($("#location").val());
            redrawDatatable();
        });
        $(".filterleaddata").click(function(){    
        //alert($("#location").val());
            redrawDatatable();
        });
        function redrawDatatable(){
            var table = $('#billinfo').DataTable(); 
            table.draw( 'page' );
        }
        $('#billinfo').DataTable({
            "oLanguage": {
                "sEmptyTable": "No data available"
            },
            "processing": true,
            "serverSide": true,
            "ordering": false,
            searching: false,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: 'fBrtip<"clear">',
            "columnDefs": [{
              className: "dt-body-center",
              "targets": ["All"], // First column
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
                "url":"<?php echo url('admin/report/reportBillInfo/get'); ?>",
                "type": "post",
                "data": function(d) {
                    d.orderid = $('#orderid').val();
                    d.buyerid = $('#buyerid').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
                    "data": "orderid"
                }, {
                    "data": "id"
                },{
                    "data": "name"
                },{
                    "data": "email"
                }, {
                    "data": "phone"
                },{
                    "data": "payment"
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