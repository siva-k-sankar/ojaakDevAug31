@extends('back.layouts.app')

@section('styles')
<style type="text/css">
    .right .direct-chat-text {
        margin-right: 0px !important;
    }
    .direct-chat-text {
        margin-left: 0px !important;
    }   
</style>

@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header"></section>
    <div class="col-md-6">
        <div class="box  box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Contact Us Attachments</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding" >
                @if(isset($check->attachments) && $check->attachments!='')
                <div class="embed-responsive embed-responsive-16by9">
                <!-- <iframe class="embed-responsive-item" src="{{ url('public/uploads/attachments/') }}/{{$check->attachments}}" allowfullscreen></iframe> -->
                <a href="{{ url('public/uploads/attachments/') }}/{{$check->attachments}}" target="_blank" download>Download Files</a>

               </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        
        <div class="box  ">
            <div class="box-header with-border">
                <h3 class="box-title">Contact Us Details</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body no-padding" >

                <ul class="nav nav-stacked margin">
                    <li>Name : {{$check->name}}</li>
                    <li>Ticket No : {{$check->tickectid}}</li> 
                    <li>Email : {{$check->mail_id}}</li>
                    <li>Mobile No : {{$check->mobileno}}</li>
                    <li>Help : {{$check->help}}</li>
                    <li>Description : {{$check->description}}</li> 
                    <li>Status : {{($check->status==0)?'Pending':'Completed'}}</li>
                </ul>

            </div>
            <div class="box-footer text-center" >

                <div class="input-group">
                    <span class="input-group-btn">
                        @if($check->status==0)
                        <button type="button" class="btn btn-primary btn-flat markascomplete" data-ticketId="{{$check->tickectid}}">Mark as completed</button>
                        @endif
                    </span>
                </div>
            </div>




        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Contact Us Chat</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" >
                <form action="{{route('admin.reportContactus.chat.save')}}" method="post" class="margin">
                    @csrf
                    <div class="input-group">
                    <input type="text" name="message" autocomplete="off" required="" placeholder="Type Message ..." class="form-control">
                    <input type="hidden" class="form-control" id="id" autocomplete="off" placeholder="Enter Message" name="id" value="{{Request::segment(5)}}">
                    <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary btn-flat">Send</button>
                    </span>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <div class="direct-chat-messages">
                    @foreach($message as $msg)
                        @if($msg->type=='2')
                            <div class="direct-chat-msg">
                                <div class="direct-chat-text">
                                    {{$msg->message}}.
                                </div>
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left">Admin</span>
                                    <span class="direct-chat-timestamp pull-right">{{date("d F Y h:i A", strtotime($msg->created_at))}}</span>
                                </div>
                            </div>
                        @else
                           <div class="direct-chat-msg right">
                               <div class="direct-chat-text">
                                    {{$msg->message}}.
                                </div>
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-right">User</span>
                                    <span class="direct-chat-timestamp pull-left">{{date("d F Y h:i A", strtotime($msg->created_at))}}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>



@endsection

@section('scripts')



<script>
    $(document).ready(function(){
        $(".markascomplete").click(function(){  

            var fd = new FormData(); 
            var _token = "{{ csrf_token() }}";
            var ticketId = $(this).attr('data-ticketId');

            fd.append('ticketId', ticketId); 
            fd.append('_token', _token); 

            swal({
                title: "Are you sure?",
                text: "Do you want to make this as completed!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                }).then((willDelete) => {
                  if (willDelete) {
                        $.ajax({
                            url: "<?php echo url('/admin/report/markascomplete/'); ?>",
                            type: "post",
                            data:fd,
                            contentType: false, 
                            processData: false, 
                            success: function(data1){
                                var response=data1.trim();
                                if(response=='1'){
                                    location.reload()
                                    //alert('Marked as completed');
                                } 
                            }
                        });

                  } 
                });

        });
    });

</script>

@endsection