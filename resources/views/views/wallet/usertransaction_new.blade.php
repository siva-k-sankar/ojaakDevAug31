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
        table.dataTable tbody td {
            word-break: break-word;
            vertical-align: top;
        }
        a.disabled {
          pointer-events: none;
          cursor: default;
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
                    <div class="pl-0 col-lg-10 mywallet_table_col">
                        <div class="mywallet_top_wrap">
                            <div class="mywallet_search_wrap">
                                <div class="form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" id="wallet_search" class="form-control" placeholder="Search">
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
                                        <th>Transaction ID</th>
                                        <th>Description</th>
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
                    <div class="pr-0 col-lg-2 mywallet_redeem_col">
                        <div class="mywallet_redeem_outer_wrap">
                            <h4>Wallet Balance</h4>
                            <span>₹ {{number_format((float)Auth::user()->wallet_point, 2, '.', '')}}</span>
                            
                            @if($reedemableamt->redeemable_amount <= number_format((float)Auth::user()->wallet_point, 2, '.', ''))
                            @php $disabled = ''; @endphp
                            @php $btndisabled = ''; @endphp
                            @else
                            @php $disabled = 'disabled'; @endphp
                            @php $btndisabled = 'btndisabled'; @endphp
                            @endif
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#myredeem_popup_modal" class="myredeem_btn_wrap {{$disabled}} {{$btndisabled}}"  >Redeem Now</a>
                                <a href="javascript:void(0)" class="" style="text-decoration: none;cursor: text;color:#000;text-align: center;">Note : Redeemable minimum amount is ₹ {{$reedemableamt->redeemable_amount}}</a>
                            <!-- <a href="{{route('userredeem')}}" class="myredeem_view_history">View Redeem History</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Redeem popup model -->
    <div id="myredeem_popup_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="close_chat_btn_wrap button_close_model">
                    <a href="#" data-dismiss="modal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
                <form name="list_redeem">
                    <div class="modal-body">
                        <h2>Redeem</h2>
                         <?php /* <!-- <div class="follow_scroll_wrap">
                            <div class="redeem_popup_content_outer_wrap">
                                <table id="wallet_table_wra" class="table nowrap" style="width:100% important;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S.I</th>
                                            <th>Transactions</th>
                                            <th>Amount</th>
                                            <!-- <th>Add</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!$pointstable->isEmpty())
                                            <?php $i=0;?>
                                            @foreach($pointstable as $point)
                                                <tr>
                                                    <td>{{++$i}}</td>
                                                    <td>{{$point->description}}</td>
                                                    <td>{{$point->point}}</td>
                                                    <!-- <td><input type="checkbox" name="choice" value="{{$point->point}}" onchange="checkTotal()" data-redeem_token="{{$point->id}}" data-redeem_point="{{$point->point}}"/></td> -->
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No Points Available</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>  -->
                        */ ?>
                        <div id="redeem_value_wrap" class="redeem_value_outer_wrap">
                            Redeem: <input type="text" step=0.01 size="2"  name="redeemtotal" id="redeemtotal" value="0.00" class="form-control" />
                        </div>

                        
                        <div class="redeem_value_button_wrap">
                            <input type="button" id="redeemamount" class=" btn btn-outline-success" value="Redeem">
                        </div>
                        <!-- redeem_value_button_wrap -->
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    // Redeem Input
    var Redeemarray="";
    var sum = 0;
    /*function checkTotal1() {
        var checkbox = document.getElementsByName('choice');
        alert(checkbox.length);
        document.list_redeem.total.value = '';
        sum = 0;
        Redeemarray="";
        for (i=0;i<=checkbox.length;i++) {
            //alert('d');
          if (document.list_redeem.choice[i].checked) {
            //sum = sum + parseInt(document.list_redeem.choice[i].value);
            
            sum = sum + parseFloat(document.list_redeem.choice[i].getAttribute("data-redeem_point"));
            if(Redeemarray==""){
                Redeemarray=parseInt(document.list_redeem.choice[i].getAttribute("data-redeem_token"));
            }else{
                Redeemarray=Redeemarray+','+parseFloat(document.list_redeem.choice[i].getAttribute("data-redeem_token"));
            }

          }
        }
        document.list_redeem.total.value = sum.toFixed(2);

        document.list_redeem.Redeemtoken.value = Redeemarray;
        
    }*/
    function checkTotal() {
        var input = document.getElementsByName("choice");
        sum = 0;
        Redeemarray="";
        for (var i = 0; i < input.length; i++) {
            if (input[i].checked) {
                sum += parseFloat(input[i].getAttribute("data-redeem_point"));
                if(Redeemarray==""){
                    Redeemarray=parseInt(input[i].getAttribute("data-redeem_token"));
                }else{
                    Redeemarray=Redeemarray+','+parseFloat(input[i].getAttribute("data-redeem_token"));
                }
            }
        }
      document.getElementsByName("redeemtotal")[0].value = sum.toFixed(2);
    }
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
                    "data": "orderId"
                },{
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
    $("#redeemamount").click(function(e){
        $(this).attr('disabled',true);
        $(this).addClass('btndisabled');
        //alert("ajax");
        var sum = $("#redeemtotal").val();
        if(!$.isNumeric(sum)) {
             toastr.warning("Enter Valid Amount!");
             return false;
        }
        e.preventDefault();
        toastr.options.preventDuplicates = false;
        // toastr.options = {
        //        "closeButton": false,
        //        "debug": false,
        //        "newestOnTop": true,
        //        "progressBar": true,
        //        "positionClass": "toast-top-right",
        //        "preventDuplicates": true,
        //        "onclick": null,
        //        "showDuration": "300",
        //        "hideDuration": "1000",
        //        "timeOut": "1000",
        //        "extendedTimeOut": "1000",
        //        "showEasing": "swing",
        //        "hideEasing": "linear",
        //        "showMethod": "fadeIn",
        //        "hideMethod": "fadeOut",
        //        "autoDismiss": true,
        //         "maxOpened": "1",
        //    }
        swal({
            title: "Are you sure do you want to redeem amount?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willyes) => {
            if (willyes) {    
            if(sum!=0 || sum >0){
            //alert(Redeemarray);exit();
            $.ajax({
                    type: "post",
                    url: "<?php echo url('/redeemamount'); ?>",
                    data: "Redeemtoken="+Redeemarray+"&sum="+sum, 
                    beforeSend:function(){
                        $(".load-more").text("Loading...");
                        $('.ajax-loader').css("visibility", "visible");

                    },
                    success: function(data){
                        $("#redeemamount").attr('disabled',false);
                        $("#redeemamount").removeClass('btndisabled');
                        var res = data.split("@@@");

                        $('.ajax-loader').css("visibility", "hidden");
                        if(res !='' && res[0] == 5){
                            toastr.warning("Possible redeem amount is "+res[1]);
                        }else if(data == 1){
                            toastr.success("Amount is redeemed!");
                            location.reload();
                            // $("#redeemamount").prop('disabled',true);
                        }else if(data == 2){
                            toastr.warning("Please verify your pan card,to redeem your amount!");
                        }else if(data == 3){
                            toastr.warning("Phone Number Not Found!");
                        }else if(data == 4){
                            toastr.warning("Redeem Transactions Failed!");
                        }else if(data == 6){
                            toastr.warning("Amount should be enter less than wallet amount");
                        }else{
                            toastr.warning("Redeem Amount Should Be Greater than {{$reedemableamt->redeemable_amount}} !");
                        }
                    }
                });
        }else{
             toastr.warning("Amount Should Be Greater than 0!");
        }
        
        }else{
            swal("Amount not redeemed");
            $(this).attr('disabled',false);
        $(this).removeClass('btndisabled');
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
