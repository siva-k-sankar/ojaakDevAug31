<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('public/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="{{ asset('public/js/sweetalert.min.js')}}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Styles -->
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/css/font-awesome.min.css') }}">
     @toastr_css
    <script type="text/javascript">
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    </script>
     @yield('styles')
</head>
<body>
    <div class='copied'></div>
    <div id="app">
        <!-- @guest
        @else
        @if (Auth()->user()->email !="")
             @if (Auth()->user()->email_verified_at =="")
             <center>
                <div class="alert alert-success" style=""role="alert">
                    {{ __('A New verification link has been sent to your email address.') }}{{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a  class="btn btn-outline-primary"href="{{ route('verification.resend') }}">{{ __('Resend Verification Link') }}</a>    
                </div>
            </center>
            @endif
        @endif
        @endguest -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
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
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-warning" href="{{ route('ads.post') }}">{{ __('Ads Posting') }}</a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Profile Update 
                            @if($badge !="")
                            <img class="float-right" src="{{asset('public/img/verify.png')}}" alt="Profile" width="25px" height="25px">
                            @endif
                        </div>
                        <div class="card-body">
                           <ul class="nav flex-column">
                                <li class="nav-item">
                                  <a class="nav-link {{ Request::is('profile') ? 'btn-primary' : '' }}" href="{{ route('profile') }}">Profile</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link {{ Request::is('info') ? 'btn-primary' : '' }}" href="{{ url('profile/info')}}/{{Auth::user()->uuid}}">info</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link {{ Request::is('profile/mobile') ? 'btn-primary' : '' }}" href="{{ route('profile.mobile') }}">Mobile</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link {{ Request::is('profile/social') ? 'btn-primary' : '' }}" href="{{ route('profile.social') }}">Social</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link {{ Request::is('profile/mail') ? 'btn-primary' : '' }}" href="{{ route('profile.mail') }}">Mail</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link {{ Request::is('profile/workmail') ? 'btn-primary' : '' }}" href="{{ route('profile.workmail') }}">Work Mail</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link {{ Request::is('profile/govtproof') ? 'btn-primary' : '' }}" href="{{ route('profile.govtproof') }}">Govt Proof</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link {{ Request::is('profile/referral') ? 'btn-primary' : '' }}" href="{{ route('profile.referraluser') }}">Referral users</a>
                                </li>
                                <!-- <li class="nav-item " >  
                                    <a class="nav-link" href="javascript:void(0);" onclick="setClipboard('{{url('profile')}}/{{Auth::user()->referral_code}}')" >Share Profile</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="javascript:void(0);" onclick="reportUser()" >Report this user</a>
                                </li> -->
                                <li class="nav-item" style="padding-top:10px;">
                                  <h6 class="nav-link border">Referral Code : OJAAK-<span class=" ">{{Auth::user()->referral_code}}</span></h6>
                                </li>
                            </ul>


                        </div>
                    </div>
                </div>


                @yield('content')
            </div>
            </div>
            
        </main>
    </div>




    <div class="modal" id="reportuser">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">User Report</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body">
                <form action="#">
                    <input type="hidden" id="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="uuuid" value="useruuid">

                    <div class="form-group">
                        <div class="form-check-inline">
                            <label class="customradio">
                                <span class="radiotextsty">Spam</span>
                                <input type="radio" class="reportuser" name="reportuser"  value="spam" >
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check-inline">
                            <label class="customradio"><span class="radiotextsty">Fraud</span>
                                <input type="radio" class="reportuser" name="reportuser"  value="fraud">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check-inline">
                            <label class="customradio"><span class="radiotextsty">Inappropriate Profile Picture</span>
                                <input type="radio" class="reportuser" name="reportuser"  value="inappropriateProfilePic">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check-inline">
                            <label class="customradio"><span class="radiotextsty">This user is threatening me</span>
                                <input type="radio" class="reportuser" name="reportuser"  value="threateningMe">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check-inline">
                            <label class="customradio"><span class="radiotextsty">This user is insulting me</span>
                                <input type="radio" class="reportuser" name="reportuser"  value="insultingMe">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check-inline">
                            <label class="customradio"><span class="radiotextsty">Other</span>
                                <input type="radio" class="reportuser" name="reportuser"  value="other">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    

                    <div class="form-group">
                        <textarea class="form-control commentss" placeholder="Comment" name="commentss"></textarea>
                    </div>
                    <button type="submit"id="reportusersubmit" class="btn btn-primary">Send complaint</button>
                </form>
                <div class="message_box" style="margin:10px 0px;">
              </div>
            </div>
        </div>
    </div>
    </div>
 

@toastr_js
@toastr_render
@yield('scripts')

<script>
    function setClipboard(value) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        $(".copied").text("Copied to clipboard").show().fadeOut(1200);
    }

    function reportUser() {
        $("#reportuser").modal('show');
    }

   $(document).ready(function(){
        $("#reportusersubmit").click(function(e){
            e.preventDefault();
            var reportuser = $('.reportuser').val();
            var commentss = $('.commentss').val();
            var uuuid = $('#uuuid').val();
            var _token = $('#_token').val();
            if ($(".reportuser").is(":checked")) {
                $.ajax({
                    type: "post",
                    url: "<?php echo url('/reportusersubmit'); ?>",
                    data: "reportuser="+reportuser+"&uuuid="+uuuid+"&_token="+_token+"&commentss="+commentss,
                    success: function(data){                        
                        swal({
                            title: "Saved",
                            text: "",
                            icon: "success",
                            buttons: false,
                            dangerMode: false,
                        });
                    }
                });
            }else{
                alert("Please select atleast one reason!.");
            }
            
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
<script type="text/javascript">userwallets("{{auth()->user()->id}}");
</script>
</body>
</html>
