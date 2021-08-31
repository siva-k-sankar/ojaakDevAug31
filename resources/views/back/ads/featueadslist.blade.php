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
       Platinum Ads List
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
                
                <!-- /.box-header -->
                <!-- form start -->
                
                    <div class="box-body">
                        <table id="featuredadslist" class="table table-bordered " width="100%">
                            <thead>
                                <tr>
                                    <th>Platinum Ads Position</th>
                                    <th>Ads Title</th>
                                    <th>User Name</th>
                                    <th>Expire date</th>
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
        featuredads();
        function featuredads(){
            $('#featuredadslist').DataTable({
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
                    "url":"<?php echo url('admin/ads/feature/view'); ?>",
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
                      "data": "id"
                    }, {
                        "data": "adsname"
                    }, {
                        "data": "username"
                    }, {
                        "data": "expiredate"
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
        }
    });
    
        function expireplan(id){
            var _token ="{{ csrf_token() }}";
            swal({
            title: "Are you sure?",
            text: "Do you want to change the status of the plan!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '<?php echo url('admin/ads/feature/expire'); ?>',
                        data:"featureddata="+id+"&_token="+_token,
                        type: "post",
                        success:function(data) {
                            console.log(data);
                            if(data==1){
                                toastr.success('Success!', 'Feature Ads Remove')
                                setTimeout(function(){ location.reload(); }, 1000);
                            }else{
                                toastr.error('Failed!', 'Feature Ads Not Remove ')
                            }
                        }
                    });
                }
            });
        }
        
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection