@extends('layouts.home')
@section('styles')
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<script src="https://cdn.kon.ovh/app/frydBox/jquery.frydBox.min.js"></script>
<style>
.profile_img_trigger {
  position: relative;
}

.image {
  opacity: 1;
  display: block;
  width: 100%;
  height: auto;
  transition: .5s ease;
  backface-visibility: hidden;
}

.edit_icon_profile {
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
}

.profile_img_trigger:hover .image {
  opacity: 0.3;
}

.profile_img_trigger:hover .edit_icon_profile {
  opacity: 1;
}

.edit_icon_profile .text {
  color: white;
  font-size: 16px;
}  
.croppie-container .cr-resizer, .croppie-container .cr-viewport {
    border-radius: 30px !important;
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
                                <li class="breadcrumb-item active" aria-current="page"><a href="{{url('/profile')}}">My Profile</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-md-6 pl-0">
                    <div class="profile_breadcum_new_wrap">
                        <span class="pull-right  text-white font-weight-bold" style="padding: 20px 0px !important;">Referral code :- OJAAK - {{Auth::user()->referral_code}}</span>
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
                        <div class="profile_edit_wrap">
                            <div class="profile_img_wrap profile_img_trigger">
                                <img id="profilepreview" src="{{asset('public/uploads/profile/original/'.Auth()->user()->photo)}}"  title="{{Auth()->user()->name}}" class="image">
                                <div class="edit_icon_profile">
                                    <div class="text" title="Change Profile Photo"><i class="fa fa-2x fa-camera" aria-hidden="true"></i></div>
                                </div>
                            </div>
                            <?php $badge=verified_profile(Auth::user()->id)?>
                            @if($badge == '1')
                                <div class="profile_water_marker">
                                    <img src="{{asset('public/frontdesign/assets/img/ojaak_watermark.png')}}">
                                </div>
                            @endif
                        </div>
                        <div class="profile_name_wrap pt-2">
                            <h2>{{Auth::user()->name}}</h2>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                                  <span class="sr-only">100% Complete</span>
                                </div>
                            </div>
                            <p>{{number_format((float)Auth::user()->wallet_point, 2, '.', '')}} points available</p>
                            <div class="common_btn_wrap">
                                <a href="{{route('usertransaction')}}">Redeem Earnings</a>
                            </div>
                        </div>
                    </div>
                    <div class="profile_nav_wrap">
                        @include('includeContentPage.profilemenu')
                        <!-- <ul>
                            <li>
                                <a class="profile_nav_link active" href="#">Ads Management</a>
                            </li>
                            <li>
                                <a class="profile_nav_link" href="#">Referral Users</a>
                            </li>
                            <li>
                                <a class="profile_nav_link" href="#">Logout</a>
                            </li>
                        </ul> -->
                    </div>
                </div>
                <div class="col-md-9 profile_right_new_col_wrap">
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
                                        <input type="text"  name="name" pattern="(.){6,15}" minlength="6" minlength="15" value="{{ $profile->name }}" required class="form-control btn_show">
                                        <small id="nameHelp" class="form-text text-muted">Name Accept Max 15 Characters Only</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group profile_edit_button_outer_wrap">
                                        <label>Mobile No</label>
                                        <p class="profile_edit_button_wrap" data-toggle="modal" data-target="#mobile_no_edit_model" >
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </p>
                                        <input type="text" value="{{ $profile->phone_no}}"  class="form-control" disabled>
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group profile_edit_button_outer_wrap">
                                        <label>Email Address</label>
                                        <p class="profile_edit_button_wrap" data-toggle="modal" data-target="#email_address_edit_model" >
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </p>
                                        <input type="email" value="{{ $profile->email }}" required disabled class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group profile_edit_button_outer_wrap">
                                        <label>Work Mail ID</label>
                                        <p class="profile_edit_button_wrap" data-toggle="modal" data-target="#workmail_address_edit_model" >
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </p>
                                        <input type="email" value="{{ $profile->work_mail}}"  class="form-control" disabled>
                                    </div>
                                </div>  
                            </div>
                            <div class="row date_field_outer_wrap">
                                <div class="col-md-6">
                                    <div class="common_select_option post_selection_field_wrap form-group">
                                        <label>Gender</label>
                                        <select class="form-control btn_show" name="gender" required="">
                                          <option value="Male" @if($profile->gender=='Male') selected @endif >Male</option>
                                          <option value="Female" @if($profile->gender=='Female') selected @endif>Female</option>
                                          <option value="LGBT" @if($profile->gender=='LGBT') selected @endif>LGBT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label>Date of Birth</label>
                                        <input id="datepicker" name="dob" value="{{ $profile->dob}}" required>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row ">
                                <div class="col-md-6">
                                    <div class="fields_common_wrap form-group">
                                        <label>Referral code</label>
                                        <input type="text" name="Referralcode" value="OJAAK-{{Auth::user()->referral_code}}"class="form-control btn_show" disabled="">
                                    </div>  
                                </div>
                            </div> -->
                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="common_btn_wrap contact_submit_btn_wrap">
                                        <button type="submit" class="save" disabled="">Update</button>
                                    </div>
                                </div>
                            </div>
                            <div class="social_media_outer_wrap new_border_bottom_wrap">
                                <h3>Social Media</h3>
                                <div class="row ">
                                    <div class="col-md-6 common_btn_wrap contact_submit_btn_wrap">
                                        @if($profile->account_register_reference == 4)
                                            <a href="javascript:void(0);" class="disconreference">Disconnect Facebook</a>
                                        @else
                                            @if($profile->facebook_id == null || $profile->facebook_id == '')
                                                <a href="{{ url('/profile/verify/social/facebook') }}" >Connect Facebook</a>
                                            @else
                                                <a href="javascript:void(0);" class="disconface">Disconnect Facebook</a>
                                            @endif
                                        @endif
                                        
                                    </div>  
                                    <div class="col-md-6 common_btn_wrap contact_submit_btn_wrap">
                                        @if($profile->account_register_reference == 3)
                                            <a href="javascript:void(0);" class="disconreference">Disconnect Google</a>
                                        @else
                                            @if($profile->google_id == null || $profile->google_id == '')
                                                <a href="{{ url('/profile/verify/social/google') }}" >Connect Google</a>
                                            @else
                                                <a href="javascript:void(0);" class="discongoo">Disconnect Google</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form role="form" action="{{route('profile.disconnectsocial')}}" method="POST"  id="disconsocialform">
                                        @csrf
                            <input type="hidden" name="socialinput" id="socialinput" class="form-control" >
                            <input type="submit" id="socialinputbtn" class="form-control" style="display: none !important" >
                        </form>
                        <?php 
                            $aadhar = get_proofcheck(1);
                            $driving  = get_proofcheck(2);
                            $pancard  = get_proofcheck(3);
                            $voter  = get_proofcheck(4);
                        ?>
                        <form role="form" action="{{route('profile.govtproof.update')}}" method="POST" enctype="multipart/form-data" id="proofuploadform">
                            @csrf
                            @if($aadhar==0 || $driving==0 || $pancard==0 || $voter==0)
                            <div class="social_media_outer_wrap">
                                <h3>Upload Govt Proof</h3>
                                <div class="row">
                                    
                                    @if($aadhar==0)
                                    <div class="col-lg-3 col-md-6">
                                        <div class="image_upload_wrap fields_common_wrap">
                                            <div class="wrap-custom-file">
                                              <input type="file" name="aadharcard" class="preview_proof"  id="aadhar_card" accept=".gif, .jpg, .png" />
                                              <label  for="aadhar_card">
                                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                <span>Aadhar Card</span>
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($driving==0)
                                    <div class="col-lg-3 col-md-6">
                                        <div class="image_upload_wrap fields_common_wrap">
                                            <div class="wrap-custom-file">
                                              <input type="file" name="drivinglicense"  class="preview_proof" id="driving_card" accept=".gif, .jpg, .png" />
                                              <label  for="driving_card">
                                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                <span>Driving Card</span>
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($pancard==0)
                                    <div class="col-lg-3 col-md-6">
                                        <div class="image_upload_wrap fields_common_wrap">
                                            <div class="wrap-custom-file">
                                              <input type="file" name="pancard"   class="preview_proof" id="pan_card" accept=".gif, .jpg, .png" />
                                              <label  for="pan_card">
                                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                <span>Pan Card</span>
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($voter==0)
                                    <div class="col-lg-3 col-md-6">
                                        <div class="image_upload_wrap fields_common_wrap">
                                            <div class="wrap-custom-file">
                                              <input type="file" name="votecard"  class="preview_proof"  id="vote_card" accept=".gif, .jpg, .png" />
                                              <label  for="vote_card">
                                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                <span>Voter ID</span>
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            <!-- <div class="form-group text-center">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="terms">
                                        <label class="form-check-label" for="terms" >By Accepting <a href="{{url('termscondition')}}" target="_blank">Terms and condition</a></label>
                                    </div>
                                </div>
                            </div> -->
                            @if(!$proofs->isEmpty())  
                            <div class="social_media_outer_wrap">
                                <h3>Proof List's</h3>
                                <div class="row justify-content-center">
                                    <div class="col-12">
                                        <table class="table  table-responsive" >
                                            <thead>
                                                <tr>
                                                    <th scope="col">SL.No</th>
                                                    <th scope="col">Proof Category</th>
                                                    <th scope="col">Proof Image</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Comments</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($proofs as $key => $proof)
                                                <tr>
                                                    <th scope="row">{{++$key}}</th>
                                                    <td>{{ (($proof->proof !='')?get_prooflist($proof->proof):"-") }}</td>
                                                    <td><center class="single_image_popup"><a id='single_{{++$key}}' href='{{url("public/uploads/proof/$proof->image")}}' class="btn btn-outline-secondary" target="_blank">View</a></center></td>
                                                    <td>
                                                        <center>
                                                            @if($proof->verified == '0')
                                                            <small>Pending</small>
                                                            @endif
                                                            @if($proof->verified == '1')
                                                            <small>Verified</small>
                                                            @endif
                                                            @if($proof->verified == '2')
                                                            <small>Rejected</small>
                                                            @endif
                                                        </center>
                                                    </td>
                                                    <td title='{{(($proof->comments !="")?"$proof->comments":"NIL")}}'>
                                                        <center>
                                                            {{(($proof->comments !="")?"$proof->comments":"NIL")}}
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                        @if($proof->verified == '0')
                                                            <!-- - -->
                                                            <button type="button" class="btn btn-outline-success" onclick="changeprooftrigger('{{$proof->proof}}')">Change</button>
                                                        @endif
                                                        @if($proof->verified == '1')
                                                            -
                                                        @endif
                                                        @if($proof->verified == '2')
                                                            <button type="button" class="btn btn-outline-success" onclick="changeprooftrigger('{{$proof->proof}}')">Change</button>
                                                        @endif
                                                        </center>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($aadhar==0 || $driving==0 || $pancard==0 || $voter==0)
                            <div class="common_btn_wrap contact_submit_btn_wrap">
                                <button type="submit" id="proof_submit" disabled="">Update</button>
                            </div>
                            @endif
                        </form>
                    </div>
                    <!-- <div class="profile_form_fields_outer_wrap mt-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="common_select_option post_selection_field_wrap form-group">
                                    <label>Profile Image</label>
                                    <div class="contact_upload_img_wrap wrap-custom-file">
                                        <input type="file" id="profileimage_upload" accept="image/*"/>
                                        <label  for="profileimage_upload">
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
                    </div> -->
                </div>
            </div>
        </div>  
    </div>

    <div id="reviews_popup_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="max-width: 850px">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="close_chat_btn_wrap button_close_model">
                    <a href="#" data-dismiss="modal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="modal-body" id='reviewscontent'>
                    <h2>Reviews</h2>
                    <div class="reviewscontent_contents">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Edit Model -->
    <div id="mobile_no_edit_model" class="profile_page_model_outer_wrap modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="close_chat_btn_wrap button_close_model primary_page_close_btn">
                    <a href="#" data-dismiss="modal" class="">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="profile_page_model_inner_wrap modal-body">
                    <div class="profile_page_model_content_wrap">
                        @if(Auth::user()->phone_no !="")
                            <h3>Current Mobile Id Is : {{ Auth::user()->phone_no }}</h3>
                        @else
                            <h3>No Mobile Number</h3>
                        @endif
                        <div class="profile_page_btn_wrap">
                            <button type="button" id="primary_mobile_update_edit_btn">Edit</button>
                        </div>
                        <div class="fields_common_wrap form-group primary_mobile_update_form"  style="display:none;">
                            <label>Enter Mobile No</label>
                            <input type="number" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189" min="0" class="form-control" name="primary_mobile_input" id="primary_mobile_input" pattern="[0-9]{10}" title="Enter 10 digit" required="">
                        </div>
                        <div class="profile_page_btn_wrap primary_mobile_update_form"  style="display:none;">
                            <button type="button" name="primary_mobile_send_otp_btn" id="primary_mobile_send_otp_btn">Send OTP</button>
                        </div>
                        <div class="fields_common_wrap form-group primary_mobile_update_otp_form"  style="display:none;">
                            <label>Enter OTP</label>
                            <input type="number"  onkeydown="return event.keyCode !== 69 && event.keyCode !== 189" min="0"name="primary_mobile_otp_input" id="primary_mobile_otp_input" class="form-control" pattern="[0-9]{6}" title="ENTER 6 DIGIT OTP" required="">
                        </div>
                        <div class="profile_page_btn_wrap primary_mobile_update_otp_form"  style="display:none;">
                            <button type="button" id="primary_mobile_update_form_post">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Edit Model -->
    <div id="email_address_edit_model" class="profile_page_model_outer_wrap modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="close_chat_btn_wrap button_close_model primary_page_close_btn">
                    <a href="#" data-dismiss="modal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="profile_page_model_inner_wrap modal-body">
                    <div class="profile_page_model_content_wrap">
                        @if(Auth::user()->email !="")
                            <h3>Current Mail Id Is : {{ Auth::user()->email }}</h3>
                        @else
                            <h3>Mail Id Is Not Registered</h3>
                        @endif
                        <div class="profile_page_btn_wrap">
                            <button type="button" id="primary_mail_update_edit_btn">Edit</button>
                        </div>
                        <div class="fields_common_wrap form-group primary_mail_update_form" style="display:none;">
                            <label>Enter Mail Address</label>
                            <input type="text"  class="form-control" name="primary_mail_input" id="primary_mail_input" required="">
                        </div>
                        <div class="profile_page_btn_wrap primary_mail_update_form" style="display:none;">
                            <button type="button" id="primary_mail_update_form_post">Update Mail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Work Mail Edit Model -->
    <div id="workmail_address_edit_model" class="profile_page_model_outer_wrap modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="close_chat_btn_wrap button_close_model primary_page_close_btn">
                    <a href="#" data-dismiss="modal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="profile_page_model_inner_wrap modal-body">
                    <div class="profile_page_model_content_wrap">
                        @if(Auth::user()->work_mail !="")
                            <h3>Current Work Mail Id Is : {{ Auth::user()->work_mail }}</h3>
                        @else
                            <h3>Work Mail Id Is Not Registered</h3>
                        @endif
                        <div class="profile_page_btn_wrap">
                            <button type="button" id="work_mail_update_edit_btn">Edit</button>
                        </div>
                        <div class="fields_common_wrap form-group work_mail_update_form" style="display:none;">
                            <label>Enter Mail Address</label>
                            <input type="email"  class="form-control" name="work_mail_input" id="work_mail_input"  required="">
                        </div>
                        <div class="profile_page_btn_wrap work_mail_update_form" style="display:none;">
                            <button type="button" id="work_mail_update_form_post">Update Mail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- Work Mail Edit Model -->
    <div id="profile_update_model" class="profile_page_model_outer_wrap modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="close_chat_btn_wrap button_close_model primary_page_close_btn">
                    <a href="#" data-dismiss="modal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="profile_page_model_inner_wrap modal-body">
                    <div class="profile_page_model_content_wrap">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="common_select_option post_selection_field_wrap form-group">
                                    <label>Profile Image</label>
                                    <div class="contact_upload_img_wrap wrap-custom-file">
                                        <input type="file" id="profileimage_upload" accept="image/*"/>
                                        <label  for="profileimage_upload">
                                            <span>Add file  or drop files here</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 image-option" style="display:none;">
                                <div id="upload-demo"></div>
                            </div>
                            <div class="col-md-12 text-center image-option" style="display:none;">
                                 <button class="upload-image-rotate btn btn-outline-secondary" data-deg="-90"><i class="fa fa-repeat" aria-hidden="true"></i></button>
                                <button class="btn btn-outline-success  upload-image ">Upload Image</button>
                                <button class="upload-image-rotate btn btn-outline-secondary" data-deg="90"><i class="fa fa-undo" aria-hidden="true"></i></button>
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
    //$('.save').hide();
    $( ".btn_show" ).on('keyup',function() {
        //$('.save').show();
        $('.save').removeAttr("disabled");
    });
    $( ".btn_show,#datepicker" ).on('change',function() {
        //$('.save').show();
        $('.save').removeAttr("disabled");
    });
    function changeprooftrigger(id){
        if(id==1){
             $('#aadhar_card').click();
        }
        if(id==2){
             $('#driving_card').click();
        }
        if(id==3){
             $('#pan_card').click();
        }
        if(id==4){
             $('#vote_card').click();
        }

    }

    $("#aadhar_card,#driving_card,#pan_card,#vote_card,#terms").change(function(){
        $('#proof_submit').removeAttr("disabled");
    });


    $("#proof_submit").click(function(e){
        e.preventDefault();
        const el = document.createElement('div')
        el.innerHTML = '<a href="{{url("termscondition")}}" target="_blank">Terms and condition</a>';

        swal({
            title: "Please make sure, you have read Terms and Condition!",
            text: '',
            icon: "warning",
            buttons: [
            'No, cancel it!',
            'Yes, I agree!'
            ],
            dangerMode: true,
            content: el,
        }).then(function(isConfirm) {
            if (isConfirm) {
                $( "#proofuploadform" ).submit();
                return true;
            } else {
                return false;
            }
        });


        /*if($("#terms").prop('checked') == true){
            return true;
        }else{
            swal({
              title: "Please make sure, you have read Terms and Condition",
              text: "",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            });
            return false;

        }*/
    });


    $(document).ready(function(){

        toastr.options.preventDuplicates = false;
        toastr.options.newestOnTop = true;
        var workmail='';
        var primarymail='';
        var primarymobileno='';
        var primarymobileotp='';
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        
        $("#work_mail_update_edit_btn").click(function(e){
            e.preventDefault();
            $(".work_mail_update_form").show();
            $("#work_mail_update_edit_btn").hide();
            
        });
        $("#work_mail_update_form_post").click(function(e){
            e.preventDefault();
            workmail=$("#work_mail_input").val();
            if(workmail !=""){
                if(emailReg.test(workmail)) {
                  // valid email
                    $.ajax({
                      url: "{{route('profile.workmail.update.ajax')}}",
                      type: "POST",
                      data: {"mail":workmail},
                      beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                      },
                      success: function (data) {
                            $('.ajax-loader').css("visibility", "hidden");
                            if(data==1){
                                toastr.success('A New verification link has been sent to your email address!');
                                setTimeout(function(){ location.reload() }, 1000);
                            }else{
                                toastr.warning('This Mail Already Taken!');
                            }
                        }
                    });
                } else {
                  // not valid
                  toastr.warning('Enter the Valid Mail Address');
                }
                    
            }else{
                toastr.warning('Enter the Mail Address');
            }
        });

        $("#primary_mail_update_edit_btn").click(function(e){
            e.preventDefault();
            $(".primary_mail_update_form").show();
            $("#primary_mail_update_edit_btn").hide();
            
        });
        $("#primary_mail_update_form_post").click(function(e){
            e.preventDefault();
            primarymail=$("#primary_mail_input").val();
            if(primarymail !=""){
                if(emailReg.test(primarymail)) {
                  // valid email
                    $.ajax({
                      url: "{{route('profile.mail.update.ajax')}}",
                      type: "POST",
                      data: {"mail":primarymail},
                      beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                      },
                      success: function (data) {
                            $('.ajax-loader').css("visibility", "hidden");
                            if(data==1){
                                toastr.success('A New verification link has been sent to your email address!');
                                setTimeout(function(){ location.reload() }, 1000);
                            }else{
                                toastr.warning('This Mail Already Taken!');
                            }
                        }
                    });
                } else {
                  // not valid
                  toastr.warning('Enter the Valid Mail Address');
                }
                    
            }else{
                toastr.warning('Enter the Mail Address');
            }
        });

        $("#primary_mobile_update_edit_btn").click(function(e){
            e.preventDefault();
            $(".primary_mobile_update_form").show();
            $("#primary_mobile_update_edit_btn").hide();
            
        });
        $("#primary_mobile_send_otp_btn").click(function(e){
            e.preventDefault();
             primarymobileno=$("#primary_mobile_input").val();
            if(primarymobileno.length  == 10 && primarymobileno!=""){
                $.ajax({
                  url: "{{route('profile.mobile.check')}}",
                  type: "POST",
                  data: {"mobile":primarymobileno},
                  beforeSend:function(){
                    $('.ajax-loader').css("visibility", "visible");
                  },
                  success: function (data) {
                        $('.ajax-loader').css("visibility", "hidden");
                        if(data==1){
                            $(".primary_mobile_update_otp_form").show();
                            $("#primary_mobile_input").attr('disabled', 'disabled');
                        }else{
                            toastr.warning('This Mobile Number Already Taken!');
                        }
                    }
                });

                
            }else{
                 toastr.warning('Enter the Valid Mobile Number');
            }
            
        });

        $("#primary_mobile_update_form_post").click(function(e){
            e.preventDefault();
            primarymobileotp=$("#primary_mobile_otp_input").val();
            if(primarymobileotp.length  == 6 && primarymobileotp!=""){
                $.ajax({
                  url: "{{route('profile.mobile.update.ajax')}}",
                  type: "POST",
                  data: {"mobile":primarymobileno,"otp":primarymobileotp,},
                  beforeSend:function(){
                    $('.ajax-loader').css("visibility", "visible");
                  },
                  success: function (data) {
                        $('.ajax-loader').css("visibility", "hidden");
                        if(data==1){
                            toastr.success('This mobile number changed successfully!');
                             setTimeout(function(){ location.reload() }, 1000);
                        }else if(data==0){
                            toastr.warning('You OTP is incorrect ,please try again!');
                        }else{
                            toastr.error('Please try again later!');
                        }
                    }
                });

                
            }else{
                toastr.warning('Enter the Valid OTP');
            }

        });

        $(".primary_page_close_btn a").click(function(e){
            location.reload();
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                //$('#profilepreview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profileimage_upload").change(function() {
            readURL(this);
        });
        
    });


    $(document).on("click", ".get_user_reviews", function(){
        var thisuuid = $(this).data('userid');
        $.ajax({
            url:"<?php echo url('profile/reviews')?>/"+thisuuid,
            //data:"id="+thisuuid,
            type: "get",
            success: function(data){
                if(data != '0'){
                    $("#reviews_popup_modal .reviewscontent_contents").html(data);
                }else{
                    $("#reviews_popup_modal .reviewscontent_contents").html("<p>No Reviews Found </p>");
                }
            }
        });
    });

        $('.preview_proof').each(function(){
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
        
        $('#profileimage_upload').each(function(){
          var $file = $(this),
              $label = $file.next('label'),
              $labelText = $label.find('span'),
              labelDefault = $labelText.text();
          $file.on('change',function(event){
            var fileName = $file.val().split('\\' ).pop(),
                tmppath = URL.createObjectURL(event.target.files[0]);
            if( fileName ){
              //$label.addClass('file-ok').css('background-image','url(' + tmppath +')');
              fileName=fileName.substr(0, 25);
              $labelText.text(fileName);
            }else{
              //$label.removeClass('file-ok');
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
         $('#profileimage_upload').on('change', function () { 
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
                        toastr.success('Profile Image Updated successfully!');
                        setTimeout(function(){ location.reload() }, 2000);
                    }else if(data==1){
                        toastr.success('The profile photo will be changed after being approved by OJAAK Team!');
                        setTimeout(function(){ location.reload() }, 2000);
                    }else{
                        toastr.error('Profile Image Updated failed!');
                    }
                  }
                });
            });
        });

        $('.profile_img_trigger').on('click', function(ev) {
            $('#profile_update_model').modal('show');
        }); 

        $('.discongoo').on('click', function(ev) {
             $('#socialinput').val('google');
             swal({
              title: "Are you sure?",
              text: "Disconnect Google",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $('#socialinputbtn').trigger('click');
              }
            });
        });

        $('.disconface').on('click', function(ev) {
            $('#socialinput').val("facebook");
            swal({
              title: "Are you sure?",
              text: "Disconnect Facebook",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $('#socialinputbtn').trigger('click');
              } 
            });
        });

        $('.disconreference').on('click', function(ev) {
             swal("Primary Account!", "This is the primary account. So the account could not be disconnected!", "warning");
        });

</script>
@endsection