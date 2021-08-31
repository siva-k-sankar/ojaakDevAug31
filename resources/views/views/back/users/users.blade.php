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
tfoot input {
    width: 100%;
    padding: 3px;
    box-sizing: border-box;
}
</style>
<style>
    .color-palette {
      height: 35px;
      line-height: 35px;
      text-align: center;
    }

    .color-palette-set {
      margin-bottom: 15px;
    }

    .color-palette span {
      display: none;
      font-size: 12px;
    }

    .color-palette:hover span {
      display: block;
    }

    .color-palette-box h4 {
      position: absolute;
      top: 100%;
      left: 25px;
      margin-top: -40px;
      color: rgba(255, 255, 255, 0.8);
      font-size: 12px;
      display: block;
      z-index: 7;
    }
    th,td{
    	text-align: center;
    }
</style>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript" defer></script>
@endsection
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Users</h1>
    </section>
    <!-- Main content -->
    <section class="content">
	     <div class="row">
	        <div class="col-xs-12">
	          	<div class="box  box-info">
	            <div class="box-header">
	              <h3 class="box-title">Users</h3>
	            </div>
	            <!-- /.box-header -->
		            <div class="box-body">
		            	<form class="form-inline" >
						  <div class="checkbox">
						    <label><input type="radio" name="userfillter" checked class="verified" value="1"> ALL</label>
						  </div>
						  <div class="checkbox">
						    <label><input type="radio"name="userfillter" class="verified" value="2"> Verified User</label>
						  </div>
						  <div class="checkbox">
						    <label><input type="radio" name="userfillter" class="unverified" value="3"> Un Verified User</label>
						  </div>
						</form>
						</br>
						<div class="nav-tabs-custom user-fillter-form" style="display: none;">
				            <ul class="nav nav-tabs">
				              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Un Verified User</a></li>
				            </ul>
				            <div class="tab-content">
				              	<div class="tab-pane active" id="tab_1">
				              		<div class="row">
					              		<div class="col-sm-4">
						              		<form role="form">
												<div class="box-body">
													<div class="form-group">
									                  <label for="exampleInputEmail1">From date</label>
									                  <input id="from_date_pick" class="form-control">
									                </div>
													<div class="form-group">
									                  <label for="exampleInputEmail1">To date</label>
									                  <input id="to_date_pick" class="form-control">
									                </div>
												</div>
												<!-- /.box-body -->
												<div class="box-footer">
													<button type="button" class="btn btn-default filterleaddata">Fillter</button>
													<button type="button" class="btn btn-danger deleteuser">Delete User</button>
												</div>
												<!-- /.box-footer -->
											</form>
										</div>
									</div>
					            	<!-- <div class="checkbox">
									    <label><input type="checkbox"  name="selectall" id="selectall" value="0"> select All</label>
									</div>
									<button type="button" class="getvalue">Get Values</button> -->
					            </div>
				              
				            </div>
				            <!-- /.tab-content -->
				        </div>
						<table id="user_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" style="width:100%">
			                <thead>
				                <tr>
				                	<th><div class="checkbox">
									    <label><input type="checkbox"  name="selectall" id="selectall" class="selectall" value="0"> Select All</label>
									</div></th>
				                	<th>Primary User ID</th>
				                	<th>User Name</th>
				                  	<th>Email</th>
				                  	<th>Phone</th>
				                  	<th>Registerd Date</th>
				                  	<th>Last Activity</th>
				                  	<th>Status</th>
				                  	<th>Action</th>
				                </tr>
			                </thead>
			                <tfoot>
				                <tr>
				                	<th>Action</th>
				                	<th>Primary User ID</th>
				                	<th>User Name</th>
				                  	<th>Email</th>
				                  	<th>Phone</th>
				                  	<th>Registerd Date</th>
				                  	<th>Last Activity</th>
				                  	<th>Status</th>
				                  	<th>Action</th>
				                </tr>
			                </tfoot>
						</table>
		            </div>
		            <!-- /.box-body -->
	          	</div>
	          	<!-- /.box -->
	          	<div class="box box-default color-palette-box">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-tag"></i> Color Scheme</h3>
					</div>
					<div class="box-body">
						<div class="row">
							<input type="hidden" name="userfillter1" id="userfillter1" value="1">
							<div class="col-sm-4 col-md-2">
								<h4 class="text-center"style="color: #000000 !important;" >Email Not Verify</h4>

								<div class="color-palette-set">
								<div class=" color-palette" style="background-color: #FFAB91;"></div>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-sm-4 col-md-2">
								<h4 class="text-center" style="color: #000000 !important;">Mobile Not Verify</h4>

								<div class="color-palette-set">
								<div class=" color-palette" style="background-color: #FFECB3;"></div>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-sm-4 col-md-2">
								<h4 class="text-center" style="color: #000000 !important;">Verified User</h4>

								<div class="color-palette-set">
								<div class=" color-palette" style="background-color: #A5D6A7;"></div>
								</div>
							</div>
							<!-- /.col -->

						<!-- /.col -->
						</div>

					</div>
					<!-- /.box-body -->
			      </div>
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
	
	$(document).ready(function() {
		if(localStorage.getItem("unverifieduser")=='true'){
            $(".locationinput").val(localStorage.getItem("cityName"));
            $("input[class=unverified]").prop('checked', true);
            //$('input:radio[name=userfillter]').trigger();

  			//$("input:radio[name=userfillter]").prop("checked", true).trigger("click");

            localStorage.setItem("unverifieduser","");
        }
		var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
		$(".filterleaddata").click(function(){    
        //alert($("#location").val());
            user();
        });
		if ($('#from_date_pick').length > 0){
			$('#from_date_pick').datepicker({
			    uiLibrary: 'bootstrap',
			    format: 'mm/dd/yyyy',
			    maxDate: today,
			});
		}
		if ($('#to_date_pick').length > 0){
			$('#to_date_pick').datepicker({
			    uiLibrary: 'bootstrap',
			    format: 'mm/dd/yyyy',
			    minDate: function () {
                return $('#from_date_pick').val();
            },
			    maxDate: today,
			});
		}

		user();

		$('input:radio[name=userfillter]').change(function(){
			$('#userfillter1').val($(this).val());
			$("#selectall").prop("checked", false);
			
			if($(this).val()==3){
				$('.user-fillter-form').show();
				user();
			}else{
				$('#from_date_pick').val('');
				$('#to_date_pick').val('');
				$('.user-fillter-form').hide();
				user();
			}
			
			 
		});
		function user(){


		    $('#user_table tfoot th').each( function (keys) {
		    	console.log(keys);
		    	if(keys != 0 && keys != 5 && keys != 6 && keys != 8){
			        var title = $(this).text();
			        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
			    }
		    } );

	        $('#user_table').DataTable({
	            "processing": true,
	            "serverSide": true,
	            "ordering": false,
				"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
				//"dom": 'Bfrtip',
		        "buttons": [
		            'copy', 'csv', 'excel', 'pdf', 'print'
		        ],
	            "ajax": {
	                "url":"<?php echo url('admin/user/get'); ?>",
	                "type": "POST",
	                "data": function(d) {
	                    d.fromdate = $('#from_date_pick').val();
	                    d.todate = $('#to_date_pick').val();
	                    d.userfillter = $('#userfillter1').val();
	                    d._token = "{{ csrf_token() }}";
	                }
	            },
	            "columns": [{
		                "data": "id"
		            },{
		                "data": "userid"
		            }, {
		                "data": "name"
		            }, {
		                "data": "email"
		            }, {
		                "data": "mobile_no"
		            }, {
		                "data": "created_at"
		            }, {
		                "data": "lastlogin"
		            }, {
		                "data": "status"
		            }, {
		                "data": "action"
		            },
	           	],
	           	
	            "order": [
	                [0, 'desc']
	            ],
	            "rowCallback": function( row, data ) {
	            	$("#selectall").prop("checked", false);
	            	if ( data.email_verify == "" || data.email_verify == null) {
	            		$( row).css('color', '#000000');
	                    $(row).css('background-color', '#FFAB91');
	                }else if ( data.phone_verify == "" || data.phone_verify == null) {
	                    $( row).css('background-color', '#FFECB3');
	                    $( row).css('color', '#000000');
	                }else{
	                	$( row).css('background-color', '#A5D6A7');
	                	$( row).css('color', '#000000');
	                }

	            },
	            "destroy" : true,
		        initComplete: function () {
		            // Apply the search
		            this.api().columns().every( function () {
		                var that = this;
		 
		                $( 'input', this.footer() ).on( 'keyup change clear', function () {
		                    if ( that.search() !== this.value ) {
		                        that
		                            .search( this.value )
		                            .draw();
		                    }
		                } );
		            } );
		        }
	        });
    	}
		
		$('.selectall').click(function(){
            if($(this).prop("checked") == true){
                $(".single").prop("checked", true);
            }
            else if($(this).prop("checked") == false){
                $(".single").prop("checked", false);
            }
        });

        // $(".getvalue").click(function(){
        //     var selectdata = [];
        //     $.each($("input[name='id[]']:checked"), function(){
        //         selectdata.push($(this).val());
        //     });
        //     alert("Selected data  are: " + selectdata.join(", "));
        // });
        $(".deleteuser").click(function(){
        	var selectdata = [];
            $.each($("input[name='id[]']:checked"), function(){
                selectdata.push($(this).val());
            });
            if (selectdata.length === 0) {
			    swal({
				  title: "Warning!",
				  text: "No Selected User!",
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
		                    url: "<?php echo url('admin/user/UnVerifiedDelete'); ?>",
		                    data: "deletedata="+selectdata+"&_token="+tok, 
		                    success: function(data){
		                    	//alert(data);
		                    	if(data==1){
		                    		user();
		                        	swal("Delete User Successfully!", "success");
		                    	}else if(data==0){
		                    		swal("Delete User Failed!", "warning");
		                    	}else{
		                    		toastr.error('Sorry, you are not authorised to view / access this page!');
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

        
		setTimeout(function(){
	        var searchText = getUrlParameter("Search");
	        //alert(searchText)
	        if(searchText == '3'){
	        	$('#userfillter1').val('3')
	        	$(".unverified").trigger( "click" );
				$('.user-fillter-form').show();
				user();

		        //var table = $("#user_table").DataTable();
		        //table.data(searchText).draw();
	    	}
		}, 1000);


    });



	  	var getUrlParameter = function getUrlParameter(sParam) {
		    var sPageURL = window.location.search.substring(1),
		        sURLVariables = sPageURL.split('&'),
		        sParameterName,
		        i;

		    for (i = 0; i < sURLVariables.length; i++) {
		        sParameterName = sURLVariables[i].split('=');

		        /*if (sParameterName[0] === sParam) {
		            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
		        }*/
	         	return sParameterName;
		    }
		};
    
</script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection