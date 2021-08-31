@extends('layouts.home')
@section('styles')
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">

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
                                <li class="breadcrumb-item active" aria-current="page"><a href="{{url('/profile')}}">Profile</a></li>
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
                    <div class="profile_edit_inner_wrap ">
                        <div class="profile_edit_wrap profile_water_marker_outer_wrap">
                            <div class="profile_img_wrap">
                                <img id="profilepreview" src="{{asset('public/uploads/profile/original/'.Auth()->user()->photo)}}" alt="{{Auth()->user()->name}}" title="{{Auth()->user()->name}}">
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
                                  <span class="sr-only">100% Complete</span>
                                </div>
                            </div>
                            <p>{{number_format((float)Auth::user()->wallet_point, 2, '.', '')}} points available</p>
                            <div class="common_btn_wrap">
                                <a href="{{route('usertransaction')}}#redeem_content">Redeem Earnings</a>
                            </div>
                        </div>
                    </div>
                    <div class="profile_nav_wrap">
                        @include('includeContentPage.profilemenu')
                    </div>
                </div>
                <div class="col-md-9 profile_right_col">
                    @if(isset($errors) and $errors->any())
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><strong>{{ __('Oops ! An error has occurred.') }}</strong></h5>
                            <ul class="list list-check">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="profile_form_fields_outer_wrap">
                        <form role="form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" pattern="(.){6,15}" minlength="6" minlength="15" value="{{ $profile->name }}" required class="form-control"  >
                                        <small id="nameHelp" class="form-text text-muted">Name Accept Max 15 Characters Only</small>
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label>Email Address</label>
                                        <input type="email" value="{{ $profile->email }}" required class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label>Work Mail ID</label>
                                        <input type="email" value="{{ $profile->work_mail}}" required class="form-control" disabled>
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label>Mobile No</label>
                                        <input type="text" value="{{ $profile->phone_no}}" required class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row date_field_outer_wrap">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label>Date of Birth</label>
                                        <input id="datepicker" name="dob" value="{{ $profile->dob}}" required >
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <!-- <div class="common_select_option post_selection_field_wrap form-group">
                                        <label>Profile Image</label>
                                        <div class="contact_upload_img_wrap wrap-custom-file">
                                            <input type="file" type="file" name="image" id="profile" value="{{ $profile->photo }}" name="attachments"  />
                                            <label  for="profile">
                                                <span>Add file  or drop files here</span>
                                            </label>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="col-md-6">
                                    <div class="multicheck_box_wrap radio_btn_wrap form-group">
                                        <label class="multicheck_label_wrap">Gender</label>
                                        <div class="multicheck_outer_wrap">
                                            <label class="multicheck_box_inner_wrap">Male
                                                <input type="radio"  @if($profile->gender=='Male') Checked @endif name="gender" value="Male">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="multicheck_box_inner_wrap">Female
                                                <input type="radio" @if($profile->gender=='Female') Checked @endif name="gender" value="Female">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="multicheck_box_inner_wrap">LGBT
                                                <input type="radio" @if($profile->gender=='LGBT') Checked @endif name="gender" value="LGBT">
                                                <span class="checkmark"></span>
                                            </label>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row justify-content-center ">
                                <div class="col-md-4 common_btn_wrap contact_submit_btn_wrap">
                                    <button type="submit" class="save" style="display: none;">Update</button>
                                </div>  
                            </div>
                        </form>
                    </div>
                    <div class="profile_form_fields_outer_wrap mt-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="common_select_option post_selection_field_wrap form-group">
                                    <label>Profile Image</label>
                                    <div class="contact_upload_img_wrap wrap-custom-file">
                                        <input type="file" id="profileimage" accept="image/*"/>
                                        <label  for="profileimage">
                                            <span>Add file  or drop files here</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 image-option" style="display:none;">
                            <div id="upload-demo"></div>
                            
                            </div>
                            <div class="col-md-12 text-center image-option" style="display:none;">
                                 <button class="upload-image-rotate btn btn-primary" data-deg="-90"><i class="fa fa-repeat" aria-hidden="true"></i></button>
                                <button class="btn btn-primary  upload-image ">Upload Image</button>
                                <button class="upload-image-rotate btn btn-primary" data-deg="90"><i class="fa fa-undo" aria-hidden="true"></i></button>
                            </div>
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
        $('.save').hide();
        $( "input" ).on('focus',function() {
          $('.save').show();
        });
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
    
    var resize = $('#upload-demo').croppie({
        enableExif: true,
        enableOrientation: true,    
        viewport: { // Default { width: 100, height: 100, type: 'square' } 
            width: 170,
            height: 170,
            type: 'square', //circle / square
            enableOrientation: true
        },
        boundary: {
            width: 250,
            height: 250
        }
    });
     
    $('#profileimage').on('change', function () { 
        $('.image-option').show();  
        var reader = new FileReader();
        reader.onload = function (e) {
        resize.croppie('bind',{
            url: e.target.result,
            orientation: 1
          }).then(function(){
            console.log('jQuery bind complete');
          });
            
        }
        reader.readAsDataURL(this.files[0]);
    });
     
    $('.upload-image-rotate').on('click', function(ev) {
        resize.croppie('rotate', parseInt($(this).data('deg')));

    }); 
    $('.upload-image').on('click', function (ev) {
        resize.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (img) {
            $.ajax({
              url: "{{route('profile.image.update')}}",
              type: "POST",
              data: {"image":img},
              success: function (data) {
                if(data==0){
                    location.reload()
                    toastr.success('Profile Image Updated successfully!');
                }else if(data==1){
                    toastr.success('The profile photo will be changed after being approved by OJAAK Team!');
                }else{
                    toastr.error('Profile Image Updated failed!');
                }
              }
            });
        });
        
    });
</script>

@endsection