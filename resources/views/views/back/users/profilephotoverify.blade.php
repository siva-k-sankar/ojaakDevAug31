@extends('back.layouts.app')
@section('styles')

@endsection
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Profile Photo verification</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-6">
	          	<div class="box  box-info">
	            <div class="box-header">
	              <h3 class="box-title">Un Verified Profile Photo</h3>
	            </div>
	            <!-- /.box-header -->
		            <div class="box-body ">
                      <center>
                        <img class="img-responsive pad" src="{{asset('public/uploads/profile/mid/')}}/{{$users->photo_temp}}" >
                      </center>
		            </div>
		            <!-- /.box-body -->
                    <div class="box-footer ">
                        <center>
                            <a href="{{ URL::previous() }}" class="btn bg-maroon btn-flat margin">Back</a>
                            <button type="button" onclick="verify()" class="btn bg-olive btn-flat margin">Verified</button>
                            <button type="button"  onclick="cancel()" class="btn bg-navy btn-flat margin">Cancel</button>
                            <form method="post"action="{{route('admin.users.photoverify')}}" id="verified">
                                @csrf
                                <input type="hidden" name="id" value="{{$users->uuid}}">
                            </form>
                            <form method="post"action="{{route('admin.users.photounverify')}}" id="cancel">
                                @csrf
                                <input type="hidden" name="id" value="{{$users->uuid}}">
                            </form>
                        </center>
                    </div>
	          	</div>
	          	<!-- /.box -->
	          	
	        </div>
	        
	        <!-- /.col -->
	    </div>
	    <!-- /.row -->
    </section>
    <!-- /.content -->
    
</div>
@endsection

@section('scripts')
<script>
    function verify(){
        swal({
                  title: "Are you sure?",
                  text: "Once verified, you will not be able to recover this file!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                    document.getElementById("verified").submit();
                    swal("Verified !", {
                      icon: "success",
                    });
                  } 
                });
    }

    function cancel(){
        swal({
                  title: "Are you sure?",
                  text: "Once unverified, you will not be able to recover this file!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                    document.getElementById("cancel").submit();
                    swal("Unverified !", {
                      icon: "success",
                    });
                  } 
                });
    }
     
</script>
@endsection