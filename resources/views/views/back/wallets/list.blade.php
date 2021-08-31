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
                  <h3 class="box-title">Add a new Plan<span></span></h3>
                </div>
                <!-- /.box-header -->
                <form action="{{route('admin.wallets.create')}}" method="post" enctype="multipart/form-data">
                
                    <div class="box-body">
                        
                            @csrf
                            <div class="form-group @error('wallet1') has-error  @enderror">
                                <label for="wallet1">Wallet 1:</label>
                                <input type="text" class="form-control" id="wallet1" placeholder="" name="wallet1" value="{{$wallets->wallet1}}">
                                @error('wallet1')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('wallet2') has-error  @enderror">
                                <label for="wallet2">Wallet 2:</label>
                                <input type="number" class="form-control" id="wallet2" placeholder="" name="wallet2" value="{{ $wallets->wallet2 }}">
                                @error('wallet2')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('wallet3') has-error  @enderror">
                                <label for="wallet3">Wallet 3:</label>
                                <input type="number" class="form-control" id="wallet3" placeholder="" name="wallet3" value="{{ $wallets->wallet3 }}">
                                @error('limit')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group @error('wallet4') has-error  @enderror">
                                <label for="wallet4">Wallet 4:</label>
                                <input type="number" class="form-control" id="wallet4" placeholder="" name="wallet4" value="{{ $wallets->wallet4 }}">
                                @error('wallet4')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.plans')}}">Back</a>
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