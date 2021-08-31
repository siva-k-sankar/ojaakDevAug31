@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
@endsection
@section('content')
<!-- Page Title -->
    <div class="container-fluid pl-0 pr-0 page_title_bg_wrap">
        <nav aria-label="breadcrumb" class="page_title_inner_wrap">
            <div class="box-size">  
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Select Category</a></li>
              </ol>
            </div>
        </nav>
    </div>
    <!-- Contact Us -->
    <div class="container-fluid pl-0 pr-0 ">
        <div class="box-size">
            <div class="faq_title_wrap">
                <h2>Post Your Ad</h2>
            </div>
            <div class="main_cate_listing_outer_wrap">
                <ul class="cat_list_wrap" >
                    @foreach($parent as $key =>$parenttree)
                    <li class="active categories_modal" data-category_id="{{$parenttree['id']}}">
                        <a href="javascript:void(0);" >
                        <div class="choose_cate_img_wrap">
                            <img src="{{url('public/uploads/categories/')}}/{{$parenttree['image']}}">
                        </div>
                        <span>{{ucwords($parenttree['name'])}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div id="category_popup" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-center">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="close_chat_btn_wrap button_close_model">
                    <a href="#" data-dismiss="modal">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="category_listing_model_outer_wrap">
                        <h2>Choose your category</h2>
                        <ul class="category_listing_model_inner_wrap category_popup_html">
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
<script type="text/javascript">
    // Custom scroll - Chat Listing
    if ($('.category_listing_model_inner_wrap').length > 0){
        $(window).on("load",function(){
            $(".category_listing_model_inner_wrap").mCustomScrollbar({
                autoHideScrollbar: false,
                theme: "dark",
            });
        });
    }

    $(document).ready(function(){
        $(".categories_modal").click(function(e){
            var id = $(this).attr('data-category_id');
            var token = "{{ csrf_token() }}";
            $.ajax({
                type:"post",
                url:"<?php echo url('/ajaxsubcate')?>",
                data:"id="+id+"&_token="+token,
                success: function(data){
                   $(".category_popup_html #mCSB_1_container").html(data);
                   $("#category_popup").modal('show');
                }
            });
        });
    });    
</script>
@endsection