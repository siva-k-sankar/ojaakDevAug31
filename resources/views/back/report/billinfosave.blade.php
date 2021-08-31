@extends('back.layouts.app')

@section('styles')



@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header"><h1>Bill info Edit</h1></section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bill Details</h3>
                        <div class="box-tools pull-right">
                                <a href="{{ route('admin.reportBillInfo') }}" class="btn bg-maroon btn-flat">Back</a>
                        </div> 
                    </div>
                    <div class="box-body">
                        <div class="form-group ">
                            <label>Bill Amount</label>
                            <input type="text"   value="{{$billinfo->planpaymemt}}" readonly="" disabled="" class="form-control">
                        </div>
                        <div class="form-group ">
                            <label>Customer Name</label>
                            <input type="text"   value="{{$billinfo->username}}" readonly="" disabled="" class="form-control">
                        </div>
                        <div class="form-group ">
                            <label>Email Address</label>
                            <input type="email" value="{{$billinfo->email}}"   readonly="" disabled="" class="form-control" >
                        </div>
                        <div class="form-group ">
                            <label>Bussiness Name</label>
                            <input type="text"   value="{{$billinfo->businessname}}"  readonly="" disabled="" class="form-control" >
                        </div>
                        <div class="form-group ">
                            <label>GST No</label>
                            <input type="text" value="{{$billinfo->gst}}"   readonly="" disabled="" class="form-control" >
                        </div>
                        <div class="form-group ">
                            <label>Address Line 1</label>
                            <input type="text" value="{{$billinfo->addr1}}"  readonly="" disabled="" class="form-control" >
                        </div>
                        <div class="form-group ">
                            <label>Address Line 2</label>
                            <input type="text" value="{{$billinfo->addr2}}"  readonly="" disabled="" class="form-control" >
                        </div>
                        <div class="form-group ">
                            <label>State</label>
                            <input type="text" value="{{$billinfo->state}}"  readonly="" disabled="" class="form-control" >
                        </div>
                        <div class="form-group ">
                            <label>City</label>
                            <input type="text" value="{{$billinfo->city}}"  readonly="" disabled="" class="form-control" >
                        </div>
                     </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <form class="form-horizontal margin" method="post" action="{{route('admin.reportBillInfo.save')}}">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Bill</h3>
                            <div class="box-tools pull-right">
                                <button type="submit" class="btn bg-olive btn-flat">Save</button>
                            </div>  
                        </div>
                        <div class="box-body">
                            @if (isset($errors) and $errors->any())
                                <div class="alert alert-danger m-2">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><strong>{{ __('Oops ! An error has occurred.') }}</strong></h5>
                                    <ul class="list list-check">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @csrf
                            <input type="hidden" name="id" value="{{$billinfo->id}}">
                            <div class="form-group ">
                                <label>Customer Name</label>
                                <input type="text" name="Customername"  value="{{old('Customername') }}"  class="form-control" required>
                            </div>
                            <div class="form-group ">
                                <label>Email Address</label>
                                <input type="email" value="{{old('Emailaddress') }}"  name="Emailaddress" class="form-control" required>
                            </div>
                            <div class="form-group ">
                                <label>Bussiness Name</label>
                                <input type="text" name="Businessname"  value="{{old('Businessname') }}"  class="form-control" >
                            </div>
                            <div class="form-group ">
                                <label>GST No</label>
                                <input type="text" value="{{old('GstNo') }}"  name="GstNo" class="form-control" >
                            </div>
                            <div class="form-group ">
                                <label>Address Line 1</label>
                                <input type="text" value="{{old('Address1') }}" name="Address1" class="form-control" required>
                            </div>
                            <div class="form-group ">
                                <label>Address Line 2</label>
                                <input type="text" value="{{old('Address2') }}" name="Address2" class="form-control" required>
                            </div>
                            <div class="form-group ">
                                <label>State</label>
                                <!-- <input type="text" value="{{old('State') }}" name="State" class="form-control" > -->
                                <select class="form-control" name="State" id="state_form" required> <option value="" hidden>Select State</option>
                                    @foreach($states as $ids => $state)
                                        <option value="{{$state->name}}" data-id="{{$state->id}}" >{{$state->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group ">
                                <label>City</label>
                                <!-- <input type="text" value="{{old('City') }}" name="City" class="form-control" > -->
                                <select class="form-control" name="City" id="city_form" required></select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>    
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    var token = "{{ csrf_token() }}";
     $( "#state_form" ).change(function() {
            var state_form_value = $('#state_form option:selected').attr('data-id');
            //alert(state_form_value);
            if($(this).val() !=0){
                $('#city_form').html('');
                $.ajax({
                    type: "post",
                    url: "<?php echo url('getcities'); ?>",
                    data: "stateid="+state_form_value+"&_token="+token,
                    success: function(data){ 
                    let datacities = JSON.parse(data)
                    jQuery.each(datacities, function(key, value) {
                      $('#city_form').append("<option value="+value+">"+value+"</option>");
                    });
                       
                    }
                });
            }
    });
</script>
@endsection