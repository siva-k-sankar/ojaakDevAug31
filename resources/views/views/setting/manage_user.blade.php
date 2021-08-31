@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
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
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Manage User</a></li>
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
                        @if(!$block_user->isEmpty())
                            <table id="wallet_table_wrap" class="table nowrap table-responsive-lg">
                                <thead>
                                    <tr>
                                        <th scope="col">SL.No</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($block_user as $key => $blockuser)
                                    <tr>
                                        <td scope="row">{{++$key}}</td>
                                        <td>{{ (($blockuser->block_user_id !='')?get_username($blockuser->block_user_id):"-") }}</td>
                                        <td>
                                            <center class="common_btn_wrap contact_submit_btn_wrap" style="margin: 0px !important;">
                                                <button type="button" onclick="unblock('{{$blockuser->block_user_id}}')" class="btn btn-outline-primary">UnBlock</button>
                                                <form action="{{route('setting.privacy.manageusersunblock',$blockuser->block_user_id)}}" method="POST" id="un-block-{{$blockuser->block_user_id}}" style="display:none;">
                                                    @csrf
                                                </form>
                                            </center>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                No Blocked User!
                            @endif
                    </div>
                </div>
            </div>
        </div>  
    </div>
    
@endsection
@section('scripts')
<script>
        function unblock(id){
            swal({
            title: "Are you sure?",
            text: "UnBlock User !",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                document.getElementById('un-block-'+id).submit();
                swal("Unblock the User!", {
                  icon: "success",
                });
              } else {
                swal("Your record is safe!");
              }
            });
        }
    $(document).ready(function(){
        $('#wallet_table_wrap').DataTable();
    });
</script>

@endsection