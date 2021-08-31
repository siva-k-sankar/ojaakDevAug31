@extends('layouts.firsthome')
@section('styles')

<style>


</style> 

@endsection
@section('content')
<!-- Search Section -->
<?php 
    if(session()->has('adsid')){
        $Sadsid =session()->get('adsid');
    }else{
        $Sadsid ='NO'; 
    }
?>
   @if($Sadsid != 'NO')
        <form action="{{route('ads.prevent.clear')}}" id="ResetadsForm" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="preventadsid" name="preventadsid" value="{{getTempAdsUuid($Sadsid)}}">
        </form>
   @endif 
    <div class="container-fluid pl-0 pr-0 ">
        <div class="box-size">
            <div class="home_search_bg_wrap">
                <div class="home_search_outer_wrap">
                    <h1>Ojaak</h1>
                    <p>OJAAK - Free classifieds in India, Buy and Sell for free anywhere ...</p>
                    <div class="home_search_inner_wrap">
                        <div class="category_drop_wrap">
                            <h3>Categories <i class="fa fa-angle-down" aria-hidden="true"></i></h3>
                            <div class="select_category_listing">
                                <ul>
                                    @if(!empty($categories))
                                        @foreach($categories as $category)
                                        <li>
                                            <div class="cat_icon_img">
                                                <img class ="selectcategory" src="{{url('public/uploads/categories/')}}/{{$category->image}}" data-category_id="{{$category->id}}">
                                                <!-- <i class="selectcategory fa {{$category->icon}}" aria-hidden="true" data-category_id="{{$category->id}}"></i> -->
                                            </div>
                                            <h4>{{$category->name}}</h4>
                                        </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="product_location_search_outer_wrap">
                            <div class="home_search_loc_inner_wrap">
                                <div class="home_search_outer_wrap form-group has-search">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" class="home_search_inner_wrap form-control input-field searchbox"  id="searchbox" name="searchbox" autocomplete="off" placeholder="Find yours favourite products">
                                    <div class="home_search_result_wrap search_result_wrap" id="listbox">
                                        
                                    </div>
                                </div>
                                <div class="form-group location_search_wrap">
                                    <span class="fa fa-map-marker form-control-feedback"></span>
                                    <input type="text" class="form-control input-field locationinput" id="locationinput" name="locationinput" placeholder="Chennai" >
                                    <div class="search_result_wrap  location_search_inner_listing_wrap" id="currentlocationList"  style="display: none;">
                                        <ul>
                                            <h3 class="nowrap current_location_addr"><i class="ojaak_icons_target"></i><span class="">Current Location </span><span class="currentAddress mt-1" style="font-size: 10px !important;"></span></h3>
                                        </ul>
                                    </div>
                                    <div class="search_result_wrap  location_search_inner_listing_wrap" id="locationList">
                                        
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="categoryId" id="categoryId"> 
                            <input type="hidden" name="locality" id="locality"> 
                            <input type="hidden" name="subCategoryId" id="subCategoryId"> 
                            <input type="hidden" name="cityId" id="cityId">
                            <div class="common_btn_wrap ">
                                <a href="#" id="main_welcome_search">Search</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Feature Ads -->
    <div class="container-fluid pl-0 pr-0">
        <div class="home_ads_display_outer_wrap">
            <div class="left_col_hidden_wrap">
            </div>
            <div class="center_col_ads_wrap">
                <div class="featured_ads_title_wrap">
                    <h3>Platinum Ads</h3>
                </div>
                <div id="feature_ads_display" class="row">
                    
                    @if(isset($featuedAd))
                    @foreach($featuedAd as $fad)
                    <div class="lazy item col-sm-12 col-md-6 col-lg-6 col-xl-3 pl-0 feature_ads_single_item  "  data-loader="customLoaderName" data-featureId="{{$fad->id}}"></div>
                    @endforeach 
                    @endif
                    <!-- Item 4 -->
                    
                    <div class="common_btn_wrap view_more_ads_btn">
                        <a href="{{route('adsview')}}">View More</a>
                    </div>
                </div>
            </div>
            <div class="right_col_google_ads">
                <div class="google_ads">
                    <div class="google_ad_img_wrap">
                        <img src="{{ asset('public/frontdesign/assets/img/google_ad.jpg') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('scripts')

<!-- <script type="text/javascript" src="http://jquery.eisbehr.de/lazy/js/min/jquery.js"></script> -->
<script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/lazyjquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/frontdesign/assets/jquery.lazy.min.js') }}"></script>
         

<script type="text/javascript">

if(!localStorage.getItem("cityId")===true || !localStorage.getItem("cityName")===true ){
    localStorage.setItem("cityId",'');
    localStorage.setItem("cityName",'');
}

