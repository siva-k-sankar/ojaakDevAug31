<aside class="main-sidebar">
    <?php 
    $segment1 = Request::segment('1');
    $segment2 = Request::segment('2');
    $segment3 = Request::segment('3');
    ?>

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview {{( $segment2 =='ads'|| $segment2 =='location'|| $segment2 =='customField'  || $segment3 =='adsdata' || $segment3 =='feature' ||$segment3 =='topAdslist' || $segment3 =='pearlAdslist' || $segment3 =='categories' || $segment3 =='sub_categories')?'menu-open':''}}">
              <a href="#">
                <i class="fa fa-area-chart"></i> <span>Ads</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu" <?php if($segment2 =='ads'|| $segment2 =='location'|| $segment2 =='customField'  || $segment3 =='adsdata' || $segment3 =='feature' ||$segment3 =='topAdslist' || $segment3 =='pearlAdslist' || $segment3 =='categories' || $segment3 =='sub_categories'){ echo 'style="display: block;"' ;} ?>>
                <li <?php if($segment3 =='adsdata'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.ads') }}"><i class="fa fa-circle-o"></i> Ads</a></li>
                <li <?php if($segment3 =='feature'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.featureads') }}"><i class="fa fa-circle-o"></i>Platinum Ads List</a></li>
                <li <?php if($segment3 =='topAdslist'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.topadslist') }}"><i class="fa fa-circle-o"></i>Feature Ads List</a></li>
                <!-- <li <?php if($segment3 =='pearlAdslist'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.pearladslist') }}"><i class="fa fa-circle-o"></i>Pearl Ads List</a></li> -->
                <li <?php if($segment3 =='categories' || $segment3 =='sub_categories'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.ads.categories') }}"><i class="fa fa-circle-o"></i> Categories</a></li>
                <li <?php if($segment2 =='location'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.location.country') }}"><i class="fa fa-circle-o"></i> Locations</a></li>
                <!-- <li><a href="{{ route('admin.location.request') }}"><i class="fa fa-circle-o"></i> Locations Requests</a></li> -->
                <li <?php if($segment2 =='customField'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.customfield') }}"><i class="fa fa-circle-o"></i> Custom Fields</a></li>
              </ul>
            </li>
            <li class="treeview {{( $segment2 =='paidadsplans' || $segment2 =='topadsplans' || $segment2 =='premiumadsplans' )?'menu-open':''}}">
              <a href="#">
                <i class="fa fa-calendar-check-o"></i> <span>Plans</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu" <?php if($segment2 =='paidadsplans' || $segment2 =='topadsplans' || $segment2 =='premiumadsplans'){ echo 'style="display: block;"' ;} ?>>
                <!-- <li><a href="{{ route('admin.plans') }}"><i class="fa fa-circle-o"></i>Plans</a></li>
                <li><a href="{{ route('admin.plans.history') }}"><i class="fa fa-circle-o"></i>Plans History</a></li> -->
               <!--  <li <?php if($segment2 =='paidadsplans'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.paidadsplan') }}"><i class="fa fa-circle-o"></i>Paid ads plan</a></li> -->
                <li <?php if($segment2 =='topadsplans'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.topadsplan') }}"><i class="fa fa-circle-o"></i>Top list ads plan</a></li>
                <li <?php if($segment2 =='premiumadsplans'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.premiumadsplan') }}"><i class="fa fa-circle-o"></i>Premium ads plan</a></li>
              </ul>
            </li>
            <li class="treeview {{( $segment2 =='user'|| $segment2 =='users'  && $segment3 =='proof_verification' || $segment3 =='profilephoto' )?'menu-open':''}}">
                <a href="#">
                    <i class="fa fa-user-secret"></i>
                    <span>Users</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" <?php if($segment2 =='user'|| $segment2 =='users'  && $segment3 =='details' || $segment3 =='proof_verification' || $segment3 =='profilephoto'){ echo 'style="display: block;"' ;} ?>>
                    <!-- <li><a href="#"><i class="fa fa-circle-o"></i>User List</a></li> -->
                    <li <?php if($segment3 =='details'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.users') }}"><i class="fa fa-circle-o"></i>User</a></li>
                    <li <?php if($segment3 =='proof_verification'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.users.proofverify') }}"><i class="fa fa-circle-o"></i>Proof Verification</a></li>
                    <li <?php if($segment3 =='profilephoto'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.users.profilephoto') }}"><i class="fa fa-circle-o"></i>Profile Photo Verification</a></li>
                    <!-- <li><a href="{{route('admin.chathistory')}}"><i class="fa fa-circle-o"></i>Chat History</a></li> -->
                </ul>
            </li>
            <li class="treeview {{( $segment2 =='report' && $segment3 =='redeemamount' || $segment3 =='contactus' || $segment3 =='planpurchase'|| $segment3 =='reportBillInfo' )?'menu-open':''}}">
                  <a href="#">
                    <i class="fa fa-file-code-o" aria-hidden="true"></i>
                    <span>Report</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" <?php if($segment2 =='report' && $segment3 =='redeemamount' || $segment3 =='contactus' || $segment3 =='planpurchase' || $segment3 =='reportBillInfo'){ echo 'style="display: block;"' ;} ?>>
                    <li <?php if($segment3 =='redeemamount'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.reportRedeem') }}"><i class="fa fa-circle-o"></i>Redeem Amount</a></li>
                    <li <?php if($segment3 =='contactus'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.reportContactus') }}"><i class="fa fa-circle-o"></i>Contact Us </a></li>

                    <li <?php if($segment3 =='planpurchase'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.planpurchases') }}"><i class="fa fa-circle-o"></i>Purchased Plans</a></li>
                    <li <?php if($segment3 =='reportBillInfo'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.reportBillInfo') }}"><i class="fa fa-circle-o"></i>Edit Bills</a></li>

                  </ul>
            </li>
            <li class="treeview  {{( $segment2 =='management' && $segment3 =='faq' )?'menu-open':''}}">
                  <a href="#">
                    <i class="fa fa-tasks" aria-hidden="true"></i>
                    <span>Faq</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" <?php if($segment2 =='management' && $segment3 =='faq'){ echo 'style="display: block;"' ;} ?>>
                    <li <?php if($segment3 =='faq'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.management') }}"><i class="fa fa-circle-o"></i>FAQ</a></li>
                  </ul>
            </li>
            <li class="treeview  {{( $segment2 =='broadcast' )?'menu-open':''}}">
                  <a href="#">
                    <i class="fa fa-bullhorn" aria-hidden="true"></i>
                    <span>Broadcast</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" <?php if($segment2 =='broadcast' ){ echo 'style="display: block;"' ;} ?>>
                    <li <?php if($segment2 =='broadcast'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.broadcast') }}"><i class="fa fa-circle-o"></i>Message</a></li>
                  </ul>
            </li>
            <li class="treeview {{( $segment2 =='complaints' && $segment3 =='user' || $segment3 =='ads' || $segment3 =='chat')?'menu-open':''}}">
                <a href="#">
                    <i class="fa fa-comment"></i>
                    <span>Complaints</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" <?php if($segment2 =='complaints' && $segment3 =='user' || $segment3 =='ads' || $segment3 =='chat'){ echo 'style="display: block;"' ;} ?>>
                    <li <?php if($segment3 =='user'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.complaint.users') }}"><i class="fa fa-circle-o"></i>Users</a></li>
                    <li <?php if($segment3 =='ads'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.complaint.ads') }}"><i class="fa fa-circle-o"></i>Ads</a></li>
                    <li <?php if($segment3 =='chat'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.complaint.chat') }}"><i class="fa fa-circle-o"></i>Message</a></li>
                </ul>
            </li>
            <li class="treeview {{($segment2 =='wallets' || $segment3 =='sites' || $segment3 =='points' || $segment3 =='profile' || $segment3 =='change_password' || $segment3 =='payment')?'menu-open':''}}" >
                  <a href="#">
                    <i class="fa fa-gear"></i>
                    <span>Settings</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" <?php if($segment2 =='wallets' || $segment3 =='sites' || $segment3 =='points' || $segment3 =='profile' || $segment3 =='change_password' || $segment3 =='payment'){ echo 'style="display: block;"' ;} ?>  >
                    <li <?php if($segment3 =='sites'){ echo 'class="active"' ;} ?> ><a href="{{ route('admin.setting') }}"><i class="fa fa-circle-o"></i>Sites</a></li>
                    <li <?php if($segment2 =='wallets'){ echo 'class="active"' ;} ?> ><a href="{{ route('wallets') }}" ><i class="fa fa-circle-o"></i>Wallets</a></li>
                    <li <?php if($segment3 =='points'){ echo 'class="active"' ;} ?> ><a href="{{ route('admin.point') }}"><i class="fa fa-circle-o"></i>Points</a></li>
                    <li <?php if($segment3 =='payment'){ echo 'class="active"' ;} ?> ><a href="{{ route('admin.payment') }}"><i class="fa fa-circle-o"></i>Payment</a></li>
                    <li <?php if($segment3 =='profile'){ echo 'class="active"' ;} ?> ><a href="{{ route('admin.profile') }}"><i class="fa fa-circle-o"></i> Profile</a></li>
                    <li <?php if($segment3 =='change_password'){ echo 'class="active"' ;} ?> ><a href="{{ route('admin.changepassword') }}"><i class="fa fa-circle-o"></i> Change Password</a></li>
                  </ul>
            </li>



            <li class="treeview {{( $segment2 =='role_access' && $segment3 =='adminusers')?'menu-open':''}}">
                <a href="#">
                    <i class="fa fa-user-secret"></i>
                    <span>Roles</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" <?php if($segment2 =='role_access'){ echo 'style="display: block;"' ;} ?>>
                    <li <?php if($segment3 =='addrole' || $segment3 =='role'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.listroles') }}"><i class="fa fa-circle-o"></i>Role</a></li>
                    <li <?php if($segment3 =='adminusers' || $segment3 =='addsubadmin'){ echo 'class="active"' ;} ?>><a href="{{ route('admin.adminusers') }}"><i class="fa fa-circle-o"></i>Sub Admin Users</a></li>
                </ul>
            </li>
            
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
