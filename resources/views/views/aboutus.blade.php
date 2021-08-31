@extends('layouts.home')
@section('styles')


@endsection

@section('content')
<!-- Page Title -->
<div class="container-fluid pl-0 pr-0 page_title_new_bg_wrap">
    <div class="box-size">
        <div class="row">
            <div class="col-md-6 pl-0">
                <div class="profile_breadcum_new_wrap">
                    <nav aria-label="breadcrumb" class="page_title_inner_new_wrap">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">About Us</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="bg-white">
	<div class="container-fluid">
		<div class="box-size">
			<div class="aboutus-content-outer-wrap">
				<div class="row">
					<div class="col-lg-6 col-md-12">
						<div class="about-img-outer-wrap">
							<img src="{{ asset('public/frontdesign/assets/img/bg-6.jpg') }}">
						</div>
					</div>
					<div class="col-lg-6 col-md-12">
						<div class="aboutus-content-wrap">
							<div class="aboutus-content-inner-wrap">
								<h4>About Us</h4>
								<p>OJAAK is India’s classified app to buy / sell products. We passionately believe that our marketplace should provide profit for the active customers when they are ready to spend their quality time with us. Since 2019 OJAAK provides the virtual marketplace to connect the trusted buyers and sellers.</p>
								<p>We’re a company made up of innovators and Lateral thinkers, with the drive and ability to constantly update and improve the online marketing and buying/selling experience.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection 


@section('scripts')
@endsection