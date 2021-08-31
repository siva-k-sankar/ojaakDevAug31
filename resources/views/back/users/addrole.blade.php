@extends('back.layouts.app')
@section('styles')

    <style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
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

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                
                <form action="{{route('admin.role.add')}}" method="post">
                
                    <div class="box-body">                        
                        @csrf
                        <div class="form-group @error('role') has-error  @enderror">
                            <label for="role">Name:</label>
                            <input type="text" class="form-control" id="role" placeholder="" name="role" value="">
                            @error('role')
                                <span class="help-block" role="alert">
                                    <strong class="text-red">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <table class="table table-bordered">
                                  <thead>
                                    <tr>
                                      <th scope="col">Pages</th>
                                      <th scope="col">Allow All</th>
                                      <th scope="col">View</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @if(count($getSitePages)>0)
                                        @foreach($getSitePages as $key=>$page)

                                        <tr>
                                            <td>{{$page}}</td>
                                            <td>
                                                <label class="switch">
                                                <input type="checkbox" name="allowallaccess[{{$key}}]" class="allowallaccess" value="1"  data-pageId="{{$key}}">
                                                <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>                                                    
                                                <label class="switch">
                                                <input type="checkbox" name="viewaccess[{{$key}}]"  class="viewaccess{{$key}}"  value="1">
                                                <span class="slider round"></span>
                                                </label>
                                            </td>
                                        </tr>

                                        @endforeach
                                    @endif
                                    <!-- <tr>
                                      <th scope="row">2</th>
                                      <td>Jacob</td>
                                      <td>Thornton</td>
                                      <td>@fat</td>
                                    </tr> -->
                                  </tbody>
                                </table>
                            </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.listroles')}}">Back</a>
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



        $(".allowallaccess").change(function() {
            //alert($(this).attr("data-pageId"))
            var pageId = $(this).attr("data-pageId");
            $(".viewaccess"+pageId).attr('checked','checked');
            
        });

    });
</script>



@endsection