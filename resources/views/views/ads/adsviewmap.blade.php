@extends('layouts.home')

@section('styles')
<style type="text/css">

</style>
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/price_range_style.css') }}">
<script language="JavaScript" type="text/javascript" src="{{ asset('public/frontdesign/assets/price_range_script.js') }}"></script>
@endsection

@section('content')
 <!-- Page Title -->
    <div class="container-fluid pl-0 pr-0 page_title_bg_wrap">
        <nav aria-label="breadcrumb" class="page_title_inner_wrap">
            <div class="box-size">  
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">All ads</a></li>
                <div class="breadcrumb_title">
                    <h3>OJAAK - Free classifieds in India, Buy and Sell for free anywhere ...</h3>
                    <input type="hidden"  id="view_point"  view-point="{{$setting->ads_view_point}}" value="{{$setting->ads_view_point}}">
                </div>
              </ol>
            </div>
        </nav>
    </div>

<!-- display all ads  -->
    <div class="container-fluid all_ads_outer_wrap">
        <div class="box-size">
            <div class="row sorting_row_wrap">
                <div class="col-lg-6 col-md-4 pl-0 left_side_content_wrap">
                    <div class="product_loc_wrap">
                        <h3>Available products</h3>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8">
                    <div class="right_side_content_wrap">
                        <div class="total_ads_wrap">
                            <p></p>
                        </div>
                        <p class="pull-right">View</p>
                        <div class="btn-group">
                            <a class="" id="list">
                                <i class="fa fa-th-list" aria-hidden="true"></i>
                            </a>
                            <a class="" id="grid">
                                <i class="fa fa-th-large" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="sort_outer_wrap">
                            <h3>Sort By:</h3>
                            <div class="sort_dropdown">
                                <div class="common_sort_btn_wrap">
                                    <div class="dropdown show">
                                        <a class="dropdown-toggle" href="#" role="button" id="sort_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Date Published
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="sort_drop">
                                            <a class="dropdown-item" href="javascript:void(0);" id="sort_drop_date_published">Date Published</a>
                                            <a class="dropdown-item" href="javascript:void(0);" id="sort_drop_price_low">Price:Lowest</a>
                                            <a class="dropdown-item" href="javascript:void(0);" id="sort_drop_price_high">Price:highest</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 pl-0 pr-0">
                    <div class="filter_outer_wrap">
                        <h3 data-toggle="modal" data-target="#category">Categories</h3>
                        <!-- <h3>Locations</h3> -->
                        <div class="price_slider_wrap">
                            <h3>Price</h3>
                            <div class="price_slider_inner_wrap">
                                <div id="slider-range" class="price-filter-range" name="rangeInput">
                                    <div class="price_input_common_outer_wrap">
                                        <div class="price_input_common_wrap">
                                            <span>min price</span>
                                            <div class="price_input_common_inner_wrap">
                                                <p>₹</p>
                                                <input type="text" min=0 max="48000" oninput="validity.valid||(value='0');" id="min_price" class="price-range-field" />
                                            </div>
                                        </div>
                                        <span>-</span>
                                        <div class="price_input_common_wrap">
                                            <span>max price</span>
                                            <div class="price_input_common_inner_wrap">
                                                <p>₹</p>
                                                <input type="text" min=2500 max="200000" oninput="validity.valid||(value='200000');" id="max_price" class="price-range-field" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="price_submit_outer_wrap">
                                    <button class="price_submit_wrap">Ok</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <h3>Rest Filter</h3> -->
                        <!-- <h3>More Filters</h3> -->
                    </div>
                </div>
            </div>
            <!-- Categories Models -->
            <div class="modal fade" id="category" tabindex="-1" role="dialog" aria-labelledby="CategoryTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="model_close_btn_wrap close_chat_btn_wrap">
                            <a href="javascript:void(0)" data-dismiss="modal">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="modal-body">
                            <h2 id="CategoryTitle">Categories</h2>
                            <div class="category_scroll_wrap">
                                <div class="category_listing_outer_wrap">
                                    <div class="category_inner_listing">
                                        @foreach($parent as $key =>$parenttree)
                                        <ul class="category_single_wrap">
                                            <h3>{{ucwords($parenttree['name'])}}</h3>
                                            @foreach($sub as $subs)
                                                @if($parenttree['id'] == $subs['parent_id'])
                                                <li class="cateid_{{$subs['parent_id']}}" onclick="choosecategory({{$subs['parent_id']}},{{$subs['id']}})"><a href="javascript:void(0);">{{ucwords($subs['name'])}}</a></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        @endforeach 
                                        <div class="category_single_wrap filter_outer_wrap" style="border-bottom:none !important;display:inline-block !important;">
                                            <h3 id="allcategory">Reset</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="all_ads_pagination">
                    <h3>Show <span id="page-from">0</span> – <span id="page-to">0</span> of <span id="page-total">0</span> Ads</h3>
                    <ul class="pager" id="pager">
                        <!-- <li class="previous_btn"><a href="#">Previous</a></li>
                        <li class="current_btn"><a href="#">1</a></li>
                        <li class="next_btn"><a href="#">Next</a></li> -->
                    </ul>
                </div>
            <div class="row  product_listing_row_wrap">
                <!-- <div class="col-xl-2 col-md-2 col-sm-12 filter_outer_wrap">
                    <h2 class="filter_title_wrap">Filters</h2>
                    
                    <div class="price_range_wrap active">
                        <p>
                        Price Range:
                        </p>
                        <form method="post" action="get_items.php">
                            <input type="hidden" id="amount1">
                            <input type="hidden" id="amount2">
                        </form>
                        <div id="slider-range"></div>
                        <p id="amount"></p>
                    </div>
                </div> -->

                <div class="col-xl-8 col-lg-8 col-md-12 col-12 pl-0 product_list_left_col">
                    
                    <!-- Ads Display -->
                    <div id="products" class="row view-group">
                        
                    </div>
                </div>
                <div class="all_ads_map_outer_wrap col-xl-4 col-lg-4 col-md-12 pl-0 pr-0">
                    <div id="map_view" style="width:100%; height:600px;"></div>
                </div>
            </div>
        </div>
    </div>   

