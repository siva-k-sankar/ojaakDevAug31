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
        Custom Fields
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
                  <h3 class="box-title">Add a new custom field<span></span></h3>
                </div>
                <!-- /.box-header -->
                <form action="{{route('admin.customfield.add')}}" method="post" enctype="multipart/form-data">
                
                    <div class="box-body">
                        
                            @csrf
                            <div class="form-group @error('name') has-error  @enderror">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('subcategory') has-error  @enderror">
                                <label for="type">Sub Category:</label>
                                <select class="form-control" name="subcategory">
                                    @foreach($subcategory as $category)
                                    <option value="{{$category->id}}">{{ucwords($category->name)}} -  [{{get_P_Cate_Name($category->parent_id)}}]</option>
                                    @endforeach
                                </select>
                
                                @error('subcategory')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('type') has-error  @enderror">
                                <label for="type">Type:</label>
                                <select class="form-control" name="type">
                                    <option value="text">text</option>
                                    <option value="date">date</option>
                                    <option value="number">number</option>
                                    <option value="textarea">textarea</option>
                                    <option value="checkbox">checkbox</option>
                                    <option value="checkbox_multiple">checkbox_multiple</option>
                                    <option value="select">select</option>
                                    <option value="radio">radio</option>
                                    <!-- <option value="file">file</option> -->
                                </select>
                
                                @error('type')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('max') has-error  @enderror">
                                <label for="max">Field Length:</label>
                                <input type="text" name="max" value="{{ old('name') }}" placeholder="Field Length" class="form-control">
                                @error('max')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('default') has-error  @enderror">
                                <label for="default">Default Value:</label>
                                <input type="text" name="default" value="{{ old('name') }}" placeholder="Default value" class="form-control">
                                @error('default')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                      <input type="hidden" name="required" value="0">
                                      <input type="checkbox" value="1" name="required"> Required
                                    </label>
                                </div>
                            </div>
                            
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.customfield')}}">Back</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
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


@endsection