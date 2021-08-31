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
                  <h3 class="box-title">GENERAL POINTS (Rs)</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="{{route('admin.point.store')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="new_reg_point">New Registeration Point</label>
                                  <input type="number" step="0.01"  min="0"class="form-control" name="new_reg_point" value="{{ $settings->new_reg_point}}" id="new_reg_point" required placeholder="">
                                    @error('new_reg_point')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                  <label for="workmailpoint">Work Mail Point</label>
                                  <input type="number" step="0.01" min="0" class="form-control" name="workmailpoint" value="{{ $settings->work_mail_point}}" id="workmailpoint" required placeholder="">
                                    @error('workmailpoint')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="ads_view_point">Ads View Point</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="ads_view_point" value="{{ $settings->ads_view_point}}" required id="ads_view_point" placeholder="" >
                                    @error('ads_view_point')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                   <label for="redeemable_amount">Redeemable Amount(min)</label>
                                   <input type="number" step="0.01"min="0"  class="form-control" name="redeemable_amount" value="{{ $settings->redeemable_amount}}" required id="redeemable_amount" placeholder="" >
                                   @error('redeemable_amount')
                                   <span class="invalid-feedback" role="alert">
                                       <strong class="text-red">{{ $message }}</strong>
                                   </span>
                                   @enderror
                               </div>
                               <div class="form-group">
                                    <label for="ads_post_point">Ads Post Point</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="ads_post_point" value="{{ $settings->ads_post_point}}" required id="ads_post_point" placeholder="" >
                                    @error('ads_post_point')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="social_point">Social Point</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="social_point" value="{{ $settings->social_reg}}" required id="social_point" placeholder="">
                                    @error('social_point')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="profilephoto">Profile Photo Point</label>
                                    <input type="number" step="0.01" class="form-control" name="profileuploadpoint" value="{{ $settings->profile_upload_point}}" id="profileuploadphoto" required placeholder="">
                                    @error('profileuploadpoint')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="govtidpoint">Govt proof Point</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="govtidpoint" value="{{ $settings->govt_id_point}}" required id="govtidpoint" placeholder="">
                                    @error('govtidpoint')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="referralpoint">Referral Point</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="referralpoint" value="{{ $settings->referral_point}}" required id="referralpoint" placeholder="">
                                    @error('referralpoint')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                  <label for="ads_view_point">Points for posting Premium Ads</label>
                                  <input type="number" step="0.01" min="0" class="form-control" name="feature_ads_point" value="{{ $settings->feature_ads_point}}" required id="feature_ads_point" placeholder="" >
                                  @error('feature_ads_point')
                                  <span class="invalid-feedback" role="alert">
                                  <strong class="text-red">{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="no_free_ads_post_per_month">Number of Post free ads per 30days</label>
                                  <input type="number" step="1" min="1" class="form-control" name="no_free_ads_post_per_month" value="{{ $settings->no_free_ads_post_per_month}}" required id="no_free_ads_post_per_month" placeholder="" >
                                  @error('no_free_ads_post_per_month')
                                  <span class="invalid-feedback" role="alert">
                                  <strong class="text-red">{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="no_feature_ads_post_per_month">Number of post feature ads per 30days</label>
                                  <input type="number" step="1" min="1" class="form-control" name="no_feature_ads_post_per_month" value="{{ $settings->no_feature_ads_post_per_month}}" required id="no_feature_ads_post_per_month" placeholder="" >
                                  @error('no_feature_ads_post_per_month')
                                  <span class="invalid-feedback" role="alert">
                                  <strong class="text-red">{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="no_free_ads_point_per_month">Number of free ads point per 30days</label>
                                  <input type="number" step="1" min="1" class="form-control" name="no_free_ads_point_per_month" value="{{ $settings->no_free_ads_point_per_month}}" required id="no_free_ads_point_per_month" placeholder="" >
                                  @error('no_free_ads_point_per_month')
                                  <span class="invalid-feedback" role="alert">
                                  <strong class="text-red">{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="minimum_ojaak_point_use_payment">Minimum Ojaak point use while payment</label>
                                  <input type="number" step="1" min="1" class="form-control" name="minimum_ojaak_point_use_payment" value="{{ $settings->minimum_ojaak_point_use_payment}}" required id="minimum_ojaak_point_use_payment" placeholder="" >
                                  @error('minimum_ojaak_point_use_payment')
                                  <span class="invalid-feedback" role="alert">
                                  <strong class="text-red">{{ $message }}</strong>
                                  </span>
                                  @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-header with-border">
                        <h3 class="box-title">GENERAL ADS</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <?php 
                                if($settings->infinitefreelimit == 1){
                                    $disabled = "disabled";
                                    $checked  = "checked";
                                }else{
                                    $disabled = "";
                                    $checked  = "";
                                }
                            ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="freeadslimit">Free Ads Limit</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <input type="checkbox" name="infinitefreelimit" id="infinitefreelimit" {{$checked}} value="1"><label for="infinitefreelimit" style="padding-left:5px;">Infinite</label>
                                        </span>
                                        <input type="number" class="form-control" name="freeadslimit" value="{{ $settings->freeadslimit}}" required id="freeadslimit" placeholder="" {{$disabled}}>
                                    </div>
                                    @error('freeadslimit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="free_ad_view_count">Per Day user can view ads points</label>
                                    <input type="number" class="form-control" name="free_ad_view_count"  step="0.01" min="0" value="{{ $settings->free_ad_view_count}}" required id="free_ad_view_count" placeholder="">
                                    @error('free_ad_view_count')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="box-header with-border">
                        <h3 class="box-title">Choose Payment Gateway</h3>
                    </div>
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
                    </div> -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
              </div>
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
<script type="text/javascript">
    $('#infinitefreelimit').change(function(){
        if($(this).is(":checked")) {
            $('#freeadslimit').attr("disabled", "disabled");
        } else {
            $('#freeadslimit').removeAttr("disabled");
        }
    });
</script>
@endsection