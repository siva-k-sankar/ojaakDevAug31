@extends('layouts.home')
@section('styles')


@endsection

@section('content')
<div class="container py-4">
	<div class="jumbotron">
		<p class="h3 text-center font-weight-bold pb-4" >Ticket Management</p>


        <p>Name : {{$check->name}}</p>
        <p>Ticket No : {{$check->tickectid}}</p> 
        <p>Help : {{$check->help}}</li>
        <p>Description : {{$check->description}}</p> 
        <p>Status : {{($check->status==0)?'Pending':'Completed'}}</p>

	  	<form action="{{route('contact.ticketmessagesave')}}" method="post" class="form">
	  		@csrf
		    <div class="form-row justify-content-center">
		      <div class="col-md-8">
		      	<input type="hidden" class="form-control" id="id" autocomplete="off" placeholder="Enter Message" name="id" value="{{Request::segment(3)}}">
		        <input type="text" class="form-control" id="message" autocomplete="off"placeholder="Enter Message" name="message" required="">
		      </div>
		      <div class="col-md-2 text-center common_btn_wrap ">
		        <button type="submit" class="btn   btn-block ">Send</button>
		      </div>
		    </div>
  		</form>
	</div>
</div>
<div class="container " >
	<div class="jumbotron">
		@if(!$message->isEmpty())
			@foreach($message as $msg)
				@if($msg->type=='1')
				  	<div class="alert alert-success alert-dismissible">
						<strong>Me :</strong> {{$msg->message}}.
						<button type="button" class="close" >
					    	<span aria-hidden="true"><small>{{date("d F Y h:i A", strtotime($msg->created_at))}}</small></span>
					  	</button>
					</div>
				@else
					<div class="alert alert-info alert-dismissible">
						<strong>Admin :</strong> {{$msg->message}}.
						<button type="button" class="close" >
					    	<span aria-hidden="true"><small>{{date("d F Y h:i A", strtotime($msg->created_at))}}</small></span>
					  	</button>
					</div>
				@endif
			@endforeach
		@else
			<div class="alert alert-info alert-dismissible">
				<strong>No Message</strong> 
			</div>
		@endif
	</div>
</div>
@endsection 


@section('scripts')
@endsection