@extends('back.layouts.app')

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Payment's Settings
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">RazorPay</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="{{route('admin.settings.razorpay')}}" method="POST" >
                  @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">RAZORPAY KEY</label>
                                  <input type="text" class="form-control" name="RAZORPAY_KEY" value="{{ $settings->RAZORPAY_KEY}}" required id="appname" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">RAZORPAY SECRET</label>
                                  <input type="text" class="form-control" name="RAZORPAY_SECRET" value="{{ $settings->RAZORPAY_SECRET}}" required id="appname" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            <!-- /.box -->
            </div>
            <!-- general form elements -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Choose Payment Gateway</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="{{route('admin.settings.settingChooseGateway')}}" method="POST" >
                  @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-4"> 
                                        <select class="form-control" name="paymentgateway" required="">
                                            <option value="">Gateway</option>
                                            <option value="paytm" <?php if($settings->paymentgateway == 'paytm'){echo "selected";} ?> >PayTM</option>
                                            <option value="razorpay" <?php if($settings->paymentgateway == 'razorpay'){echo "selected";} ?>>Razorpay</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2" style="font-size: 26px;"> 
                                        >=
                                    </div>
                                    <div class="col-sm-6">          
                                        <input type="number" class="form-control" name="choosepaymentgateway" step="1" value="{{ $settings->choosepaymentgateway }}" required id="choosepaymentgateway" placeholder="">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
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

@endsection