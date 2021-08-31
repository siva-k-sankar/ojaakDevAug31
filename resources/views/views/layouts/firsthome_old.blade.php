<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('public/frontdesign/assets/style1.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,500,600,700,900|Poppins:300,400,500,700,800,900|Roboto:400,500,700,900&display=swap" rel="stylesheet">
    <link type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery-ui.js') }}">
    <link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/jquery-3.4.0.min.js') }}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/jquery-ui.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/category-filter.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
    <link href="{{ asset('public/css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/Bold-BS4-Animated-Back-To-Top.css') }}" rel="stylesheet">
    <script type="text/javascript">
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    </script>
    <style type="text/css">
    body {
        position: relative;
    }
    .ajax-loader {
        visibility: hidden;
        background-color: rgba(0, 0, 0, 0.74) !important;
        position: absolute;
        z-index: 9999 !important;
        width: 100%;
        height: 100%;
    }
    .ajax-loader svg {
        position: relative;
        top: 23%;
        left: 0%;
    }
    .list-form{
        position: relative;
        display: block;
        padding: .75rem 1.25rem;
        width:100px;
        font-weight: 600;
        text-align: center;
        letter-spacing: 0.1em; 
    }
    .list-group .show{
        color:#ff0000;
    }
    </style>
    @yield('styles')
</head>
<body>
<div class="ajax-loader" style="visibility: hidden;">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:transparent;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" r="32" stroke-width="8" stroke="#09d26d" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round" transform="rotate(326.163 50 50)">
                <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
            </circle>
            <circle cx="50" cy="50" r="23" stroke-width="8" stroke="#ffffff" stroke-dasharray="36.12831551628262 36.12831551628262" stroke-dashoffset="36.12831551628262" fill="none" stroke-linecap="round" transform="rotate(-326.163 50 50)">
                <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;-360 50 50"></animateTransform>
            </circle>
        </svg>
    </div>
    <a href="#" id="scrolltop" style="display: none;z-index: 999999;"><span></span></a>

    @include('partials.header')

    @yield('content')

    <!-- Modal -->
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="logincontent" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="m-2" >
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <ul id="titlelh" class="list-group list-group-horizontal-sm d-flex justify-content-center">
                            <li id="logclk" class="list-form m-1 show">Login</li>
                            <li  class="list-form m-1 show">|</li>
                            <li id="regclk" class="list-form m-1 ">Register</li>
                        </ul>

                        <form class="py-4" id="Hloginform" method="POST" action="{{ route('login') }}">
                            <div class="form-group">
                                <input id="lemail" type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email or Phone" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                            <div class="form-group">
                                <input id="lpassword" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                            </div>
                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                            </div>
                            <div id="loginErrorMsg"  class="m-2 text-danger">
                                <p id="logerrortxt"></p>
                            </div>
                            <input type="button" id="loginsubmit" class="btn btn-block btn-primary" value="{{ __('Login') }}">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                        </form>
                        <form class="py-4" id="Hregisterform" method="POST" action="" style="display: none;">
                             @csrf
                            <div class="form-group">
                                <input id="remail" type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Mobile or Email" name="email" value="{{ old('email') }}" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <input id="rpassword" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter Password" name="password" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <input id="rpassword-confirm" type="password"  class="form-control " name="password_confirmation" required placeholder="Enter Confirm Password"autocomplete="off">
                            </div>
                            
                            <div class="form-group " id="btn-referral-form">
                                <button type="button" id="btn-referral"class="btn btn-block btn-light" style="color:blue;">Have a referral code?</button>                          
                            </div>
                            <div class="form-group " id="form-referral" style="display:none;">
                                <div class="">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text ">OJAAK - </div>
                                        </div>
                                        <input id="referral"  type="text" class="form-control" name="referral_register" value="@if(!empty($ref)) {{$ref}} @endif" Placeholder=""autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group " >
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="terms">
                                        <label class="form-check-label" for="terms">By Accepting <a href="javascript:void(0);">terms and condition</a></label>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="registerErrorMsg"  class="m-2 text-danger">
                                <p id="regerrortxt"></p>
                            </div>
                            <div class="form-group row mb-0">
                                <button type="button" id="registerbutton"class="btn btn-block btn-primary">
                                        {{ __('Register') }}
                                </button>
                            </div>

                        </form>
                        <form class="py-4" id="Hotpform" method="POST" action="" style="display: none;">
                             @csrf
                            <div class="form-group">
                                <input id="mob" type="text" class="form-control" name="mob" value="{{ old('mob') }}" required autocomplete="off" disabled="">
                            </div>
                            <div class="form-group">
                                <input id="otpdata" type="password" class="form-control " placeholder="Enter OTP" name="otpdata" required autocomplete="off">
                            </div>
                            <div id="otpErrorMsg"  class="m-2 text-danger">
                                <p id="otperrortxt"></p>
                            </div>
                            <div class="form-group row mb-0">
                                <button type="button" id="otpbutton"class="btn btn-block btn-primary">
                                        {{ __('verify') }}
                                </button>
                            </div>
                        </form>
                        <center id="soname">
                                <a href="{{ url('/auth/redirect/facebook') }}" class="m-2"><img src="{{asset('public/img/facebook.png')}}" width="32px" height="32px"></a>
                                <a href="{{ url('/auth/redirect/google') }}" class="m-2"><img src="{{asset('public/img/google.png')}}" width="30px" height="30px"></a>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="model-mobile" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header">
                <h5 class="modal-title">{{ config('app.name') }} Mobile Verification</h5>
                
              </div>

              <!-- Modal body -->
              <div class="modal-body">
                <form action="#">
                    <div class="form-group">
                        <input type="text" id="mobile"class="form-control" pattern="[0-9]{10}" title="Enter 10 digit"placeholder="MOBILE NO">
                    </div>
                    <div class="form-group" id="otp-form">
                       <input type="text" id="otp" class="form-control" placeholder="ENTER OTP"pattern="[0-9]{6}" title="ENTER 6 DIGIT OTP">
                    </div>
                    <button id="sendotp" type="button" class="btn btn-primary">Send OTP</button>
                    <button id="mobilesubmit" type="submit" class="btn btn-primary">Submit</button>
                </form>
                <div class="message_box" style="margin:10px 0px;">
              </div>
            </div>
        </div>
    </div>
  </div>
    <div class="modal" id="model-email"  data-backdrop="static" data-keyboard="false" >
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">{{ config('app.name') }}</h4>
               </div>

              <!-- Modal body -->
              <div class="modal-body">
                <form action="#">
                    <div class="form-group">
                         <input type="email" id="email"class="form-control"  required placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                         <input type="email" id="cnf-email" class="form-control"  required placeholder="Confirm Email">
                    </div>
                    <button type="submit"id="emailsubmit" class="btn btn-primary">Submit</button>
                </form>
                <div class="message_box" style="margin:10px 0px;">
              </div>
            </div>
        </div>
    </div>
  </div>
<div class="modal" id="verifyemailmodal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Please verify your email address</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A verification link has been sent to your email address.') }}
                        </div>
            @endif
            <p style="text-align: left;">{{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, </p>
            <div class="py-3">        
            <a href="{{ route('verification.resend') }}" class=" btn btn-primary">{{ __('click here to request another') }}</a>
            </div>
          </div>
          
        </div>
      </div>
    </div>
    @include('includeContentPage.category_modal')
    @include('partials.footer1')

    @yield('scripts')
    <script src="{{ asset('public/js/sweetalert.min.js')}}"></script>
    <script src="{{ asset('public/js/Bold-BS4-Animated-Back-To-Top.js')}}"></script><script> 
    var APP_URL = "{{(url('/'))}}"; 
    </script>
    <script type="text/javascript">
        //register script
    $(document).ready(function(){
        function regerrormsg(msg){
            $('#regerrortxt').empty();
            $('#regerrortxt').text(msg);
            $('#regerrortxt').fadeIn('slow', function() {
            setTimeout("$('#regerrortxt').fadeOut('slow');", 3000);});
        }
        $(document).on('click', '#registerbutton', function(){
            var remail=$('#remail').val();
            var terms=0;
            var _token = "{{ csrf_token() }}";
            var rpassword = $("#rpassword").val();
            var rconfirmPassword = $("#rpassword-confirm").val();
            var useravail=0;
            var passwordmatch=0;
            var ifemail=0;
            if($('#terms').prop("checked") == true){
                   terms=1; 
            }
            if(remail == '' || remail == null){
                regerrormsg("Enter Email Id or Phone number!");
                 exit(); 
            }
            if (rpassword != rconfirmPassword){
                regerrormsg("Passwords do not match!");
                 exit(); 
            }else{
                if(rpassword.length >= 8){
                    passwordmatch=1;
                }else{
                     passwordmatch=0;
                     regerrormsg("Password is minimum 8 digit");
                    exit(); 
                }
                
            }
            $.ajax({
                    type:"post",
                    url:"<?php echo url('/useravailable'); ?>",
                    data:"email="+remail+"&_token="+_token,
                    success: function(data){
                        if(data == 0){
                            regerrormsg("Please Enter Valid Email or Mobile Number");

                            $('#remail').addClass('text-danger');
                            $('#remail').focus();
                             exit(); 
                        }else if(data == 1){
                            if(passwordmatch == 1){
                                if(terms==1){
                                    $("#soname").hide();
                                    $("#titlelh").remove();
                                    $("#Hloginform").hide();
                                    $("#Hregisterform").hide();
                                    $('#Hotpform').show();
                                    $('#mob').val(remail);
                                    $(document).on('click', '#otpbutton', function(){
                                        if(document.getElementById('otpdata').value !=''){
                                            var form_data = new FormData();
                                            form_data.append("email", document.getElementById('remail').value);
                                            form_data.append("password", document.getElementById('rpassword').value);
                                            form_data.append("_token", _token);
                                            form_data.append("referral_register", document.getElementById('referral').value);
                                            form_data.append("otp", document.getElementById('otpdata').value);
                                              $.ajax({
                                                    url:"<?php echo url('mobileregister'); ?>",
                                                    method:"POST",
                                                    data: form_data,
                                                    contentType: false,
                                                    cache: false,
                                                    processData: false,
                                                    beforeSend:function(){
                                                        $('#login').modal('hide');
                                                        $('.ajax-loader').css("visibility", "visible");
                                                    },
                                                    success:function(data)
                                                    {   if(data==1){
                                                            location.reload();
                                                        }
                                                    }
                                            });  
                                          }else{
                                            console.log('d');
                                          }
                                    });
                                }else{
                                    regerrormsg("Check Terms and Condition");
                                     exit(); 
                                }    
                                
                            }else{
                                regerrormsg("Password Miss Matches");
                                 exit(); 
                            }

                        }else{
                            if(passwordmatch == 1){
                                if(terms==1){
                                    var form_data = new FormData();
                                    form_data.append("email", document.getElementById('remail').value);
                                    form_data.append("password", document.getElementById('rpassword').value);
                                    form_data.append("_token", _token);
                                    form_data.append("referral_register", document.getElementById('referral').value);
                                    $.ajax({
                                            url:"<?php echo url('emailregister'); ?>",
                                            method:"POST",
                                            data: form_data,
                                            contentType: false,
                                            cache: false,
                                            processData: false,
                                            beforeSend:function(){
                                                $('#login').modal('hide');
                                                $('.ajax-loader').css("visibility", "visible");
                                            },
                                            success:function(data)
                                            {   if(data==1){
                                                    location.reload();
                                                }else{
                                                    $('#otperrortxt').empty();
                                                    $('#otperrortxt').text('otp Invaild');
                                                    $('#otperrortxt').fadeIn('slow', function() {
                                                    setTimeout("$('#otperrortxt').fadeOut('slow');", 3000);});  
                                                }

                                            }
                                    });
                                }else{
                                 regerrormsg("Check Terms and Condition");
                                  exit(); 
                                }
                            }else{
                                 regerrormsg("Password Miss Matches");
                                  exit(); 
                            }
                        }
                    }
            });
            

            /*if(passwordmatch==1 && useravail == 1 && ifemail==1){
                var form_data = new FormData();
                form_data.append("email", document.getElementById('remail').value);
                form_data.append("password", document.getElementById('rpassword').value);
                form_data.append("_token", _token);
                form_data.append("referral_register", document.getElementById('referral').value);
                $.ajax({
                        url:"<?php echo url('emailregister'); ?>",
                        method:"POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                            console.log(data);
                        }
                });
            }*/


        });

    });

    </script>
    <script type="text/javascript">
        function userwallets($id){
                var user_id =$id;
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    type:"post",
                    url:"<?php echo url('/walletpoints'); ?>",
                    data:"user_id="+user_id+"&_token="+_token,
                    success: function(data){
                        $("#walletpoint").html(data);
                    }
                });
            }
    </script>
    <script type="text/javascript">
        $(window).on('load', function () {
        var _token = "{{ csrf_token() }}";
         $( "#parent_category" ).append( $( "<option  value='' hidden>Select Categories</option>" ) );
         $.ajax({
                    type:"post",
                    url:"<?php echo url('/adspost/categories/'); ?>",
                    data:"_token="+_token,
                    success: function(data){
                        var categories=JSON.parse(data);
                        $.each( categories, function( key, value ) {
                            $( "#parent_category" ).append( $( "<option  value="+ key +">"+ value +"</option>" ) );
                        });
                        
                    }
                });
        }); 
        $('#parent_category').on('change', function() {
            var _token = "{{ csrf_token() }}";
            $( "#sub_category" ).html('');
            var parent=this.value;
            $.ajax({
                    type:"post",
                    url:"<?php echo url('/adspost/subcategories/'); ?>",
                    data:"id="+parent+"&_token="+_token,
                    success: function(data){
                        var categories=JSON.parse(data);
                        $.each( categories, function( key, value ) {
                            $( "#sub_category" ).append( $( "<option  value="+ key +">"+ value +"</option>" ) );
                        });
                        
                    }
                });
        });
    </script>
    @if (Auth::check()) 
    <script type="text/javascript">
    $(document).ready(function(){
        var emailavil="<?php echo  Auth::user()->email;?>"
        var emailverifys = "<?php echo  Auth::user()->email_verified_at;?>";
        if(emailverifys.length === 0 && emailavil.length != 0){
            setTimeout(function(){ 
                $("#verifyemailmodal").modal('show');
            }, 2000);
        }
    });
    </script>
    <script>
       $(document).ready(function(){
            var id = "<?php echo  Auth::user()->id;?>"
            var phone_no = "<?php echo  Auth::user()->phone_no;?>"
            var email = "<?php echo  Auth::user()->email;?>"
            var emailverify = "<?php echo  Auth::user()->email_verified_at;?>"
            if(email==""){
                
                $("#model-email").modal('show');
            }
            if(emailverify){
                if(!phone_no){
                    $("#model-mobile").modal('show');
                    $("#otp-form").hide();
                    $("#mobilesubmit").hide();
                }
            }
            
            $("#mobilesubmit").click(function(e){
                e.preventDefault();
                var mobile = $('#mobile').val();
                var _token = "{{ csrf_token() }}";
                var otp = $('#otp').val();
                if(otp==123456){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('/mobile'); ?>",
                        data: "id="+id+"&mobile="+mobile+"&_token="+_token,
                        success: function(data){
                            
                            if(data.success) {
                                setInterval(function(){ 
                                    $("#model-mobile").modal('hide');
                                    location.reload(true);
                                }, 1000);
                            }else{
                                alert('Invaild  Data');
                                location.reload(true);
                            }
                            
                        }
                   });
                     
                }else{
                    alert("Invalid otp");
                }
                
            });
            
            $("#emailsubmit").click(function(e){
                e.preventDefault();
                var email = $('#email').val();
                var _token = "{{ csrf_token() }}";
                var cnfemail = $('#cnf-email').val();
                if(email==cnfemail){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('/emailupdate'); ?>",
                        data: "id="+id+"&email="+email+"&_token="+_token,
                        success: function(data){
                            if(data.success) {
                                setInterval(function(){ 
                                    $("#model-email").modal('hide');
                                    location.reload(true);
                                }, 1000);
                            }else{
                                alert('already exists');
                                location.reload(true);
                            }
                        }
                    });
                }else{
                    alert("Mail Not Same");
                }
                
            });

            $("#sendotp").click(function(e){
                e.preventDefault();
                var mobile = $('#mobile').val();
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    type: "post",
                    url: "<?php echo url('/mobile/check'); ?>",
                    data: "mobile="+mobile+"&_token="+_token,
                    success: function(data){
                        if(data.success) {
                            setInterval(function(){ 
                                $("#otp-form").show();
                                $("#mobilesubmit").show();
                                $("#sendotp").hide();
                            }, 1000);
                        }else if(data.error){
                            alert('Mobile Number already exists');
                            location.reload(true);
                        }else if(data.invalid){
                            alert('Mobile Number invalid Format');
                            location.reload(true);
                        }else{
                            alert('Mobile Number is Empty');
                            location.reload(true);
                        }
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        userwallets("{{auth()->user()->id}}");
    </script>
    @endif


    <script>
        $(document).ready(function(){
            $("#regclk").click(function(){
                $("#Hregisterform").show();
                $("#Hloginform").hide();
                $('#Hotpform').hide();
                $("#logclk").removeClass('show');
                $("#regclk").addClass('show');
            });
            $("#logclk").click(function(){
                $("#Hloginform").show();
                $("#Hregisterform").hide();
                $('#Hotpform').hide();
                $("#regclk").removeClass('show');
                $("#logclk").addClass('show');
            });
           $("#btn-referral").click(function(e){
                $("#form-referral").show();
                $("#btn-referral-form").hide();
                e.preventDefault();
            });
            if (window.location.href.indexOf("referral") > -1) {
                $("#form-referral").show();
                $("#btn-referral-form").hide();
            }
        });
    </script>
    <script>
        $(document).ready(function(){
            $("#loginsubmit").click(function(e){
               var _token = "{{ csrf_token() }}";
               var email = $('#lemail').val();
               var password = $('#lpassword').val();
               if(email && password){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('/login'); ?>",
                        data: "email="+email+"&password="+password+"&_token="+_token,
                        success: function(data){
                            //console.log(data.error);
                            //alert('1');
                            if(data.error){
                                $('#logerrortxt').empty();
                                $('#logerrortxt').text(data.error);
                                $('#logerrortxt').fadeIn('slow', function() {
                                setTimeout("$('#logerrortxt').fadeOut('slow');", 3000);});
                            }else{
                                if(data.role_id==2){
                                    location.reload(true);
                                    $("#loginErrorMsg" ).empty();
                                    $("#login").modal('hide');
                                    $(".ajaxEffectLogin").hide();
                                    $(".ajax-effect").prepend('<li class="nav-item dropdown walletajax"><a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Wallet <i class="fa fa-rupee"></i><span class="caret" id="walletpoint"></span></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"><a class="dropdown-item" href="{{route('usertransaction')}}">Passbook</a> </div></li><li class="nav-item dropdown"><a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>'+data.name+'<span class="caret"></span></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"><a class="dropdown-item" href="{{route("profile")}}">{{ __("Profile") }}</a><a class="dropdown-item" href="{{route("setting")}}">{{ __("Setting") }}</a><a class="dropdown-item" href="{{route("ads.user.index")}}">{{ __("Ads") }}</a><a class="dropdown-item" href="{{route("ajaxlogout")}}" >Logout</a> </div></li>');
                                        userwallets(data.id);
                                    
                                }else{
                                    window.location.href ="<?php echo url('/admin/dashboard'); ?>";
                                }
                                
                            }
                        },
                        
                    });

               }else{
                    $('#logerrortxt').empty();
                    $('#logerrortxt').text('Please enter your email address and Password');
                    $('#logerrortxt').fadeIn('slow', function() {
                    setTimeout("$('#logerrortxt').fadeOut('slow');", 3000);});
               }
                
            });
        });
        function logoutForm(){
            document.getElementById("logout-forms").submit();
        }
    </script>
    <script type="text/javascript">
     function fav(val1,val2){
                if('{{Auth::id()}}'){
                var favid="#favourite-"+val2;
                $.ajax({
                    type:"get",
                    url:"<?php echo url('/favads')?>",
                    data:"uuid="+val1+"&ads_id="+val2,
                    success:function(data){
                        if(data == 1){
                            $(favid).removeClass("fa-heart-o");
                            $(favid).addClass("fa-heart");
                         }else{
                            $(favid).removeClass("fa-heart");   
                            $(favid).addClass("fa-heart-o");
                        }
                    }
                });
                }else{
                    $("#login").modal('show');
                }   

        }
    </script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/admin_script.js') }}"></script>
    <script type="text/javascript" src="{{ asset('public/js/toastr.min.js') }}"></script>
    <!-- @toastr_js -->
    @toastr_render
</body>
</html>