@endsection
@section('scripts')

  <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4
&libraries=places'></script>
  <script src="{{ url('public/js/locationpicker.jquery.min.js') }}"></script>
  <!-- <script src="{{ url('public/js/jquery.timeago.js') }}"></script> -->
  <script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
  
    <script type="text/javascript">
        show_loader();
        var markers      = [];
        var allowRefresh = true;
        localStorage.setItem("sortBy","0");
        if(localStorage.getItem("categoryId")!=''){
            $('.category_single_wrap li.active').removeClass('active');
            catedata='.cateid_'+localStorage.getItem("categoryId");
            $(catedata).addClass('active');
        }
        if(localStorage.getItem("sortBy")=='0'){
            $('#sort_drop').html(' Date Published ');
        }
        if(localStorage.getItem("lng") =='' && localStorage.getItem("lat") ==''){
                    localStorage.setItem("lng",'80.1980416');
                localStorage.setItem("lat",'13.0449408');
        }
        /*navigator.geolocation.watchPosition(function(position) {
            //console.log("i'm tracking you!");
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
              };
                if(localStorage.getItem("lng") =='' && localStorage.getItem("lat") ==''){
                    localStorage.setItem("lng",geolocation.lng);
                    localStorage.setItem("lat",geolocation.lat);
                }
          },
          function(error) {
            if (error.code == error.PERMISSION_DENIED)
                //console.log("you denied me :-(");
                localStorage.setItem("lng",'80.1980416');
                localStorage.setItem("lat",'13.0449408');
                //console.log(localStorage.getItem('lat')+localStorage.getItem('lng'));
          });*/

        /*$("#price-range").slider();
        $("#price-range").on("slideStop", function(slideEvt) {
            allowRefresh = true;
            deleteMarkers();
            getProperties($('#map_view').locationpicker('map').map);
        });

        $('#search-pg-guest').on('change', function(){
            allowRefresh = true;
            deleteMarkers();
            getProperties($('#map_view').locationpicker('map').map);
        });

        $("#search-pg-checkin").datepicker({
            dateFormat: "dd-mm-yy",
            minDate: 0,
            onSelect: function(e) {
                var t = $("#search-pg-checkin").datepicker("getDate");
                t.setDate(t.getDate() + 1), $("#search-pg-checkout").datepicker("option", "minDate", t), setTimeout(function() {
                    $("#search-pg-checkout").datepicker("show")
                }, 20);
                allowRefresh = true;
                getProperties($('#map_view').locationpicker('map').map);
            }
        });

        $("#search-pg-checkout").datepicker({
            dateFormat: "dd-mm-yy",
            minDate: 1,
            onClose: function() {
                var e = $("#checkin").datepicker("getDate"),
                    t = $("#header-search-checkout").datepicker("getDate");
                if (e >= t) {
                    var a = $("#search-pg-checkout").datepicker("option", "minDate");
                    $("#search-pg-checkout").datepicker("setDate", a)
                }
            }, onSelect: function(){
                allowRefresh = true;
                getProperties($('#map_view').locationpicker('map').map);
            }
        });*/


        function showPosition(position) {
            latitudeg=position.coords.latitude;
            longitudeg=position.coords.longitude;
            //console.log('lat '+latitudeg);
            //console.log('lng '+longitudeg);
            localStorage.setItem("lng",longitudeg);
            localStorage.setItem("lat",latitudeg);
        }

        $(document).ready(function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
            /*navigator.geolocation.watchPosition(
                function(position) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                    console.log('Location Tracking...')
                },
                function(error) {
                    if (error.code == error.PERMISSION_DENIED){
                        alert('Location denied')
                        // swal({
                        //     title: "Geolocation is blocked!",
                        //     text: "Looks like your geolocation permissions are blocked. Please, provide geolocation access in your browser settings and get the nearest ads.",
                        //     icon: "warning",
                        //     //button: "Aww yiss!",
                        // });

                    }
            });*/

            $('#map_view').locationpicker({
                location: {
                    latitude: localStorage.getItem("lat"),
                    longitude: localStorage.getItem("lng")
                },
                radius: 0,
                zoom: 15,
                addressFormat: "",
                markerVisible: false,
                markerInCenter: true,
                /*inputBinding: {
                    latitudeInput: $('#latitude'),
                    longitudeInput: $('#longitude'),
                    locationNameInput: $('#address_line_1')
                },*/
                enableAutocomplete: true,
                draggable: true,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    if (allowRefresh == true) {
                        deleteMarkers();
                        getProperties($(this).locationpicker('map').map);
                        //allowRefresh = false;
                        /*localStorage.setItem("cityName","");
                        $('.product_loc_wrap h3').html('Available product</p>');*/
                    }
                },

                oninitialized: function (component) {
                    var addressComponents = $(component).locationpicker('map').location.addressComponents;
                    //updateControls(addressComponents);
                }
            });
        });

        $(document.body).on('click', '.page-data', function(e){
            e.preventDefault();
            var hr = $(this).attr('href');
            allowRefresh = true;
            getProperties($('#map_view').locationpicker('map').map, hr);
            

        });

        $(document.body).on('click','.price_submit_wrap', function(e){
            e.preventDefault();
            allowRefresh = true;
            $(".price_slider_wrap" ).toggleClass( "active" );
            getProperties($('#map_view').locationpicker('map').map);
        });
        $(document.body).on('click','#allcategory', function(e){
            e.preventDefault();
            allowRefresh = true;
            $('.category_single_wrap li.active').removeClass('active');
            localStorage.setItem("categoryId","");
            localStorage.setItem("subCategoryId","");
            $("#category" ).modal('hide');
            getProperties($('#map_view').locationpicker('map').map);
        });
        $(document.body).on('click','#sort_drop_date_published', function(e){
            e.preventDefault();
            allowRefresh = true;
            localStorage.setItem("sortBy","0");
            $('#sort_drop').html(' Date Published ');
            getProperties($('#map_view').locationpicker('map').map);
        });
        $(document.body).on('click','#sort_drop_price_low', function(e){
            e.preventDefault();
            allowRefresh = true;
            localStorage.setItem("sortBy","1");
            $('#sort_drop').html(' Price: Lowest ');
            getProperties($('#map_view').locationpicker('map').map);
        });
        $(document.body).on('click','#sort_drop_price_high', function(e){
            e.preventDefault();
            allowRefresh = true;
            localStorage.setItem("sortBy","2");
            $('#sort_drop').html(' Price: highest ');
            getProperties($('#map_view').locationpicker('map').map);
        });
        
        function choosecategory(categoryId,subCategoryId){
            //alert(id);
            localStorage.setItem("categoryId", categoryId);
            localStorage.setItem("subCategoryId", subCategoryId);
            //$("#choosecategory" ).val(id);
            $("#category" ).modal('hide');
            getProperties($('#map_view').locationpicker('map').map);
            
        }
        ///var contentString = '<div id="content"><ul id="slider"><li><img src="http://localhost/vrent_live/public/images/property/1/1523429218_crowne-plaza-jamaica-2589646442-2x1.jpg" alt=""></li><li><img src="http://localhost/vrent_live/public/images/property/2/1523430028_Property-FourSeasonsHotelNewYorkDowntown-Hotel-GuestroomSuite-RoyalSuiteDiningArea-FourSeasonsHotelsLimited.jpg" alt=""></li><li><img src="http://localhost/vrent_live/public/images/property/3/1523431422_ie_hyatt_andaz_palm_springs_rendering.jpg" alt=""></li><li><img src="http://localhost/vrent_live/public/images/property/2/1523430028_Property-FourSeasonsHotelNewYorkDowntown-Hotel-GuestroomSuite-RoyalSuiteDiningArea-FourSeasonsHotelsLimited.jpg" alt=""></li></ul></div>';

        function addMarker(map, features){
            
            var infowindow = new google.maps.InfoWindow();
            /*var icons = {
                path: "M-20,0a20,20 0 1,0 40,0a20,20 0 1,0 -40,0",
                fillColor: '#FFFFFF',
                fillOpacity: .6,
                anchor: new google.maps.Point(0,0),
                strokeWeight: 0,
                scale: 1
            }*/

             var svg = [
                '<?xml version="1.0"?>',
                    '<svg width="300px" height="100px"  version="1.1" xmlns="http://www.w3.org/2000/svg">',
                        '<rect x="50" y="20" rx="20" ry="20" width="200" height="50" style="fill:#FFFFFF;stroke:rgb(0,0,0,0.1);stroke-width:5;opacity:0.5" />',
                    '</svg>'
                ].join('\n');

            for (var i = 0, feature; feature = features[i]; i++) {
             var marker = new google.maps.Marker({
                    //position: new google.maps.LatLng(newLat,newLng),
                    position: new google.maps.LatLng(feature.latitude,feature.longitude),
                    icon: { url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg), scaledSize: new google.maps.Size(100, 50) },
                    //icon: feature.icon !== undefined? feature.icon : undefined,
                    optimized: false,
                    label: "₹ "+feature.price,
                    map: map,
                    zIndex: 99999999,
                    title: feature.title !== undefined? feature.title : undefined,
                    content: feature.content !== undefined? feature.content : undefined,
                });

                markers.push(marker);

                google.maps.event.addListener(marker, 'click', function (e) {
                    //e.preventDefault();
                    if(this.content){
                        infowindow.setContent(this.content);
                        infowindow.open(map, this);
                    }
                });

                /*google.maps.event.addListener(infowindow, 'domready', function() {
                  $('#slider').anythingSlider();

                });*/

                /*google.maps.event.addListener(map, 'zoom_changed', function(event) {       
                    searchOnZoom(map);
                });*/
            }

        
        }
        
        
      
        // Sets the map on all markers in the array.
        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        // Removes the markers from the map, but keeps them in the array.
        function clearMarkers() {
            setMapOnAll(null);
        }

        // Deletes all markers in the array by removing references to them.
        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }

        function getProperties(map,url){
            url = url||'';
            p = map;
            var a = p.getZoom(),
                t = p.getBounds(),
                o = t.getSouthWest().lat(),
                i = t.getSouthWest().lng(),
                s = t.getNorthEast().lat(),
                r = t.getNorthEast().lng(),
                l = t.getCenter().lat(),
                n = t.getCenter().lng();

            /*var range       = $('#price-range').attr('data-value');
            //var range       = $('#price-range').attr('data-slider-value');
            range           = range.split(',');*/
            var map_details = a + "~" + t + "~" + o + "~" + i + "~" + s + "~" + r + "~" + l + "~" + n;
            //var location    = $('#location').val();

            //Input Search value set
            $('#header-search-form').val(location);
            //Input Search value set
            var min_price       = $('#min_price').val();
            var max_price       = $('#max_price').val();
            var parentcategory  = localStorage.getItem("categoryId");
            var subcategory     = localStorage.getItem("subCategoryId");
            //var location     = localStorage.getItem("cityId");
            var locationName     = localStorage.getItem("cityName");
            var sort     = localStorage.getItem("sortBy");
            //var min_price       = range[0];
            //var max_price       = range[1];
            //var amenities       = getCheckedValueArray('amenities');
            //var property_type   = getCheckedValueArray('property_type');
            //var space_type      = getCheckedValueArray('space_type');
            //var beds            = $('#map-search-min-beds').val();
            //var bathrooms       = $('#map-search-min-bathrooms').val();
            //var bedrooms        = $('#map-search-min-bedrooms').val();
            //var checkin         = $('#search-pg-checkin').val();
            //var checkout        = $('#search-pg-checkout').val();
            //var guest           = $('#search-pg-guest').val();
            //var map_details = map_details;
            var dataURL = '{{url("item")}}';
            if(url != '') dataURL = url;

            if($('#more_filters').css('display') != 'none'){
                $.ajax({
                    url: dataURL,
                    data: {
                        //'location': location,
                        'min_price': min_price,
                        'max_price': max_price,
                        'parentcategory': parentcategory,
                        'subcategory': subcategory,
                        'sort': sort,
                        //'amenities': amenities,
                        //'property_type': property_type,
                        //'space_type': space_type,
                        //'beds': beds,
                        //'bathrooms': bathrooms,
                        //'bedrooms': bedrooms,
                        //'checkin': checkin,
                        //'checkout': checkout,
                        //'guest': guest,
                        'map_details': map_details,
                        '_token': "{{ csrf_token() }}"
                    },
                    type: 'post',
                    //async: false,
                    dataType: 'json',
                    beforeSend: function (){
                        $('#properties_show').html("");
                        show_loader();
                    },
                    success: function (result) {
                        console.log("result",result);
                        $('#page-total').html(result.total);
                        $('#page-from').html(result.from);
                        $('#page-to').html(result.to);
                        $('.total_ads_wrap').html('<p>'+result.total+' Ads Near You </p>'); 
                        if(localStorage.getItem("cityName")!=''){
                            
                            $('.product_loc_wrap h3').html('Available product in  '+locationName.substring(0,20)+'.... </p>');
                        }

                        allowRefresh = false;

                        var pager = '';
                        //var pagination=Math.ceil(result.total/result.per_page);
                        //alert(pagination);
                        if(result.prev_page_url) pager +=  '<li class="previous_btn "><a class="page-data" href="'+result.prev_page_url+'">Previous</a></li>';
                        if(result.current_page) pager +=  '<li class="current_btn"><a href="#">'+result.current_page+'</a></li>';
                        if(result.next_page_url) pager +=  '<li class="next_btn"><a class="page-data" href="'+result.next_page_url+'">Next</a></li>';
                        $('#pager').html(pager);


                        var properties = result.data;
                        var room_point = [];
                        var room_div   = "";
                        for (var key in properties) {
                            if (properties.hasOwnProperty(key)) {
                                //var image = 'data:image/svg+xml,';
                                room_point[key] = {
                                    latitude: properties[key].latitude,
                                    longitude: properties[key].longitude,
                                    price: properties[key].price,
                                    //icon:"",
                                    //label:"saravanan",
                                    //content: '<h5>'+properties[key].name+'</h5>'+'<p>'+properties[key].summary+'</p>'
                                    content: '<a href="#" class="media-cover">'
                                    +'<img style="max-height:150px;max-width:200px;" src="'+APP_URL+'/public/uploads/ads/'+properties[key].image+'" alt="">'
                                    +'</a>'
                                    +'<div style="max-height:150px;max-width:200px;">'
                                    +'<div class="col-xs-12" style="padding:2px 0px;">'
                                    +'<div class="location-title"><h5 style="margin-bottom:0px;">'+properties[key].title+'</h5></div>'
                                    +'<div class="text-muted">'+properties[key].description.substring(0,50)+' ...<a href="'+APP_URL+'/item/'+properties[key].uuid+'" class="media-cover">more>></a>'+'</div>'
                                    +'</div>'
                                    +'</div>'
                                };
                                
                                    review_sec = '';

                                    room_div += '<div class="item col-md-12 col-lg-6 filter"><div class="thumbnail card"><div class="img-event"><img class="group list-group-image img-fluid" src="'+APP_URL+'/public/uploads/ads/'+properties[key].image+'" alt="" />';
                                    var loggedIn = {{ auth()->check() ? 'true' : 'false' }};
                                    if (loggedIn){
                                        var authUserid = "{{{ (Auth::user()) ?Auth::user()->id : null }}}";
                                        if(properties[key].fav_uuid != null){
                                            room_div += '<i class="fa fa-heart heart_wrap favitem" aria-hidden="true" adsuuid-attr="'+properties[key].uuid+'" adsid-attr="'+properties[key].id+'" id="favourite-'+properties[key].id+'"></i>';
                                        }else if(properties[key].seller_id != authUserid){
                                                room_div += '<i class="fa fa-heart-o heart_wrap favitem" aria-hidden="true" adsuuid-attr="'+properties[key].uuid+'" adsid-attr="'+properties[key].id+'" id="favourite-'+properties[key].id+'"></i>';

                                        }else{

                                        }
                                    }else{
                                       room_div += '<i class="fa fa-heart-o heart_wrap favitem" aria-hidden="true" adsuuid-attr="'+properties[key].uuid+'" adsid-attr="'+properties[key].id+'" id="favourite-'+properties[key].id+'"></i>'; 
                                    }
                                    room_div += '</div><div class="caption card-body"><a target="listing_'+properties[key].title+'" href="'+APP_URL+'/item/'+properties[key].uuid+'"><h4 class="group card-title inner list-group-item-heading"> ₹ '+properties[key].price+'</h4><p class="group inner list-group-item-text"><span>'+properties[key].title+'</span><br><span>'+properties[key].cityname+'</span></p></a><div class="row bottom_card_wrap"><div class="col-xs-12 col-md-6 col-sm-4"><p class="day_wrap">'+properties[key].approved_date+'</p></div><div class="col-xs-12 col-md-6 col-sm-8"><a class="view_earn_wrap';
                                    var dNow = new Date();
                                    var localdate= dNow.getFullYear() + '-' + ("0" + (dNow.getMonth() + 1)).slice(-2) + '-' + ("0" + dNow.getDate()).slice(-2) + ' ' + dNow.getHours() + ':' + dNow.getMinutes()+ ':' + dNow.getSeconds();
                                    //alert(localdate);
                                    if(properties[key].point < $('#view_point').attr("view-point") ||  properties[key].point_expire_date < localdate){
                                        room_div += ' free_ads_color_wrap ';

                                    }

                                    room_div += '" href="#">View to Earn</a></div></div></div></div></div>';
                                }
                            }

                            if(room_div != '') $('#products').html(room_div);
                            else $('#products').html('<h2 class="text-center">No Results Found</h2>');

                            //deleteMarkers();
                            addMarker(map, room_point);


                        },
                        error: function (request, error) {
                            allowRefresh = false;
                            // This callback function will trigger on unsuccessful action
                            console.log(error);
                        },
                        complete: function(){
                            hide_loader();
                        }
                });
            }
        }


        $(document.body).on('click','.favitem', function(e){
            //alert($(this).attr("adsuuid-attr"))
            //alert($(this).attr("adsid-attr"))
            fav($(this).attr("adsuuid-attr"),$(this).attr("adsid-attr"));

        }); 
               
        $('.space_type').on('click', function(){
            allowRefresh = true;
            deleteMarkers();
            getProperties($('#map_view').locationpicker('map').map);
            $('.room_filter').addClass('display-off');
            $('#more_filters').show();
        });

        $('#more_filters').on('click', function(){
            $('#more_filters').hide();
            //console.log($('#more_filters').css('display'));
            $('#pagination').hide();
            $('#properties_show').html("");
            $('.room_filter').removeClass('display-off');
            var width = $( window ).width();
            if(width < 980) $('.exPod-btnOn').show();
        });

        $('.filter-cancel').on('click', function(){
            allowRefresh = true;
            $('.room_filter').addClass('display-off');
            $('#more_filters').show();
            $('#pagination').show();
            $('.exPod-btnOn').hide();
            getProperties($('#map_view').locationpicker('map').map);
        });

        $('.filter-apply').on('click', function(){
            allowRefresh = true;
            $('.room_filter').addClass('display-off');
            $('#more_filters').show();
            $('#pagination').show();
            $('.exPod-btnOn').hide();
            deleteMarkers();
            getProperties($('#map_view').locationpicker('map').map);
        });



        function getCheckedValueArray(field_name){
            var array_Value = '';
            /*var i=0;
             $('input[name="'+field_name+'[]"]').each(function() {
             if($(this).prop( "checked" ))
             array_Value[i++] = $(this).val();
             });*/
            array_Value = $('input[name="'+field_name+'[]"]:checked').map(function() {
                return this.value;
            })
                .get()
                .join(',');

            return array_Value;
        }
        /*function searchOnZoom(map){
            var  zoomLevel = map.getZoom();
            var minLevel   = 16;
            if(minLevel<=zoomLevel){
                allowRefresh = true;
                getProperties($('#map_view').locationpicker('map').map);  
            }else{
                console.log(zoomLevel);
                console.log('In Else');
                setMapOnAll(null);
            }
        }*/
        $(document.body).on('click','#map_view',function(){
            allowRefresh = true;
            getProperties($('#map_view').locationpicker('map').map);
        });
        

        //$('.slider-selection').trigger('click');

        $( window ).resize(function() {
            var width = $( window ).width();
            if(width > 980) $('.exPod-btnOn').hide();
        });

        function show_loader(){
            //$('#loader').removeClass('display-off');
            $('.ajax-loader').css("visibility", "visible");
            $('#pagination').hide();
        }

        function hide_loader(){
            //$('#loader').addClass('display-off');
            $('.ajax-loader').css("visibility", "hidden");
            $('#pagination').show();
        }


    </script>
    @endsection