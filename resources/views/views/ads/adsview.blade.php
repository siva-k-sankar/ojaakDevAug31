@extends('layouts.home')
@section('styles')
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('public/frontdesign/assets/price-filter.css') }}">
<script src="{{ asset('public/frontdesign/assets/price-filter.js') }}"></script>
<style type="text/css">
        .box-size {width: 1300px !important; }

        @media(max-width:1280px){
            .box-size {width: 95vw !important; }            
        }
         /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {-webkit-appearance: none; margin: 0; }
        /* Firefox */
        input[type=number] {-moz-appearance:textfield; }
        div#location li {
            cursor: pointer;
            padding-left: 8px;
        }
        .search_result_loadmore{
            text-align: center;
        }
        *:focus {outline: none; }
    </style>
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
                </div>
              </ol>
            </div>
        </nav>
    </div>

<!-- Mobile Search  -->
    <div class="ads_listing_mobile_search_wrap">
    </div> 

<!-- display all ads  -->
    <div class="container-fluid all_ads_outer_wrap">
        <div class="box-size">
            <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-12 col-sm-12 filter_outer_wrap">

                    <h2 class="filter_title_wrap">Filters  <div style="float: right;cursor: pointer;" onclick="resetall()"><i class="fa fa-trash" aria-hidden="true"></i></div></h2>


                    <div class = "panel-group hide">
                        <div class = "panel panel-default">
                           <div class = "panel-heading">
                              <h4 class = "panel-title">
                                 <a data-toggle = "collapse" href = "#categories">Categories</a>
                              </h4>
                           </div>
                           <div id = "categories" class="panel-collapse collapse show">
                                <ul class="list-group">

                                    <li class = "list-group-item" onclick="resetall()">All Categories</li>

                                </ul>
                           </div>
                        </div>
                    </div>
                    <div class = "panel-group location_outer_wrap hide">
                        <div class = "panel panel-default">
                           <div class = "panel-heading">
                              <h4 class = "panel-title">
                                 <a data-toggle = "collapse" href = "#location_new">Locations</a>
                              </h4>
                           </div>
                           <div id = "location_new" class="panel-collapse collapse show">
                                <div class="location_filter_searrch_wrap">
                                    <input type="text" id="location_search_wrap" onkeyup="location_search()" placeholder="Search for more localities" >
                                </div>
                                <div id="location">
                                    
                                </div>
                           </div>
                        </div>
                    </div>
                    <div class = "panel-group price_panel_outer_wrap hide">
                        <div class = "panel panel-default">
                           <div class = "panel-heading">
                              <h4 class = "panel-title">
                                 <a data-toggle = "collapse" href = "#price_panel">Price</a>
                              </h4>
                           </div>
                           <div id = "price_panel" class="panel-collapse collapse show">
                                <div class="price_slider_inner_wrap">
                                    <div id="slider-range" class="price-filter-range" name="rangeInput">
                                        <div class="price_input_common_outer_wrap">
                                            <div class="price_input_common_wrap">
                                                <span>min price</span>
                                                <div class="price_input_common_inner_wrap">
                                                    <p>₹</p>
                                                    <input type="text" min=0  oninput="validity.valid||(value='0');" id="minprice" class="price-range-field" />
                                                </div>
                                            </div>
                                            <span>-</span>
                                            <div class="price_input_common_wrap">
                                                <span>max price</span>
                                                <div class="price_input_common_inner_wrap">
                                                    <p>₹</p>
                                                    <input type="text" min="0" oninput="validity.valid||(value='50000');" id="maxprice" class="price-range-field" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="price_submit_outer_wrap">
                                            <button class="price_submit_wrap" onclick="price()">Ok</button>
                                        </div>
                                    </div>
                                </div>
                           </div>
                       </div>
                   </div>
                    <div class = "panel-group common_cat_list_wrap hide extra" style="display: none;">
                        <div class = "panel panel-default">
                           <div class = "panel-heading">
                              <h4 class = "panel-title">
                                 <a data-toggle = "collapse" href = "#ExtraFillter">Extra Fillter</a>
                              </h4>
                           </div>
                           <div id ="ExtraFillter" class="panel-collapse collapse show">
                            
                           </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-10 col-lg-9 col-md-12 col-sm-12">
                    <div class="row sorting_row_wrap">
                        <div class="col-lg-6 col-md-4 pl-0 left_side_content_wrap">
                            <div class="total_ads_wrap">
                                <!-- <p>16,823 ads in India</p> -->
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-8">
                            <div class="right_side_content_wrap">
                                <p>View <strong> :</strong></p>
                                <div class="btn-group">
                                    <a class="" id="list" data-listing="0">
                                        <i class="fa fa-th-list" aria-hidden="true"></i>
                                    </a>
                                    <a class="active" id="grid">
                                        <i class="fa fa-th-large" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="sort_outer_wrap">
                                    <h3>Sort By:</h3>
                                    <div class="sort_dropdown">
                                        <select  id="sortingfilters">
                                            <option value="0" selected >Date Published</option>
                                            <option value="1">Price (Low - High)</option>
                                            <option value="2">Price (High - Low)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ads Display -->
                    <div class="product_list_left_col">
                        <div id="products" class="row view-group">
                            
                        </div>
                        
                    </div>
                    <div class="search_result_loadmore">
                            <div id="nodatafound" style="display: none;"> No More Data Found</div>
                    </div>
                    <div class="load_more_outer_wrap">
                        <a class="load-more" href="javascript:void(0)">Load More</a>
                        <input type="hidden" id="row" value="0">
                        <input type="hidden" id="all" value="10">
                        <input type="hidden" id="cate" value="0">
                        <input type="hidden" id="subcate" value="0">
                        <input type="hidden" id="state" value="0">
                        <input type="hidden" id="city" value="0">
                        <input type="hidden" id="area" value="0">
                        <input type="hidden" id="fillter" value="">
                        <input type="hidden" id="customFilters" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script language="JavaScript" type="text/javascript" defer src="{{ asset('public/frontdesign/assets/jquery.mCustomScrollbar.js') }}"></script>
<script type="text/javascript">
    //$(window).on("load",function(){
     //  if(localStorage.getItem("categoryId")!=''){
     //       var category;
     //       category=localStorage.getItem("categoryId");
     //       $("#cate").val(category);//alert(category);
     //   }
    // });
</script>
<script type="text/javascript">
    
     

    if(!localStorage.getItem("minprice")===true || !localStorage.getItem("maxprice")===true ){
        localStorage.setItem("minprice",'0');
        localStorage.setItem("maxprice",'50000000');
    }
    setTimeout(function(){
        //alert(localStorage.getItem("squery"))
        console.log(localStorage.getItem("squery"));
        if(localStorage.getItem("cityName")!=''){
            //alert(localStorage.getItem("cityName"));
            $(".locationinput").val(localStorage.getItem("cityName"));
            //$(".headerSearch").trigger('click');
        }
        if(localStorage.getItem("squery")!=''){
            $(".searchbox").val(localStorage.getItem("squery"));
            //$(".headerSearch").trigger('click');
        }
    }, 1000);

    if(!localStorage.getItem("state")=== true){ 
        localStorage.setItem("state","0");
        $("#state").val('0');
    }else{
        if(localStorage.getItem("state")!='0' || localStorage.getItem("state")!='' || localStorage.getItem("state")!= null ){
            $("#state").val(localStorage.getItem("state"));
        }else{
            localStorage.setItem("state","0");
            $("#state").val('0');
        }
    }

    if(!localStorage.getItem("cityId")=== true){ 
        localStorage.setItem("cityId","0");
        localStorage.setItem("cityName",'');
        $("#city").val('0');
    }else{
        if(localStorage.getItem("cityId")=='0' || localStorage.getItem("cityId")!='' || localStorage.getItem("cityId")!= null ){
            $("#city").val(localStorage.getItem("cityId"));
        }else{
            localStorage.setItem("cityId","0");
            localStorage.setItem("cityName",'');
            $("#city").val('0');
        }
    }

    if(!localStorage.getItem("areaId")=== true){ 
        localStorage.setItem("areaId","0");
        $("#area").val('0');
    }else{
        if(localStorage.getItem("areaId")!='0' || localStorage.getItem("areaId")!='' || localStorage.getItem("areaId")!= null ){
            $("#area").val(localStorage.getItem("areaId"));
        }else{
            localStorage.setItem("areaId","0");
            $("#area").val('0');
        }
    }

    /*if(!localStorage.getItem("query")===true){
        localStorage.setItem("query","");
    }*/
    // setTimeout(function(){
    //     if(localStorage.getItem("query")!=''){
    //         $("#searchbox").val(localStorage.getItem("query"));
    //     }
    // }, 200);
    $("#minprice").val(localStorage.getItem("minprice"));
    $("#maxprice").val(localStorage.getItem("maxprice"));
    
    var _token = "{{ csrf_token() }}";
    var string1 = '';

    checkstateId = localStorage.getItem("stateId");
    //console.log("old",checkstateId);
    if(checkstateId == null){
        var stateValue = [];
    }else{
        var stateValue = checkstateId.split(",");
    }

    /*parameterValues = localStorage.getItem("parameter");
    console.log("old parameterValues",parameterValues);
    if(parameterValues == null){
        var parameter = [];
    }else{
        var parameter = parameterValues;
    }*/
    var parameter = [];
    
    function reset(){
        $("#row").val('0');
        $("#products").html('');
    }
    
    function resetall(){
        $("#customFilters").val("0");
        parameter = [];
        stateValue = [];
        parameter.length = 0;
        /*setTimeout(function() {
            $("#state").val("0");
            //alert(localStorage.getItem("stateId"))
            localStorage.removeItem("stateId");
            window.localStorage.removeItem('stateId');
            localStorage.setItem("stateId","0");

            //alert(localStorage.getItem("stateId"));
        }, 600);*/

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
        var query="";
        $("#searchbox").val('');
        categoriesSelect('0','0');

        ChangeUrl('Page1', APP_URL+'/items');
    }
    
    function categoriesSelect(cate,subcate){

        var searchbox=$("#searchbox").val();
        if(searchbox == ''){
        console.log("EEE",searchbox);
            localStorage.setItem("squery","");
            var query="";
        }else{
        console.log("WWWW",searchbox);
            localStorage.setItem("squery",searchbox);
            var query=searchbox;
        }

        if(localStorage.getItem("squery")!=''){
            $(".searchbox").val(localStorage.getItem("squery"));
        }


        $("#cate").val(cate);
        $("#subcate").val(subcate);
        console.log("query",query);
        //$("#customFilters").val("0");
        var cfs = $("#customFilters").val();
        if(cfs == null || cfs == 0){
            $("#customFilters").val("0");
        }


        if(cate != '0' && subcate !='0'){

            localStorage.setItem("squery","");
            var query="";
            $("#searchbox").val("");
        }



        if(cate == '0' && subcate =='0'){
            $("#customFilters").val("0");
            parameter = [];
            parameter.length = 0;
            /*setTimeout(function() {
                $("#state").val("0");
                //alert(localStorage.getItem("stateId"))
                localStorage.removeItem("stateId");
                window.localStorage.removeItem('stateId');
                localStorage.setItem("stateId","0");

                //alert(localStorage.getItem("stateId"));
            }, 600);*/
            var locationinput = $(".locationinput").val();
            console.log('locationinput',locationinput);
            if(locationinput == ''){
                //$("#state").val("0");
                $("#area").val("0");
                $("#city").val("0");
                /*localStorage.removeItem("stateId");
                window.localStorage.removeItem('stateId');
                localStorage.setItem("stateId","0");*/
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
            }


            //$("#searchbox").val("");
            //localStorage.setItem("squery","");

            ChangeUrl('Page1', APP_URL+'/items');
        }

        localStorage.setItem("categoryId",cate);
        localStorage.setItem("subCategoryId",subcate);


        $("#ExtraFillter").html('');
        var fd = new FormData();
        fd.append('_token', _token);
        fd.append('cate', cate); 
        fd.append('subcate', subcate); 
        fd.append('customFilters', cfs);
        fd.append('query', query);
        $.ajax({
            url: APP_URL+'/loadmoreitemsExtra',
            type: 'post',
            data: fd,
            contentType: false, 
            processData: false, 
            beforeSend:function(){
                $(".load-more").text("Loading...");
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function(response){
                //console.log(response)
                setTimeout(function() {
                    $('.ajax-loader').css("visibility", "hidden");
                    $(".load-more").text("Load more");
                    if($.trim(response)=== ""){
                        $(".extra").hide();
                    }else{
                        $(".extra").show();
                        $("#ExtraFillter").append(response).show().fadeIn("slow");
                    }
                reset()
                load_category();
                load_location();
                load_filterdata();
                //loadmaxprice();

                }, 1000);

            }
        });

    }

   
    function locate(state,city){
        
        var checkbox = document.getElementById(state);
        if($(checkbox).prop("checked") == true){
            //alert(state)

            stateValue.push(state);
            stateValue = getUnique(stateValue);

            console.log("checked",stateValue);
            $("#state").val(stateValue);
            $("#city").val(city);
            localStorage.setItem("stateId",stateValue);
            
        }
        else if($(checkbox).prop("checked") == false){
            const index = stateValue.indexOf(state);
           
            if (index > -1) {
              stateValue.splice(index, 1);
              //localStorage.setItem("stateId",stateValue);
               //console.log("after remove",stateValue);
            }
            localStorage.setItem("stateId",0);
            localStorage.setItem("stateId",stateValue);
            $("#state").val(stateValue);
            $("#city").val(city);
            
           
        }
        //console.log("stateValue",stateValue);
        reset();
        load_category();
        load_location();
        load_filterdata();

       
    }
    function getUnique(array){
        var uniqueArray = [];
        
        // Loop through array values
        for(i=0; i < array.length; i++){
            if(uniqueArray.indexOf(array[i]) === -1) {
                uniqueArray.push(array[i]);
            }
        }
        return uniqueArray;
    }

    function price(){
            
            var minprice=$("#minprice").val();
            var maxprice=$("#maxprice").val();
            localStorage.setItem("minprice",minprice);
            localStorage.setItem("maxprice",maxprice);
            var price=minprice+';'+maxprice;

            load_category();
            load_location(); 
            reset();
            load_filterdata();
            // if(minprice!='' && maxprice!='' ){
            //     load_category();
            //     load_location(); 
            //     reset();
            //     load_filterdata();
                
            // }else{
            //     toastr.warning('Plz Enter Min or Max Price...');
            // }
            
    }

    $(document).ready(function() {

        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName;
                return sURLVariables;

        };

        var searchText = getUrlParameter();
        getsearchparameters(searchText);
        
        //setTimeout(function(){ loading_first_time() },1000);

        $('.load-more').click(function(){

            var rowperpage = 10;
            var rowno = parseInt($("#row").val()) + parseInt(rowperpage);
            $("#row").val(rowno);
            load_filterdata();
            //loadmaxprice();
        });

        $('#sortingfilters').change(function(){
            reset()
            load_filterdata();            
        });

        /*$('.customfselect').change(function(){
           alert('j');            
        });*/
        $('body').on('change', '.customfselect', function(e){
            //console.log('qqq',parameter);


            var string2name = $(this).attr('data-customfieldid');
            var string2value = '='+$(this).val();
            var attrName = string1.concat(string2name);
            var result = attrName.concat(string2value);

            //parameterCount++;
            //var result = '2=2';
            getparameter(result);
            getselectbtnparameter();
            //getselectparameter();
            //console.log('ttt',parameter);
            return false;

        });

        $('body').on('keyup', '.customfinputtext', function(e){

            var string1min = '';

            var string1max = '';
            var customfinputname = $(this).attr('data-customfieldid');
            var customfinputvalue = '='+$(this).val();
            var attrName = string1max.concat(customfinputname);
            var finresult = attrName.concat(customfinputvalue);
            console.log('customfinputname',customfinputname);
            //var finresult = minresult+','+maxresult;
            getparameter(finresult);


            //getparameter(result);
        });

        $('body').on('click', '.customfinputclick', function(e){

            var string1min = '';

            var string1max = '';
            var customfinputmaxname = $('.customfinputmax').attr('data-customfieldid');
            var customfinputmaxvalue = '='+$('.customfinputmin').val()+','+$('.customfinputmax').val();
            var attrName = string1max.concat(customfinputmaxname);
            var finresult = attrName.concat(customfinputmaxvalue);

            getparameter(finresult);
        });


        $('body').on('click', '.customfcheckbox', function(e){
            if ($(this).is(":checked")){
            //$( ".customfcheckbox option:selected" ).text();
                var string2name = $(this).attr('data-customfieldid');
                var string2value = '='+$(this).val();
                var attrName = string1.concat(string2name);
                var result = attrName.concat(string2value);
                //parameterCount++;
                getparameter(result);
            }else{

                var string2name = $(this).attr('data-customfieldid');
                var string2value = '='+$(this).val();
                var attrName = string1.concat(string2name);
                var result = attrName.concat(string2value);
                for(var i=0; i<parameter.length; i++) {
                    if(parameter[i] == result) {
                        parameter.splice(i, 1);
                    }
                }
            }
        });
        
        $('body').on('click', '.customfcheckboxmultiple', function(e){
            //console.log("before",parameter);
            if ($(this).is(":checked")){
                var string2name = $(this).attr('data-customfieldid');
                var string2value = '='+$(this).val();
                var attrName = string1.concat(string2name);
                var result = attrName.concat(string2value);
                //parameterCount++;
                getparameter(result);
            }else{

                var string2name = $(this).attr('data-customfieldid');
                var string2value = '='+$(this).val();
                var attrName = string1.concat(string2name);
                var result = attrName.concat(string2value);
                for(var i=0; i<parameter.length; i++) {
                    if(parameter[i] == result) {
                        //console.log("in",parameter[i]);
                        parameter.splice(i, 1);
                    }
                }
            }
            //console.log("after",parameter);
        });

        $('body').on('click', '.customfradio', function(e){
            if ($(this).is(":checked")){

                getradiobtnparameter();

                var string2name = $(this).attr('data-customfieldid');
                var string2value = '='+$(this).val();
                var attrName = string1.concat(string2name);
                var result = attrName.concat(string2value);
                //parameterCount++;


                getparameter(result);
            }
            /*else{
                console.log('asdf');
                var string2name = $(this).attr('data-customfieldid');
                var string2value = '='+$(this).val();
                var attrName = string1.concat(string2name);
                var result = attrName.concat(string2value);
                console.log("result",result);
                for(var i=0; i<parameter.length; i++) {
                    console.log("loop",parameter[i]);
                    if(parameter[i] == result) {
                        console.log("in",parameter[i]);
                        parameter.splice(i, 1);
                    }
                    //console.log("Inloop",parameter);
                }
            }*/
        });
        
        

    });

    function getselectparameter(){
        //console.log('before',parameter);
        var string11 = '';
        $('.customfselect').each(function(){
            var string2name = $(this).attr('data-customfieldid');
            ////console.log("string2name",string2name);
            var string2value = '='+$(this).val();
            ////console.log("string2value",string2value);
            var attrName = string11.concat(string2name);
            var result = attrName.concat(string2value);

            //console.log("result",result);
            //console.log("result",result);
            /*for(var i=0; i<parameter.length; i++) {
                //console.log("saraloop",parameter[i]);
                //console.log("result",result);
                if(parameter[i] == result) {
                    //console.log("sara",parameter[i]);
                    parameter.splice(i, 1);
                }
            }*/
        });

        //console.log('after',parameter);
        //return parameter;

    }

    function getradiobtnparameter(){
        $('.customfradio').each(function(){
            var string2name = $(this).attr('data-customfieldid');
            var string2value = '='+$(this).val();
            var attrName = string1.concat(string2name);
            var result = attrName.concat(string2value);
            //alert(result);

            for(var i=0; i<parameter.length; i++) {
                //console.log("loop",parameter[i]);
                if(parameter[i] == result) {
                    //console.log("in",parameter[i]);
                    parameter.splice(i, 1);
                }
            }
        });
    }


    function getselectbtnparameter(){
        $('.customfselect').each(function(){
            var string2name = $(this).attr('data-customfieldid');
            var string2value = '='+$(this).val();
            var attrName = string1.concat(string2name);
            var result = attrName.concat(string2value);
            //alert(result);

            for(var i=0; i<parameter.length; i++) {
                //console.log("loop",parameter[i]);
                if(parameter[i] == result) {
                    //console.log("in",parameter[i]);
                    parameter.splice(i, 1);
                }
            }
            console.log("parameterselect",parameter);
        });
    }

    function getparameter(result){

        parameter.push(result);
        //localStorage.setItem("parameter",parameter);

        console.log("parameter",parameter);
        //console.log('parameter',parameter);
        var strres = '';
        var res = '';
        for(var i=0; i<parameter.length; i++) {
            //console.log("parameter[i]",parameter[i]);
            res += strres.concat(parameter[i])+'&';
        }
        //console.log('res',res);
        ChangeUrl('Page1', APP_URL+'/items/search?'+res);
        $("#customFilters").val(res);
        loadmorecustomfileds(res);
    }

    function getsearchparameters(result){
        //console.log('resultresult',result);
        if(result != null || result != ''){
            //var array = result.split("&");
            //console.log('customFiltersarray',result);
            $.each(result, function( index, value ) {
                //alert( index + ": " + value );
                parameter.push(value);
            });
        }

        if(result[0] != null){
            var strres = '';
            var res = '';
            for(var i=0; i<result.length; i++) {
                //console.log("parameter[i]",parameter[i]);
                res += strres.concat(result[i])+'&';
            }
            //console.log('res',res);
            ChangeUrl('Page1', APP_URL+'/items/search?'+res);
            $("#customFilters").val(res);
            //localStorage.setItem("parameter",parameter);

            loadmorecustomfileds(res);
        }
    }

    function loadmorecustomfileds(cfparameter = ''){
        reset();

        var category = '';
        var subcategory = '';
        if(localStorage.getItem("categoryId")!='' ){
            category=localStorage.getItem("categoryId");
            $("#cate").val(category);//alert(category);
        }
        if(localStorage.getItem("subCategoryId")!='' ){
            subcategory=localStorage.getItem("subCategoryId");
            $("#subcate").val(subcategory);//alert(category);
        }
        if(localStorage.getItem("stateId")!=null ){
            var stateId;
            stateId=localStorage.getItem("stateId");
            //alert(stateId);
            $("#state").val(stateId);//alert(category);
        }
        if(localStorage.getItem("cityId")!=null ){
            var cityId;
            cityId=localStorage.getItem("cityId");
            $("#city").val(cityId);//alert(category);
        }
        if(localStorage.getItem("areaId")!=null ){
            var areaId;
            areaId=localStorage.getItem("areaId");
            $("#area").val(areaId);//alert(category);
        }

            

        if(category !='' && subcategory !=''){
            //console.log(category);
            //console.log(subcategory);
            console.log("query1");
            categoriesSelect(category,subcategory);
        }else{
            console.log("query2");
            var cate=$("#cate").val();
            var subcate=$("#subcate").val();
            var state=$("#state").val();
            var city=$("#city").val();
            var area=$("#area").val();
            var minprice=$("#minprice").val();
            var maxprice=$("#maxprice").val();
            var price=minprice+';'+maxprice;
            var listing_id = $("#list").data("listing");
            var row=$("#row").val();
            var all=$("#all").val();
            var sortingfilters=$("#sortingfilters").val();


            if(localStorage.getItem("squery")!=''){
                $(".searchbox").val(localStorage.getItem("squery"));
            }
            var searchbox=$("#searchbox").val();
            if(searchbox == ''){
                localStorage.setItem("squery","");
                var query="";
            }else{
                localStorage.setItem("squery",searchbox);
                var query=searchbox;
            }


            console.log("query",query);

            var fd = new FormData();
            fd.append('_token', _token);
            fd.append('listing_id', listing_id);
            fd.append('row', row); 
            fd.append('cate', cate);
            fd.append('subcate', subcate); 
            fd.append('state', state); 
            fd.append('city', city);
            fd.append('area', area); 
            fd.append('price', price); 
            fd.append('all', all);
            fd.append('sortingfilters', sortingfilters); 
            fd.append('customFilters', cfparameter); 
            fd.append('query', query);

            $.ajax({
                url: APP_URL+'/loadmoreitems',
                type: 'post',
                data: fd,
                contentType: false, 
                processData: false, 
                beforeSend:function(){
                    $(".load-more").text("Loading...");
                    $('.ajax-loader').css("visibility", "visible");
                },
                success: function(response){
                    //console.log(response)
                    setTimeout(function() {
                        $("#products").html('');
                        $('.ajax-loader').css("visibility", "hidden");
                        $(".load-more").text("Load more");
                        if($.trim(response)=== ""){
                            $('#nodatafound').show();
                            $('.load-more').hide();
                        }else{
                            $('.load-more').show();
                            $('#nodatafound').hide();
                            $("#products").append(response).show().fadeIn("slow");
                        }

                    }, 1000);
                    load_category();
                    load_location();
                    load_more_hide_limit();

                }
            });
        }

        
    }
        
    function ChangeUrl(page, url) {
        if (typeof (history.pushState) != "undefined") {
            var obj = { Page: page, Url: url };
            history.pushState(obj, obj.Page, obj.Url);
        } else {
            alert("Browser does not support HTML5.");
        }
    }
    /*function loading_first_time() {
        //console.log("APP_URL",APP_URL);
        // if(!localStorage.getItem("categoryId")===true){
        //     localStorage.setItem("categoryId","");
        // }
        // if(!localStorage.getItem("subCategoryId")===true){
        //     localStorage.setItem("subCategoryId","");
        // }

            var category = '';
            var subcategory = '';
        if(localStorage.getItem("categoryId")!='' ){
            category=localStorage.getItem("categoryId");
            $("#cate").val(category);//alert(category);
        }
        if(localStorage.getItem("subCategoryId")!='' ){
            subcategory=localStorage.getItem("subCategoryId");
            $("#subcate").val(subcategory);//alert(category);
        }
        if(localStorage.getItem("stateId")!=null ){
            var stateId;
            stateId=localStorage.getItem("stateId");
            $("#state").val(stateId);//alert(category);
        }

        if(category !='' && subcategory !=''){
            //console.log(category);
            //console.log(subcategory);
            categoriesSelect(category,subcategory);
        }
        


        var cate=$("#cate").val();
        var cateid="#cate_"+cate;
        $(cateid).collapse({
            toggle: true
        });
        //var cate=$("#cate").val();
        var subcate=$("#subcate").val();
        var state=$("#state").val();
        var city=localStorage.getItem("cityId");
        var area=$("#area").val();
        var minprice=$("#minprice").val();
        var maxprice=$("#maxprice").val();
        var price=minprice+';'+maxprice;
        var row=$("#row").val();
        var all=$("#all").val();
        var sortingfilters=$("#sortingfilters").val();
        var customFilters=$("#customFilters").val();
        var searchbox=$("#searchbox").val();
        if(searchbox == ''){
            localStorage.setItem("query","");
            var query="";
        }else{
            localStorage.setItem("query",searchbox);
            var query=searchbox;
        }
        

        var fd = new FormData();
        fd.append('_token', _token);
        fd.append('row', row); 
        fd.append('cate', cate); 
        fd.append('subcate', subcate); 
        fd.append('state', state);
        fd.append('city', city);
        fd.append('area', area); 
        fd.append('price', price); 
        fd.append('all', all);
        fd.append('sortingfilters', sortingfilters); 
        fd.append('customFilters', customFilters); 
        fd.append('query', query);

        $.ajax({
            url: APP_URL+'/loadmoreitems',
            type: 'post',
            data: fd,
            contentType: false, 
            processData: false, 
            beforeSend:function(){
                $(".load-more").text("Loading...");
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function(response){
                //console.log(response)
                setTimeout(function() {
                $("#products").html('');
                    $('.ajax-loader').css("visibility", "hidden");
                    $(".load-more").text("Load more");
                    if($.trim(response)=== ""){
                        $('#nodatafound').show();
                        $('.load-more').hide();
                    }else{
                        $('.load-more').show();
                        $('#nodatafound').hide();
                        $("#products").append(response).show().fadeIn("slow");
                    }

                }, 1000);
                //load_category()
                //load_location()
                load_more_hide_limit()
            }
        });
    
    }*/

    function load_filterdata() {

        var cate=$("#cate").val();
        var subcate=$("#subcate").val();
        var state=$("#state").val();
        var city=localStorage.getItem("cityId");
        var area=$("#area").val();
        var minprice=$("#minprice").val();
        var maxprice=$("#maxprice").val();
        var price=minprice+';'+maxprice;
        var row=$("#row").val();
        var all=$("#all").val();
        var sortingfilters=$("#sortingfilters").val();
        var customFilters=$("#customFilters").val();
        var searchbox=$("#searchbox").val();
        var listing_id = $("#list").data("listing");
        //console.log(listing_id);
        if(searchbox ==''){
            localStorage.setItem("squery","");
            var query="";
        }else{
            localStorage.setItem("squery",searchbox);
            var query=searchbox;
        }

        var fd = new FormData();
        fd.append('_token', _token);
        fd.append('listing_id',listing_id);
        fd.append('row', row); 
        fd.append('cate', cate); 
        fd.append('subcate', subcate); 
        fd.append('state', state); 
        fd.append('city', city);
        fd.append('area', area);
        fd.append('price', price); 
        fd.append('all', all);
        fd.append('sortingfilters', sortingfilters);  
        fd.append('customFilters', customFilters);
        fd.append('query', query); 

        $.ajax({
            url: APP_URL+'/loadmoreitems',
            type: 'post',
            data: fd,
            contentType: false, 
            processData: false, 
            beforeSend:function(){
                $(".load-more").text("Loading...");
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function(response){
                //console.log(response)
                setTimeout(function() {
                //$("#products").html('');
                    $('.ajax-loader').css("visibility", "hidden");
                    $(".load-more").text("Load more");
                    if($.trim(response)=== ""){
                        $('#nodatafound').show();
                        $('.load-more').hide();
                    }else{
                        $('.load-more').show();
                        $('#nodatafound').hide();
                        $("#products").append(response).show().fadeIn("slow");
                    }

                }, 1000);

                load_more_hide_limit()

            }
        });
    
    }
    
    function load_more_hide_limit() {
        //alert($("#row").val());
        setTimeout(function() {
            if ($('.loadmorebtnexists_'+$("#row").val()).length > 8){
                $('.load-more').show();
            }else{
                $('.load-more').hide();
            }
        }, 1000);
        //$('.load-more').show();

    }

     function load_category() {
        var state=$("#state").val();
        var city=$("#city").val();
        var minprice=$("#minprice").val();
        var maxprice=$("#maxprice").val();
        var cate=$("#cate").val();
        var subcate=$("#subcate").val();
        var price=minprice+';'+maxprice;
        localStorage.setItem("minprice",minprice);
        localStorage.setItem("maxprice",maxprice);
        //localStorage.setItem("state",state);
        //var price=localStorage.getItem("minprice")+';'+localStorage.getItem("maxprice");
        var fd = new FormData();
        fd.append('_token', _token);
        fd.append('state', state);
        fd.append('city', city);
        fd.append('price', price);
        fd.append('cate', cate); 
        fd.append('subcate', subcate);
        $.ajax({
            url: APP_URL+'/loadcategory',
            type: 'post',
            data: fd,
            contentType: false, 
            processData: false, 
            beforeSend:function(){
                $(".load-more").text("Loading...");
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function(response){
                //console.log(response)
                $('.ajax-loader').css("visibility", "hidden");
                $('#categories').html(response);

            }
        });
    }
    function load_location() {
        var state=$("#state").val();
        var city=$("#city").val();
        var minprice=$("#minprice").val();
        var maxprice=$("#maxprice").val();
        var cate=$("#cate").val();
        var subcate=$("#subcate").val();
        var price=minprice+';'+maxprice;

        /*var check = [];
        var check = localStorage.getItem("stateId");
        console.log("check.length",check);
        $.each(check, function(key,value) {
            console.log("key",key);
            console.log("value",value);
        });*/

        /*for (var i = 0; i < check.length; i++) {
            var checkbox = document.getElementById(check[i]);
            console.log("check[i]",check[i]);
            $(checkbox).prop('checked');
        }*/

        localStorage.setItem("minprice",minprice);
        localStorage.setItem("maxprice",maxprice);
        //var price=localStorage.getItem("minprice")+';'+localStorage.getItem("maxprice");
        var fd1 = new FormData();
        fd1.append('_token', _token);
        fd1.append('price', price);
        fd1.append('state', state);
        fd1.append('city', city);
        fd1.append('cate', cate); 
        fd1.append('subcate', subcate);
        $.ajax({
            url: APP_URL+'/loadlocation',
            type: 'post',
            data: fd1,
            contentType: false, 
            processData: false, 
            beforeSend:function(){
                $(".load-more").text("Loading...");
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function(response){
                //console.log(response)
                $('.ajax-loader').css("visibility", "hidden");
                $('#location').html(response);

            }
        });


        setTimeout(function(){ 
            var check = localStorage.getItem("stateId");

            if(check!=null ){
                var array = check.split(",");
                //console.log("response",array);
                //console.log("array.length",array.length);
                for (var i = 0; i < array.length; i++) {
                    //var checkbox = document.getElementById(array[i]);
                    //console.log("array[i]",array[i]);
                    $("#"+array[i]).prop('checked',true);
                }
            }
        }, 1000);

    }
    
    
</script>
<script type="text/javascript">
    $(document).ready(function () {
    $( "h4.panel-title" ).click(function() {
          $(this).toggleClass( "active" );
        });
    
    $(document).on('click', '.selectcategory', function(){
        var categoryId = $(this).attr('data-category_id');
        localStorage.setItem("categoryId", categoryId);
        window.location.href = "{{url('items')}}";
    });
    
    $(document).on('click', '.category_drop_wrap h3', function(){
        $(".searchbox").val(""); 
        $("#listbox").hide();
    });

    $(document).on('keyup', '.searchbox', function(){
        if($(".searchbox").val().length >= '2'){
            var query = $(this).val();
            $.ajax({
                url:"{{ url('search') }}",
                type:"GET",
                data:{'q':query},
                success:function (data) {
                    $("#listbox").show();
                    $('#listbox').html(data);
                }
            });
        }
    });
    
    $(document).on('click', '.locationinput', function(){
        $("#currentlocationList").show();
    });
    
    $(document).on("click", function(event){
        var $trigger = $(".home_search_outer_wrap");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            // $(".home_search_outer_wrap").removeClass("active");
            $("#listbox").hide();
        }            
    });

    $(document).on('keyup', '.locationinput', function(){
        $("#currentlocationList").hide();
        $('#locationList').html('');
        if($(".locationinput").val().length >= '2'){
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
        localStorage.setItem("squery",$("#searchbox").val());
        //window.location.href = "{{url('items')}}";
    });
    
    $(document).on('keyup', '.locationinput', function(){
        if($(".locationinput").val().length <= '1'){
        //console.log('val emp',$("#country").val());
            $("#locationList").hide();
        }else{
            $("#locationList").show();                        
        }
    });
    

    jQuery(window).ready(function() {
        setTimeout(function(){  location() }, 1000);
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

    $(document).on('click', '.current_location_addr', function(){
        $('#currentlocationList').hide();
        localStorage.setItem("cityId",localStorage.getItem("currentAddressID"));
        localStorage.setItem("cityName",localStorage.getItem("currentAddress"));
        localStorage.setItem("state",localStorage.getItem("currentstate"));
        $("#locationinput").val(localStorage.getItem("currentAddress"));
    });

    $(document).on('click', '.li_locationaddr', function(){
            var cityId = $(this).attr('data-cityid');
            var cityName = $(this).attr('data-cityname');
            var stateId = $(this).attr('data-stateid');
            var areaId = $(this).attr('data-areaid');
            localStorage.setItem("cityId", cityId);
            localStorage.setItem("cityName", cityName);
            localStorage.setItem("state", stateId);
            localStorage.setItem("areaId", areaId);
            $('.locationinput').val(cityName);

            // after click is done, search results segment is made empty
            $('#locationList').html("");
            $('#currentlocationList').hide();
            $("#state").val(stateId);
            $("#city").val(cityId);
            $("#area").val(areaId);
            
            //load_category()
            //load_location()
            //loading_first_time();
            loadmorecustomfileds();
    });
    $(document).on('click', '.headerSearch', function(){

            $("#cate").val("0");
            $("#subcate").val("0");
            localStorage.setItem("categoryId", "0");
            localStorage.setItem("subCategoryId", "0");
            $("#customFilters").val("0");
            parameter = [];
            parameter.length = 0;
            /*setTimeout(function() {
                $("#state").val("0");
                //alert(localStorage.getItem("stateId"))
                localStorage.removeItem("stateId");
                window.localStorage.removeItem('stateId');
                localStorage.setItem("stateId","0");

                //alert(localStorage.getItem("stateId"));
            }, 600);*/
            var locationinput = $(".locationinput").val();
            console.log('locationinput',locationinput);
            if(locationinput == ''){
                //$("#state").val("0");
                $("#area").val("0");
                $("#city").val("0");
                /*localStorage.removeItem("stateId");
                window.localStorage.removeItem('stateId');
                localStorage.setItem("stateId","0");*/
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
            }


            //$("#searchbox").val("");
            //localStorage.setItem("query","");

            ChangeUrl('Page1', APP_URL+'/items');
        

        reset();
        //resetall();
        //loading_first_time();
        loadmorecustomfileds();
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
$(document).ready(function () {
    
        $searchdata=$(".ads_listing_search_outer_wrap").html();
        if( isMobile.any() ){
            $(".ads_listing_search_outer_wrap").html('');
            $(".ads_listing_mobile_search_wrap").html($searchdata);
            
        }else{
            $(".ads_listing_mobile_search_wrap").html('');
            $(".ads_listing_search_outer_wrap").html($searchdata);
            
        }

    $("#searchbox").keydown(function (e) {
        if($("#searchbox").val().length >= '2'){  
            if (e.keyCode == 13) {
                localStorage.setItem("squery",$("#searchbox").val());
                window.location.href = "{{url('items')}}";
            }
        }
    });



    if ($('#slider-range').length > 0){
    //$("#highest").val();
        var maxvv = 500000;
        $("#slider-range").slider({
            range: true,
            orientation: "horizontal",
            min: 0,
            max: maxvv,
            values: [0, maxvv],
            step: 10,

            slide: function (event, ui) {
              if (ui.values[0] == ui.values[1]) {
                  return false;
              }
              
              $("#minprice").val(ui.values[0]);
              $("#maxprice").val(ui.values[1]);
            }
          });

          $("#minprice").val($("#slider-range").slider("values", 0));
          $("#maxprice").val($("#slider-range").slider("values", 1)); 
    }

});



    function loadmaxprice(){

        setTimeout(function(){

            var maxvv = $("#highest").val();
            if ($('#slider-range').length > 0){
                //$("#highest").val();

                var maxvv = $("#highest").val();
                //alert(maxvv)
                $("#slider-range").slider({
                range: true,
                orientation: "horizontal",
                min: 0,
                max: maxvv,
                values: [0, maxvv],
                step: 10,

                slide: function (event, ui) {
                  if (ui.values[0] == ui.values[1]) {
                      return false;
                  }
                  
                  $("#minprice").val(ui.values[0]);
                  $("#maxprice").val(ui.values[1]);
                }
                });

                $("#minprice").val($("#slider-range").slider("values", 0));
                $("#maxprice").val($("#slider-range").slider("values", 1)); 

                    

            }
        }, 2000);
    }

</script>
@endsection