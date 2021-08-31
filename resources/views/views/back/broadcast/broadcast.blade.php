@extends('back.layouts.app')

@section('styles')
<style>
.box-info{
	margin-top: 18px;
	margin-left: 15px;
	width: 97.8%;
}
</style>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" defer type="text/javascript"></script>
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header"><h1>Broadcast Message / Announcement </h1></section>
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                ADD Broadcast Message
                            </h3>
                        </div>
                        <div class="box-body">
                            <form action="{{route('admin.broadcast.send')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="box-body">
                                    <div class="form-group @error('message') has-error  @enderror">
                                        <label for="message">Message:</label>
                                        <textarea  class="form-control" id="message" maxlength="2000" rows="10" placeholder="" name="message" value="" autocomplete="off" required="">{{old('message')}}</textarea>
                                        @error('message')
                                            <span class="help-block" role="alert">
                                                <strong class="text-red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group @error('date') has-error  @enderror">
                                        <label for="date">Date:</label>
                                        <input  name="date" id="date" autocomplete="off" required="">
                                        @error('date')
                                            <span class="help-block" role="alert">
                                                <strong class="text-red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <!-- <span class="help-block" role="alert">
                                        <strong>Note: Date is only allow Feature date only.</strong>
                                    </span> -->
                                </div>
                            <!-- /.box-body -->
                                <div class="box-footer">
                                    <button class="btn btn-primary" type="submit">Save Broadcast Message</button>
                                </div>
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </section>
	    
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    All BroadCast Message
                </h3>
            </div>
            <div class="box-body">
                <table id="broadCast" class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Createdby</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>    
            </div>
        </div>
</div>
@endsection

@section('scripts')
<script>

	$(document).ready(function() {
        
        $('#date').datepicker({
            uiLibrary: 'bootstrap',
            format: 'yyyy-mm-dd',
            /*disableDates: function(date) {
                const currentDate = new Date();
                return date > currentDate ? true : false;
            }*/
        });

        maxLength = $("textarea#message").attr("maxlength");
        $("textarea#message").after("<div><span id='remainingLengthTempId'>"
                  + maxLength + "</span> Character(s) Remaining</div>");

        $("textarea#message").bind("keyup change", function(){checkMaxLength(this.id,  maxLength); } );

        function checkMaxLength(textareaID, maxLength){

            currentLengthInTextarea = $("#"+textareaID).val().length;
            $(remainingLengthTempId).text(parseInt(maxLength) - parseInt(currentLengthInTextarea));

            if (currentLengthInTextarea > (maxLength)) {

                // Trim the field current length over the maxlength.
                $("textarea#message").val($("textarea#message").val().slice(0, maxLength));
                $(remainingLengthTempId).text(0);

            }
        }

        $('#broadCast').DataTable({
            "processing": true,
            "serverSide": true,
            "ordering": false,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"dom": 'Bfrtip',
	        "buttons": ['copy', 'csv', 'excel', 'pdf', 'print'],
            "ajax": {
                "url":"<?php echo url('admin/broadcast/details'); ?>",
                "type": "post",
                "data": function(d) {
                	d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
	                "data": "date"
	            }, {
	                "data": "created_by"
	            },{
                    "data": "message"
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
</script>
<script>
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
</script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection