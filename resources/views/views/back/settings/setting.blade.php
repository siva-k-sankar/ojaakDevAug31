@extends('back.layouts.app')
@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       SETTINGS
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
                  <h3 class="box-title">GENERAL SETTING</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="{{route('admin.settings.store')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">App Name</label>
                                  <input type="text" class="form-control" name="appname" value="{{ $settings->appname}}" required id="appname" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="phone">Phone</label>
                                  <input type="tel" pattern="[0-9]{10}"  class="form-control" name="phone" value="{{ $settings->phone}}" id="phone" required title="Please enter 10 digit phone number"placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="footer">Footer</label>
                                  <input type="text" class="form-control" name="footer" value="{{ $settings->footer}}" id="footer" required placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="facebook">Facebook</label>
                                  <input type="text" class="form-control" name="facebook" value="{{ $settings->facebook}}" required id="facebook" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="instagram">Instagram</label>
                                  <input type="text" class="form-control" name="instagram" value="{{ $settings->instagram}}" required id="instagram" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="email">EMAIL ADDRESS</label>
                                  <input type="email" class="form-control" name="email" value="{{ $settings->email}}" id="email" required placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="address">ADDRESS</label>
                                  <input type="text" class="form-control" name="address" value="{{ $settings->address}}" id="address" required placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="twitter">Twitter</label>
                                  <input type="text" class="form-control" name="twitter" value="{{ $settings->twitter}}" id="twitter" required placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="linkedin">Pinterest</label>
                                  <input type="text" class="form-control" name="linkedin" value="{{ $settings->linkedin}}" required id="linkedin" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
              </div>
              <!-- /.box -->

              <!-- <div class="box box-info"> -->
                <!-- <div class="box-header with-border">
                  <h3 class="box-title">Paytm</h3>
                </div> -->
                <!-- /.box-header -->
                <!-- form start -->
                <!-- <form role="form" action="{{route('admin.settings.paytm')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">ENVIRONMENT (values : local | production)</label>
                                  <input type="text" class="form-control" name="PAYTM_ENV" value="{{ $settings->PAYTM_ENV}}" required id="appname" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">MERCHANT ID</label>
                                  <input type="text" class="form-control" name="MERCHANT_ID" value="{{ $settings->MERCHANT_ID}}" required id="appname" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">MERCHANT KEY</label>
                                  <input type="text" class="form-control" name="MERCHANT_KEY" value="{{ $settings->MERCHANT_KEY}}" required id="appname" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">WEBSITE</label>
                                  <input type="text" class="form-control" name="WEBSITE" value="{{ $settings->WEBSITE}}" required id="appname" placeholder="Ojaak">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">CHANNEL</label>
                                  <input type="text" class="form-control" name="CHANNEL" value="{{ $settings->CHANNEL}}" required id="appname" placeholder="WEB">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">INDUSTRY TYPE</label>
                                  <input type="text" class="form-control" name="INDUSTRY_TYPE" value="{{ $settings->INDUSTRY_TYPE}}" required id="appname" placeholder="Retail">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="appname">SALES WALLET GUID</label>
                                  <input type="text" class="form-control" name="SALESWALLETGUID" value="{{ $settings->SALESWALLETGUID}}" required id="appname" placeholder="Wallet GUID">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
              </div> -->
              <!-- /.box-body -->
              <!-- /.box -->

            <!-- <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">RazorPay</h3>
                </div>
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
            </div> -->
            <!-- /.box -->

            <!-- </div> -->
        <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
@endsection