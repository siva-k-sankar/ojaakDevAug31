<?php 
   $lang = Session::get('language');
?>
@if(Request::path() == '/')
  <header class="header transp sticky"> <!-- available class for header: .sticky .center-content .transp -->
    <nav class="navbar navbar-inverse">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed menubar-toggle">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('/') }}"><img src="{{@$logo}}" alt="logo"></a>
          <!-- img/logo.png -->
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          @if(Request::segment(1) != 'help')
            <ul class="nav navbar-nav navbar-right">
              <li><a href="{{ url('property/create') }}">{{trans('messages.header.list_space')}}</a></li>
            </ul>
          @endif
          @if(!Auth::check())
            <ul class="nav navbar-nav navbar-right">
              <li><a href="{{ url('signup') }}">{{trans('messages.sign_up.sign_up')}}</a></li>
              <li><a href="{{ url('login') }}">{{trans('messages.header.login')}}</a></li>
            </ul>
          @endif
          @if(Auth::check())
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::guard('users')->user()->first_name }} <span class="caret"></span></a>
                <ul class="dropdown-menu">
                <li><a href="{{ url('dashboard') }}">{{trans('messages.header.dashboard')}}</a></li>
                <li><a href="{{ url('inbox') }}">{{trans('messages.header.inbox')}}</a></li>
                <li><a href="{{ url('properties') }}">{{trans('messages.header.your_listing')}}</a></li>
                <li><a href="{{ url('my_bookings') }}">{{trans('messages.header.property_booking')}}</a></li>
                <li><a href="{{ url('trips/active') }}">{{trans('messages.header.your_trip')}}</a></li>
                <li><a href="{{ url('users/profile') }}">{{trans('messages.header.edit_profile')}}</a></li>
                <li><a href="{{ url('users/account_preferences') }}">{{trans('messages.header.account')}}</a></li>
                <li><a href="{{ url('logout') }}">{{trans('messages.header.logout')}}</a></li>
                </ul>
              </li>
            </ul>
          @endif
        </div>
      </div><!-- /.container-fluid -->
    </nav>
  </header>
  @if(Session::has('message'))
  <div class="alert {{ Session::get('alert-class') }} alert-dismissable fade in top-message-text">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ Session::get('message') }}
  </div>
  @endif 
@else
  <div class="header2">   
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed menubar-toggle">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url('/') }}"><img src="{{@$logo}}" alt="logo"></a>
      </div>
      
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <form action="{{ url('/') }}/s" id="head-form" class="navbar-form navbar-left search-form">
        <!-- <form action="{{ url('search') }}" id="head-form" class="navbar-form navbar-left search-form"> -->
          <div class="form-group sm-dropdown">
            <input type="text" name="location" id="header-search-form" class="form-control" placeholder="{{ trans('messages.header.where_are_you_going') }}" style="width:200px;margin-bottom:1px;">
            <div id="search-drop-down" class="sm-dropdown-content">
                <div class="col-md-12" style="padding:0px 5px;">
                    <div class="col-md-4" style="padding:0px 5px;">
                        <label class="nav-label">{{trans('messages.header.check_in')}}</label>
                        <input type="text" name="checkin" id="header-search-checkin" class="nav-form-control" autocomplete="off" readonly="readonly">
                    </div>
                    <div class="col-md-4" style="padding:0px 5px;">
                        <label class="nav-label">{{trans('messages.header.check_out')}}</label>
                        <input type="text" name="checkout" id="header-search-checkout" class="nav-form-control" autocomplete="off" readonly="readonly">
                    </div>
                    <div class="col-md-4" style="padding:0px 5px;">
                        <label class="nav-label">{{trans('messages.home.guest')}}</label>
                        <select class="nav-form-control" id="header-search-guests" name="guests">
                            @for($i=1;$i<=16;$i++)
                              <option value="{{ $i }}"> {{ ($i == '16') ? $i.'+ ' : $i }} </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-12">
                      <button class="btn ex-btn navbar-btn topbar-btn" style="width:100%;" type="submit">
                        <i class="fa fa-search" aria-hidden="true"></i>
                       {{trans('messages.header.find_place')}} 
                      </button>
                    </div>
                </div>
            </div>
          </div>
        </form>

      <!--  <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Browse<span class="caret"></span></a>
            <ul class="dropdown-menu">
            <li><a href="{{url('wishlists/popular')}}">Popular</a></li>
            </ul>
          </li>
        </ul>-->
        @if(\Auth::check())
        <ul class="nav navbar-nav navbar-right">
          <li><a href="{{url('dashboard')}}"><div class="user-div"><img src="{{ (Auth::user()) && isset(Auth::user()->profile_src) ? Auth::user()->profile_src : '' }}" alt="" title="" style="width:100%;"></div></a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::guard('users')->user()->first_name }} <span class="caret"></span></a>
            <ul class="dropdown-menu">
            <li><a href="{{ url('dashboard') }}">{{trans('messages.header.dashboard')}}</a></li>
            <li><a href="{{ url('inbox') }}">{{trans('messages.header.inbox')}}</a></li>
            <li><a href="{{ url('properties') }}">{{trans('messages.header.your_listing')}}</a></li>
            <li><a href="{{ url('my_bookings') }}">{{trans('messages.header.property_booking')}}</a></li>
            <li><a href="{{ url('trips/active') }}">{{trans('messages.header.your_trip')}}</a></li>
            <li><a href="{{ url('users/profile') }}">{{trans('messages.header.edit_profile')}}</a></li>
            <li><a href="{{ url('users/account_preferences') }}">{{trans('messages.header.account')}}</a></li>
            <li><a href="{{ url('logout') }}">{{trans('messages.header.logout')}}</a></li>
            </ul>
          </li>
          <li><a href="{{ url('property/create') }}" class="btn ex-btn navbar-btn topbar-btn">{{trans('messages.header.list_space')}}</a></li>
        </ul>
        @else
        <ul class="nav navbar-nav navbar-right">
          <li><a href="{{ url('signup') }}">{{trans('messages.sign_up.sign_up')}}</a></li>
          <li><a href="{{ url('login') }}">{{trans('messages.header.login')}}</a></li>
          <li><a href="{{ url('property/create') }}" class="btn ex-btn navbar-btn topbar-btn">{{trans('messages.header.list_space')}}</a></li>
        </ul>
        @endif

      </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
  </div>
  @if(Session::has('message'))
  <div class="alert {{ Session::get('alert-class') }} alert-dismissable fade in top-message-text">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ Session::get('message') }}
  </div>
  @endif  
@endif
