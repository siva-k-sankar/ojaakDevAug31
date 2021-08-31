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
        Users
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
                  <h3 class="box-title">Add a new admin users<span></span></h3>
                </div>
                <!-- /.box-header -->
                <form action="{{route('admin.savesubadmin')}}" method="post">
                
                    <div class="box-body">                        
                        @csrf
                        <div class="form-group @error('name') has-error  @enderror">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" placeholder="" name="name" value="{{old('name')}}">
                            @error('name')
                                <span class="help-block" role="alert">
                                    <strong class="text-red">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group @error('email') has-error  @enderror">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control" id="email" placeholder="" name="email" value="{{old('email')}}">
                            @error('email')
                                <span class="help-block" role="alert">
                                    <strong class="text-red">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group @error('password') has-error  @enderror">
                            <label for="password">Password:</label>
                            <input type="text" class="form-control" id="password" placeholder="" name="password" value="{{old('password)}}">
                            @error('password')
                                <span class="help-block" role="alert">
                                    <strong class="text-red">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select name="role" id="role" class="form-control">               
                                <option value="">Select Role</option>   
                                @if(!empty($roles))
                                    @foreach($roles as $role)   
                                        <option value="{{$role->id}}" <?php if(old('role') == $role->id){echo "selected";} ?>>{{$role->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.adminusers')}}">Back</a>
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