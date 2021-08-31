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
                <form action="{{route('admin.customfield.update')}}" method="post" enctype="multipart/form-data">
                
                    <div class="box-body">
                        
                            @csrf
                            <input type="hidden" class="form-control" id="id" placeholder="" name="id" value="{{$data->uuid}}">
                            <div class="form-group @error('name') has-error  @enderror">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name" value="{{$data->name}}">
                                @error('name')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('subcategory') has-error  @enderror">
                                <label for="name">Sub Category:</label>
                                <select class="form-control" name="subcategory" readonly>
                                    <option value="{{$data->subcategory}}">{{get_S_Cate_Name($data->subcategory)}}</option>
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
                                    <option value="text" <?php if ($data->type == "text" ): ?>
                                        <?php echo "selected" ?> <?php endif ?> >text</option>
                                    <option value="number" <?php if ($data->type == "number" ): ?>
                                        <?php echo "selected" ?> <?php endif ?>>number</option>
                                    <option value="date" <?php if ($data->type == "date" ): ?>
                                        <?php echo "selected" ?> <?php endif ?>>date</option>
                                    <option value="textarea" <?php if ($data->type == "textarea" ): ?>
                                        <?php echo "selected" ?> <?php endif ?> >textarea</option>
                                    <option value="checkbox" <?php if ($data->type == "checkbox" ): ?>
                                        <?php echo "selected" ?> <?php endif ?> >checkbox</option>
                                    <option value="checkbox_multiple"<?php if ($data->type == "checkbox_multiple" ): ?>
                                        <?php echo "selected" ?> <?php endif ?> >checkbox_multiple</option>
                                    <option value="select" <?php if ($data->type == "select" ): ?>
                                        <?php echo "selected" ?> <?php endif ?> >select</option>
                                    <option value="radio"<?php if ($data->type == "radio" ): ?>
                                        <?php echo "selected" ?> <?php endif ?> >radio</option>
                                </select>
                
                                @error('type')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('max') has-error  @enderror">
                                <label for="max">Field Length:</label>
                                <input type="text" name="max" value="{{$data->max}}" placeholder="Field Length" class="form-control">
                                @error('max')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('default') has-error  @enderror">
                                <label for="default">Default Value:</label>
                                <input type="text" name="default" value="{{$data->default}}" placeholder="Default value" class="form-control">
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
                                      <input type="checkbox" value="1" <?php if($data->required == 1){
                                        echo"checked";} ?> name="required"> Required
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