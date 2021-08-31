@extends('layouts.home')

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
                                <li class="breadcrumb-item" aria-current="page"><a href="{{url('/profile')}}">Profile</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Govt Proofs Updatation</a></li>
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
                        @include('includeContentPage.profilemenu')
                    </div>
                </div>
                <div class="col-md-9 profile_right_col">
                    <div class="profile_form_fields_outer_wrap">
                        <form role="form" action="{{route('profile.govtproof.update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center govt_proof_select_wrap">
                                <div class="col-md-6">
                                    <div class="common_select_option post_selection_field_wrap form-group">
                                        <select class="form-control" name="list" id="list" required="">
                                            @foreach($prooflist as $key => $proof)
                                                <option value="{{ $key }}" 
                                                        selected
                                                    >{{$proof}} </option>
                                                
                                            @endforeach
                                        </select>
                                    </div>
                                </div>  
                                
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-6 ">
                                    <div class="image_upload_wrap fields_common_wrap">
                                        <div class="wrap-custom-file">
                                          <input type="file" name="govtproof"   id="profile" accept=".gif, .jpg, .png" />
                                          <label  for="profile">
                                            <i class="fa fa-picture-o" aria-hidden="true"></i>
                                            <span>Drag & Drop Proof here or click to browse</span>
                                          </label>
                                        </div>
                                    </div>
                                </div>  
                                
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-4 common_btn_wrap contact_submit_btn_wrap">
                                    <center><button type="submit" class="">Update Proof</button></center>
                                </div>  
                            </div>
                        </form>
                    </div>
                    <div class="profile_form_fields_outer_wrap my-3">
                        @if(!$proofs->isEmpty())
                     <h2>Proof List's</h2>   
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
                                        <small>Not Yet Verified</small>
                                        @endif
                                        @if($proof->verified == '1')
                                        <small>Verified</small>
                                        @endif
                                        @if($proof->verified == '2')
                                        <small>Rejected</small>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{(($proof->comments !="")?"$proof->comments":"-")}}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                    @if($proof->verified == '1')
                                    <a href="{{route('profile.govtproof.edit',$proof->uuid)}}" class="btn btn-outline-primary">Edit</a>
                                    @endif
                                    @if($proof->verified == '0' || $proof->verified == '2')
                                    
                                    <button type="button" onclick="deleteProof('{{$proof->id}}')" class="btn btn-outline-danger">Delete</button>
                                    <form action="{{route('profile.govtproof.delete',$proof->id)}}" method="POST" id="del-proof-{{$proof->id}}" style="display:none;">
                                            @csrf
                                    </form>
                                    @endif
                                    </center>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    </div>
                </div>
            </div>
        </div>  
    </div>
    
@endsection
@section('scripts')
<script>

function deleteProof(id){
            
    swal({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        document.getElementById('del-proof-'+id).submit();
        swal("Poof has been deleted!", {
          icon: "success",
        });
      } else {
        swal("Your file is safe!");
      }
    });
}
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