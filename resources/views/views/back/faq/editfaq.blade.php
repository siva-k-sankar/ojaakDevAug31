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
        FAQ
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Edit FAQ<span></span></h3>
                </div>
                <!-- /.box-header -->
                <form action="{{route('admin.faq.update')}}" method="post" enctype="multipart/form-data">
                
                    <div class="box-body">
                        
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{$data->id}}">
                            
                            <div class="form-group @error('questions') has-error  @enderror">
                                <label for="questions">Questions:</label>
                                <input type="text" class="form-control" id="questions" placeholder="" name="questions" value="{{$data->questions}}">
                                @error('questions')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('answers') has-error  @enderror">
                                <label for="answers">Answers:</label>
                                <textarea class="form-control" id="answers" placeholder="" name="answers" required="" row="10">{{$data->answers}}</textarea>
                                @error('answers')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.management')}}">Back</a>
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
<script src="//cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('answers',{
        height:250,
        filebrowserUploadUrl:"{{route('admin.management.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
    });
</script>

@endsection