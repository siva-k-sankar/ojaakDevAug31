@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
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
    <div class="container-fluid pl-0 pr-0 page_title_new_bg_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-md-6 pl-0">
                    <div class="profile_breadcum_new_wrap">
                        <nav aria-label="breadcrumb" class="page_title_inner_new_wrap">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="#">Settings</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Logout</a></li>
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
                <div class="col-md-3 pl-0 profile_left_new_col_wrap">
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
                        @include('includeContentPage.settingmenu')
                    </div>
                </div>
                <div class="col-md-9 profile_right_new_col_wrap">
                    <div class="profile_form_fields_outer_wrap">
                        <form role="form" class="form" method="post"action="{{route('setting.logout.all')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="show_contact_detail_wrap form-group">
                                        <label>Logout Feature</label>
                                        <div class="show_detail_inner_wrap">
                                            <p>This device</p>
                                            <input type="hidden" name="Sthislogout"  value="0">
                                            <label class="switch">
                                              <input type="checkbox"  name="Sthislogout"  value="1">
                                              <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="show_detail_inner_wrap">
                                            <p>All Other device</p>
                                            <label class="switch">
                                              <input type="radio"  name="Slogout"  value="2">
                                              <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="show_detail_inner_wrap">
                                            <p>All device</p>
                                            <label class="switch">
                                              <input type="radio"  name="Slogout"  value="3">
                                              <span class="slider round"></span>
                                            </label>
                                        </div>
                                        
                                    </div>
                                </div>  
                            </div>
                            <div class="row SlogoutHide" style="margin-bottom: 8px !important; display: none;">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label style="font-size: 23px !important;">Logout to Enter Current Password</label>
                                     </div>
                                </div>  
                            </div>
                            <div class="row SlogoutHide" style="margin-bottom: 15px !important; display:none;">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <input type="password" name="password" value="" placeholder="Current Password" required class="form-control">
                                    </div>
                                </div>  
                            </div>
                            <div class="row SlogoutHide justify-content-center"  style="display:none;">
                                <div class="col-md-5 common_btn_wrap contact_submit_btn_wrap">
                                    <button type="submit" class="">logout</button>
                                </div>  
                            </div>
                        </form>
                    </div>
                    <div class="profile_form_fields_outer_wrap mt-2">
                       <!-- @if(!$loginsession->isEmpty())
                            <table id="wallet_table_wrap" class="table nowrap table-responsive-lg">
                                <thead>
                                    <tr>
                                        <th scope="col">SL.No</th>
                                        <th scope="col">Ip Address</th>
                                        <th scope="col">User Agent Device</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loginsession as $key => $login)
                                    <tr>
                                        <td scope="row">{{++$key}}</td>
                                        <td>{{ $login->ip_address  }}</td>
                                        <td>{{$login->user_agent}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                NO Login Device
                            @endif -->
                            <div class="table-responsive">
                            <table id="wallet_table_wra" class="table nowrap table-bordered" width="100%">
                                <thead class="thead-light">
                                    <tr >
                                        <th scope="col"> IP ADDRESS <!-- / OJAAK APP --></th>
                                        <th scope="col"> User Agent Device</th>
                                    </tr>
                                </thead>
                            </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $('input[name="Slogout"]').change(function(){
            if($(this).prop("checked") == true){
                $('.SlogoutHide').show();
            }
            else if($(this).prop("checked") == false){
                $('.SlogoutHide').hide();
            }
        });
        $('input[name="Sthislogout"]').change(function(){
            if($(this).prop("checked") == true){
                swal({
                  title: "Are you sure?",
                  text: "Logout this device!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                    $.ajax({
                        type: 'get',
                        url: APP_URL+'/'+'/ajaxlogout',
                        dataType: 'json',
                        beforeSend:function(){
                            $('.ajax-loader').css("visibility", "visible");
                        },
                        success: function(response)
                        {
                            location.reload(); // but it is GET request... should be POST request
                        }
                    });
                  }else{
                        location.reload();
                  }
                });
            }
        });
        setTimeout(function(){
            redrawDatatable()
        }, 3000);
        function redrawDatatable(){
            var table = $('#wallet_table_wra').DataTable(); 
            table.draw( 'page' );
        }
        
            $('#wallet_table_wra').DataTable({
                "oLanguage": {
                    "sEmptyTable": "No data available"
                },
                "processing": false,
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
                    "url":"<?php echo url('/setting/login/getdevice'); ?>",
                    "type": "POST",
                    "data": function(d) {
                        d.fromdate = $('#invoice_from_date_pick').val();
                        d.todate = $('#invoice_to_date_pick').val();
                        d._token = "{{ csrf_token() }}";
                    }
                },
                "columns": [ {
                        "data": "ip"
                    }, {
                        "data": "useragent"
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