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
        Location
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
                  <h3 class="box-title">Add a New State<span></span></h3>
                </div>
                <!-- /.box-header -->
                
                
                    <div class="box-body">
                        <form action="{{route('admin.location.addstate')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="id" name="country-uuid" value="{{$id}}">
                            <div class="form-group @error('name') has-error  @enderror">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{old('name')}}" placeholder="" name="name">
                                @error('name')
                                    <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.location.state',$id)}}">Back</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>   
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        
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



@endsection