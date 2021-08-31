@extends('layouts.app')
@section('styles')
<style>
sup{
    color:red;margin: 3px;
}
tm-input:focus{
    border:none;
}
.input-field label {
    width: 100%;
    color: #9e9e9e;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    font-size: 1em;
    cursor: text;
    -webkit-transition: -webkit-transform .2s ease-out;
    transition: -webkit-transform .2s ease-out;
    -webkit-transform-origin: 0 100%;
    transform-origin: 0 100%;
    text-align: initial;
    -webkit-transform: translateY(7px);
    transform: translateY(7px);
    pointer-events: none;
}

input:focus + label {
    color: #2196f3;
}
.input-field {
    position: relative;
    margin-top: 2.2rem;
}
.input-field label.active {
-webkit-transform: translateY(-15px) scale(0.8);
transform: translateY(-15px) scale(0.8);
-webkit-transform-origin: 0 0;
transform-origin: 0 0;
}

</style>
<style type="text/css">
    input[type=file]{
      display: inline;
    }
    #image_preview{
      border: 1px solid rgba(0,0,0,.125);
      padding: 10px;
    }
    #image_preview img{
      width: 200px;
      padding: 5px;
    }
  </style>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('public/css/image-uploader.min.css') }}" >
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ads Details</div>

                <div class="card-body">
                    <form method="post" action="{{route('ads.post.add')}}" enctype="multipart/form-data">
                        @csrf
                        <div id="tag"></div>
                        <div class="form-group row">
                            <label for="category" class="col-sm-3 col-form-label">Category<sup>*</sup></label>
                            <div class="col-sm-9">
                                <select class="  custom-select my-1 mr-sm-2 @error('category') is-invalid @enderror" id="category" name="category" required >
                                    <option value="">--- Select Category ---</option>
                                    @foreach ($parent as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" id="sub"style="display: none; ">
                            <label for="sub-category" class="col-sm-3 col-form-label @error('sub-category') is-invalid @enderror">Sub-Category<sup>*</sup></label>
                            <div class="col-sm-9">
                                <select class="custom-select my-1 mr-sm-2" id="sub-category" name="sub-category" required>
                                
                                </select>
                                @error('sub-category')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">Title<sup>*</sup></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control mb-2 @error('title') is-invalid @enderror" id="title" name="title" placeholder="" required value="{{ old('title') }}">
                                @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-3 col-form-label">Description<sup>*</sup></label>
                            <div class="col-sm-9">
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Required example textarea" required></textarea>
                                @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cities" class="col-sm-3 col-form-label">Cities<sup>*</sup></label>
                            <div class="col-sm-9">
                                <select class="form-control js-states @error('city_name') is-invalid @enderror" name="city_name" id="cities">
                                <option value="">Select a Cities</option>
                                
                                </select>
                                @error('city_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                                
                            </div>
                            
                        </div>
                        <div class="form-group row"  id="not" style="display: none;">
                            <label class="col-sm-3 col-form-label">Others Cities</label>
                            <div class="col-sm-9">
                            <input class="form-control"  name="not_in_list">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="price" class="col-sm-3 col-form-label">Price<sup>*</sup></label>
                            <div class="col-sm-9">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="price"> &#8377;</span>
                                    </div>
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="" aria-label="" aria-describedby="basic-addon1"required>
                                    @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">Tags<sup>*</sup></label>
                            <div class="col-sm-9">
                                <div style="border:1px solid #ced4da;">
                                    <div style="margin: 10px;">
                                    
                                    <input type="text" name="tags" id="tags"placeholder="Write Some Tags" class="form-control tm-input tm-input-info " style="border:none;outline:none;" />
                                    
                                    </div>

                                </div >
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label for="image" class="col-sm-3 col-form-label">Image<sup>*</sup></label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control-file mb-2 @error('image[]') is-invalid @enderror"  value=""id="image" name="image[]"placeholder=""  multiple="" required="">
                                @error('image[]')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <label for="image" class="col-sm-3 col-form-label">Image<sup>*</sup></label>
                            <div class="col-sm-9">
                                <div class="input-field ">
                                    <div class="input-images-1" style="padding-top: .5rem;"></div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group row" id="preview" style="display: none;">
                            <div class="col-sm-12">
                                <div id="image_preview"></div>
                            </div>
                        </div>
                        <div class=" card">
                            <div class="  card-header"><strong> Contact Details</strong></div>
                        </div>
                        </br>
                        <div class="form-group row">
                            <label for="sell-email" class="col-sm-3 col-form-label">Email<sup>*</sup></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control mb-2 @error('sell-email') is-invalid @enderror" value="@guest @else @if(Auth::user()->email !=''){{ Auth::user()->email }}
                                @endif @endguest"id="sell-email" name="sell-email"placeholder="" required>
                                @error('sell-email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sell-phone" class="col-sm-3 col-form-label">Phone<sup>*</sup></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control mb-2 @error('sell-phone') is-invalid @enderror"  value="@guest @else @if(Auth::user()->phone_no !=''){{ Auth::user()->phone_no }}
                                @endif @endguest"id="sell-phone" name="sell-phone"placeholder="" required>
                                @error('sell-phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="information" class="col-sm-3 col-form-label">Information<sup>*</sup></label>
                            <div class="col-sm-9">
                                <input type="checkbox" checked data-toggle="toggle" data-onstyle="outline-success" data-offstyle="outline-danger" data-on="Show" data-off="Hide" name="information" >
                                
                            
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <center>
                                    <button type="submit" class="btn btn-primary">Post</button>
                                </center>
                                
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

    
@endsection
@section('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="category"]').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
                $.ajax({
                    url: 'adspost/categories/'+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('select[name="sub-category"]').empty();
                        $('select[name="sub-category"]').append('<option value=""> --- Select Sub Categories --- </option>');
                        $.each(data, function(key, value) {
                            $('select[name="sub-category"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });


                    }
                });
                 $('#sub').show();
            }else{
                $('select[name="sub-category"]').empty();
            }
        });
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {
        
        function loadertagjs(){
            // DOM: Create the script element
            var jsElm = document.createElement("script");
            // set the type attribute
            jsElm.type = "application/javascript";
            // make the script element load file
            jsElm.src = "{{ asset('public/js/tagmanager.min.js') }}";
            // finally insert the element to the body element in order to load the script
            document.body.appendChild(jsElm);

        }

        $('select[name="sub-category"]').on('change', function() {
            
            var sub = $(this).val();
            if(sub) {
                $.ajax({
                    url: 'adspost/tags/'+sub,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        loadertagjs();
                        /*// DOM: Create the script element
                        var jsElm = document.createElement("script");
                        // set the type attribute
                        jsElm.type = "application/javascript";
                        // make the script element load file
                        jsElm.src = "{{ asset('public/js/tagmanager.min.js') }}";
                        // finally insert the element to the body element in order to load the script
                        document.body.appendChild(jsElm);*/
                        
                        setTimeout(function(){ 
                            var hiddenval=$('input[name="hidden-tags"]').val();
                            if(hiddenval){
                                $(".tm-input").tagsManager('empty');
                            }
                        
                        $.each(data, function(key, value) {
                            $(".tm-input").tagsManager({
                                prefilled: value,                                
                            });
                            $(".tm-input").tagsManager("pushTag", value);
                        });
                        }, 1000);


                    }
                });
                 
            }
        });
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
                    url: "{{ route('autocomplete_cities') }}",
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $.each(data, function(key, value) {
                            $('select[name="city_name"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                         $('select[name="city_name"]').append('<option value="-1">Not In The List</option>');

                    }
                });
    });

</script>


<script src="https://cdn.ckeditor.com/4.13.0/basic/ckeditor.js"></script>

<script>
    CKEDITOR.replace( 'description' );
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
      $("#cities").select2({
          placeholder: "Select a Cities",
          allowClear: true
      });
      
</script>

<script>

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
<script src="{{ asset('public/js/image-uploader.min.js') }}" ></script>

<script>
   $('.input-images-1').imageUploader();
</script>
@endsection