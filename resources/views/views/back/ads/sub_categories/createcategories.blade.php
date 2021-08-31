@extends('back.layouts.app')
@section('styles')
<style>
    
</style>
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/tagmanager/3.0.2/tagmanager.min.css"> -->
<link rel="stylesheet" type="text/css" href="{{asset('public/back/css/tagmanager.min.css')}}">
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
                  <h3 class="box-title">Add New Sub Category<span></span></h3>
                </div>
                <!-- /.box-header -->
                
                
                    <div class="box-body">
                        <form action="{{route('admin.ads.addsubcategories')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="id" name="parentid" value="{{$id}}">
                            <div class="form-group">
                                <label for="name">Name :</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="desc">Description :</label>
                                <textarea class="form-control" name="desc" rows="5" id="desc"></textarea>
                                @error('desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- <div class="form-group">
                                <label for="tag">Tags :</label>
                                <div style="border:1px solid #ced4da;">
                                    <div style="margin: 10px;">
                                        <input type="text" name="tag" id="tag"placeholder="Write Some Tags" class="form-control tm-input tag tm-input-info " style="border:none;outline:none;" />
                                    </div>
                                 </div>
                                @error('hidden-tags')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> -->

                            <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.ads.subcategories',$id)}}">Back</a>
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
<script src="{{ asset('public/back/js/tagmanager.min.js') }}" ></script>
    
  
<script type="text/javascript">
        $(".tm-input").tagsManager();
</script>

@endsection