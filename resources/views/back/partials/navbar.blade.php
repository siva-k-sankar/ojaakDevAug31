<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('admin.dashboard') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b></b>{{ config('app.name', 'OJAAK') }}</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>{{ config('app.name', 'OJAAK') }}</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <!-- <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
          </li> -->
          <!-- Notifications: style can be found in dropdown.less -->

            @php

                $showNotification = 0;
                $notifcationCount = 0;

                $response = DB::table("role_accesses")->where("page_id",'23')->where("allow_all","1")->where("role_id",Auth::user()->role_id)->count();
                if($response>=1){
                    $showNotification = 1;
                    $notifcationCount = DB::table("notification_admin")->where("read_status",'0')->count();
                    $notifcationData = DB::table("notification_admin")->where("read_status",'0')->limit(5)->latest()->get();

                }

            @endphp
            @if($showNotification == 1)
                <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">{{$notifcationCount}}</span>
                </a>

                <ul class="dropdown-menu list-group">
                    @if(!empty($notifcationData))
                        @foreach($notifcationData as $notifi)
                            <a href="{{url('admin/read_notification')}}/{{$notifi->uuid}}"><li class="list-group-item">{{$notifi->message}}</li></a>
                        @endforeach
                    @else
                        <li class="list-group-item">No New Notifcations</li>
                    @endif
                </ul>

                </li>
            @endif
          <!-- Tasks: style can be found in dropdown.less -->
          <!-- <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
          </li> -->
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{asset('public/uploads/profile/small/'.auth()->user()->photo)}}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ strtok(Auth::user()->name, " ") }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src=" {{asset('public/uploads/profile/small/'.auth()->user()->photo)}}" class="img-circle" alt="User Image">

                <p>
                 {{ strtok(Auth::user()->name, " ") }} - OJAAK
                </p>
              </li>
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ route('admin.profile') }}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a class="btn btn-default btn-flat" href="javascript:void(0);" id="logout-ojaak-btn">{{ __('Logout') }}</a>
                  <!-- <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Sign out</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                    </form> -->
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>
  