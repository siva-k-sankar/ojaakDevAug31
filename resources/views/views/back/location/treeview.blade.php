@extends('back.layouts.app')
@section('styles')
<style type="text/css">
   
</style>
<link rel="stylesheet" href="{{ asset('public/back/css/treeview.css')}}">
@endsection
@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Locations
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- left column -->
            <div class="col-md-6 ">
                
              <!-- general form elements -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Tree View</h3>
                </div>
                <!-- /.box-header -->
                    <div class="box-body">

                        <ul  id="trees">
                        @foreach($countries as $key =>$country)
                            <li class=""><a href="#">{{ucwords($country['name'])}}</a>
                                <ul class="">
                                    @foreach($states as $state)
                                        @if($country['id'] == $state['country_id'])
                                            <li><a href="#">{{ucwords($state['name'])}}</a>
                                                <ul class="">
                                                    @foreach($cities as $city)
                                                        @if($state['id'] == $city['state_id'])
                                                        <li><a href="#">{{ucwords($city['name'])}}</a></li>  
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>  
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach 
                        </ul>

                    </div>
                    
                
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
<script src="{{ asset('public/back/js/treeview.js')}}"></script>
<script>
//Initialization of treeviews
$('#trees').treed();
</script>
@endsection