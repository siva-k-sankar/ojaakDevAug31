@extends('back.layouts.app')
@section('styles')
<style>
.box-info{
	margin-top: 18px;
	margin-left: 15px;
	width: 97.8%;
}
</style>
@endsection

@section('content')

	<div class="content-wrapper">
    	<!-- Content Header (Page header) -->
    	<section class="content-header"><h1>Questions and Answers</h1></section>
	    <div class="box box-info">
	        <form action="{{route('admin.faq.answer')}}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="box-body">
					<div class="form-group">
						<label for="Question">Question:</label>
						<textarea class="form-control" id="questions" placeholder="" name="questions" value="" autocomplete="off" required=""></textarea>
					</div>
					<div class="form-group">
						<label for="Answer">Answer:</label>
						<textarea  class="form-control" id="answers" placeholder="" name="answers" value="" autocomplete="off" required=""></textarea>
					</div>
				</div>
			<!-- /.box-body -->
				<div class="box-footer">
					<a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.management')}}">Back</a>
					<button class="btn btn-primary" type="submit">Submit</button>
					
				</div>
			</form>
		</div>
	</div>

@endsection
@section('scripts')
<script src="//cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
<script>
	CKEDITOR.replace('answers',{
		height:250,
		filebrowserUploadUrl:"{{route('admin.management.upload', ['_token' => csrf_token() ])}}",
		filebrowserUploadMethod: 'form',
	});
</script>
@endsection