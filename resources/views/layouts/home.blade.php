<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'OJAAK') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/frontdesign/assets/img/favicon-32x32.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('public/frontdesign/assets/style1.css') }}">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,500,600,700,900|Poppins:300,400,500,700,800,900|Roboto:400,500,700,900&display=swap" rel="stylesheet">
    <link type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('public/frontdesign/assets/amsify.suggestags.css') }}">
    <link href="{{ asset('public/css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/Bold-BS4-Animated-Back-To-Top.css') }}" rel="stylesheet">
    
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/jquery-3.4.0.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/jquery.amsify.suggestags.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/jquery-ui.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/category-filter.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.13.1/basic/ckeditor.js"></script>

    <script type="text/javascript">
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        
        $(function () {
          $('[data-toggle="popover"]').popover()
          $('[data-toggle="tooltip"]').tooltip()
        })
        
    </script>
    <style type="text/css">
    body {
        position: relative;
    }
    .ajax-loader {
        visibility: hidden;
        background-color: rgba(0, 0, 0, 0.74) !important;
        position: fixed;
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
        cursor: pointer;
    }
    .list-group .show{
        color:#ff0000;
    }
    </style>
    @yield('styles')
</head>
<body>
<div class="ajax-loader" style="visibility: hidden;">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: transparent; display: block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" r="36.3163" fill="none" stroke="#ffffff" stroke-width="7">
              <animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline" begin="-0.5s"></animate>
              <animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline" begin="-0.5s"></animate>
            </circle>
            <circle cx="50" cy="50" r="18.1595" fill="none" stroke="#09d26d" stroke-width="7">
              <animate attributeName="r" repeatCount="indefinite" dur="1s" values="0;40" keyTimes="0;1" keySplines="0 0.2 0.8 1" calcMode="spline"></animate>
              <animate attributeName="opacity" repeatCount="indefinite" dur="1s" values="1;0" keyTimes="0;1" keySplines="0.2 0 0.8 1" calcMode="spline"></animate>
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
                        <button type="button" id="logregclosebtn" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <ul id="titlelh" class="list-group list-group-horizontal-sm d-flex justify-content-center">
                            <li id="logclk" class="list-form m-1 show">Login</li>
                            <li  class="list-form m-1 show">|</li>
                            <li id="regclk" class="list-form m-1 ">Register</li>
                        </ul>

                        <form class="py-4" id="Hloginform" method="POST" action="{{ route('login') }}">
                            <div class="form-group">
                                <input id="lemail" type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email or Phone" autofocus="autofocus"value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                            <div class="form-group">
                                <div class="input-group ">
                                  <input id="lpassword" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password" aria-describedby="basic-lpass1">
                                  <div class="input-group-append">
                                    <span class="input-group-text toggle-password" style="cursor: pointer;" toggle="#lpassword"  id="basic-lpass1"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                  </div>
                                </div>
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
                                <input id="remail" type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Mobile or Email" autofocus="autofocus" name="email" value="{{ old('email') }}" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <input id="rphone" type="number" class="form-control @error('phone') is-invalid @enderror" placeholder="Enter Mobile " autofocus="autofocus" name="phone" value="" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <!-- <input id="rpassword" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter Password" name="password" required autocomplete="off"> -->
                                <div class="input-group ">
                                  <input id="rpassword" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter Password" name="password" required autocomplete="off" aria-describedby="basic-lpass2">
                                  <div class="input-group-append">
                                    <span class="input-group-text toggle-password" style="cursor: pointer;" toggle="#rpassword"  id="basic-lpass2"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <input id="rpassword-confirm" type="password"  class="form-control " name="password_confirmation" required placeholder="Enter Confirm Password"autocomplete="off"> -->
                                <div class="input-group ">
                                  <input  id="rpassword-confirm" type="password"  class="form-control " name="password_confirmation" required placeholder="Enter Confirm Password"autocomplete="off" aria-describedby="basic-lpass3">
                                  <div class="input-group-append">
                                    <span class="input-group-text toggle-password" style="cursor: pointer;" toggle="#rpassword-confirm"  id="basic-lpass3"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                  </div>
                                </div>
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
                            <div id="OtpErrorMsg"  class="m-2 text-danger">
                                <p id="otperrortxt"></p>
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
        <div class="modal-dialog ">
            <div class="modal-content">
                <button type="button" id=""class="close model_close_logout" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">x</span>
                </button>
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
                    <button id="sendotp" type="button" class="btn sent_otp_wrap btn-primary">Send OTP</button>
                    <button id="mobilesubmit" type="submit" class="btn btn-primary">Submit</button>
                    <button id="resendotp" type="button" class="btn sent_otp_wrap btn-primary">Resend OTP
                    </button>
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
                <button type="button" id=""class="close model_close_logout" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span>
                </button>
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
            <button type="button" class="close model_close_logout" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">x</span>
            </button>
          <div class="modal-header">
            <h5 class="modal-title">Please verify your email address</h5>
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
    <script type="text/javascript" id="cookieinfo"
    src="{{ asset('public/frontdesign/assets/cookie.js') }}"
    data-bg="#09d26d"
    data-fg="#FFFFFF"
    data-link="#ffffff"
    data-cookie="OjaakInfoScript"
    data-divlink="#09d26d"
    data-divlinkbg="#FFFFFF"
    data-text-align="left"
    data-moreinfo="{{url('/termscondition')}}"
    data-close-text="Got it!">
    </script>
    <script src="{{ asset('public/js/sweetalert.min.js')}}"></script>
    <script src="{{ asset('public/js/Bold-BS4-Animated-Back-To-Top.js')}}"></script>
    
    <script type="text/javascript">
         
        $(document).ready(function(){
            $("#ads_posting_login").on('click', function () {        
                $('#login').modal('show');
            });
            $("#wallet_login").on('click', function () {        
                $('#login').modal('show');
            });
            $(document).on('click','.favitem',function(e){
                fav($(this).attr("adsuuid-attr"),$(this).attr("adsid-attr"));

            });

            $(document).on('click','#logout-ojaak-btn',function(e){
                
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

            });
        });
    </script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.favitem').on('click',function(e){
            //alert($(this).attr("adsuuid-attr"))
            //alert($(this).attr("adsid-attr"))
            fav($(this).attr("adsuuid-attr"),$(this).attr("adsid-attr"));

        });
     /*function fav(val1,val2){
         //console.log('s');
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
                    jQuery.noConflict();
                    $("#login").modal('show');
                }   

        }*/
    });
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
        function otperrormsg(msg){
            $('#otperrortxt').empty();
            $('#otperrortxt').text(msg);
            $('#otperrortxt').fadeIn('slow', function() {
            setTimeout("$('#otperrortxt').fadeOut('slow');", 3000);});
        }
        

        $(document).on('click', '#registerbutton', function(){
            var remail=$('#remail').val();
            var rphone= $('#rphone').val();
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
                regerrormsg("Enter Email Id!");
                 exit(); 
            }
            if(rphone == '' || rphone == null){
                regerrormsg("Enter Phone number!");
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
                    data:"email="+remail+"&phone="+rphone+"&_token="+_token,
                    success: function(data){
                        if(data == 0){
                            regerrormsg("Please Enter Valid Email or Mobile Number");

                            //$('#remail').addClass('text-danger');
                            $('#remail').focus();
                             exit(); 
                        }else if(data == 1){
                            if(passwordmatch == 1){
                                if(terms==1){

                                    var form_data = new FormData();
                                    form_data.append("email", document.getElementById('remail').value);
                                    form_data.append("phone", document.getElementById('rphone').value);
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
                                            {   
                                                if(data==1){
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
    <!-- <script type="text/javascript">
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
    </script> -->
    <script type="text/javascript">
       $("#ads_posting").click(function() {
        var _token = "{{ csrf_token() }}";
         $( "#parent_category" ).append( $( "<option  value='' hidden>Select Categories</option>" ) );
         $.ajax({
                    type:"post",
                    url:"<?php echo url('/adspost/categories/'); ?>",
                    data:"_token="+_token,
                    success: function(data){
                        var categories=JSON.parse(data);
                        $( "#parent_category" ).html('');
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
                    $("#resendotp").hide();
                    $("#otp-form").hide();
                    $("#mobilesubmit").hide();
                }
            }
            
            $("#mobilesubmit").click(function(e){
                e.preventDefault();
                var mobile = $('#mobile').val();
                var _token = "{{ csrf_token() }}";
                var otp = $('#otp').val();
                var votp="<?php echo  getOTP()?>";
                if(otp==votp){
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
                               swal({title: "Already Taken!",icon: "warning",});
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

            $(".sent_otp_wrap ").click(function(e){
                e.preventDefault();
                var mobile = $('#mobile').val();
                var _token = "{{ csrf_token() }}";
                if(mobile.length == 10){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('/mobile/check'); ?>",
                        data: "mobile="+mobile+"&_token="+_token,
                        success: function(data){
                            if(data.success) {
                                setInterval(function(){ 
                                    $("#otp-form").show();
                                    $("#mobilesubmit").show();
                                    $("#resendotp").show();
                                    $("#sendotp").hide();
                                }, 1000);
                            }else if(data.error){
                                swal({title: "Mobile Number already exists!",icon: "warning",});
                                //alert('Mobile Number already exists');
                                //location.reload(true);
                                exit();
                            }else if(data.invalid){
                                swal({title: "Mobile Number invalid Format!",icon: "warning",});
                                exit();
                                //alert('Mobile Number invalid Format');
                                //location.reload(true);
                            }else{
                                swal({title: "Mobile Number is Empty!",icon: "warning",});
                                exit();
                                //alert('Mobile Number is Empty');
                                //location.reload(true);
                            }
                        }
                    });
                }else{
                    swal({title: "Please enter valid mobile number!",icon: "warning",});
                    exit();
                }
            });
        });
    </script>
    <!-- <script type="text/javascript">
        userwallets("{{auth()->user()->id}}");
    </script> -->
    @endif


    @if (Auth::check())
    <script>

        
    $(document).ready(function(){
        var emailavil="<?php echo  Auth::user()->email;?>"
        var emailverifys = "<?php echo  Auth::user()->email_verified_at;?>";
        var phone_no_check="<?php echo  Auth::user()->phone_no;?>"
        var phone_no_verifys = "<?php echo  Auth::user()->phone_verified_at;?>";
        //console.log("phone_no_verifys",phone_no_verifys)
        if(emailverifys.length === 0 && emailavil.length != 0){
            setTimeout(function(){ 
                $("#verifyemailmodal").modal('show');
            }, 2000);
        }else if(phone_no_verifys == ''){
            setTimeout(function(){
                $("#model-mobile").modal('show');
                $("#resendotp").hide();
                //alert(phone_no_verifys);
                $('#mobile').val(phone_no_check);

                $(document).on('click', '#otpbutton', function(){
                    var otplength=$('#otpdata').val();
                    if(document.getElementById('otpdata').value !='' && otplength.length == 6){
                        var form_data = new FormData();
                        form_data.append("email", document.getElementById('remail').value);
                        form_data.append("phone", document.getElementById('rphone').value);
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
                        otperrormsg("Invaild OTP");
                        exit(); 
                      }
                });

            }, 2000);
        }
    });
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
            $("#logregclosebtn").click(function(e){
                $('#lemail').val("");
                $('#lpassword').val("");
                $('#remail').val("");
                $('#rpassword').val("");
                $('#rpassword-confirm').val("");
                $('#referral').val("");
                $("#titlelh").show();     
            });
            $("#loginsubmit").click(function(e){
               var _token = "{{ csrf_token() }}";
               var email = $('#lemail').val();
               var password = $('#lpassword').val();
               if(email && password){
                    $.ajax({
                        type: "post",
                        url: "<?php echo url('/login'); ?>",
                        data: "email="+email+"&password="+password+"&_token="+_token,
                        beforeSend:function(){
                            $(".load-more").text("Loading...");
                            $('.ajax-loader').css("visibility", "visible");
                        },
                        success: function(data){
                            //console.log(data.error);
                            //alert('1');
                            $('.ajax-loader').css("visibility", "hidden");
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
                                    //$(".ajax-effect").prepend('<li class="nav-item dropdown walletajax"><a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Wallet <i class="fa fa-rupee"></i><span class="caret" id="walletpoint"></span></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"><a class="dropdown-item" href="{{route('usertransaction')}}">Passbook</a> </div></li><li class="nav-item dropdown"><a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>'+data.name+'<span class="caret"></span></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"><a class="dropdown-item" href="{{route("profile")}}">{{ __("Profile") }}</a><a class="dropdown-item" href="{{route("setting")}}">{{ __("Setting") }}</a><a class="dropdown-item" href="{{route("ads.user.index")}}">{{ __("Ads") }}</a><a class="dropdown-item" href="{{route("ajaxlogout")}}" >Logout</a> </div></li>');
                                        //userwallets(data.id);
                                    
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


        $(document).ready(function(){

            setInterval(function(){
                var _token = "{{ csrf_token() }}";
                $.ajax({
                    type:"post",
                    url:"<?php echo url('/shownotificationicon')?>",
                    data:"_token="+_token,
                    success:function(suc_data){
                        var respp = suc_data.split("@@@");
                        console.log(respp[0]);
                        console.log(respp[1]);
                        if(respp[0] >= 1){
                            $(".notificon").empty();
                            $(".notificon").append('<p class="shownotificationiconforads"></p>');
                        }else{
                            $(".notificon").empty();
                        }
                        if(respp[1] >= 1){
                            $(".chatnotific").empty();
                            $(".chatnotific").append('<p class="shownotificationicon"></p>');
                        }else{
                            $(".chatnotific").empty();
                        }
                    }
                });

             }, 20000);
        });

    </script>
    <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/admin_script.js') }}"></script>

    @if (Auth::check())
        <script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/userstatus.js') }}"></script>
    @endif
    
    <script type="text/javascript" src="{{ asset('public/js/toastr.min.js') }}"></script>
    <!-- @toastr_js -->
    @toastr_render
</body>
</html>