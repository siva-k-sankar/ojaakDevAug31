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
        Categories
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
                  <h3 class="box-title">Add a new category<span class="pull-right"></span></h3>
                  <h3 class="box-title pull-right"><a style="" target="_blank"class="text-olive" href="{{route('admin.icons')}}">Icon Link</a></h3>
                </div>
                <!-- /.box-header -->
                
                
                    <div class="box-body">
                        <form action="{{route('admin.ads.addcategories')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="desc">Description:</label>
                                <textarea class="form-control" name="desc" rows="5" id="desc"></textarea>
                                @error('desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- <div class="form-group">
                                <label for="icon">Icon Name :</label>
                                <input type="text" class="form-control" id="icon" placeholder="" name="icon">
                                @error('icon')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> -->
                            <div class="form-group">
                                <label for="picture" class="">Image</label>
                                <input type="file" class="form-control-file " name="image" accept="image/svg+xml" id="picture" >
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group " id="hidden-preview" style="display: none";>
                                 
                            <img src="" id="picturepreview"alt="picture" width="200" height="200" class="img-responsive">
                            </div>
                            <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.ads.categories')}}">Back</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>   
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer ">
                        <span class="help-block text-red">Notes:</span>
                        <span class="help-block text-red">Upload Only Svg Format Files Only</span>
                    </div>
                
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


@endsection