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
                            <form action="{{route('admin.broadcast.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id"  value="{{$broad->id}}" autocomplete="off" required="">
                                <div class="box-body">
                                    <div class="form-group @error('message') has-error  @enderror">
                                        <label for="message">Message:</label>
                                        <textarea  class="form-control" id="message" maxlength="2000" rows="10" placeholder="" name="message" value="" autocomplete="off" required="">{{$broad->message}}</textarea>
                                        @error('message')
                                            <span class="help-block" role="alert">
                                                <strong class="text-red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group @error('date') has-error  @enderror">
                                        <label for="date">Date:</label>
                                        <input  name="date" id="date" value="{{$broad->date}}"autocomplete="off" required="">
                                        @error('date')
                                            <span class="help-block" role="alert">
                                                <strong class="text-red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <span class="help-block" role="alert">
                                        <strong>Note: Date is only allow Feature date only.</strong>
                                    </span>
                                </div>
                            <!-- /.box-body -->
                                <div class="box-footer">
                                    <button class="btn btn-primary" type="submit">Update Broadcast Message</button>
                                </div>
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </section>
	    
</div>
@endsection

@section('scripts')
<script>

	$(document).ready(function() {
        
        $('#date').datepicker({
            uiLibrary: 'bootstrap',
            format: 'yyyy-mm-dd',
            disableDates: function(date) {
                const currentDate = new Date();
                return date > currentDate ? true : false;
            }
        });

        maxLength = $("textarea#message").attr("maxlength");
        $("textarea#message").after("<div><span id='remainingLengthTempId'>"
                  + maxLength + "</span> Character(s) Remaining</div>");

        $("textarea#message").bind("keyup change", function(){checkMaxLength(this.id,  maxLength); } );
        checkMaxLength('message', maxLength);
        function checkMaxLength(textareaID, maxLength){

            currentLengthInTextarea = $("#"+textareaID).val().length;
            $(remainingLengthTempId).text(parseInt(maxLength) - parseInt(currentLengthInTextarea));

            if (currentLengthInTextarea > (maxLength)) {

                // Trim the field current length over the maxlength.
                $("textarea#message").val($("textarea#message").val().slice(0, maxLength));
                $(remainingLengthTempId).text(0);

            }
        }

        
    });
</script>

<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>

@endsection