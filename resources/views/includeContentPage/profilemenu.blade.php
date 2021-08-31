<ul>
	<!-- <li>
		<a class="profile_nav_link {{ Request::is('profile') ? 'active' : '' }}" href="{{ route('profile') }}">Personal Information</a>
	</li>
	
	<li>
		<a class="profile_nav_link {{ Request::is('profile/social') ? 'active' : '' }}" href="{{ route('profile.social') }}">Social</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('profile/mobile') ? 'active' : '' }}" href="{{ route('profile.mobile') }}">Mobile</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('profile/mail') ? 'active' : '' }}" href="{{ route('profile.mail') }}">Mail</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('profile/workmail') ? 'active' : '' }}" href="{{ route('profile.workmail') }}">Work Mail</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('wallet') ? 'active' : '' }}" href="{{route('usertransaction')}}">My Wallet</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('profile/govtproof') ? 'active' : '' }}" href="{{ route('profile.govtproof') }}">Govt Proof</a>
	</li> -->
	<li>
		<a class="profile_nav_link {{ Request::is('info') ? 'active' : '' }}" href="{{ url('profile/info')}}/{{Auth::user()->uuid}}">Profile Information</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('user/ads') ? 'active' : '' }}" href="{{ route('ads.user.index') }}">Ads Management</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('profile/referral') ? 'active' : '' }}" href="{{ route('profile.referraluser') }}">Referral users</a>
	</li>
	<!-- <li>
		<a class="get_user_reviews" data-toggle="modal" data-target="#reviews_popup_modal"  data-userid="{{Auth::user()->uuid}}">Review</a>
	</li> -->
	<li>
		<a class="profile_nav_link " href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">Logout</a>
	</li>
</ul>