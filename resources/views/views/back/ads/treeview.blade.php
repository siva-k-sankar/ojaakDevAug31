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
       Categories
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
                  <h3 class="box-title">Tree View</h3>
                </div>
                <!-- /.box-header -->
                    <div class="box-body">

                        <ul  id="trees">
                        @foreach($parent as $key =>$parenttree)
                            <li class=""><a href="#">{{ucwords($parenttree['name'])}}</a>
                                <ul class="">
                                    @foreach($sub as $subs)
                                        @if($parenttree['id'] == $subs['parent_id'])
                                            <li><a href="#">{{ucwords($subs['name'])}}</a></li>  
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