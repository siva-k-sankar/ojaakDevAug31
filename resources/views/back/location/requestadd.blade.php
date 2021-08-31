@extends('back.layouts.app')
@section('styles')

    

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
                  <h3 class="box-title">Location Requests</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                
                    <div class="box-body">
                        <form class="form" action="{{route('admin.location.addrequest')}}"method="post">
                            @csrf
                            <input type="hidden" id="post_id" name="post_id" value="{{$check->post_id}}">
                            <input type="hidden" id="id" name="id" value="{{$check->uuid}}">
                            <div class="form-group @error('country') has-error @enderror">
                                <label for="country">Country:</label>
                                <select class="form-control " name="country" id="country">
                                </select>
                                @error('country')
                                    <span class="help-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group @error('state') has-error @enderror">
                                <label for="state">State</label>
                                <select class="form-control " name="state" id="state">
                                </select>
                                @error('state')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('city') has-error @enderror">
                                <label for="city">City</label>
                                <input class="form-control " name="city" value="{{$check->name}}" id="city">
                                @error('city')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" id="submit">Approve</button>
                            </div>
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
<script>
    $(document).ready(function(){
        $.ajax({
                    url: "{{ route('admin.location.autocompletecountry') }}",
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('select[name="country"]').append('<option value="">--- Select Country ---</option>');
                        $.each(data, function(key, value) {
                            $('select[name="country"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                         

                    }
                });
        
    });
   $('select[name="country"]').on('change', function() {
            var countryID = $(this).val();
            var routeurl='{{route("admin.location.autocompletestate",":countryID")}}';
            routeurl = routeurl.replace(':countryID',countryID);
            //alert(routeurl);
            if(countryID) {
                $.ajax({
                    url: routeurl,
                    //url: "{{ url('admin/location/request/state/') }}/"+countryID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        
                        $('select[name="state"]').empty();
                        $('select[name="state"]').append('<option value="">--- Select State ---</option>');
                        $.each(data, function(key, value) {
                            $('select[name="state"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });


                    }
                });
                 
            }else{
                $('select[name="state"]').empty();
                $('select[name="state"]').append('<option value="">--- No Data--- </option>');
            }
        });
</script>



@endsection