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
        
        <div class="box  ">
            <div class="box-header with-border">
                <h3 class="box-title">ReFund Details</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body no-padding" >

                    @if(!empty($plans_purchase_refund_details))
                        @foreach($plans_purchase_refund_details as $reund)
                            <ul class="nav nav-stacked margin">
                                <li>Payment Method : {{$plans_purchase_details->payment_method}}</li>
                                <li>Refund Order Id: {{$reund->refund_id}}</li>
                                <li>Status         : {{$plans_purchase_details->refund_order_status}}</li>
                                <li>Payment Id     : {{$reund->payment_id}}</li>
                                <li>Amount         : {{$reund->refund_amount}}</li>
                                <li>Created At     : {{date("d-m-Y H:i ", strtotime($reund->created_at))}}</li>
                            </ul>
                            <br><br>
                        @endforeach
                    @endif

            </div>


    </div>

        </div>
    </div>

</div>



@endsection

@section('scripts')
@endsection