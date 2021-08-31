@extends('layouts.app')
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
@endsection
@section('content')
<div class="container py-4">
    <div class="card text-center">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-6">
                    <h6>Enter user email/mobile to transfer amount</h6>
                    <span><input type="text" name="sendtofriends" id="sendtofriends" class="form-control"></span>
                </div>
                <div class="col-md-2">
                    <input type="button" class="btn btn-primary" id="checkuser"  name="checkuser" style="margin-top: 13px;" value="Check user">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 sendamount" style="display: none;" >
                    <input type="hidden" name="sendamttofromtext" id="sendamttofromtext" value="{{auth()->user()->uuid}}">
                    <input type="hidden" name="sendamttofriendstext" id="sendamttofriendstext" >
                    <span id="sendamtlabel"></span>
                    <input type="button" class="btn btn-primary" id="sendamttofriends"  name="sendamttofriends" style="margin-top: 13px;" value="Send amount" >
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#checkuser").click(function(e){
        e.preventDefault();

        $.ajax({
            type: "post",
            url: "<?php echo url('/getuserdetails'); ?>",
            data: "userinfo="+$("#sendtofriends").val(),
            beforeSend:function(){
                $(".sendamount").hide(); 
                $('.ajax-loader').css("visibility", "visible");
            }, 
            success: function(data){ 
                $('.ajax-loader').css("visibility", "hidden");
                if(data == 0){
                    toastr.warning("No user found !");
                }else if(data == 1){
                    toastr.warning("Amount can not send to your same account!");
                }else{
                    $(".sendamount").show();              
                    var userdetails = data.replace(/\s/g, "");
                    var res = userdetails.split("@@@");
                    console.log(res);
                    $("#sendamttofriendstext").val(res['0']);
                    $("#sendamtlabel").text('Send Wallet amount ({{$sum}}) to  '+res['1']);
                }
            }
        });
    });


    $("#sendamttofriends").click(function(e){
        swal({
            title: "Are you sure do you want to send amount?",
            text: "You won't be able to revert this!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            }).then((willyes) => {
              if (willyes) {
                
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: "<?php echo url('/sendamounttofriends'); ?>",
                    data: "fromuser="+$("#sendamttofromtext").val()+"&touser="+$("#sendamttofriendstext").val(),
                    beforeSend:function(){
                        $('.ajax-loader').css("visibility", "visible");
                    }, 
                    success: function(data){
                        $('.ajax-loader').css("visibility", "hidden");
                        if(data == 0){
                            toastr.warning("Amount should be greater than zero!");
                        }else if(data == 1){
                            toastr.success("Amount transferred!");
                            location.reload();
                        }
                    }
                });

              }
        });



        
    });
});
</script>
@endsection
