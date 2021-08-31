<!-- Category Model Popup -->
	<div id="category_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="model_close_btn_wrap close_chat_btn_wrap">
                            <a href="javascript:void(0)" data-dismiss="modal">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                </div>
				<form action="{{route('ads.post.redirectPost')}}" method="post">
					@csrf
				<div class="modal-body">
					<h2>Choose your category</h2>
					<div class="row">
						<div class="col-md-12 pl-0 pr-0">
							<div class="common_select_option post_selection_field_wrap form-group">
								<label>Category</label>
								<select class="form-control" id="parent_category" >
								</select>
							</div>
						</div>	
						<div class="col-md-12 pl-0 pr-0">
							<div class="common_select_option post_selection_field_wrap form-group" >
								<label>Sub Category</label>
								<select class="form-control" id="sub_category"  name="category" required="" >
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer common_btn_wrap">
					<div class="common_btn_wrap contact_submit_btn_wrap">
						<a class="cancel_btn_wrap" data-dismiss="modal">Cancel</a>
                          <button type="submit" class="">Proceed</button>
                    </div>
				</div>
				</form>
			</div>
		</div>
	</div>