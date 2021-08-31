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
                  <h3 class="box-title">Add a new countries<span></span></h3>
                </div>
                <!-- /.box-header -->
                
                
                    <div class="box-body">
                        <form action="{{route('admin.location.addcountry')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group @error('name') has-error  @enderror">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{old('name')}}" placeholder="" name="name">
                                @error('name')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('sortname') has-error  @enderror">
                                <label for="sortname">Sort Name:</label>
                                <input type="text" class="form-control " id="sortname" placeholder="" name="sortname" value="{{old('name')}}">
                                @error('sortname')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.location.country')}}">Back</a>
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