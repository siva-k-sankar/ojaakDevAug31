@extends('layouts.ads')

@section('content')

        <div class="col-md-9">
            <div class="card">
                <h5 class="card-header">Promoted Ads</h5>
                <div class="card-body">
                    <form method="post" class="needs-validation" action="{{route('ads.user.promote.save')}}">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="ads">Select Ads</label>
                                    <select class="form-control" id="ads" name="ads">
                                        <option value="" hidden>Select Ads</option>
                                        @foreach($ads as $ad)
                                        <option value="{{$ad->uuid}}">{{$ad->title}}</option>
                                        @endforeach
                                    </select>
                                    @error('ads')
                                        <span class="help-block" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="plan">Select Plans</label>
                                    <select class="form-control" id="plan" name="plan">
                                        <option value="" hidden>Select Plans</option>
                                        @foreach($Plans as $plan)
                                        <option value="{{$plan->id}}">{{getTopPlanName($plan->feature_plan_id)}} ({{$plan->ads_count}}) - {{$plan->expire_date}}</option>
                                        @endforeach
                                    </select>
                                    @error('plan')
                                        <span class="help-block" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Promote</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 py-4">
                <div class="card text-white bg-primary" >
                    <div class="card-header">Featured Ads</div>
                    <div class="card-body" style="max-height: 200px;min-height: 200px; overflow-y: scroll;">
                         @foreach($feature as $fe)
                            <p class="card-text ">Ads Name : {{ucwords(get_adsname($fe->ads_id))}}</p>
                            <p class="card-text ">Featured Name : Platinum plan {{$fe->id}}</p> 
                            <p class="card-text "> Expire Date : {{date("j M Y , h:i A", strtotime($fe->expire_date))}}</p>
                            <hr>              
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4 py-4">
                <div class="card text-white bg-primary" >
                    <div class="card-header">TopListed Ads</div>
                    <div class="card-body " style="max-height: 200px; min-height: 200px;overflow-y: scroll;">
                        @foreach($top as $top)
                            <p class="card-text ">Ads Name : {{ucwords(get_adsname($top->ads_id))}}</p><p class="card-text "> Expire Date : {{date("j M Y , h:i A", strtotime($top->expire_date))}}</p> 
                             <hr>            
                        @endforeach
                    </div>
                </div>
            </div><!-- 
            <div class="col-md-4 py-4">
                <div class="card text-white bg-primary" >
                    <div class="card-header">Pearls Ads</div>
                    <div class="card-body" style="max-height: 200px;min-height: 200px; overflow-y: scroll;">
                        @foreach($pearls as $pe)
                            <p class="card-text ">Ads Name : {{ucwords(get_adsname($pe->ads_id))}}</p> 
                            <p class="card-text "> Expire Date : {{date("j M Y , h:i A", strtotime($pe->expire_date))}}</p>
                              <hr>             
                        @endforeach
                    </div>
                </div>
            </div> -->
@endsection
@section('scripts')
<script>
   
</script>

@endsection