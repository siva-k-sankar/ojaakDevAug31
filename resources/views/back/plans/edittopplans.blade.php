@extends('back.layouts.app')
@section('styles')
<style>
    
</style>

@endsection
@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Plans
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
              <!-- general form elements -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Edit Plan<span></span></h3>
                </div>
                <!-- /.box-header -->
                <form action="{{route('admin.topplans.update')}}" method="post" enctype="multipart/form-data">
                
                   <div class="box-body">
                        
                            @csrf
                            <div class="form-group @error('category') has-error  @enderror">
                                <label for="caategory">Category:</label>
                                <input type="text" class="form-control" id="caategory" placeholder="" name="category" value="{{ $data->category }}">
                                @error('category')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('name') has-error  @enderror">
                                <label for="name">Plan Name:</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name" value="{{ $data->name }}">
                                @error('name')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('type') has-error  @enderror">
                                <label for="type">Plan Type:</label>
                                <select id="type"  name="type"class="form-control">
                                    <option value="1" {{ $data->type == 1 ? 'selected' : '' }} >Platinum</option>
                                    <option value="2" {{ $data->type == 2 ? 'selected' : '' }} >Featured</option>
                                </select>
                                @error('type')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <input type="hidden" name="uuid" value="{{$data->uuid}}">
                            <div class="form-group @error('validity_7') has-error  @enderror">
                                <label for="validity_7">7 day validity:</label>
                                <input type="number" class="form-control" id="validity_7" placeholder="" name="validity_7" value="{{ $data->validity_7 }}">
                                @error('validity_7')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('validity_15') has-error  @enderror">
                                <label for="validity_15">15 day validity:</label>
                                <input type="number" class="form-control" id="validity_15" placeholder="" name="validity_15" value="{{ $data->validity_15 }}">
                                @error('validity_15')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div><div class="form-group @error('validity_30') has-error  @enderror">
                                <label for="validity_30">30 day validity:</label>
                                <input type="number" class="form-control" id="validity_7" placeholder="" name="validity_30" value="{{ $data->validity_30 }}">
                                @error('validity_30')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('discount') has-error  @enderror">
                                <label for="discount">Discount:(<i class="fa fa-inr"></i> / <i class="fa fa-percent"></i>)</label>
                                <input type="text" class="form-control" id="discount" placeholder="" name="discount" value="{{ $data->discount }}">
                                @error('discount')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('comment') has-error  @enderror">
                                <label for="comment">Comment:</label>
                                <textarea class="form-control" id="comment" placeholder="" name="comment">{{$data->comments}}</textarea> 
                                @error('comment')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                         <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.topadsplan')}}">Back</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>   
              </div>
              <!-- /.box -->
            </div>
        <!--/.col (left) -->
        </div>
        <!-- /.row -->
      
    </section>
    <!-- /.content -->
  </div>
  
    

@endsection
@section('scripts')
<script>
    $(document).ready(function(){
            function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#picturepreview').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#picture").change(function() {
  readURL(this);
  $('#hidden-preview').show();
});
    });
</script>
<script src="https://cdn.ckeditor.com/4.13.0/basic/ckeditor.js"></script>

<script>
    CKEDITOR.replace( 'comment' );
</script>

@endsection