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
                  <h3 class="box-title"><a class="btn btn-primary" href="{{route('admin.paidadsplan.create')}}">Add Plans</a><!-- <a class="btn btn-primary" href="{{route('admin.plans.history')}}" style="margin: 3px;">Plans History</a> --></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                
                    <div class="box-body">
                        <table id="paidplans" class="table table-bordered " width="100%">
                            <thead>
                                <tr>
                                    <th>Cateory</th>
                                    <th>Plan Name</th>
                                    <th>Validity</th>
                                    <th>Points</th>
                                    <th>Quantity_1(Rs.)</th>
                                    <th>Quantity_3(Rs.)</th>
                                    <th>Quantity_5(Rs.)</th>
                                    <th>Quantity_10(Rs.)</th>
                                    <th>Discount</th>
                                    <th>Comments</th>
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
        $('#paidplans').DataTable({
            "processing": true,
            "serverSide": true,
            //"ordering": false,
            "scrollX":true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "dom": 'Bfrtip',
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "ajax": {
                "url":"<?php echo url('admin/paidadsplansview'); ?>",
                "type": "POST",
                "data": function(d) {
                   // var dump = JSON.parse(d);
                  // alert(JSON.parse(d));
                  // alert("ajax success!")
                    /*d.fromdate = $('#fromdate').val();
                    d.todate = $('#todate').val();*/
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [
                {
                  "data": "category"
                }, {
                    "data": "name"
                }, {
                    "data": "validity"
                }, {
                    "data": "points"
                },{
                    "data": "quantity_1"
                },{
                    "data": "quantity_3"
                },{
                    "data": "quantity_5"
                }, {
                    "data": "quantity_10"
                },{
                    "data": "discount"
                },{
                    "data": "comments"
                },{
                    "data": "status"
                },{
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
            text: "Do you want to change the status of the plan!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
              if (willDelete) {
                document.getElementById('status-plan-'+id).submit();
                
              } else {
                swal("Plan status is not changed!");
              }
            });
        }
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection