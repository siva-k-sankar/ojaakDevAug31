<ul>
	<li>
		<a class="profile_nav_link {{ Request::is('bought_packages') ? 'active' : '' }}" href="{{ route('ads.bought_packages') }}">Bought Packages</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('billing') ? 'active' : '' }}" href="{{ route('ads.billing') }}">Billing</a>
	</li>
	<li>
		<a class="profile_nav_link {{ Request::is('invoice') ? 'active' : '' }}" href="{{ route('ads.invoice') }}">Invoices </a>
	</li>
</ul>