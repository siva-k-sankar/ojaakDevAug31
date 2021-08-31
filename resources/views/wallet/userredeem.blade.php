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
                <li class="breadcrumb-item"><a href="{{url('/wallet')}}">My Wallet</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">My Reddem History</a></li>
              </ol>
            </div>
        </nav>
    </div>

<!-- Contact Us -->
    <div class="container-fluid pl-0 pr-0 my_wallet_outer_row_wrap">
        <div class="box-size">
            <div class="wallet_tabs_outer_wrap">
                <div class="wallet_title_wrap">
                    <h3>My Reddem History</h3>
                </div>
                <div class="row">
                    <div class="pl-0 col-lg-12 mywallet_table_col">
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
                                        <input placeholder="From Date" id="invoice_from_date_pick">
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="To Date" id="invoice_to_date_pick">
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
                        <div class="mywallet_table_outer_wrap table-responsive-lg">
                            <table id="wallet_table_wra" class="table nowrap" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Transactions</th>
                                        <th>Transactions ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
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
            /*"columnDefs": [{
              className: "dt-right",
              "targets": [0] // First column
            }],*/
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
                "url":"<?php echo url('/wallet/redeem/Get'); ?>",
                "type": "POST",
                "data": function(d) {
                    d.fromdate = $('#invoice_from_date_pick').val();
                    d.todate = $('#invoice_to_date_pick').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
                    "data": "title"
                },{
                    "data": "trnid"
                }, {
                    "data": "point"
                }, {
                    "data": "status"
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
    $("#redeemamount").click(function(e){
        //alert("ajax");
        e.preventDefault();
        //redeemtxtamount
        var user_id = "{{Auth::user()->id}}";
        var amount = $("#redeemtxtamount").val();
        //alert(amount);
        $.ajax({
                type: "post",
                url: "<?php echo url('/redeemamount'); ?>",
                data: "user_id="+user_id+"&redeemamount="+amount, 
                beforeSend:function(){
                    $(".load-more").text("Loading...");
                    $('.ajax-loader').css("visibility", "visible");
                },
                success: function(data){
                    //alert(data);
                    $('.ajax-loader').css("visibility", "hidden");
                    if(data == 1){
                        toastr.success("Amount is redeemed!");
                        location.reload();
                        // $("#redeemamount").prop('disabled',true);
                    }else if(data == 2){
                        toastr.warning("Pan card is not verified!");
                    }else{
                        toastr.warning("Wallet Amount Should Be Greater than!");
                    }
                }
            });
        });
    });
</script>
<!-- <script type="text/javascript">
    $(function(){
        var hash = window.location.hash;
        hash && $('a[href="' + hash + '"]').tab('show');
        
        $('.nav-tabs a').click(function (e) {
                $(this).tab('show');
                var scrollurl = $('body').scrollTop() || $('html').scrollTop();
                window.location.hash = this.hash;
                $('html,body').scrollTop(scrollurl);
        });
    });
</script> -->
@endsection
