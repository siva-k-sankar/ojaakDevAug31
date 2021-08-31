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
      <input type="hidden" id="id" name="id" value="{{$id}}">
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
                  <h3 class="box-title"><a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.location.state',$data->uuid)}}">Back</a><a  style="margin: 3px;"class="btn btn-primary" href="{{route('admin.location.createcities',$id)}}">Add Cities</a></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                
                    <div class="box-body">
                        <table id="categories" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Cities Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>    
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
        var id =$('#id').val();
        var url ="<?php echo url('admin/location/cities/view'); ?>"+"/"+id;
        $('#categories').DataTable({
        "processing": true,
        responsive: true,
        "ajax": url,
        "deferRender": true
    });
    });
    function deletecate(id){
            
            swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                document.getElementById('del-cate-'+id).submit();
                swal("categories has been deleted!", {
                  icon: "success",
                });
              } else {
                swal("Your file is safe!");
              }
            });
        }
        function status(id){
            
            swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                document.getElementById('status-cate-'+id).submit();
                
              } else {
                swal("Your file is safe!");
              }
            });
        }
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection