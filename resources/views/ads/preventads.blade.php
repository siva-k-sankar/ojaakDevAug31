@extends('layouts.home')
@section('styles')
<style type="text/css">
  #map {
       position: initial;
        overflow: hidden; 
       height: 240px;
       }
     #map #infowindow-content {
       display: inline;
     }
     
     
     #description {
       font-family: Roboto;
       font-size: 15px;
       font-weight: 300;
     }

     #infowindow-content .title1 {
       font-weight: bold;
     }

     #infowindow-content {
       display: none;
     }


     .pac-card {
       margin: 10px 10px 0 0;
       border-radius: 2px 0 0 2px;
       box-sizing: border-box;
       -moz-box-sizing: border-box;
       outline: none;
       box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
       background-color: #fff;
       font-family: Roboto;
     }

     #pac-container {
       padding-bottom: 12px;
       margin-right: 12px;
     }

     .pac-controls {
       display: inline-block;
       padding: 5px 11px;
     }

     .pac-controls label {
       font-family: Roboto;
       font-size: 13px;
       font-weight: 300;
     }

     #autocomplete {
       background-color: #fff;
       font-family: Roboto;
       font-size: 16px;
       font-weight: 300;
       margin-left: 12px;
       padding: 0 11px 0 13px;
       text-overflow: ellipsis;
       width: 239px;
       height: 35px;
       top:15px !important;
     }

     #autocomplete:focus {
       border-color: #4d90fe;
     }

     #title1{
       color: #fff;
       background-color: #4d90fe;
       font-size: 25px;
       font-weight: 500;
       padding: 6px 12px;
     }
     #target {
       width: 345px;
     }
     .box-size.ads_post_box_wrap.add_ads_bg_wrap {width: 60vw !important; }
@media(max-width:768px) {
  .box-size.ads_post_box_wrap.add_ads_bg_wrap {width: 90vw !important; }
    #autocomplete {
       background-color: #fff;
       font-family: Roboto;
       font-size: 14px;
       font-weight: 300;
       margin-left: 0px;
       padding: 0 11px 0 13px;
       text-overflow: ellipsis;
       width: 230px;
       height: 30px;
       top:15px;
     }
}  
</style>

<link rel="stylesheet" type="text/css" href="{{ asset('public/css/image-uploader.min.css') }}" >
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" type="text/css" href="{{asset('public/css/tagmanager.min.css')}}">
<link href="https://fonts.googleapis.com/css?family=Lato:300,700|Montserrat:300,400,500,600,700|Source+Code+Pro&display=swap"
          rel="stylesheet">
@endsection
@section('content')
<!-- Add Ads -->
    <div class="container-fluid pl-0 pr-0 add_ads_outer_wrap">
        <div class="box-size ads_post_box_wrap add_ads_bg_wrap">
            <form action="{{route('ads.prevent.clear')}}" id="ResetadsForm" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="preventadsid" name="preventadsid" value="{{$ads->uuid}}">
            </form>
            <form action="{{route('ads.prevent.update')}}" id="updateads" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="id" name="id" value="{{$ads->uuid}}">
                <input type="hidden" id="city" value="{{$ads->cities}}">
                <input type="hidden" id="in-tag" value="{{$ads->tags}}">
                <input type="hidden" id="in-images" value="{{$ads->images}}">
                <input type="hidden" id="latitude" name="latitude" value="{{$ads->latitude}}">
                <input type="hidden" id="longitude" name="longitude" value="{{$ads->longitude}}">
                <div class="row post_ads_title_wrap">
                    <div class="col-md-6">
                        <h2>Post your ad</h2>
                        <p>{{ucwords($parent->name)}} / {{ucwords($sub->name)}}</p>
                    </div>
                    <div class="col-md-6">
                        <div class="post_now_btn_wrap common_btn_wrap">
                            <div class="common_btn_wrap contact_submit_btn_wrap">
                                <button type="button" class=""style="background-color: red !important;" id="resetpost">Back to Home</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="postdiv"  style="" class="row ads_form_field_wrap">
                    <div class="col-lg-12 col-md-12 pl-0">
                        @if (isset($errors) and $errors->any())
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><strong>{{ __('Oops ! An error has occurred.') }}</strong></h5>
                                <ul class="list list-check">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-12 col-md-12 ads_post_common_pad_wrap pl-0">
                        <div class="form-group fields_common_wrap">
                            <label>Post Title <sup>*</sup></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="" pattern="(.){10,35}" minlength="10" maxlength="35" required value="{{$ads->title}}">
                            <small id="titleHelp" class="form-text text-muted">Title Accept Max 35 And Min 10 Characters Only</small>
                                <!-- @error('title')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror -->
                        </div>


                        @if(count($brands) > 0)
                        <div class="form-group">
                            <label>Brands <sup>*</sup></label>
                            <select class="form-control" id="brands" name="brands" required="">
                                <option value='0'>Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{$brand->id}}" <?php if($ads->brand_id == $brand->id){ echo "selected"; } ?>>{{$brand->brand}}</option>                                    
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group brandmodel">
                            <label>Models <sup>*</sup></label>                            
                            <select class="form-control" id="models" name="models" required="">
                                <option value='0'>Select Brand</option>
                                @foreach($category_models as $models)
                                    <option value="{{$models->id}}"  <?php if($ads->model_id == $models->id){ echo "selected"; } ?>>{{$models->model}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="brandmodeldiv">

                            </div>
                        </div>

                        @endif

                        <div class="ads_post_common_pad_wrap">
                        <!-- <div class="image_upload_wrap fields_common_wrap">
                            <label>Images <sup>*</sup></label>
                            <div class="wrap-custom-file" style="text-align: left !important; ">
                              <div class="input-field ">
                                    <div class="input-images-1" style="padding-top: .5rem;"></div>
                                </div>
                            </div>
                        </div> -->
                        @if(!empty($customfields))
                            {!! $customfields !!}
                        @endif
                    </div>
                        
                        
                        <div class="image_upload_wrap fields_common_wrap">
                            <label>Images <sup>*</sup></label>
                            <div class="wrap-custom-file" style="text-align: left !important; ">
                              <div class="input-field ">
                                    <div class="input-images-1" style="padding-top: .5rem;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group fields_common_wrap">
                            <label>Description <sup>*</sup></label>
                            <textarea class="ckeditor @error('description') is-invalid @enderror" id="description" cols="80" rows="10" name="description" placeholder="" required>{{$ads->description}}</textarea>
                                <!-- @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror -->
                        </div>
                        <div class="form-group fields_common_wrap">
                            <label>Price <sup>*</sup></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{$ads->price}}" pattern="(.){1,12}" maxlength="12" onkeypress="return isNumber(event)" placeholder="" required>
                            <!-- @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror -->
                        </div>
                        <!-- <div class="form-group fields_common_wrap">
                            <label>Tags </label>
                            <input type="text" name="tags" id="tags"placeholder="Write Some Tags" class="form-control tm-input tm-input-info "  />
                            @error('tags')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div> -->
                        <!-- <div class="form-group fields_common_wrap">
                            <label>Address <sup>*</sup></label>
                            <input type="text" name="addresshow" id="addresshow" class="form-control" disabled=""  value="{{$getfulladress}}"/>
                            <a href="javascript:void(0);" id="changes_address">Change Address</a>
                            @error('addresshow')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="map_outer_wrap fields_common_wrap" id="gmap" style="display: none;">
                            <label>Locate in Map <sup>*</sup></label><br>
                            <div id="map"></div>
                            <input id="autocomplete" name="map" class="controls" type="text" placeholder="Search Places">
                        </div> -->
                        <div class="form-group common_select_option contact_form_select_btn_wrap">
                            <label>State <sup>*</sup></label>
                            <select class="form-control" name="state" id="state_form" required> <option value="" hidden>Select State</option>
                                @foreach($states as $ids => $state)
                                    <option value="{{$state->id}}" <?php if($state->id == $stateid->state_id){echo "selected"; } ?> >{{$state->name}}</option>
                                @endforeach
                            </select>
                                
                        </div>
                        <div class="form-group common_select_option contact_form_select_btn_wrap">
                            <label>City <sup>*</sup></label>
                            <select class="form-control" name="cities" id="cities_form" required>
                            </select>
                        </div>
                        <div class="form-group common_select_option contact_form_select_btn_wrap">
                            <label>Area <sup>*</sup></label>
                            <select class="form-control" name="areas" id="area_form" required="">
                            </select>
                        </div>
                        <div class="form-group fields_common_wrap">
                            <label>Pincode <sup>*</sup></label>
                            <input type="number" class="form-control" name="pincode" id="pincode_form"  value="{{$ads->pincode}}" required="" maxlength="6">
                        </div>
                        
                        <div class="show_contact_detail_wrap form-group">
                            <label>Contact Details</label>
                            <div class="show_detail_inner_wrap">
                                <p>Show your information</p>
                                <label class="switch">
                                    <input type="hidden"  name="information"  value="0">
                                  <input type="checkbox"  @if($ads->seller_information==1)  checked @endif name="information" id="information" value="1">
                                  <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group fields_common_wrap" id='seller_name'>
                            <label>Seller Name <sup>*</sup></label>
                            <input type="text" class="form-control  @error('sell-phone') is-invalid @enderror"  value="<?php if($ads->name !=''){echo $ads->name;}else{
                                echo(Auth::user()->name); } ?>"id="sell-name" name="sell-name"placeholder="" required>
                                <!-- @error('sell-name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror -->
                        </div>
                        <div class="form-group fields_common_wrap" id='seller_phone'>
                            <label>Seller Phone <sup>*</sup></label>
                            <input type="tel" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" title="Please enter 10 digit valid mobile number"class="form-control  @error('sell-phone') is-invalid @enderror"  value="<?php if($ads->phone_no !=''){echo $ads->phone_no;}else{
                                echo(Auth::user()->phone_no); } ?>"id="sell-phone" name="sell-phone"placeholder="" required>
                                <!-- @error('sell-phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                @enderror -->
                        </div>
                        

                    </div>
                    
                    <!-- <div class="col-lg-6 col-md-6 ads_post_common_pad_wrap pl-0">
                        
                    </div> -->
                    <div class="col-lg-12 col-md-12 ads_post_common_pad_wrap  pr-0">
                        <div class=" common_btn_wrap">
                            <div class="common_btn_wrap contact_submit_btn_wrap">
                                <button type="submit" class="" id="saveplanbtn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form> 
        </div>
    </div>
    
@endsection
@section('scripts')
<script src="{{ asset('public/js/tagmanager.min.js') }}"></script>
<script src="{{ asset('public/js/image-uploader.min.js') }}" ></script>

<script type="text/javascript">
    var tag=$("#in-tag").val();
    $(".tm-input").tagsManager({
            prefilled: tag,
    });
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    var imageup=$("#in-images").val();
    let preloadedimage = JSON.parse(imageup);
    $('.input-images-1').imageUploader({
        preloaded: preloadedimage,
        extensions: ['.jpg','.jpeg','.png'],
        mimes: ['image/jpg','image/jpeg','image/png'],
        imagesInputName: 'photos',
        preloadedInputName: 'old',
        maxSize: 2 * 1024 * 1024,
        maxFiles: 5
    });
    $(document).ready(function(){
        $('input[id="information"]').change(function(){
            if($(this).prop("checked") == true){
                $('#seller_phone').show();
                $('#seller_name').show();
            }
            else if($(this).prop("checked") == false){
                $('#seller_phone').hide();
                $('#seller_name').hide();
            }
        });
        if($('input[id="information"]').is(':checked')){
            $('#seller_phone').show();
            $('#seller_name').show();
        }else{
            $('#seller_phone').hide();
            $('#seller_name').hide();
        }

        $('#brands').change(function(){
            //alert($(this).val());
            if($(this).val() !=0){
                $.ajax({
                    type: "post",
                    url: "<?php echo url('getbrandmodels'); ?>",
                    data: "brandId="+$(this).val(),
                    success: function(data){  
                        if(data == '0'){
                            alert('no data');
                        }else{
                            $(".brandmodel").remove();
                            $('.brandmodeldiv').html(data);
                        }
                    }
                });
            }

        });
        var citykey='';
        $( "#state_form" ).change(function() {
            if($(this).val() !=0){
                $('#cities_form').html('');
                $('#area_form').html('');
                $('#pincode_form').html('');
                citykey='';
                $.ajax({
                    type: "post",
                    url: "<?php echo url('getcities'); ?>",
                    data: "stateid="+$(this).val(),
                    success: function(data){ 
                        let datacities = JSON.parse(data)
                        jQuery.each(datacities, function(key, value) {
                            if(citykey==''){
                                citykey=key; 
                            }
                          $('#cities_form').append("<option value="+key+">"+value+"</option>");
                        });
                        if(citykey !=''){
                            $.ajax({
                                type: "post",
                                url: "<?php echo url('getareas'); ?>",
                                data: "cityid="+citykey,
                                success: function(data){ 
                                let dataareas = JSON.parse(data)
                                jQuery.each(dataareas, function(key, value) {
                                  $('#area_form').append("<option value="+key+">"+value+"</option>");
                                });
                                   
                                }
                            });
                        }
                       
                    }
                });
            }
        });
        $( "#cities_form" ).change(function() {
            if($(this).val() !=0){
                $('#area_form').html('');
                $('#pincode_form').html('');
                $.ajax({
                    type: "post",
                    url: "<?php echo url('getareas'); ?>",
                    data: "cityid="+$(this).val(),
                    success: function(data){ 
                    let dataareas = JSON.parse(data)
                    jQuery.each(dataareas, function(key, value) {
                      $('#area_form').append("<option value="+key+">"+value+"</option>");
                    });
                       
                    }
                });
            }
        });

        $( "#area_form" ).change(function() {
            if($(this).val() !=0){
                $('#pincode_form').html('');
                $.ajax({
                    type: "post",
                    url: "<?php echo url('getpincode'); ?>",
                    data: "areaid="+$(this).val(),
                    success: function(data){ 
                        let dataareas = JSON.parse(data)
                        console.log("dataareas",dataareas[0]);
                        $('#pincode_form').val(dataareas[0]);
                    /*$.each(dataareas, function(key, value) {
                      $('#pincode_form').val(value);
                    });*/
                       
                    }
                });
            }
        });

        var stateid=$('#state_form option:selected').val();
        //var cityid=$('#cities_form option:selected').val();
        var selectarea="{{$ads->area_id}}";
        var selectcityid="{{$ads->cities}}";

        if(stateid !=0){
                $.ajax({
                    type: "post",
                    url: "<?php echo url('getcities'); ?>",
                    data: "stateid="+stateid,
                    success: function(data){ 
                    let datacities = JSON.parse(data)
                    jQuery.each(datacities, function(key, value) {
                      if(key==selectcityid){
                        $('#cities_form').append("<option value="+key+" selected >"+value+"</option>");
                        //cityid=key;
                      }else{
                        $('#cities_form').append("<option value="+key+">"+value+"</option>");
                      }  
                      
                    });
                       
                    }
                });

        }

        if(selectcityid !=0){
            $.ajax({
                type: "post",
                url: "<?php echo url('getareas'); ?>",
                data: "cityid="+selectcityid,
                success: function(data){ 
                let dataareas = JSON.parse(data)
                jQuery.each(dataareas, function(key, value) {
                    if(key==selectarea){
                        $('#area_form').append("<option value="+key+" selected >"+value+"</option>");
                      }else{
                        $('#area_form').append("<option value="+key+">"+value+"</option>");
                      } 
                });
                   
                }
            });
        }
    });
</script>
<script type="text/javascript">
    
var latitudeg=13.0827;
    var longitudeg=80.2707;
    var zoomlevel=12;

    $("#changes_address").click(function(e){
        $("#addresshow").attr("disabled","disabled");
        $("#gmap").show();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
            initAutocomplete();
        }
    });

    /*createInputOfLatLang(latitudeg,longitudeg);
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
        initAutocomplete();
    }*/
    function getaddress(latitudeg,longitudeg){
        $("#addresshow").attr("disabled","disabled");
        console.log("getaddress",latitudeg);
        console.log(longitudeg);

        $.ajax({
            type: "post",
            url: "<?php echo url('getaddress'); ?>",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: "latitude="+latitudeg+"&longitude="+longitudeg,
            success: function(data){  
               $('#addresshow').val(data); 
            }
        });

    }
            
    function showPosition(position) {
        $("#addresshow").attr("disabled","disabled");
        var google;
        latitudeg=position.coords.latitude;
        longitudeg=position.coords.longitude;
        /*console.log('lat'+latitudeg)
        console.log('lng'+longitudeg)*/
        zoomlevel=16;
        createInputOfLatLang(latitudeg,longitudeg);
        getaddress(latitudeg,longitudeg);
    }

    function initAutocomplete() {
        $("#addresshow").attr("disabled","disabled");

        var mapProp= {
            center:new google.maps.LatLng(latitudeg,longitudeg),
            zoom:zoomlevel,
            mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
            },
            disableDefaultUI: true, // a way to quickly hide all controls
            mapTypeControl: true,
            scaleControl: true,
            zoomControl: true,
            zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE 
            },
            
        };
        var map=new google.maps.Map(document.getElementById("map"),mapProp);


        var input = document.getElementById('autocomplete');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
            map.fitBounds(bounds);
            var bound = bounds.getCenter();
            var data=JSON.stringify(bound);
            var parsedata = JSON.parse(data);
            createInputOfLatLang(parsedata.lat,parsedata.lng);
        });

        map.addListener('click', function(e) {
            //alert(e.latLng.lat() + ", " + e.latLng.lng());
            placeMarker(e.latLng, map);
            createInputOfLatLang(e.latLng.lat(),e.latLng.lng());
            getaddress(e.latLng.lat(),e.latLng.lng());
        });
        

        var marker;
        function placeMarker(location) {
            if (marker) {
                //if marker already was created change positon
                marker.setPosition(location);
            } else {
                //create a marker
                marker = new google.maps.Marker({          
                    position: location,
                    map: map,
                    draggable: false
                });
            }
        }

    }

    function createInputOfLatLang(latitude,longitude) {
        $("#addresshow").attr("disabled","disabled");
        $( "#latitude" ).remove();
        $( "#longitude" ).remove();    
        $( "#updateads" ).prepend( '<input type="hidden" id="latitude" name="latitude" value="'+ latitude +'"><input type="hidden" id="longitude" name="longitude" value="'+longitude+'">' );
    }
</script>
<script type="text/javascript">
    // Image upload
    $('input[type="file"]').each(function(){
      var $file = $(this),
          $label = $file.next('label'),
          $labelText = $label.find('span'),
          labelDefault = $labelText.text();
      $file.on('change',function(event){
        var fileName = $file.val().split('\\' ).pop(),
            tmppath = URL.createObjectURL(event.target.files[0]);
        if( fileName ){
          $label.addClass('file-ok').css('background-image','url(' + tmppath +')');
          $labelText.text(fileName);
        }else{
          $label.removeClass('file-ok');
          $labelText.text(labelDefault);
        }
      });
    });

    $("#resetpost").click(function(){
        swal({
          title: "Are you sure?",
          text: "Entered Ad details will not be saved. Do you want to proceed ?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $("#ResetadsForm").submit();
          } else {
            swal("Your record is safe!");
          }
        });
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC3N0dNh2eXa9jIjfj2tl6CNvkPPJUAO4
&libraries=places&callback=initAutocomplete"
         async defer></script>

@endsection