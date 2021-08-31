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
                <form action="{{route('admin.paidplans.update')}}" method="post" enctype="multipart/form-data">
                
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
                            <input type="hidden" name="uuid" value="{{$data->uuid}}">
                            <div class="form-group @error('name') has-error  @enderror">
                                <label for="name">Plan Name:</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name" value="{{$data->plan_name }}">
                                @error('name')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('validity') has-error  @enderror">
                                <label for="validity">Validity:</label>
                                <input type="number" class="form-control" id="validity" placeholder="" name="validity" value="{{$data->validity}}">
                                @error('validity')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('points') has-error  @enderror">
                                <label for="points">Wallet points:</label>
                                <input type="text" class="form-control" id="points" placeholder="" name="points" value="{{$data->wallet_points}}">
                                @error('points')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('quantity_1') has-error  @enderror">
                                <label for="quantity_1">Quantity_1:(<i class="fa fa-inr"></i>)</label>
                                <input type="text" class="form-control" id="quantity_1" placeholder="" name="quantity_1" value="{{ $data->quantity_1 }}">
                                @error('quantity_1')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                             <div class="form-group @error('quantity_3') has-error  @enderror">
                                <label for="quantity_3">Quantity_3:(<i class="fa fa-inr"></i>)</label>
                                <input type="text" class="form-control" id="quantity_3" placeholder="" name="quantity_3" value="{{ $data->quantity_3}}">
                                @error('quantity_3')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                             <div class="form-group @error('quantity_5') has-error  @enderror">
                                <label for="quantity_5">Quantity_5:(<i class="fa fa-inr"></i>)</label>
                                <input type="text" class="form-control" id="quantity_5" placeholder="" name="quantity_5" value="{{ $data->quantity_5 }}">
                                @error('quantity_5')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                             <div class="form-group @error('quantity_10') has-error  @enderror">
                                <label for="quantity_10">Quantity_10:(<i class="fa fa-inr"></i>)</label>
                                <input type="text" class="form-control" id="quantity_10" placeholder="" name="quantity_10" value="{{ $data->quantity_10 }}">
                                @error('quantity_10')
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
                        <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.paidadsplan')}}">Back</a>
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