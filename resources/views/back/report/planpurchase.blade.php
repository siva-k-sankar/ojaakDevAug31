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
    <section class="content-header"><h1>Plan Purchase Report</h1></section>
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
                </div>
			</div>
	        <div class="box-body">
                <table id="reddemtable" class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Order ID (Prefix: OPID )</th>
                            <th>Name</th>
                            <th>Gateway</th>
                            <th>Expire Date</th>
                            <th>Payment Id</th>
                            <th>Amount</th>
                            <th>Created at</th>
                            <th>Refund</th>
                            <th>Refunded</th>
                            <th>Refund Details</th>
                        </tr>
                    </thead>
                </table>  
            </div>
	    </div>
</div>



@endsection

@section('scripts')
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

        /*$(".partial_payment").change(function(){   
            if($(this).prop("checked") == true){
                //$('.sub_btn').attr('value','Partial Refund');
                $(this).val('1');
            }
            else if($(this).prop("checked") == false){
                //$('.sub_btn').attr('value','Full Refund');
                $(this).val('0');
            }
        });

        $(".plan_delete").change(function(){   
            if($(this).prop("checked") == true){
                $(this).val('1');
            }
            else if($(this).prop("checked") == false){
                $(this).val('0');
            }
        });*/

        $(".refundmodal").click(function(){    
            $("#myModal").modal()
        });

        $(".filterleaddata").click(function(){    
        //alert($("#location").val());
            redrawDatatable();
        });
        function redrawDatatable(){
            var table = $('#reddemtable').DataTable(); 
            table.draw( 'page' );
        }
        $('#reddemtable').DataTable({
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
              "targets": [0] // First column
            }],
            "buttons": [{
                extend: 'collection',
                text: 'Export',
                buttons: [
                    { extend: 'copyHtml5', title: 'OJAAK Plan Planpurchase Export'},
                    { extend: 'excelHtml5', title: 'OJAAK Plan Planpurchase Export' },
                    { extend: 'csvHtml5', title: 'OJAAK Plan Planpurchase Export'},
                    { extend: 'pdfHtml5', title: 'OJAAK Plan Planpurchase Export' },
                    'print',
                ]
            }
            ],
            "ajax": {
                "url":"<?php echo url('admin/report/planpurchase/get'); ?>",
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
                    "data": "user"
                }, {
                    "data": "payment_type"
                }, {
                    "data": "expire_date"
                }, {
                    "data": "payment_id"
                },{
                    "data": "amount"
                },{
                    "data": "createdate"
                },{
                    "data": "refund"
                },{
                    "data":"refunded"
                },{
                    "data":"refunded_details"
                }
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

    function plan_delete(id){
        if($("#plan_delete"+id).prop("checked") == true){
            $("#plan_delete"+id).val('1');
        }
        else if($("#plan_delete"+id).prop("checked") == false){
            $("#plan_delete"+id).val('0');
        }
    }
    function partial_payment(id){
        if($("#partial_payment"+id).prop("checked") == true){
            $("#partial_payment"+id).val('1');
        }
        else if($("#partial_payment"+id).prop("checked") == false){
            $("#partial_payment"+id).val('0');
        }
    }


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

    function refundalert(id){
            
            swal({
            title: "Are you sure do you want to refund?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                window.location.href = "{{url('admin/report/purchaserefund')}}"+'/'+id;
                //document.getElementById('del-cate-'+id).submit();
                swal("Refund the amount within 6-7 days to the transferred account!", {
                  icon: "success",
                });
              } else {
                swal("Your are cancelled the refund option!");
              }
            });
        }
</script>
@endsection