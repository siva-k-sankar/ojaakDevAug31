@extends('back.layouts.app')
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/image-uploader.min.css') }}" >
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css?family=Lato:300,700|Montserrat:300,400,500,600,700|Source+Code+Pro&display=swap"
          rel="stylesheet">
@endsection
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> User Ads Edit</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          	<div class="box ">
		            <!-- Add the bg color to the header using any of the bg-* classes -->
		            <div class="box-header bg-purple">
		              	<center>
		              	<h3 class="box-title ">Ads Details</h3>
		                </center>
		            </div>
		            <div class="box-body ">
		            	 @if (isset($errors) and $errors->any())
                    	<div class="alert alert-danger">
	                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	                        <h5><strong>{{ __('Oops ! An error has occurred.') }}</strong></h5>
	                        <ul class="list list-check">
	                            @foreach ($errors->all() as $error)
	                                <li>{{ $error }}</li>
	                            @endforeach
	                        </ul>
                    	</div>
                    	@endif
			            <form style="padding: 10px;" action="{{route('admin.ads.save')}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{$ads->uuid}}">
                        <input type="hidden" id="city" value="{{$ads->cities}}">
                        <input type="hidden" id="in-tag" value="{{$ads->tags}}">
                        <input type="hidden" id="in-images" value="{{$ads->images}}">
                        @if(!empty($customfields))
                            {!! $customfields !!}
                        @endif
                        <div class="form-group row">

                            <label for="title" class="col-sm-3 col-form-label">Title<sup>*</sup></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control mb-2 @error('title') is-invalid @enderror" id="title" name="title" placeholder=""  value="{{$ads->title}}" required>
                                @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-red">{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Description<sup>*</sup></label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Required example textarea" required>{{$ads->description}}</textarea>
                                @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-red">{{ $message }}</strong>
                                            </span>
                                @enderror

                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="cities" class="col-sm-3 col-form-label">Cities<sup>*</sup></label>
                            <div class="col-sm-9">
                                <select class="form-control js-states @error('city_name') is-invalid @enderror" name="city_name" id="cities">
                                </select>
                                @error('city_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-red">{{ $message }}</strong>
                                            </span>
                                @enderror
                                
                            </div>
                            
                        </div>
                        <div class="form-group row"  id="not" style="display: none;">
                            <label class="col-sm-3 col-form-label">Others Cities</label>
                            <div class="col-sm-9">
                            <input class="form-control"  name="not_in_list">
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <label for="price" class="col-sm-3 col-form-label">Price<sup>*</sup></label>
                            <div class="col-sm-9">
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="" aria-label="" aria-describedby="basic-addon1" value="{{$ads->price}}" required>
                                    @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-red">{{ $message }}</strong>
                                            </span>
                                @enderror
                                
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">Tags<sup>*</sup></label>
                            <div class="col-sm-9">
                                <div style="border:1px solid #ced4da;">
                                    <div style="margin: 10px;">
                                        
                                    <input type="text" name="tags" id="tags"placeholder="Write Some Tags" class="form-control tm-input tm-input-info " style="border:none;outline:none;" />
                                    
                                    </div>

                                </div >
                            </div>
                        </div> -->
                        
                        <div class="form-group row">
                            <label for="image" class="col-sm-3 col-form-label">Image<sup>*</sup></label>
                            <div class="col-sm-9">
                                <div class="input-field">
                                    <div class="input-images-1" style="padding-top: .5rem;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <center>
                                	<a class="btn bg-navy btn-flat margin" href="{{route('admin.ads.view',$ads->uuid)}}">
	                				 back</a>
                                    <button type="submit" class="btn btn-flat bg-olive margin">Update</button>
                                </center>
                                
                            </div>
                        </div>
                    </form>
		            </div>
		            
		        </div>
		        <!-- /.widget-user -->
	        </div>
	        <!-- /.col -->
	    </div>
	    <!-- /.row -->
    </section>
    <!-- /.content -->
    
</div>
@endsection

@section('scripts')
 <script src="{{ asset('public/js/tagmanager.min.js') }}" ></script>
<script type="text/javascript">
    
    var tag=$("#in-tag").val();
    $(".tm-input").tagsManager({
            prefilled: tag,
    });
     
</script>
<script>
        function rejectads(id){
        	var reason= $('#reason');
        	if (reason.val() != null && reason.val() != '') {  
                	swal({
			            title: "Are you sure?",
						text: "You won't be able to revert this!",
						icon: "warning",
						buttons: true,
						dangerMode: true,
			            }).then((willDelete) => {
						  if (willDelete) {
						  	document.getElementById('rej-ads-'+id).submit();
						    swal("Ads Rejected!", {
						      icon: "success",
						    });
						  } else {
						    swal("Your file is safe!");
						  }
					});      
                } else {  
                   swal("Reason Required!", "", "error", { button: "close",});  
                }
		}

		function verifyads(id){
            
            swal({
            title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			buttons: true,
			dangerMode: true,
            }).then((willDelete) => {
			  if (willDelete) {
			  	document.getElementById('verify-ads-'+id).submit();
			    swal("Ads Approved!", {
			      icon: "success",
			    });
			  } else {
			    swal("Your file is safe!");
			  }
			});
		}

		$(document).ready(function(){
	       $("#rejectedbtn").click(function(e){
	            $("#actionform").hide();
	            $("#reasonform").show();
	            e.preventDefault();
	        });
	        $("#back").click(function(e){
	            $("#actionform").show();
	            $("#reasonform").hide();
	            e.preventDefault();
	        });
        });
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        var city = $('#city').val();
        $('select[name="city_name"]').append('<option value="-1"  >Not In The List</option>');
        $.ajax({
                    url: "{{ route('autocomplete_cities') }}",
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $.each(data, function(key, value) {
                            if(city==key){
                                 $('select[name="city_name"]').append('<option value="'+ key +'" selected >'+ value +'</option>');
                            }else{

                                $('select[name="city_name"]').append('<option value="'+ key +'">'+ value +'</option>');
                            }
                        });
                         

                    }
                });
    });

</script>
<script src="https://cdn.ckeditor.com/4.13.0/basic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
      $("#cities").select2({
          placeholder: "Select a Cities",
          allowClear: true
      });
      
    $("#cities").change(function(e){
        cities=$("#cities").val();
        //alert(cities);
        if(cities=="-1"){
            $("#not").show();
        }else{
            $("#not").hide();
        }
        e.preventDefault();
    });
</script>
<script>
    CKEDITOR.replace( 'description' );
</script>
<script src="{{ asset('public/js/image-uploader.min.js') }}" ></script>

<script>
    var tag=$("#in-images").val();
    let preloaded = JSON.parse(tag);
    //console.log(preloaded);
    $('.input-images-1').imageUploader({
    preloaded: preloaded,
    imagesInputName: 'photos',
    preloadedInputName: 'old',
    maxSize: 2 * 1024 * 1024,
    maxFiles: 5
});

</script>

@endsection