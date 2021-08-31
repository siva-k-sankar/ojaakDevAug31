@extends('layouts.home')
@section('styles')
<style type="text/css">
	
</style>
@endsection
@section('content')
<!-- Page Title -->
	<div class="container-fluid pl-0 pr-0 page_title_bg_wrap">
		<nav aria-label="breadcrumb" class="page_title_inner_wrap">
			<div class="box-size">	
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
			    <li class="breadcrumb-item active" aria-current="page"><a href="#">Contact Us</a></li>
			  </ol>
			</div>
		</nav>
	</div>
		
<!-- Contact Us -->
	<div class="container-fluid pl-0 pr-0 contact_us_row_wrap">
		<div class="box-size">
			<div class="faq_title_wrap">
				<h2>Contact US</h2>
				<p>Fill out our form below or <a href="mailto:support@ojaak.com" title="support@ojaak.com">send us an email (support@ojaak.com).</a></p>
				@if ($errors->any())
					<div class="alert alert-danger m-3">
						<ul>
						    @foreach ($errors->all() as $error)
						        <li>{{ $error }}</li>
						    @endforeach
						</ul>
					</div>
				@endif
				@if(session()->has('messages'))
					<div class="alert alert-success">
						{{session()->get('messages')}}
					</div>
				@endif
				@if(session()->has('error'))
					<div class="alert alert-danger">
						{{session()->get('error')}}
					</div>
				@endif
			</div>
			<div class="row contact_form_outer_wrap">
				<div class="manage_ads_outer_wrap">
					<div class="wallet_nav_outer_wrap">
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<a class="nav-item nav-link active" id="contact_request_tab" data-toggle="tab" href="#contact_request_content" role="tab" aria-controls="contact_request_content" aria-selected="true">New Request</a>
								<a class="nav-item nav-link" id="track_request_tab" data-toggle="tab" href="#track_request_content" role="tab" aria-controls="track_request_content" aria-selected="false">Track Request</a>
							</div>
						</nav>
					</div>
					<div class="tab-content py-3 px-3 px-sm-0">
						<div class="tab-pane fade show active" id="contact_request_content" role="tabpanel" aria-labelledby="contact_request_tab">
							<form action="{{route('contactus')}}" method="post" enctype="multipart/form-data">
								@csrf
								<div class="contact_form_inner_wrap form-group">
									<input type="email" name="email" class="form-control"  value="{{old('email')}}" placeholder="Your email address*" required>
									<div class="common_select_option contact_form_select_btn_wrap">
										<select class="form-control" name="question" required>
											<option value=""hidden>Select Question</option>
											<option <?php if(old('question') == 'I have question about my account'){ echo "selected"; } ?>>I have question about my account</option>
											<option <?php if(old('question') == 'I have a technical issue'){ echo "selected"; } ?>>I have a technical issue</option>
											<option <?php if(old('question') == 'I  have issues in buying and selling'){ echo "selected"; } ?>>I  have issues in buying and selling</option>
											<option <?php if(old('question') == 'I want to report abusive/fraud content'){ echo "selected"; } ?>>I want to report abusive/fraud content</option>
											<option <?php if(old('question') == 'I have queries in credit system'){ echo "selected"; } ?>>I have queries in credit system</option>
											<option <?php if(old('question') == 'Suggestions'){ echo "selected"; } ?>>Suggestions</option>
										 </select>
									</div>
									<input type="tel" name="mobileno" class="form-control"  placeholder="Your phone*" value="{{old('mobileno')}}" required>
									<input type="text"  placeholder="Your name*" class="form-control" name="name" required value="{{old('name')}}">
									<input type="text"  class="form-control"  placeholder="Ad ID" name="adid"  value="{{old('adid')}}">
									<textarea class="form-control" rows="5" placeholder="Description" name="description">{{old('description')}}</textarea>
									<div class="contact_upload_img_wrap wrap-custom-file">
										  <input type="file" id="attach" name="attachments"  />
										  <label  for="attach">
										  	<span>Add file  or drop files here</span>
										  </label>
									</div>
								    <div class="common_btn_wrap contact_submit_btn_wrap">
								    	<button class=""  type="submit" >Submit</button>
								    </div>
								</div>
							</form>
						</div>
						
						<div class="tab-pane fade" id="track_request_content" role="tabpanel" aria-labelledby="track_request_tab">
							@auth
							<form action="{{route('contactus.gettrack')}}" method="post">
								@csrf
								<div class="input-group mb-3">
									<input type="text" class="form-control" placeholder="Enter Ticket ID" aria-label="Enter Ticket ID" name="track_id"aria-describedby="basic-addon2">
									<div class="input-group-append">
										<button class="btn btn-outline-secondary" type="submit">Search</button>
									</div>
								</div>
							</form>
							<div class="search_track_outer_wrap">
								<ul class="track_request_single_outer">
									@if(!empty($trackget))
									@foreach ($trackget as $track)
										<li class="track_request_single_content">
											<h4>
												<a href="#{{$track->tickectid}}" data-toggle="collapse">			{{$track->help}}
												</a>
											</h4>
											<div id="{{$track->tickectid}}" class="track_detail_wrap collapse">
												<p>
													<strong>Description :</strong>
													{{$track->description}}
												</p>
												<p>
													<strong>Ticket ID :</strong>
													{{$track->tickectid}}
												</p>
												<div class="status_wrap">
													<h5>
														<strong>Status :</strong>
														@if($track->status == 0)	
															pending
														@endif
														@if($track->status == 1)	
															Complete
														@endif	
													</h5>
													<h5><strong>Date :</strong> {{date('d-m-Y', strtotime($track->created_at))}}</h5>
												</div>
												@if($track->status == 1)
													<form method="post" action="{{route('track.reopen',$track->id)}}">
														@csrf
														@method ('patch')
														<input type="hidden" name="tickectid" value="{{$track->tickectid}}">
														<button type="submit" class="btn btn-outline-primary track_reopen_btn_wrap">Reopen</button>
													</form>
												@endif
											</div>
										</li>
									@endforeach	
									@endif
								</ul>
							</div>
							<div class="track_request_listing">
								<ul class="track_request_single_outer">
									@if(!empty($trackreq))
									@foreach ( $trackreq as $track )
										<li class="track_request_single_content">
											<h4>
												<a href="#{{$track->tickectid}}" data-toggle="collapse">
													{{$track->help}}
												</a>
											</h4>
											<div id="{{$track->tickectid}}" class="track_detail_wrap collapse">
												<p>
													<strong>Description :</strong>
													{{$track->description}}
												</p>
												<p>
													<strong>Ticket ID :</strong>
													{{$track->tickectid}}
												</p>
												<div class="status_wrap">
													<h5>
														<strong>Status :</strong>
														@if($track->status == 0)	
															pending
														@endif
														@if($track->status == 1)	
															Complete
														@endif	
													</h5>
													<h5><strong>Date :</strong> {{date('d-m-Y', strtotime($track->created_at))}}</h5>
												</div>
												@if($track->status == 1)
													<form method="post" action="{{route('track.reopen',$track->id)}}">
														@csrf
														@method ('patch')
														<input type="hidden" name="tickectid" value="{{$track->tickectid}}">
														<button type="submit" class="btn btn-outline-primary track_reopen_btn_wrap">Reopen</button>
													</form>
												@endif
											</div>

										</li>
									@endforeach
									@endif
								</ul>
							</div>
							@else
							<div class="login_track">
								<h4>Login to view track request</h4>
							</div>
							@endauth
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	
@endsection


@section('scripts')
@php
	$active = '';
if(!empty($trackget)){
@endphp

<script type="text/javascript">
	$(document).ready(function() {
		
		// Image upload
		$('#contact_request_tab').removeClass('active');
		$('#track_request_tab').addClass('active');
		$('#track_request_tab').trigger('click');

		$('#contact_request_content').removeClass('active');
		$('#contact_request_content').removeClass('show');
		$('#track_request_content').addClass('active');
		$('#track_request_content').addClass('show');

	});
</script>
@php
}

@endphp
<script type="text/javascript">
	$('input[type="file"]').each(function(){
	  var $file = $(this),
	      $label = $file.next('label'),
	      $labelText = $label.find('span'),
	      labelDefault = $labelText.text();
	  $file.on('change',function(event){
	    var fileName = $file.val().split('\\' ).pop(),
	        tmppath = URL.createObjectURL(event.target.files[0]);
	    if( fileName ){
	      $label.addClass('file-ok').css('background-image','url(' + tmppath +')');
	      $labelText.text(fileName);
	    }else{
	      $label.removeClass('file-ok');
	      $labelText.text(labelDefault);
	    }
	  });
	});
</script>
@endsection