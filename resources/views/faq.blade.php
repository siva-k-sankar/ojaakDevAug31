@extends('layouts.home')
@section('styles')


@endsection

@section('content')
<!-- Page Title -->
    <div class="container-fluid pl-0 pr-0 page_title_bg_wrap">
        <nav aria-label="breadcrumb" class="page_title_inner_wrap">
            <div class="box-size">  
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">FAQ's</a></li>
              </ol>
            </div>
        </nav>
    </div>

<!-- FAQ -->
    <div class="container-fluid pl-0 pr-0 faq_outer_row_wrap">
        <div class="box-size">
            <div class="faq_title_wrap">
                <h2>Frequently asked questions</h2>
            </div>

            <!-- <ul class="nav nav-tabs faq_nav_wrap">
                <li class="nav-item">
                  <a class="nav-link active" data-toggle="tab" href="#general_faq_outer_wrap">General</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#seller_faq_outer_wrap">Seller FAQ's</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#buyer_faq_outer_wrap">Buyer FAQ's</a>
                </li>
            </ul> -->
            <div class="row justify-content-center faq_search_outer_wrap">
                <div class="col-md-6">
                    <form class="" method="GET" action="{{route('faqqns')}}">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="search" id="searchbar" placeholder=" Search..." autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit">Go</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-content">
                <!-- General FAQ -->
                <div id="general_faq_outer_wrap" class="common_faq_outer_wrap tab-pane active">
                    <div class="general_faq_accordion common_faq_inner_wrap" id="general_faq_accordion_wrap">
                        @if($userqns->Isempty())
                        <!-- <strong>Warning!</strong> -->
                            <div class="alert alert-warning">
                                <strong>No Results Found </strong><br>
                                <a role="button"  href="{{route('faq')}}">Go Back</a>
                            </div>
                        @else
                        @foreach($userqns as $key => $userqn)
                        <div class="faq_quesstion_wrap card">
                            <div class="card-header" id="question{{$key}}">
                                <h2 data-toggle="collapse" data-target="#col_{{$key}}">
                                    {{$userqn->questions}}
                                    <i class="fa fa-plus"></i>
                                </h2>
                            </div>
                            <div id="col_{{$key}}" class="collapse" aria-labelledby="question{{$key}}" data-parent="#general_faq_accordion_wrap">
                                <div class="card-body">
                                    {!! $userqn->answers !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                    </div>
                </div>
                <!-- General FAQ End -->
                
            </div>
        </div>
    </div>                                                   
@endsection
