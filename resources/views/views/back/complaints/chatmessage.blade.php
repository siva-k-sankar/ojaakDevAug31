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
<!-- Content Header (Page header) -->
    <section class="content-header"></section>
    <div class="col-md-6">
        <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Chat Message</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" >
                <form action="{{route('admin.complaint.chatmessage.save')}}" method="post" class="margin">
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
                    @foreach($chatmessage as $msg)
                        @if($msg->user_id== $adminid)
                            <div class="direct-chat-msg">
                                <div class="direct-chat-text">
                                    {{$msg->msg}}.
                                </div>
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left">Me</span>
                                    <span class="direct-chat-timestamp pull-right">{{date("d F Y h:i A", strtotime($msg->created_at))}}</span>
                                </div>
                            </div>
                        @else
                           <div class="direct-chat-msg right">
                               <div class="direct-chat-text">
                                    {{$msg->msg}}.
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

@endsection