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
.follow_scroll_wrap { max-height:350px; }
  div#followers_popup_modal .modal-dialog {max-width: 50%; }
  .follow_img_wrap img {width: 100%; height: 100%; object-fit: cover; border-radius: 50px; }
  .follow_img_wrap {width: 70px; height: 70px; flex: 0 0 10%; }
  .follow_outer_wrap {display: flex; justify-content: space-around; align-items: center; padding-bottom: 20px; margin-bottom: 20px; }
  .follow_outer_wrap:not(:last-child) {border-bottom: 1px solid rgba(0, 0, 0, 0.13); }
  div#followers_popup_modal .modal-body {padding: 15px 35px; }
  div#followers_popup_modal .modal-body h2 {font-size: 24px; padding-bottom: 15px; }
  .close_chat_btn_wrap.button_close_model a {position: absolute; right: 0; z-index: 1; font-size: 16px; color: #000; padding: 7px 13px; }
  .follow_name {flex: 0 0 50%; }
  .follow_name h4 { font-size: 18px; width:100%; }
  .follow_button.common_btn_wrap a {padding: 7px 26px; }
  .listing_profile_info_wrap p {cursor: pointer; }
  div#following_popup_modal .modal-dialog {max-width: 50%; }
   div#following_popup_modal .modal-body {padding: 15px 35px; }
  div#following_popup_modal .modal-body h2 {font-size: 24px; padding-bottom: 15px; }
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Users</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="{{asset('public/uploads/profile/small/'.$users->photo)}}" style="width: 100px; height: 100px;"alt="User profile picture">
                            <h3 class="profile-username text-center">{{$users->name}}</h3>
                            <ul class="list-group list-group-unbordered">
                                
                                <li class="list-group-item">
                                  <b>Followers</b><a class="pull-right badge bg-purple showfollowersmodal" data-toggle="modal" data-target="#Followers" data-userid="{{$users->uuid}}">{{$fcount['follower']}}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Following</b><a class="pull-right badge bg-purple showfollowingmodal" data-toggle="modal" data-target="#Following" data-userid="{{$users->uuid}}">{{$fcount['following']}}</a>
                                </li>
                                
                                <li class="list-group-item">
                                  <b>Ads</b> <a class="pull-right badge bg-purple" href="{{route('view.profile.all',$users->uuid)}}">{{$users->ads}}</a>
                                </li>
                            </ul>
                            <a href="{{route('view.profile.all',$users->uuid)}}" target="_blank"class="btn bg-purple btn-flat btn-block"><b>View Profile</b></a>
                            
                            @if($users->status == 1)
                            <a  id="block" class="btn bg-maroon btn-flat btn-block"><b>Block User</b></a>
                            @else
                            <a  id="unblock" class="btn bg-orange btn-flat btn-block"><b>UnBlock User</b></a>
                            <!-- <form class="form-horizontal" id="unblock-form" method="post" action="{{route('admin.users.unblock')}}"style="display:none;">
                                @csrf
                                <input type="hidden" name="id" value="{{$users->uuid}}">
                            </form> -->
                            @endif
                            <!-- <a id="deleteuser" href="#" class="btn bg-red btn-flat btn-block"><b>Delete User</b></a> -->
                            <form class="form-horizontal" id="deleteuser-form" method="post" action="{{route('admin.users.deleteuser')}}"style="display:none;">
                                @csrf
                                <input type="hidden" name="userid" value="{{$users->uuid}}">
                            </form>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
            <!-- /.col -->
            <div class="col-md-8">
                <div class="box box-widget widget-user-2" id="details">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-purple">
                        <h3 class="widget-user-username">Details</h3>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="nav nav-stacked">
                            <li>
                                <a href="#">Id<span class="pull-right">@if(!empty($users->id)) {{ $users->id }} @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">Email<span class="pull-right">@if(!empty($users->email)) {{ $users->email }} @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">Work Mail<span class="pull-right">
                                @if(!empty($users->work_mail)) {{ $users->work_mail }} @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">Phone No<span class="pull-right">@if(!empty($users->phone_no)) {{ $users->phone_no }} @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif </span></a>
                            </li>
                            <li>
                                <a href="#">Date of Birth <span class="pull-right">
                                @if(!empty($users->dob)) {{ $users->dob }} @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif </span></a></li>
                            <li>
                                <a href="#">Gender <span class="pull-right">@if(!empty($users->gender)) {{ $users->gender }} @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">Facebook <span class="pull-right">@if(!empty($users->facebook_id)) <i class="fa fa-check fa-2x text-success" aria-hidden="true"></i> @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif</span>
                                </a>
                            </li>
                            <li><a href="#">Google <span class="pull-right">@if(!empty($users->google_id)) <i class="fa fa-check fa-2x text-success" aria-hidden="true"></i> @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif</span></a></li>
                            <li>
                                <a href="#">Referral <span class="pull-right">@if(!empty($users->referral_code)) {{ $users->referral_code }} @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif</span></a>
                            </li>
                            <li>
                                <a href="#">Wallet Point<span class="pull-right">@if(!empty($users->wallet_point)) {{ $users->wallet_point }} @else <i class="fa fa-minus fa-2x text-info" aria-hidden="true"></i> @endif</span></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="box add_val">
                    <div class="box-header bg-purple">
                        <center>
                        <h3 class="box-title ">Add Wallet Points</h3>
                        </center>
                    </div>
                    <h4>Wallet Points : {{ $users->wallet_point }} </h4>
                    <div class="box-body no-padding">
                        <form class="form" action="{{route('admin.user.adminaddwallet')}}" method="POST" id="addwallet_point" style="padding:10px;">
                                    @csrf
                            <input type="hidden" id="user_id" name="userid" value="{{$users->id}}">
                            
                            <div class="form-group">
                                <label>Add Amount :</label>
                                <input type="number" class="form-control" name="point" >
                                <!-- <input > -->
                            </div>
                            <div class="form-group">
                                <label>Description :</label>
                                <input type="text" class="form-control" name="description">
                                <!-- <input > -->
                            </div>
                            <div class="form-group">
                                <button type="submit" onclick="" class="btn bg-maroon margin">Add Point</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box add_val">
                    <div class="box-header bg-purple">
                        <center>
                        <h3 class="box-title ">Deduct Wallet Points</h3>
                        </center>
                    </div>
                    <h4>Wallet Points : {{ $users->wallet_point }} </h4>
                    <div class="box-body no-padding">
                        <form class="form" action="{{route('admin.user.admindeductwallet')}}" method="POST" id="deduct_wallet_point_form" style="padding:10px;">
                                    @csrf
                            <input type="hidden" id="deduct_user_id" name="deduct_userid" value="{{$users->id}}">
                            <input type="hidden" id="deduct_wallet_point" name="deduct_wallet_point" value="{{$users->wallet_point}}">
                            
                            <div class="form-group">
                                <label>Deduct Amount :</label>
                                <input type="number" class="form-control" name="deduct_point" >
                                <!-- <input > -->
                            </div>
                            <div class="form-group">
                                <label>Description :</label>
                                <input type="text" class="form-control" name="deduct_description">
                                <!-- <input > -->
                            </div>
                            <div class="form-group">
                                <button type="button" onclick="" id="deduct_point_btn_ID" class="deduct_point_btn_wrap btn bg-maroon margin">Deduct Point</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="box box-danger " id="block-form" style="display:none;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Block Reason</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="post" action="{{route('admin.users.block')}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$users->uuid}}">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="3" name="reason" placeholder="Enter ..." required=""></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cancel"class="btn bg-navy btn-flat">Cancel</button>
                            <button type="submit" class="btn bg-maroon btn-flat">Block</button>
                        </div>
                      <!-- /.box-footer -->
                    </form>
                </div>
                <div class="box box-danger " id="unblock-form" style="display:none;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Unblock Reason</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="post" action="{{route('admin.users.unblock')}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$users->uuid}}">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="3" name="reason" placeholder="Enter ..." required=""></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cancel"class="btn bg-navy btn-flat">Cancel</button>
                            <button type="submit" class="btn bg-maroon btn-flat">Unblock</button>
                        </div>
                      <!-- /.box-footer -->
                    </form>
                </div>
                <div class="box box-widget widget-user-2" id="proofs">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-purple">
                        <h3 class="widget-user-username">Proofs</h3>
                    </div>
                    <div class="box-body ">
                        @if(!$proofs->isEmpty())
                            @foreach($proofs as $key => $proof)
                                 @if($proof->verified == 1)
                                    <a href="{{url('public/uploads/proof',$proof->image)}}" target="_blank">{{get_prooflist($proof->proof)}}</a>
                                    <button style="margin:3px; float: right;" class=" btn bg-green"><i class="fa fa-check"></i></button><hr>
                                 @endif
                            @endforeach
                            @else
                            <p>No Proof is uploaded.</p>
                        @endif
                    </div>
                </div>


                
                
                @if(!empty($users->deactive_reason) && $users->status == 2)
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-purple">
                        <h3 class="widget-user-username">DeActive Reason </h3>
                    </div>
                    <div class="box-body ">
                        {{ $users->deactive_reason }} 
                    </div>
                </div>
                @endif

                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-purple">
                        <h3 class="widget-user-username">Blocked / Unblocked Reason </h3>
                    </div>
                    <div class="box-body ">
                        <button type="button" class="btn  btn-primary" data-target="#modal-default" data-toggle="modal">view all</button>
                    </div>
                </div>

                <div class="modal fade" id="modal-default" style="display: none;">
                  <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Blocked / Unblocked Reason</h4>
                      </div>
                      <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                              <th>Reason</th>
                              <th>Action Taken</th>
                              <th>Type</th>
                              <th>Date</th>
                            </tr>
                            @if(!$blockreason->isEmpty())
                            @foreach($blockreason as $key => $reason)
                            <tr>
                                <td>{{$reason->reason}}</td>
                                <td>{{getUserEmail($reason->blocked_by)}}</td>
                                <td>{{$reason->status}}</td>
                                <td>{{$reason->created_at}}</td>
                            </tr>
                             @endforeach
                            @else
                                <tr><td colspan="3">No Blocked Reason</td></tr>
                            @endif    
                            </tbody>
                        </table>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!-- <div class="box box-widget widget-user-2" id="Chat with User">
                    <form method="post" action="{{route('admin.usermail')}}" enctype="multipart/form-data" >
                        @csrf
                        <div class="widget-user-header bg-purple form-group">
                            <h3 class="widget-user-username">Chat with User</h3>
                            <input type="hidden" name= "email" value="{{$users->email}}">
                            <input type="hidden" name= "user_id" value="{{$users->id}}">
                            
                        </div>
                        <div class="box-body">
                            <textarea class="form-control" id="description" rows="7" placeholder="Enter a message..." name="description">{{old('description')}}</textarea>
                            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Send</button>
                        </div>
                    </form>
                </div> -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
            <!-- general form elements -->
                <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Complaint By</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                    <div class="box-body" style="min-height: 100px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="complaintbyuser" id="complaintbyuser" class="form-control">               
                                    <option value="">Complaint By</option>      
                                    @if(!empty($reportingUsers))
                                        @foreach($reportingUsers as $user)
                                            <option value="{{$user->uuid}}">{{$user->user_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                </div>
                                
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-6">
            <!-- general form elements -->
                <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Complaint Details</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                    <div class="box-body" style="min-height: 100px;max-height: 200px;overflow-y: scroll;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="showdata">
                                    
                                </div>                                  
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            <!-- general form elements -->
                <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Complaint Given User</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                    <div class="box-body" style="min-height: 100px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Message to user" name="complaintgivenusermsg" id="complaintgivenusermsg"></textarea>
                                </div>                          
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button class="btn btn-primary" id="complaintgivenusersend" disabled="disabled">Send Message</button>                               
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">
            <!-- general form elements -->
                <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Ad posted user</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                    <div class="box-body" style="min-height: 100px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Comment" name="fakeusermsg" id="fakeusermsg"></textarea>
                                </div>                          
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button class="btn btn-primary" id="fakeusersend" disabled="disabled">Send Message</button>                             
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
       
    </section>
    <!-- /.content -->
</div>
<!-- Followers Modal -->
<div class="modal fade" id="Followers" tabindex="-1" role="dialog" aria-labelledby="Followers" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Followers"><strong>Followers</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="followers">
                <div id="follows">
                    
                </div>              
            </div>
        </div>
    </div>
</div>
<!-- Following Modal -->
<div class="modal fade" id="Following" tabindex="-1" role="dialog" aria-labelledby="Following" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Following"><strong>Following</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="followingg">
                <div id="followg">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    $(".deduct_point_btn_wrap").click(function(){
        var wallet_point = $("input[name=deduct_wallet_point]").val();
        var deduct_point = $("input[name=deduct_point]").val();

        var total_point =  wallet_point - deduct_point;

        if(total_point>0){ 
            document.getElementById('deduct_wallet_point_form').submit();
        }else{
            swal("Total Amount less than zero!");
        }

    });

    $(document).ready(function() {
        $('#block').click(function(){
            $('#details').hide();
            $('#proofs').hide();
            $('#block-form').show();
        });
        $('#cancel').click(function(){
            $('#details').show();
            $('#proofs').show();
            $('#block-form').hide();
        });
        $('#unblock').click(function(){
            $('#details').hide();
            $('#proofs').hide();
            $('#block-form').hide();
            $('#unblock-form').show();
        });
        // $('#unblock').click(function(){
        //     swal({
        //           title: "Are you sure?",
        //           text: "Once unblock, you will not be able to recover this file!",
        //           icon: "warning",
        //           buttons: true,
        //           dangerMode: true,
        //         })
        //         .then((willDelete) => {
        //           if (willDelete) {
        //             $('#unblock-form').submit();
        //             swal("UnBlocked !", {
        //               icon: "success",
        //             });
        //           } 
        //         });
        // });
        $('#deleteuser').click(function(){
            swal({
                  title: "Are you sure?",
                  text: "Once deleted, you will not be able to recover this User data!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $('#deleteuser-form').submit();
                    } 
                });
        });

        $(".showfollowingmodal").click(function(){
            $.ajax({
                url: '{{url("getfollwing")}}/'+$(this).data('userid'),
                type: "get",
                //data:{'uuid':$(this).data('userid')},
                success:function(data) {
                    $("#followg").html(data);
                }
            });
        });

        $(".showfollowersmodal").click(function(){
            $.ajax({
                url: '{{url("getfollowers")}}/'+$(this).data('userid'),
                type: "get",
                success:function(data) {
                    $("#follows").html(data);
                }
            });
        });




        $("#complaintbyuser").change(function(e){
            var useruuid = $(this).val();
            if($(this).val() != ''){
                $.ajax({
                    type: "post",
                    url: "<?php echo url('admin/getcomplaintuserdetails'); ?>",
                    data: "useruuid="+$(this).val()+"&_token={{ csrf_token() }}",
                    success: function(data){
                        $("#complaintgivenusersend").removeAttr("disabled"); 
                        $("#complaintgivenusersend").attr("data-userid",useruuid);
                        $("#fakeusersend").removeAttr("disabled");   
                        $(".showdata").html(data);      
                    }
                });
            }
        });


        $("#complaintgivenusersend").click(function(e){
            //alert($(this).attr('data-userid'));
            var useruuid = $(this).attr('data-userid');
            $.ajax({
                type: "post",
                url: "<?php echo url('admin/adminchattouser'); ?>",
                data: "typemessage="+$("#complaintgivenusermsg").val()+"&_token={{ csrf_token() }}"+"&useruuid="+useruuid,
                success: function(data){
                    $("#complaintgivenusermsg").val('');
                    if(data == 1){
                        swal("Message Sent!", {icon: "success",});     
                    }else{
                        swal("Message Not Sent!", {icon: "success",});  
                    }                   
                }
            });
        });

        $("#fakeusersend").click(function(e){
            //alert($(this).attr('data-userid'));
            $.ajax({
                type: "post",
                url: "<?php echo url('admin/adminchattouser'); ?>",
                data: "typemessage="+$("#fakeusermsg").val()+"&_token={{ csrf_token() }}"+"&useruuid={{$users->uuid}}",
                success: function(data){
                    $("#fakeusermsg").val('');
                    if(data == 1){
                        swal("Message Sent!", {icon: "success",});     
                    }else{
                        swal("Message Not Sent!", {icon: "success",});  
                    }        
                }
            });
        });

    });
</script>
@endsection