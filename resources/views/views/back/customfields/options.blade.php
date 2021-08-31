@extends('back.layouts.app')
@section('styles')

    

@endsection
@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       CustomField
      </h1>
      
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
                  <h3 class="box-title"><a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.customfield')}}">Back Custom Fields</a><a class="btn btn-primary" href="{{route('admin.customfield.option.create',$field->uuid)}}">Add Options  &nbsp;&nbsp; <i class="fa fa-arrow-right"></i>&nbsp;&nbsp;  {{$field->name}}</a></h3>
                  <input type="hidden" name="id" id="id" value="{{$field->uuid}}"  class="form-control">
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                
                    <div class="box-body">
                        <table id="categories" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Name</th>
                                    <th>Created By</th>
                                    <th>Modified Date</th>
                                    <th>Created Date</th>
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
        var id=$('#id').val();
        $('#categories').DataTable({
        
        "processing": true,
        responsive: true,
        "ajax": "<?php echo url('admin/customField/options/view'); ?>/"+id,
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
                document.getElementById('del-plan-'+id).submit();
                swal("Plan has been deleted!", {
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
                document.getElementById('status-plan-'+id).submit();
                
              } else {
                swal("Your file is safe!");
              }
            });
        }
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection