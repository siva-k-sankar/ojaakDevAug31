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
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
<style type="text/css">
    .common_btn_wrap a .blockbtn {
    background-color: #62040f;
    }
</style>
@endsection
@section('content')
<?php 
    $segment1 = Request::segment('1');
    $segment2 = Request::segment('2');
    $segment3 = Request::segment('3');
?>

<!-- Page Title -->
    <div class="container-fluid pl-0 pr-0 page_title_new_bg_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-md-6 pl-0">
                    <div class="profile_breadcum_new_wrap">
                        <nav aria-label="breadcrumb" class="page_title_inner_new_wrap">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Profile info</a></li>
                            </ol>
                        </nav>
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
                                <img src="{{asset('public/uploads/profile/mid/'.$userdetails->photo)}}" alt="avatar" title="avatar">
                            </div>
                            <?php $badge=verified_profile($userdetails->id)?>
                            @if($badge == '1')
                                <div class="profile_water_marker">
                                    <img src="{{asset('public/frontdesign/assets/img/ojaak_watermark.png')}}">
                                </div>
                            @endif
                        </div>
                        <div class="profile_name_wrap profile_info_name_wrap">
                            <h2>{{get_name($userdetails->id)}}</h2>
                            
                            <div class="share_profile_link_outer_wrap">
                                @if($segment2 == 'info')
                                <a href="javascript:void(0)" onclick="setClipboard('{{url('profile/info')}}/{{$userdetails->uuid}}')">Share Profile Link</a>
                                 @endif
                            </div>
                           
                        </div>
                    </div>
                    <div class="profile_nav_wrap">
                        @include('includeContentPage.profilemenuforadmin')
                    </div>

                    <!-- Category Model Popup -->
                    <div id="share_profile_link">
                        Link is copied to clipboard 
                    </div>
                    <div class="profile_nav_wrap profile_info_listing_outer_wrap">
                        <h3>Linked Accounts</h3>
                        @if($userdetails->google_id)
                        <div class="listing_profile_info_wrap">
                            <p>Google</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                        @if($userdetails->facebook_id)
                        <div class="listing_profile_info_wrap">
                            <p>Facebook</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                        @if($userdetails->phone_verified_at)
                        <div class="listing_profile_info_wrap">
                            <p>Phone Number</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                        @if($userdetails->email_verified_at)
                        <div class="listing_profile_info_wrap">
                            <p>Email</p>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-9 pl-0 profile_right_new_col_wrap mywallet_table_outer_wrap table-responsive">
                    <div class="billing_outer_bg_wrap admin_view_user_wrap">
                        <div class="table-responsive">
                            <table id="wallet_table_wra11" class="table nowrap" style="width:100%">
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
                </div>

            </div>
        </div>  
    </div>
@endsection
@section('scripts')

    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js" type="text/javascript"></script>


<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
        $('#wallet_table_wra11').DataTable({
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
                "url":"<?php echo url('/users/wallet/Get'); ?>",
                "type": "POST",
                "data": function(d) {
                    d.fromdate = $('#invoice_from_date_pick').val();
                    d.todate = $('#invoice_to_date_pick').val();
                    d._token = "{{ csrf_token() }}";
                    d.useruuid = "{{ $userdetails->id }}";
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
@endsection