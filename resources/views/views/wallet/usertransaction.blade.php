@extends('layouts.home')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>

   <!-- new -->
    <link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js" type="text/javascript"></script>

    <style type="text/css">
        .box-size {width: 1300px !important; }

        @media(max-width:1280px){
            .box-size {width: 95vw !important; }            
        }
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {-webkit-appearance: none; margin: 0; }
        /* Firefox */
        input[type=number] {-moz-appearance:textfield; }
        div#wallet_table_wrap_wrapper .dt-buttons button{
            background: #09d26d;
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
        div#wallet_table_wrap_wrapper .dt-buttons button:hover{
            background: #02363d;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            -ms-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
        }
        /* Right aligning the export button */
        div.dt-buttons {
            float: right; // Add !important for right aligned on mobiles
        }
        .dataTables_wrapper .dataTables_filter {
            float: left;
        }
        .dataTables_filter ,.dt-buttons{
        display: none;
        }
    </style>
@endsection
@section('content')
<!-- Page Title -->
    <div class="container-fluid pl-0 pr-0 page_title_bg_wrap">
        <nav aria-label="breadcrumb" class="page_title_inner_wrap">
            <div class="box-size">  
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">My Wallet</a></li>
              </ol>
            </div>
        </nav>
    </div>
    
<!-- Contact Us -->
    <div class="container-fluid pl-0 pr-0 my_wallet_outer_row_wrap">
        <div class="box-size">
            <div class="wallet_tabs_outer_wrap">
                <div class="wallet_title_wrap">
                    <h3>My Wallet</h3>
                </div>
                <div class="row">
                    <div class="pl-0 col-lg-8 mywallet_table_col">
                        <div class="mywallet_top_wrap">
                            <div class="mywallet_search_wrap">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="wallet_search"class="form-control" placeholder="Search">
                                </div>
                            </div>
                            <div class="mywallet_date_wrap">
                                <div class="invoice_date_outer_wrap">
                                    <div class="form-group">
                                        <input placeholder="From Date" id="invoice_from_date_pick" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="To Date" id="invoice_to_date_pick" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="mywallet_btn_wrap">
                                <a href="javascript:void(0);" class="filterleaddata">
                                    <i class="fa fa-sliders" aria-hidden="true"></i>Filter
                                </a>
                                <!-- <button class="dt-button buttons-collection" tabindex="0" aria-controls="wallet_table_wrap" type="button" aria-haspopup="true" aria-expanded="false"><span>Export</span></button> -->
                                <a href="javascript:void(0);" id="exportButton">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    Export
                                </a>
                            </div>
                        </div>
                        <div class="mywallet_table_outer_wrap table-responsive">
                            <table id="wallet_table_wra" class="table nowrap" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Transactions</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Expire date</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pr-0 col-lg-4 mywallet_redeem_col">
                        <div class="mywallet_redeem_outer_wrap">
                            <h4>Wallet Balance</h4>
                            <span>₹ {{number_format((float)Auth::user()->wallet_point, 2, '.', '')}}</span>
                            
                            @if($reedemableamt->redeemable_amount <= number_format((float)Auth::user()->wallet_point, 2, '.', ''))
                            <div  style="padding: 5px;">
                                <input type="number"  min="0" class="form-control" value="0"name="wallet_balance_field" id="redeemtxtamount">
                            </div>
                            <a href="javascript:void(0)" class="myredeem_btn_wrap" id="redeemamountcheck">Redeem Now</a>
                            <!-- <a href="javascript:void(0)" data-toggle="modal" data-target="#myredeem_popup_modal" class="myredeem_btn_wrap">Redeem Now</a> -->
                            @else
                                <a href="javascript:void(0)" class="" style="text-decoration: none;cursor: text;color:#000;">Note : Redeemable minimum amount is ₹ {{$reedemableamt->redeemable_amount}}</a>
                            @endif
                            <a href="{{route('userredeem')}}" class="myredeem_view_history">View Redeem History</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('scripts')
<script type="text/javascript">
    // Redeem Input
    var Redeemarray="";
    var sum = 0;
    $(document).ready(function() {

        $('#wallet_search').keyup(function(){
            var table = $('#wallet_table_wra').DataTable();
            table.search($(this).val()).draw();
        });
        $("#exportButton").on("click", function() {
            var table = $('#wallet_table_wra').DataTable();
            table.button('.buttons-csv').trigger();
        });
        $(".filterleaddata").click(function(){    
        //alert($("#location").val());
            if($("#invoice_from_date_pick").val()!=''){
                //alert($("#invoice_from_date_pick").val())
                redrawDatatable();
            }else{
                toastr.warning("Please Enter From  /  To Date!");
            }
        });
        function redrawDatatable(){
            var table = $('#wallet_table_wra').DataTable(); 
            table.draw( 'page' );
        }
        $('#wallet_table_wra').DataTable({
            "oLanguage": {
                "sEmptyTable": "No data available"
            },
            "processing": true,
            "serverSide": true,
            "ordering": false,
            //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: 'Brtip',
            
            /*"buttons": [{
                extend: 'collection',
                text: 'Export',
                buttons: [
                    { extend: 'copyHtml5', title: 'OJAAK Wallet Data Export'},
                    { extend: 'excelHtml5', title: 'OJAAK Wallet Data Export' },
                    { extend: 'csvHtml5', title: 'OJAAK Wallet Data Export'},
                    { extend: 'pdfHtml5', title: 'OJAAK Wallet Data Export' },
                    'print',
                ]
            }
            ],*/
            buttons: [
            'csv',
            ],
            "ajax": {
                "url":"<?php echo url('/wallet/Get'); ?>",
                "type": "POST",
                "data": function(d) {
                    d.fromdate = $('#invoice_from_date_pick').val();
                    d.todate = $('#invoice_to_date_pick').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
                    "data": "title"
                }, {
                    "data": "point"
                }, {
                    "data": "status"
                }, {
                    "data": "expire"
                }, {
                    "data": "date"
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
<script type="text/javascript">
$(document).ready(function(){
    toastr.options.preventDuplicates = true;
    // toastr.options.timeOut = "800";
    // toastr.options.hideDuration = "800";
    // toastr.options.extendedTimeOut = "800";
    $("#redeemamountcheck").click(function(e){
        var holdamount=Number("{{Auth::user()->wallet_point}}");
        var redmamount= Number($("#redeemtxtamount").val());
        //var condamount= holdamount+1;
        //alert(condamount);
        if(redmamount > 0 && holdamount >= redmamount){
            $.ajax({
                type: "post",
                url: "<?php echo url('/checkingRedeem'); ?>",
                data: "Redeemamount="+redmamount, 
                success: function(data){
                    alert(data);
                }
            });
        }else{
             toastr.warning("Not  Possible to Redeemed!");
        }
    });
});
</script>


@endsection
