@extends('back.layouts.app')
@section('styles')
<style>
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  background: none;
  color: black!important;
  border-radius: 4px;
  border: 1px solid #828282;
}
 
.dataTables_wrapper .dataTables_paginate .paginate_button:active {
  background: none;
  color: black!important;
}
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
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><a class="btn btn-primary" href="{{route('admin.plans.create')}}">Add Plans</a><a class="btn btn-primary" href="{{route('admin.plans.history')}}" style="margin: 3px;">Plans History</a></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                
                    <div class="box-body">
                        <table id="plans" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Plan Price ( Rs )</th>
                                    <th>No Ads</th>
                                    <th>Modified User</th>
                                    <th>Modified Date</th>
                                    <th>Created Date</th>
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
        $('#plans').DataTable({
            "processing": true,
            "serverSide": true,
            //"ordering": false,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "dom": 'Bfrtip',
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "ajax": {
                "url":"<?php echo url('admin/plans/view'); ?>",
                "type": "POST",
                "data": function(d) {
                    d.fromdate = $('#fromdate').val();
                    d.todate = $('#todate').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
                    "data": "name"
                }, {
                    "data": "price"
                }, {
                    "data": "plan_Ads"
                }, {
                    "data": "createdby"
                },{
                    "data": "updated"
                },{
                    "data": "create"
                },{
                    "data": "status"
                }, {
                    "data": "action"
                },
            ],
            "order": [
                [0, 'desc']
            ],
            createdRow: function(row, data, index) {
                //console.log("a",data['status']);
                $('td', row).eq(4).addClass('td-actions'); // 6 is index of column
                if ( data['status'] == "" ) {
                    $(row).css("opacity", "0.2");
                }
            },
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