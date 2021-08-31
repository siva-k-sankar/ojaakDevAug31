@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
@endsection
@section('content')
<!-- Page Title -->
    <div class="container-fluid pl-0 pr-0 page_title_bg_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-md-6">
                    <div class="profile_breadcum_wrap ads_managemnet_title_wrap">
                        <nav aria-label="breadcrumb" class="page_title_inner_wrap">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Invoice</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="refer_code_wrap">
                        <h2>
                            <strong>Referral code :</strong>
                            <span>OJAAK-{{Auth::user()->referral_code}}</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Profile -->
    <div class="container-fluid pl-0 pr-0 profile_edit_row_outer_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-md-3 pl-0 profile_left_col">
                    <div class="profile_edit_inner_wrap">
                        <div class="profile_edit_wrap profile_water_marker_outer_wrap">
                            <div class="profile_img_wrap">
                                <img id="profilepreview" src="{{asset('public/uploads/profile/original/'.Auth()->user()->photo)}}" alt="avatar" title="avatar">
                            </div>
                            <?php $badge=verified_profile(Auth::user()->id)?>
                            @if($badge == '1')
                                <div class="profile_water_marker">
                                    <img src="{{asset('public/frontdesign/assets/img/ojaak_watermark.png')}}">
                                </div>
                            @endif
                        </div>
                        <div class="profile_name_wrap">
                            <h2 class="pt-1">{{Auth::user()->name}}</h2>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                  <span class="sr-only">70% Complete</span>
                                </div>
                            </div>
                            <p>{{number_format((float)Auth::user()->wallet_point, 2, '.', '')}} points available</p>
                            <div class="common_btn_wrap">
                                <a href="{{route('usertransaction')}}#redeem_content">Redeem Earnings</a>
                            </div>
                        </div>
                    </div>
                    <div class="profile_nav_wrap">
                        @include('includeContentPage.billingmenu')
                    </div>
                </div>
                <div class="col-md-9 profile_right_col">
                    <div class="invoice_outer_wrap">
                        <div class="invoice_title_outer_wrap row">
                            <div class="col-md-2 invoice_title_left_col">
                                <h4>Invoices</h4>
                            </div>

                            <div class="col-md-9 pr-0 invoice_title_right_col">
                                <form id="invoice_filter_form">
                                    <div class="invoice_date_outer_wrap">
                                        @csrf
                                        <div class="form-group">
                                            <input placeholder="From Date" name="invoice_from_date_pick" id="invoice_from_date_pick" autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <input placeholder="To Date" name="invoice_to_date_pick" id="invoice_to_date_pick" autocomplete="off">
                                        </div>
                                       
                                    </div>
                                </form>
                                <div class="mywallet_btn_wrap">
                                    <a href="javascript:void(0);" id="invoice_filter" style="margin-left:0px !important;" >
                                        <i class="fa fa-sliders" aria-hidden="true"></i>Filter
                                    </a>
                                </div>

                            </div>
                            <div class="invoice_table_wrap table-responsive-lg table-responsive-md table-responsive-sm">
                                <table class="table nowrap" id="invoice_table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Order ID</th>
                                            <th scope="col">AMOUNT</th>
                                            <th scope="col">DATE</th>
                                            <th scope="col">REFUND</th>
                                            <th scope="col">ACTION</th>
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
    </div>
    
@endsection
@section('scripts')
<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<!-- <script type="text/javascript">
    
    var table = $('#invoice_table').DataTable({
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "oLanguage": {
                "sEmptyTable": "No data available"
            },
    });
    $(document).on('click', '#invoice_filter', function(){
            localStorage.setItem("invoice_from_date",$('#invoice_from_date_pick').val());
            localStorage.setItem("invoice_to_date",$('#invoice_to_date_pick').val());
            localStorage.setItem("invoice_date_show","1"); 
            $('#invoice_filter_form').submit();
    });
    var date1,date2;
    if(localStorage.getItem("invoice_date_show")=='1'){
        
        if(localStorage.getItem("invoice_from_date")!=''){
            date1=localStorage.getItem("invoice_from_date");
            setTimeout(function(){ $("#invoice_from_date_pick").val(date1); }, 500);
        }
        if(localStorage.getItem("invoice_to_date")!=''){
            date2=localStorage.getItem("invoice_to_date");
            setTimeout(function(){ $("#invoice_to_date_pick").val(date2); }, 500);
            
        } 
    }else{
       localStorage.setItem("invoice_date_show","0");
        
    }
    
    
</script> -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#invoice_filter").click(function(){    
        //alert($("#location").val());
            redrawDatatable();
        });
        function redrawDatatable(){
            var table = $('#invoice_table').DataTable(); 
            table.draw( 'page' );
        }
        $('#invoice_table').DataTable({
            "oLanguage": {
                "sEmptyTable": "No data available"
            },
            "processing": true,
            "serverSide": true,
            "ordering": false,
            dom: 'Brtip',
            buttons: [
            'csv',
            ],
            "ajax": {
                "url":"<?php echo url('/invoice/Get'); ?>",
                "type": "POST",
                "data": function(d) {
                    d.invoice_from_date_pick = $('#invoice_from_date_pick').val();
                    d.invoice_to_date_pick = $('#invoice_to_date_pick').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
                    "data": "orderid"
                }, {
                    "data": "amount"
                }, {
                    "data": "date"
                }, {
                    "data": "refund"
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
    });
</script>

@endsection