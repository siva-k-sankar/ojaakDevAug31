<ul>
	<li>
		<a class="profile_nav_link {{ Request::is('setting') ? 'active' : '' }}" href="{{ route('setting') }}">Change Password</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('setting/privacy') ? 'active' : '' }}" href="{{ route('setting.privacy') }}">Privacy Settings</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('setting/privacy/manage_user') ? 'active' : '' }}" href="{{ route('setting.privacy.manageusers')}}">Manage Blocked Users</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('setting/notification') ? 'active' : '' }}" href="{{ route('setting.notification') }}">Notifications</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('setting/deactive') ? 'active' : '' }}" href="{{ route('setting.deactive') }}">Deactivate account</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('setting/logoutFeature') ? 'active' : '' }}" href="{{ route('setting.logout') }}">Logout</a>
	</li>
</ul>