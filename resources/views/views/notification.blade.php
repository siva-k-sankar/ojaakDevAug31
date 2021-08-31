@extends('layouts.home')
@section('styles')


@endsection

@section('content')
<div class="container">
	<div class="notification_outer_row_wrap">
		<h4>Notification</h4>
		<button type="button" class="btn btn-danger deletenotifi">Delete All Notification</button>
		<div class="noti_table_outer_wrap table-responsive">
			<table class="table table-striped table-no-bordered text-nowrap table-hover" cellspacing="0" style="width:100%">
                <thead>
	                <tr>
	                	<th><div class="checkbox">
						    <label><input type="checkbox"  name="selectall" id="selectall" class="selectall" value="0"> Select All</label>
						</div></th>
						<th>S.no</th>
	                	<th scope="col">Message</th>
						<th scope="col">Received At</th>
						<th scope="col">Action</th>
	                </tr>
                </thead>
                <tbody>
                	<?php $i=0;?>
					@foreach($notification as $notify)
					<?php $i++; ?>
					<tr>
						<th><input type="checkbox" class="single"  name="notifi_id[]" value="{{$notify->id}}"></th>
						<th scope="row">{{$i}}</th>
						<td>{{$notify->message}}</td>
						<td>{{time_elapsed_string($notify->created_at)}}</td>
						<td><button style="margin:3px;" class=" btn bg-red delete_btn_out_wrap " onclick="notiDelete('{{$notify->id}}')"><i class="fa fa-trash"></i></button></td>
						<form action="{{route('noti.delete',$notify->id)}}" method="POST" id="delete-notification-{{$notify->id}}">
		                	@csrf
		                </form>
					</tr>
					@endforeach
					@if($i==0)
					<tr>
						<th colspan="3">Notification not available</th>
					@endif
                </tbody>
            </table>
		</div>
	</div>
</div>


@endsection         
@section('scripts')
	<script>
    function notiDelete(id){
        swal({
        title: "Are you sure do you want to delete?",
        text: "You won't be able to revert this!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        }).then((willDelete) => {
        	console.log(id);
          if (willDelete) {
            document.getElementById('delete-notification-'+id).submit();
            
          } else {
            swal("Notification Not Deleted!");
          }
        });
    }

    $(".deletenotifi").click(function(){
        	var notifidata = [];
            $.each($("input[name='notifi_id[]']:checked"), function(){
                notifidata.push($(this).val());
            });
            if (notifidata.length === 0) {
			    swal({
				  title: "Warning!",
				  text: "No Selected Notification!",
				  icon: "warning",
				  button: "OK",
				});
			}else{
				var tok="{{ csrf_token() }}";
				swal({
					title: "Are you sure Delete?",
					icon: "warning",
					buttons: true,
					dangerMode: true,
					})
					.then((willDelete) => {
					if (willDelete) {
						$.ajax({
		                    type: "post",
		                    url: "<?php echo url('notification/deleteall'); ?>",
		                    data: "deletenotifidata="+notifidata+"&_token="+tok, 
		                    success: function(data){
		                    	//alert(data);
		                    	if(data==1){
		                    		swal("Delete Notification Successfully!", "success");
		                    	}else if(data==0){
		                    		swal("Delete Notification Failed!", "warning");
		                    	}
		                    }
		                });
					} else {
						swal("Your Records is safe!");
						
					}
				});
				//alert("Selected data  are: " + selectdata);
			}
        });

    $('.selectall').click(function(){
            if($(this).prop("checked") == true){
                $(".single").prop("checked", true);
            }
            else if($(this).prop("checked") == false){
                $(".single").prop("checked", false);
            }
        });
</script>
@endsection