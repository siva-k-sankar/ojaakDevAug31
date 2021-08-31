<?php //echo "<pre>";print_r($userdetails);die; ?>
<ul>
	<li>
		<a class="profile_nav_link {{ ($segment2=='info-all') ? 'active' : '' }}" href="{{route('view.profile.all',$userdetails->uuid)}}">Profile Info</a>
	</li>
	<li>
		<a class="profile_nav_link {{ ($segment2=='userwallets') ? 'active' : '' }}" href="{{route('userwallets',$userdetails->uuid)}}">Wallet</a>
	</li>
	<li>
		<a class="profile_nav_link {{ ($segment2=='chathistory') ? 'active' : '' }}" href="{{route('chathistory',$userdetails->uuid)}}">Chat History</a>
	</li>
	<li>
		<a class="profile_nav_link {{ ($segment2=='notifications') ? 'active' : '' }}" href="{{ route('notifications',$userdetails->uuid) }}">Notifications</a>
	</li>
	<li>
		<a class="profile_nav_link {{ ($segment2=='boughtpackages') ? 'active' : '' }}" href="{{ route('boughtpackages',$userdetails->uuid) }}">Bought Packages</a>
	</li>
	<li>
		<a class="profile_nav_link {{ ($segment2=='userbilling') ? 'active' : '' }}" href="{{ route('userbilling',$userdetails->uuid) }}">Billing</a>
	</li>
	<li>
		<a class="profile_nav_link {{ ($segment2=='userinvoices') ? 'active' : '' }}" href="{{ route('userinvoices',$userdetails->uuid) }}">Invoices</a>
	</li>
	<li>
		<a class="profile_nav_link {{ ($segment2=='userblocked') ? 'active' : '' }}" href="{{ route('userblocked',$userdetails->uuid) }}">Blocked Users</a>
	</li>
</ul>