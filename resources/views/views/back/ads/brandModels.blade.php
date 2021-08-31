@extends('back.layouts.app')
@section('styles')

		

@endsection
@section('content')
	
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
			Add Brands and Models for {{isset($parentdata->name)?ucfirst($parentdata->name):''}} / {{isset($sub_categories->name)?ucfirst($sub_categories->name):''}}
			</h1>
			<input type="hidden" id="id" name="id" value="{{$id}}">
		</section>

		<!-- Main content -->
		<section class="content">
				<!-- Small boxes (Stat box) -->
				<div class="row">
					@if ($errors->any())
					    <div class="alert alert-danger">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
					@endif

						<!-- left column -->
						<div class="col-md-6">
							<!-- general form elements -->
							<div class="box box-info">
								
							<form action="{{route('admin.ads.brandModels.add')}}" method="post" >
			                    <div class="box-body">			                        
		                            @csrf
									<input type="hidden" name="sub_cate_id" value="{{$sub_categories->id}}">
									<input type="hidden" name="sub_cate_uuid" value="{{$sub_categories->uuid}}">
		                            <div class="form-group @error('field') has-error  @enderror">
		                                <label for="value">Add Brands:</label>
		                                <input type="text" name="brandname" class="form-control" required="">
		                            </div>
			                    </div>
			                    <!-- /.box-body -->

			                    <div class="box-footer">
			                        <button type="submit" class="btn btn-primary">Save</button>
			                        <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.ads.subcategories',$parentdata->uuid)}}">Back</a>
			                    </div>
		                	</form>  
								
							</div>
							<!-- /.box -->
						</div>

						<!-- Right column -->
						<div class="col-md-6">
							<!-- general form elements -->
							<div class="box box-info">
								<form action="{{route('admin.ads.brandModelsValues.add')}}" method="post" >
								
			                    <div class="box-body">
			                            @csrf
									<input type="hidden" name="sub_cate_id" value="{{$sub_categories->id}}">
									<input type="hidden" name="sub_cate_uuid" value="{{$sub_categories->uuid}}">
			                            <div class="form-group @error('field') has-error  @enderror">
			                                <label for="value">Select Brand:</label>
			                                <select class="form-control" name="brands" id="brands">
			                                    <option value=""> --- Select Brand --- </option>
			                                    @foreach($brands as $key => $brand)
			                                    <option value="{{$brand->id}}" >{{$brand->brand}}</option>
			                                    @endforeach
			                                </select>
			                            </div>

			                            <div class="form-group @error('field') has-error  @enderror">
			                                <label for="value">Add Models:</label>
			                                <input type="text" name="modelsname" class="form-control">
			                            </div>
				                            
			                    </div>
			                    <!-- /.box-body -->

			                    <div class="box-footer">
			                        <button type="submit" class="btn btn-primary">Save</button>
			                        <a style="margin: 3px;"class="btn btn-primary" href="{{route('admin.ads.subcategories',$parentdata->uuid)}}">Back</a>
			                    </div>   
								<!-- /.box-body -->

								<div class="box-footer">
										
								</div>
			                	</form>
								
							</div>
							<!-- /.box -->
						</div>
				<!--/.col (left) -->
				</div>
				<!-- /.row -->
			
		</section>



		<!-- Main content -->
		<section class="content">
				<!-- Small boxes (Stat box) -->
				<div class="row">
						<!-- left column -->
						<div class="col-md-12">
							<!-- general form elements -->
							<div class="box box-info">
								
										<div class="box-body">
											<table id="categories" class="table table-bordered ">
												<thead>
													<tr>
														<th>Brand</th>
														<th>Model</th>
														<th>Created Date</th>
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

	$(document).ready(function() {
		var id =$('#id').val();
		var url ="<?php echo url('admin/ads/sub_categories/getbrandmodels/view'); ?>";
        $('#categories').DataTable({
            "processing": true,
            "serverSide": true,
            //"ordering": false,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"dom": 'Bfrtip',
	        "buttons": [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ],
            "ajax": {
                "url":url,
                "type": "post",
                "data": function(d) {
                    d.id = $('#id').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            "columns": [{
	                "data": "brand"
	            }, {
	                "data": "model"
	            }, {
	                "data": "createdAt"
	            }
           	],
            "order": [
                [0, 'desc']
            ]
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
						document.getElementById('del-custom-'+id).submit();
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