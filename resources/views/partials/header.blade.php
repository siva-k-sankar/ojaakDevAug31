<!-- Menu -->
<?php $responsivesearch='navbar-expand-lg' ?>
 @if(Request::segment(1) =='items' || Request::segment(1) =='item')
    <?php $responsivesearch='navbar-expand-xl search_page_nav_outer_wrap' ?>
 @endif

    <nav class="navbar {{$responsivesearch}} sticky-top ">
        <div class="menu_wrap container-fluid pl-0 pr-0">
            <div class="box-size">
                <a class="navbar-brand" href="{{url('/')}}">
                    <img src="{{ asset('public/frontdesign/assets/img/ojaak_logo.png') }}" alt="ojaak_logo" title="OJAAK">  
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                @if(Request::segment(1) =='items' || Request::segment(1) =='item')
                <div class="ads_listing_search_outer_wrap">
                    <div class="home_search_inner_wrap">
                        <div class="category_drop_wrap">
                            <h3>Categories <i class="fa fa-angle-down" aria-hidden="true"></i></h3>
                            <div class="select_category_listing">
                                <ul>
                                    @if(!empty($categories))
                                        @foreach($categories as $category)
                                        <li>
                                            <div class="cat_icon_img">
                                                <img class ="selectcategory" src="{{url('public/uploads/categories/')}}/{{$category->image}}" data-category_id="{{$category->id}}">
                                                
                                            </div>
                                            <h4>{{$category->name}}</h4>
                                        </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="product_location_search_outer_wrap">
                            <div class="header_search_loction_inner_new_wrap">
                                <div class="home_search_outer_wrap form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" class="home_search_inner_wrap form-control input-field searchbox"  id="searchbox" name="searchbox" autocomplete="off" placeholder="Find yours favourite products">
                                    <div class="home_search_result_wrap search_result_wrap" id="listbox">
                                        
                                    </div>
                                </div>
                                <div class="form-group location_search_wrap">
                                    <span class="fa fa-map-marker form-control-feedback"></span>
                                    <input type="text" class="form-control input-field locationinput" id="locationinput" name="locationinput" placeholder="Chennai" >
                                    <div class="search_result_wrap  location_search_inner_listing_wrap" id="currentlocationList"  style="display: none;">
                                        <ul>
                                            <h3 class="nowrap current_location_addr" location-attr="0";><i class="ojaak_icons_target"></i><span class="">Current Location </span><span class="currentAddress mt-1" style="font-size: 10px !important;"></span></h3>
                                        </ul>
                                    </div>
                                    <div class="search_result_wrap  location_search_inner_listing_wrap" id="locationList">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="common_btn_wrap">
                                <a href="javascript:void(0);" class="headerSearch">Search</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="menu_inner_wrap collapse navbar-collapse" id="navbarNavAltMarkup">
                    <ul class="navbar-nav">
                        @guest
                            
                            <!-- <li class="nav-item">
                                <a class=" nav-link" href="javascript:void(0);" id="wallet_login"><span class="ojaak_icons_wallet">My Wallet</span> <span class="sr-only">(current)</span></a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link active" href="#" data-toggle="modal" data-target="#login"><span class="login_btn_wraps">Login</span></a>
                            </li>
                            @if(Auth::user())
                            <li class="nav-item">
                                <a class=" nav-link login_btn_outer_wrap " id="ads_posting_login" href="javascript:void(0);"> <span class="ojaak_icons_megaphone login_btn_wrap">Post Ad</span></a>
                            </li>
                            @endif
                        @else
                            @if(Auth::user()->role_id==2)
                           
                            <li class="nav-item">
                                <a class=" nav-link active" id="" href="{{route('usertransaction')}}" title="Ojaak Wallet"> <span class="ojaak_icons_wallet"><p>₹ {{number_format((float)Auth::user()->wallet_point, 2, '.', '')}}</p></span></a>
                            </li>
                            <li class="nav-item">
                                <a class=" nav-link active" id="" href="{{route('notification')}}" title="Notification"> <span class=" chat_header_icon_wrap ojaak_icon_notification notificon">
                                    
                                    @if(!empty($notificationicon))
                                        <p></p>
                                        <!-- <p>{{$chatUnreadCount}}</p> -->
                                    @endif
                                    <p class="shownotificationiconforads" style="display: none;"></p>
                                </span>
                                    </a>
                            </li>
                            <li class="nav-item">
                                <a class=" nav-link active" id="" href="{{route('chat')}}" title="Ojaak Wallet"> <span class="fa fa-comments chat_header_icon_wrap chatnotific">
                                    @if(!empty($chatUnreadCount))
                                        <p></p>
                                        <!-- <p>{{$chatUnreadCount}}</p> -->
                                    @endif
                                        <p class="shownotificationicon" style="display: none;"></p>
                                </span></a>
                            </li>
                            <!-- <li class="nav-item dropdown ">
                                <div class="">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="ojaak_icons_wallet"> ₹ {{number_format((float)Auth::user()->wallet_point, 2, '.', '')}} </span><span class="sr-only">(current)</span></a>
                                    <div class="dropdown-menu">
                                      <a class="dropdown-item" href="{{route('usertransaction')}}">Passbook</a>
                                    </div>
                                </div>
                            </li> -->
                            <li class="nav-item dropdown">
                                <div class="login_btn_outer_wrap">
                                        <div class="User_icon_wrap">
                                            <img src="{{asset('public/uploads/profile/small/'.Auth()->user()->photo)}}" title="{{ Auth::user()->name }}" alt="{{ Auth::user()->name }}">
                                        </div>
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                           <span class="">{{ Auth::user()->name }}</span><span class="sr-only">(current)</span>  
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <a class="dropdown-item" href="{{route('profile')}}">{{ __('My Profile') }}</a>
                                            <a class="dropdown-item" href="{{route('chat')}}">{{ __('Chat') }}</a>
                                            
                                            <a class="dropdown-item" href="{{route('ads.user.index')}}">{{ __('My Ads') }}</a>
                                            <!-- <a class="dropdown-item" href="{{route('usertransaction')}}">{{ __('My Wallet') }}</a> -->
                                            <!-- <a class="dropdown-item" href="{{route('plans')}}">{{ __('Plans') }}</a> -->
                                            <a class="dropdown-item" href="{{route('setting')}}">My Settings</a>
                                            <a class="dropdown-item" href="{{route('showpackage')}}">{{ __('Buy Business Packages') }}</a>
                                            <a class="dropdown-item" href="{{ route('ads.bought_packages') }}">{{ __('Bought Packages & Billing') }}</a>
                                            <a class="dropdown-item" href="javascript:void(0);" id="logout-ojaak-btn" >{{ __('Logout') }}</a>
                                            <!-- <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>-->
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                <input type="text" id="formlogout" value="1" name="formlogout">
                                                @csrf
                                            </form> 
                                        </div>
                                    </div>
                                
                            </li>
                            <li class="nav-item">
                                <a class=" nav-link login_btn_outer_wrap " id="" href="{{route('ads.post')}}"> <span class="ojaak_icons_megaphone login_btn_wrap">Post Ad</span></a>
                            </li>
                            @else
                                <li class="nav-item dropdown">
                                    <div class="login_btn_outer_wrap">
                                            <div class="User_icon_wrap">
                                                <img src="{{asset('public/uploads/profile/small/'.Auth()->user()->photo)}}" title="avatar" alt="avatar">
                                            </div>
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                               <span class="">{{ Auth::user()->name }}</span><span class="sr-only">(current)</span>  
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                <a class="dropdown-item" href="{{route('admin.dashboard')}}">{{ __('My Dashboard') }}</a>
                                                <a class="dropdown-item" href="javascript:void(0);" id="logout-ojaak-btn" >{{ __('Logout') }}</a>
                                                <!-- <a class="dropdown-item" href="{{ route('logout') }}"
                                                   onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
                                                </a>-->
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    <input type="text" id="formlogout" value="1" name="formlogout">
                                                    @csrf
                                                </form> 
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                
                            @endif
                            

                        @endguest
                        
                    </ul>
                </div>
            </div>
        </div>
    </nav>