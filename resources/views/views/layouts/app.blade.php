<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
   
    <!-- Styles -->
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/Bold-BS4-Animated-Back-To-Top.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/css/font-awesome.min.css') }}">
    <link href="{{ asset('public/css/toastr.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .ajax-loader {
        visibility: hidden;
        background-color: rgba(255,255,255,0.7);
        position: absolute;
        z-index: +100 !important;
        width: 100%;
        height: 98%;
    }
    .ajax-loader svg {
        position: relative;
        top: 20%;
        left: 0%;
    }
    </style>
    <!-- @toastr_css -->
    @yield('styles')
</head>
<body id="top">
    <div class="ajax-loader" style="visibility: hidden;">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:transparent;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" r="32" stroke-width="8" stroke="#e15b64" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round" transform="rotate(326.163 50 50)">
                <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
            </circle>
            <circle cx="50" cy="50" r="23" stroke-width="8" stroke="#f8b26a" stroke-dasharray="36.12831551628262 36.12831551628262" stroke-dashoffset="36.12831551628262" fill="none" stroke-linecap="round" transform="rotate(-326.163 50 50)">
                <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;-360 50 50"></animateTransform>
            </circle>
        </svg>
    </div>
    <a href="#" id="scrolltop" style="display: none;z-index: 999999;"><span></span></a>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand font-weight-bold" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto ajax-effect">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item m-1 ajaxEffectLogin">
                                <a class="btn form-radius form-radius-input-btn-nav" href="#" data-toggle="modal" data-target="#login">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item m-1">
                                    <a class="btn form-radius form-radius-input-btn-nav" href="{{ route('ads.post') }}">{{ __('Ads Posting') }}</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link btn btn-outline-warning" href="{{ route('ads.post') }}">{{ __('Ads Posting') }}</a>
                                </li>
                            @endif -->
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Wallet <i class="fa fa-rupee"></i><span class="caret" id="walletpoint"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{route('usertransaction')}}">Passbook</a>
                                    
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{route('profile')}}">{{ __('Profile') }}</a>
                                    <a class="dropdown-item" href="{{route('setting')}}">{{ __('Setting') }}</a>
                                    <a class="dropdown-item" href="{{route('ads.user.index')}}">{{ __('Ads') }}</a>
                                    <a class="dropdown-item" href="{{route('plans')}}">{{ __('Plans') }}</a>
                                    <a class="dropdown-item" href="{{route('showpackage')}}">{{ __('Buy Business Packages') }}</a>
                                    <!-- <a class="dropdown-item" href="{{route('profile.changepassword')}}">{{ __('Change Password') }}</a> -->
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item ">
                                    <a class="nav-link" href="{{ route('chat') }}"><i class="fa fa-comments fa-2x" aria-hidden="true"></i></a>
                            </li>
                            <!-- @if (Auth()->user()->photo !="")
                            <li class="nav-item">
                                <img class="nav-link" src="{{asset('public/uploads/profile/small/'.Auth()->user()->photo)}}" style="border-radius: 50%;width:40px;height:40px;" alt="User Image">    
                            </li>
                            @endif -->
                            <li class="nav-item ">
                                    <a class="btn form-radius form-radius-input-btn-nav" href="{{ route('ads.post') }}">{{ __('Ads Posting') }}</a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-0">
            @yield('content')
        </main>
    </div>
            

    <!-- Modal -->
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="logincontent" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm" role="document">
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
                            
                            <div class="form-group row" id="btn-referral-form">
                                <button type="button" id="btn-referral"class="btn btn-block btn-light" style="color:blue;">Have a referral code?</button>                          
                            </div>
                            <div class="form-group row" id="form-referral" style="display:none;">
                                <div class="col-md-12">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text ">OJAAK - </div>
                                        </div>
                                        <input id="referral"  type="text" class="form-control" name="referral_register" value="@if(!empty($ref)) {{$ref}} @endif" Placeholder=""autocomplete="off">
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
 @include('partials.footer')
 
    <script src="{{ asset('public/js/jquery.min.js') }}" ></script>
    <script src="{{ asset('public/js/app.js') }}" ></script>
    <script src="{{ asset('public/js/sweetalert.min.js')}}"></script>
    <script src="{{ asset('public/js/Bold-BS4-Animated-Back-To-Top.js')}}"></script>

    <script> 
    var APP_URL = "{{(url('/'))}}"; 
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    </script>
    @yield('scripts')
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
        var _token = "{{ csrf_token() }}";
        var rpassword = $("#rpassword").val();
        var rconfirmPassword = $("#rpassword-confirm").val();
        var useravail=0;
        var passwordmatch=0;
        var ifemail=0;
        if (rpassword != rconfirmPassword){
            regerrormsg("Passwords do not match! or minimum 8 digit");
        }else{
            if(rpassword.length >= 8){
                passwordmatch=1;
            }else{
                 passwordmatch=0;
                 regerrormsg("password is minimum 8 digit");
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
                    }else if(data == 1){
                        if(passwordmatch == 1){
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
                            
                            
                        }
                    }else{
                        if(passwordmatch == 1){
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
<script type="text/javascript" src="{{ asset('public/js/toastr.min.js') }}"></script>
<!-- @toastr_js -->
@toastr_render
</body>
</html>
