@extends('back.layouts.app')
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<style>
  .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #aaa;
    border-radius: 4px;
    width: 100%;
    height: fit-content;
}  
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
                  <h3 class="box-title">Edit a Custom Field<span></span></h3>
                </div>
                <!-- /.box-header -->
                <form action="{{route('admin.ads.customfield.update')}}" method="post" enctype="multipart/form-data">
                
                    <div class="box-body">
                        
                            @csrf
                            <input type="hidden" name="id" value="{{$id}}" placeholder="Value" class="form-control">
                            <input type="hidden" name="pid" value="{{$fieldid}}" placeholder="Value" class="form-control">
                            <div class="form-group @error('field') has-error  @enderror">
                                <label for="value">Fields:</label>
                                <select class="form-control js-states" name="field" id="field">
                                    <option value=""> --- Select Field --- </option>
                                    @foreach($field as $key => $fields)
                                    <option value="{{$fields->id}}" <?php if ($fields->id == $data->field_id ): ?>
                                        <?php echo "selected" ?> <?php endif ?>>{{$fields->name}}</option>
                                    @endforeach
                                </select>

                                @error('field')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.ads.customfield',$fieldid)}}">Back</a>
                        <button type="submit" class="btn btn-primary">Save</button>
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
    <!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
      $("#field").select2({
          placeholder: "Select a Fields",
          allowClear: true
      });
      
</script>


@endsection