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
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Change Password</a></li>
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
                        <form role="form" action="{{ route('profile.updatepassword') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row" style="margin-bottom: 8px !important;">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label style="font-size: 23px !important;">Change Password</label>
                                     </div>
                                </div>  
                            </div>
                            <div class="row" style="margin-bottom: 15px !important;">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <input type="password" name="old" value="" placeholder="Current Password" required class="form-control">
                                    </div>
                                </div>  
                            </div>
                            <div class="row" style="margin-bottom: 15px !important;">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <input type="password" name="new"  placeholder="New Password" value="" required class="form-control">
                                    </div>
                                </div>  
                            </div>
                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <input type="password" placeholder="Confirm Password"name="confirm" value="" required class="form-control">
                                    </div>
                                </div>  
                            </div>
                            <div class="row ">
                                <div class="col-md-4 common_btn_wrap contact_submit_btn_wrap">
                                    <button type="submit" class="">Change Password</button>
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                $('#profilepreview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile").change(function() {
            readURL(this);
        });
    });
    $('input[type="file"]').each(function(){
      var $file = $(this),
          $label = $file.next('label'),
          $labelText = $label.find('span'),
          labelDefault = $labelText.text();
      $file.on('change',function(event){
        var fileName = $file.val().split('\\' ).pop(),
            tmppath = URL.createObjectURL(event.target.files[0]);
        if( fileName ){
          $label.addClass('file-ok').css('background-image','url(' + tmppath +')');
          $labelText.text(fileName);
        }else{
          $label.removeClass('file-ok');
          $labelText.text(labelDefault);
        }
      });
    });
</script>

@endsection