jQuery(window).ready(function() {
    
    setTimeout(function(){  
        var featureid = $( "div[data-featureid]" ).length;
        //console.log( featureid ); 
            $('.lazy').Lazy({
                customLoaderName: function(element) {
                    var featureIID = element.context.dataset.featureid;
                    var _token = "{{ csrf_token() }}";
                        $.ajax({
                            url:"{{ url('showfads') }}",
                            type:"post",
                            data:{'fad':featureIID,"_token":_token},
                            beforeSend:function(){
                                $('.ajax-loader').css("visibility", "visible");
                            },
                            success:function (data) {
                                $('.ajax-loader').css("visibility", "hidden");
                                element.html(data);
                                element.load();
                            }
                        });

                    
                },
                
            });
        
    }, 1000);
});

function postadslogin(){
    $("#login").modal('show');
}
function postadspurchased(){
    toastr.warning("Ad Purchased!");
}

$(document).ready(function () {
    $(".locationinput").val('');
    localStorage.setItem("categoryId","");
    localStorage.setItem("subCategoryId","");
    localStorage.setItem("cityId","");
    localStorage.setItem("cityName","");
    localStorage.setItem("state","");
    localStorage.setItem("areaId","");
    localStorage.setItem("squery",""); 
    
    // if(!localStorage.getItem("query")===true  ){
    //     localStorage.setItem("query","");
    // }
    // setTimeout(function(){
    //     if(localStorage.getItem("query")!=''){
    //         $("#searchbox").val(localStorage.getItem("query"));
    //     }
    // }, 200);

    // localStorage.setItem("currentAddress","");
    // localStorage.setItem("currentAddressID","");
    // localStorage.setItem("lng",'');
    // localStorage.setItem("lat",'');
    
    
    $('#main_welcome_search').on('click',function() {
        if($('#locationinput').val().length >= '1' || $('#searchbox').val().length >= '1'){
            localStorage.setItem("squery",$('#searchbox').val());
            window.location.href = "{{url('items')}}";
        }else{
            toastr.warning('Fill details to search');
        }
    });

    $('.selectcategory').on('click',function() {
        var categoryId = $(this).attr('data-category_id');
        localStorage.setItem("categoryId", categoryId);
        window.location.href = "{{url('items')}}";
    });

    $(".category_drop_wrap h3").click(function(){
        $(".searchbox").val(""); 
        $("#listbox").hide();
    });

    $('.searchbox').on('keyup',function() {
        if($(".searchbox").val().length >= '2'){
            var query = $(this).val();
            $.ajax({
                url:"{{ url('search') }}",
                type:"GET",
                data:{'q':query},
                success:function (data) {
                    $('#listbox').html(data);
                }
            });
        }
    });

    $('.locationinput').on('click',function() {
        $("#currentlocationList").show();
    });
    $(document).on("click", function(event){
        var $trigger = $(".home_search_outer_wrap");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            // $(".home_search_outer_wrap").removeClass("active");
            $("#listbox").hide();
        }            
    });
    $('.locationinput').on('keyup',function() {
        $("#currentlocationList").hide();
        $('#locationList').html('');
        if($(".locationinput").val().length >= '3'){
            // the text typed in the input field is assigned to a variable 
            var query = $(this).val();
            // call to an ajax function
            $.ajax({
                // assign a controller function to perform search action - route name is search
                url:"{{ url('searchlocation') }}",
                // since we are getting data methos is assigned as GET
                type:"GET",
                // data are sent the server
                data:{'q':query},
                // if search is succcessfully done, this callback function is called
                success:function (data) {
                    // print the search results in the div called country_list(id)
                    $('#locationList').html(data);
                }
            })
            // end of ajax call
        }
    });

    $(document).on('click', '.li_product_data', function(){
        //var value = $(this).text();
        var value = $(this).attr('data-cate-search');
        var categoryId = $(this).attr('data-categoryId');
        var subCategoryId = $(this).attr('data-subCategoryId');
        $("#categoryId").val(categoryId);
        $("#subCategoryId").val(subCategoryId);

        localStorage.setItem("categoryId", categoryId);
        localStorage.setItem("subCategoryId", subCategoryId);

        // assign the value to the search box
        $('.searchbox').val(value);
        // after click is done, search results segment is made empty
        $('#listbox').html("");
        // window.location.href = "{{url('items')}}";
        localStorage.setItem("squery",$("#searchbox").val());
    });

    $(".locationinput").keyup(function(){ 
        //console.log('asdf',$(".locationinput").val());

        if($(".locationinput").val().length <= '1'){
        //console.log('val emp',$("#country").val());
            $("#locationList").hide();
        }else{
            $("#locationList").show();                        
        }
    });

    jQuery(window).ready(function() {
        setTimeout(function(){  location();


                $(".locationinput").val('');

                $("#state").val("0");
                $("#area").val("0");
                $("#city").val("0");
                localStorage.removeItem("stateId");
                window.localStorage.removeItem('stateId');
                localStorage.setItem("stateId","0");
                localStorage.removeItem("areaId");
                window.localStorage.removeItem('areaId');
                localStorage.setItem("areaId","0");
                localStorage.removeItem("cityId");
                window.localStorage.removeItem('cityId');
                localStorage.setItem("cityId","0");
                localStorage.removeItem("currentAddress");
                window.localStorage.removeItem('currentAddress');
                localStorage.removeItem("cityName");
                window.localStorage.removeItem('cityName');
                

                localStorage.setItem("squery","");
                //console.log('done')

         }, 1000);
    });

    function location(){
        navigator.geolocation.watchPosition(
            function(position) {
                    $(".currentAddress").html(localStorage.getItem("currentAddress"));
                },
            function(error) {
                if (error.code == error.PERMISSION_DENIED){
                    
                    $('.currentAddress').html('Location blocked. Check browser/phone settings.');
                    $('#currentlocationList ul h3').addClass('current_location_addr_denied');
                    $('#currentlocationList ul h3').removeClass('current_location_addr');
                }
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
    }

    function showPosition(position) {
        latitudeg=position.coords.latitude;
        longitudeg=position.coords.longitude;
        localStorage.setItem("lng",longitudeg);
        localStorage.setItem("lat",latitudeg);
        getcity(latitudeg,longitudeg)
        $(".currentAddress").html(localStorage.getItem("currentAddress"));
    }

    function getcity(latitudeg,longitudeg){

        var _token = "{{ csrf_token() }}";
        $.ajax({
            type: "post",
            url: "<?php echo url('getcity'); ?>",
            data: "latitude="+latitudeg+"&longitude="+longitudeg+"&_token="+_token,
            success: function(data){ 
               
                $.each( data, function( key, value ) {
                    if(key=='cityName'){
                        localStorage.setItem("currentAddress",value);
                        //$("#locationinput").val(value);
                        $(".currentAddress").html(value);
                    }

                    if(key=='state'){
                        localStorage.setItem("currentstate",value);
                    }

                    if(key=='cityId'){
                        localStorage.setItem("currentAddressID",value);
                    }
                               
                });
            }
        });

    }

    // $(".current_location_addr").click(function(){
    //     $('#currentlocationList').hide();
    //     localStorage.setItem("cityId",localStorage.getItem("currentAddressID"));
    //     localStorage.setItem("cityName",localStorage.getItem("currentAddress"));
    //     localStorage.setItem("state",localStorage.getItem("currentstate"));
    //     $("#locationinput").val(localStorage.getItem("currentAddress"));
    // });
    

    $(document).on('click', '.li_locationaddr', function(){
            var cityId = $(this).attr('data-cityid');
            var cityName = $(this).attr('data-cityname');
            var stateId = $(this).attr('data-stateid');
            var areaId = $(this).attr('data-areaid');
            $("#cityId").val(cityId);
            localStorage.setItem("cityId", cityId);
            localStorage.setItem("cityName", cityName);
            localStorage.setItem("state", stateId);
            localStorage.setItem("areaId", areaId);

            $('.locationinput').val(cityName);
            // after click is done, search results segment is made empty
            $('#locationList').html("");
            $('#currentlocationList').hide();
    });


    $(document).on('click', '.current_location_addr', function(){
        $('#currentlocationList').hide();
        localStorage.setItem("cityId",localStorage.getItem("currentAddressID"));
        localStorage.setItem("cityName",localStorage.getItem("currentAddress"));
        localStorage.setItem("state",localStorage.getItem("currentstate"));
        $("#locationinput").val(localStorage.getItem("currentAddress"));
    });

    $(document).on('click', '.current_location_addr_denied', function(){
        swal({
            title: "Geolocation is blocked!",
            text: "Looks like your geolocation permissions are blocked. Please, provide geolocation access in your browser settings and get the nearest ads.",
            icon: "warning",
            //button: "Aww yiss!",
        }); 
    });

});

$("#searchbox").keydown(function (e) {
    if($("#searchbox").val().length >= '1'){  
        if (e.keyCode == 13) {
            localStorage.setItem("squery",$("#searchbox").val());
            window.location.href = "{{url('items')}}";
        }
    }
});


</script>

@if(Auth::check())
<script type="text/javascript">
    var adsID="{{getTempAdsUuid($Sadsid)}}";
    if(adsID!='NO' && adsID!=''){
        swal({
          title: "The post ad is pending",
          text: "Click OK to continue posting the ad!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            window.location.href="{{ url('post/Prevent') }}";
          } else {
            $("#ResetadsForm").submit();
             //window.location.href="{{ url('/') }}";
          }
        });
        //window.location.href="{{ url('post/Prevent') }}";
    }
</script>
@endif
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4
&libraries=places&callback=initAutocomplete"
        async defer></script> -->


@endsection
