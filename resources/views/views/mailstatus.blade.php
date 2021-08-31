@extends('layouts.home')
@push('styles')
<style>

</style>
@endpush
@section('content')
<div class="container-fluid pl-0 pr-0 product_view_outer_row_wrap">
	<div class="box-size">
	    <div class="row justify-content-center" style="">
	    	<div class="col-6 my-auto">
	    		<img class="my-5 mx-auto d-block "src="{{asset('public/frontdesign/assets/img/ojaak_logo.png')}}" alt="ojaak logo" title="ojaak logo">
		    	<h3 class="my-5 text-center text-success">Your Email Verified Successfully</h3>
		    	<div class="mx-auto my-3 common_btn_wrap text-center">
					<a href="{{url('/')}}">Go Sites</a>
				</div>
	    	</div>
	    </div>
	</div>
</div>
@endsection
