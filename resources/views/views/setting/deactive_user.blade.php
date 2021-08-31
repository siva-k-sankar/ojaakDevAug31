@extends('layouts.home')
@section('styles')
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

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
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">deactive</a></li>
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
                        <form role="form" action="{{route('setting.deactive.user')}}" method="POST" id="deactive" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="common_select_option post_selection_field_wrap form-group">
                                        <label>Select Reason for leaving</label>
                                        <select class="form-control" name="reason" style="width: 100% !important;">
                                            <option value="" hidden>Reason for leaving</option>
                                            <option value="This is temporary. I'll be back">This is temporary. I'll be back</option>
                                            <option value="I spend too much time using Ojaak">I spend too much time using OJAAK</option>
                                            <option value="I have another Ojaak account">I have another OJAAK account</option>
                                            <option value="My account was hacked">My account was hacked</option>
                                            <option value="I don't find Ojaak useful">I don't find OJAAK useful</option>
                                            <option value="I have a privacy concern">I have a privacy concern
                                            </option>
                                            <option value="I don't understand how to use Ojaak">I don't understand how to use OJAAK</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>  
                                </div>
                                
                            </div>
                            <div class="row justify-content-center" id='otherreason' style="display: none;">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label>Other Reason's</label>
                                        <!-- <input type="text" name="other" id='other' value=""  class="form-control"> -->
                                        <textarea class="form-control" rows="5" name="other" id='other'></textarea>
                                    </div>  
                                </div>
                                
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-4 common_btn_wrap contact_submit_btn_wrap">
                                    <button type="button" onclick="deactive()" id="update" class="btn save" disabled="">Deactive Account</button>
                                </div>  
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    
@endsection
@section('scripts')
<script>
 $(document).ready(function(){
        //$('.save').hide();
        /*$("select" ).on('focus change',function() {
          $('.save').show();
        });*/
        $( "select" ).on('change',function() {
            $('.save').removeAttr("disabled");
        });
    });   
</script>
<script>
    var other=0;
    $('select').on('change', function() {
        if('other'==this.value){
           $('#otherreason').show(); 
           other=1;
        }else{
             $('#otherreason').hide(); 
             other=0;
        }
    });
    function deactive(){
        if(other==0){
            swal({
            title: "Are you sure?",
            text: "Deactive  Account !",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                document.getElementById('deactive').submit();
                
              }
            });
        }else{
            if ($('#other').val() != null && $('#other').val() != '') { 
                swal({
                title: "Are you sure?",
                text: "Deactive  Account !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then((willDelete) => {
                  if (willDelete) {
                    document.getElementById('deactive').submit();
                    
                  }
                });

            }else{
                toastr.warning('Enter other reason');
            }
        }
    }
</script>

@endsection