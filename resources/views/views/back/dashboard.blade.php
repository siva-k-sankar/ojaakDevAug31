@extends('back.layouts.app')

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Dashboard<small>Control panel</small>
        </h1>

      
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
      
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3 id="userunverified">{{$userunverified_count}}</h3>

              <p>User Unverified</p>
            </div>
            <div class="icon">
              <i class="ion ion-filing"></i>
            </div>
            <a href="javascript:void(0);" class="small-box-footer unverifieduser">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3 id="adsunverified">{{$unverifiedAds}}</h3>

              <p>Ads Unverified</p>
            </div>
            <div class="icon">
              <i class="ion ion-filing"></i>
            </div>
            <a href="{{url('admin/ads/adsdata')}}?pending" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 id="userProfilePhotoCount">{{$userProfilePhotoCount}}</h3>

              <p>Profile Photo</p>
            </div>
            <div class="icon">
              <i class="ion ion-camera"></i>
            </div>
            <a href="{{ route('admin.users.profilephoto') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3 id="proofsCount">{{$proofsCount}}</h3>
              <p>Proof Verification</p>
            </div>
            <div class="icon">
              <i class="ion ion-camera"></i>
            </div>
            <a href="{{ route('admin.users.proofverify') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3 id="enquirysCount">{{$enquirysCount}}</h3>
              <p>Contact Us Ticket Management</p>
            </div>
            <div class="icon">
              <i class="ion ion-filing"></i>
            </div>
            <a href="{{ route('admin.reportContactus') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>Ads</h3>

              <p>Verification</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ route('admin.ads') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Complaints</h3>

              <p>User Complaints</p>
            </div>
            <div class="icon">
              <i class="ion ion-social-dribbble"></i>
            </div>
            <a href="{{ route('admin.complaint.users') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>Ads</h3>

              <p>Feature List</p>
            </div>
            <div class="icon">
              <i class="ion ion-cash"></i>
            </div>
            <a href="{{ route('admin.topadslist') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$user_count}}</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('admin.users') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>Report</h3>

              <p>Redeem Amount</p>
            </div>
            <div class="icon">
              <i class="ion ion-filing"></i>
            </div>
            <a href="{{ route('admin.reportRedeem') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Report</h3>

              <p>Purchase Plans</p>
            </div>
            <div class="icon">
              <i class="ion ion-filing"></i>
            </div>
            <a href="{{ route('admin.planpurchases') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>Ads</h3>

              <p>Platinum List</p>
            </div>
            <div class="icon">
              <i class="ion ion-filing"></i>
            </div>
            <a href="{{ route('admin.featureads') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
        

      </div>
      <!-- /.row -->
      
     
    </section>
    <!-- /.content -->
  </div>
  

@endsection

@section('scripts')   
<script type="text/javascript">
  $(document).ready(function () {
    localStorage.setItem("unverifieduser","");
    $('.unverifieduser').on('click',function() {
        localStorage.setItem("unverifieduser", "true");
        window.location.href = "{{ url('admin/user/details') }}?3";
    });

    //alert($('#userunverified').text());
    setInterval(function(){
        $.ajax({
            url: "<?php echo url('/admin/getunverifieddetails/'); ?>",
            type: "get",
            contentType: false, 
            processData: false, 
            success: function(data){
                var res = data.split("@@@");
                $("#userunverified").text(res[0]); 
                $("#adsunverified").text(res[1]);
                $("#userProfilePhotoCount").text(res[2]);
                $("#proofsCount").text(res[3]);
                $("#enquirysCount").text(res[4]);
            }
        });
    }, 20000);

  });
</script>
@endsection