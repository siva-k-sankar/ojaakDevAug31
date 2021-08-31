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
                            <li class="breadcrumb-item"><a href="#">How it Works</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<section>
	<div class="box-size">
		<div class="works-title-wrap">
			<h2>How It Works</h2>
			<p>OJAAK is a classified site to buy/sell products. We help users to explore the buying/selling opportunities for the products and bring it to limelight efficiently. We offer cash rewards for posting and visiting advertisements. </p>
		</div>
	</div>
</section>

<section class="bg-light">
	<div class="container-fluid">
		<div class="box-size">
			<div class="works-content-outer-wrap">
				<div class="row">
					<div class="col-md-6">
						<div class="works-content-wrap">
							<div class="works-content-inner-wrap">
								<h4>OJAAK Wallet</h4>
								<p>OJAAK Wallet is a point based system which allows users to earn reward points based on their activities at OJAAK. Users can redeem wallet points by transferring money to their paytm accounts or can buy any paid plans at OJAAK. One wallet point is equal to Rs 1.</p>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="works-img-outer-wrap">
							<img src="{{ asset('public/frontdesign/assets/img/about-bg.jpg') }}">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section>
	<div class="box-size">
		<div class="points-outer-wrap">
			<h3>How It Earn Points At OJAAK</h3>
			<p>Registered / Signed in with social account users can earn points at OJAAK. Users can earn more points by visiting posted Ads at OJAAK.Whenever users visit the posted Ads with the label “Visit to earn” on them for more than 10 seconds, they get 0.1 points added to their wallet. Users can earn more points as many Ad as they visit.</p>
			<p>In addition users can earn points for posting Ad and updating profile information. </p>
			<div class="earn-points-outer-wrap">
				<div class="earn-points-wrap">
					<ul class="earn-points-inner-wrap">
						<li class="mb-3">
							<h6><i class="fa fa-star"></i> 10 points for new user registration.</h6>
						</li>
						<li class="mb-3">
							<h6><i class="fa fa-star"></i> 5 points for referenced registration.</h6>
						</li>
						<li class="mb-3">
							<h6><i class="fa fa-star"></i> 5 points to update facebook accounts at OJAAK.</h6>
						</li>
						<li class="mb-3">
							<h6><i class="fa fa-star"></i> 5 points for posting Free/Premium (paid plan) Ad.</h6>
						</li>
						<li class="mb-3">
							<h6><i class="fa fa-star"></i> 5 points for company/work Email ID.</h6>
						</li>
						<li class="mb-3">
							<h6><i class="fa fa-star"></i> 5 points for Profile photo.</h6>
						</li>
						<li class="mb-3">
							<h6><i class="fa fa-star"></i> 5 points for Government Id proof. </h6>
						</li>
					</ul>
				</div>
			</div>
			<div class="earn-points-img-outer-wrap">
				<div class="row">
					<div class="col-md-6">
						<div class="earn-points-img-wrap">
							<img src="{{ asset('public/frontdesign/assets/img/img-1.jpg') }}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="earn-points-img-wrap">
							<img src="{{ asset('public/frontdesign/assets/img/img-2.jpg') }}">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section>
	<div class="box-size">
		<div class="redeem-points-outer-wrap">
			<h3>How to Redeem Wallet Points</h3>
			<p>To redeem, go to the Wallet link on the home page. Users can redeem by entering the redeem amount and paytm account detail. Redeem is allowed for a minimum account balance of 200 points.</p>
			<div class="redeem-points-wrap">
				<ul class="redeem-points-inner-wrap">
					<li class="mb-3">
						<h6><i class="fa fa-star"></i> Upload of PAN card is mandatory to redeem earned wallet points.</h6>
					</li>
					<li class="mb-3">
						<h6><i class="fa fa-star"></i> User can redeem earned points at any time before expiry</h6>
					</li>
					<li class="mb-3">
						<h6><i class="fa fa-star"></i> Wallet points will expire after 180 days from the date when it is earned.</h6>
					</li>
				</ul>
			</div>
			<div class="redeem-img-outer-wrap">
				<div class="row">
					<div class="col-md-6">
						<div class="redeem-points-img-wrap">
							<img src="{{ asset('public/frontdesign/assets/img/img-3.jpg') }}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="redeem-points-img-wrap">
							<img src="{{ asset('public/frontdesign/assets/img/img-4.jpg') }}">
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