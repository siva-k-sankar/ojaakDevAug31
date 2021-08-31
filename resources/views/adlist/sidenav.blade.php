<ul class="sidenav-list">

@if (Route::current()->uri() == 'properties' || Route::current()->uri() == 'booking/{id}' || Route::current()->uri() == 'my_bookings')
  <li>
    <a href="{{ url('properties') }}" aria-selected="{{ (Route::current()->uri() == 'properties') ? 'true' : 'false' }}" class="sidenav-item">{{trans('messages.sidenav.your_listing')}}</a>
  </li>
  <li>
    <a href="{{ url('my_bookings') }}" aria-selected="{{ (Route::current()->uri() == 'my_bookings') ? 'true' : 'false' }}" class="sidenav-item">{{trans('messages.sidenav.property_booking')}}</a>
  </li>
@endif

@if (Route::current()->uri() == 'trips/active' || Route::current()->uri() == 'trips/previous')
  <li>
    <a class="sidenav-item" aria-selected="{{ (Route::current()->uri() == 'trips/active') ? 'true' : 'false' }}" href="{{ url('/') }}/trips/active">{{trans('messages.sidenav.your_trip')}}</a>
  </li>
  <li>
    <a class="sidenav-item" aria-selected="{{ (Route::current()->uri() == 'trips/previous') ? 'true' : 'false' }}" href="{{ url('/') }}/trips/previous">{{trans('messages.sidenav.previous_trip')}}</a>
  </li>
@endif

@if (Route::current()->uri() == 'users/profile' || Route::current()->uri() == 'users/reviews' || Route::current()->uri() == 'users/profile/media' || Route::current()->uri() == 'users/edit_verification' || Route::current()->uri() == 'reviews/details/{id}')
    <li>
      <a href="{{ url('users/profile') }}" aria-selected="{{ (Route::current()->uri() == 'users/profile') ? 'true' : 'false' }}" class="sidenav-item">{{trans('messages.sidenav.edit_profile')}}</a>
    </li>
    <li>
      <a href="{{ url('users/profile/media') }}" aria-selected="{{ (Route::current()->uri() == 'users/profile/media') ? 'true' : 'false' }}" class="sidenav-item">{{trans('messages.sidenav.photo')}}</a>
    </li>

     <li>
      <a href="{{ url('users/reviews') }}" aria-selected="{{ (Route::current()->uri() == 'users/reviews' || Route::current()->uri() == 'reviews/details/{id}') ? 'true' : 'false' }}" class="sidenav-item">{{trans('messages.sidenav.reviews')}}</a>
    </li>


    <li>
      <a href="{{ url('users/edit_verification') }}" aria-selected="{{ (Route::current()->uri() == 'users/edit_verification') ? 'true' : 'false' }}" class="sidenav-item">{{trans('messages.sidenav.verification')}}</a>
    </li>

@endif

@if (Route::current()->uri() == 'users/security' || Route::current()->uri() == 'users/account_preferences' || Route::current()->uri() == 'users/transaction_history')
  <li>
    <a href="{{ url('users/account_preferences') }}" aria-selected="{{ (Route::current()->uri() == 'users/account_preferences') ? 'true' : 'false' }}" class="sidenav-item">{{trans('messages.account_sidenav.account_preference')}}</a>
  </li>

  <li>
    <a href="{{ url('users/transaction_history') }}" aria-selected="{{ (Route::current()->uri() == 'users/transaction_history') ? 'true' : 'false'}}" class="sidenav-item">{{trans('messages.account_sidenav.transaction_history')}}</a>
  </li>

  <li>
    <a href="{{ url('users/security') }}" aria-selected="{{ (Route::current()->uri() == 'users/security') ? 'true' : 'false' }}" class="sidenav-item">{{trans('messages.account_sidenav.security')}}</a>
  </li>
@endif

</ul>