$(document).ready(function() {
	/*// Price Slider
	if ($('#slider-range').length > 0){
		$(function() {
	    $( "#slider-range" ).slider({
	      range: true,
	      min: 0,
	      max: 8000,
	      values: [ 500, 6000 ],
	      slide: function( event, ui ) {
	        $( "#amount" ).html( "₹" + ui.values[ 0 ] + " - ₹" + ui.values[ 1 ] );
			$( "#amount1" ).val(ui.values[ 0 ]);
			$( "#amount2" ).val(ui.values[ 1 ]);
	      }
	    });
	    $( "#amount" ).html( "₹" + $( "#slider-range" ).slider( "values", 0 ) +
	     " - ₹" + $( "#slider-range" ).slider( "values", 1 ) );
	   });
	}*/
	//localStorage.clear();


	// Toggle class
	if ($('h4.panel-title').length > 0){
		$( "h4.panel-title" ).click(function() {
		  $(this ).toggleClass( "active" );
		});
	}

	// post title
	if ($('.add_ads_bg_wrap input#title').length > 0){
		$( ".add_ads_bg_wrap input#title" ).on('input', function() {
		    if ($(this).val().length>=35) {
		    	$("#titleHelp").addClass('text-danger').removeClass('text-muted');     
		    }else{
		    	$("#titleHelp").addClass('text-muted').removeClass('text-danger');
		    }
		});
	}

	if ($('.sender_delete_btn').length > 0){
		$('.sender_delete_btn').click(function(e){
		    e.preventDefault();
		    $(this).find('.msg_delete_indiv').toggleClass('active');
		});	
	}

	if ($('#list').length > 0){
		$( "#list" ).click(function() {
		    $("#grid").removeClass('active');
		    $("#list").addClass('active');
		    $('#list').attr('data-listing',1);     
		});
	}
	if ($('#list').length > 0){
		$( "#grid" ).click(function() {
		    $("#list").removeClass('active');
		    $("#grid").addClass('active');  
		    $('#list').attr('data-listing',0);
		       
		});
	}
	if ($('.profile_view_tool').length > 0){
		$('.profile_view_tool').popover({
		    
		    trigger: 'focus'
		  });
	}
	// Toggle class for filter responsive
	if ($('h2.filter_title_wrap').length > 0){
		$( "h2.filter_title_wrap" ).click(function() {
		  $(".filter_outer_wrap .panel-group" ).toggleClass( "active" );
		  $(".filter_outer_wrap .price_range_wrap" ).toggleClass( "active" );
		});
	}

	//Toogle list/grid
	if ($('#list').length > 0){
	$('#list').click(function(event){
		event.preventDefault();
		$('#products .item').addClass('list-group-item');
		//$('#products .item').removeClass('grid-group-item');
	});
	}
	if ($('#grid').length > 0){
	    $('#grid').click(function(event){
	    	event.preventDefault();
	    	$('#products .item').removeClass('list-group-item');
	    	$('#products .item').addClass('grid-group-item');
	    });
	}
    // Filters
    if ($('.category-button').length > 0){
    	$('.category-button').categoryFilter();
	}
    
	
    // Tags
    if ($('input[name="color"]').length > 0){
	    $('input[name="color"]').amsifySuggestags({
	  	type :'bootstrap',
		});
	}
	// Image upload
	/*$('input[type="file"]').each(function(){
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
	});*/

	// Date Picker
	if ($('#datepicker').length > 0){
		$('#datepicker').datepicker({
	            uiLibrary: 'bootstrap4'
	        });
	}

	// Popup Model
	if ($('#category_modal').length > 0){
		$("#ads_posting").on('click', function () {        
		   	$('#category_modal').modal('show');
		}); 
	}
	/*if ($('#category_modal').length > 0){
		$("#ads_posting_login").on('click', function () {        
		   	$('#login').modal('show');
		});
		$("#wallet_login").on('click', function () {        
		   	$('#login').modal('show');
		}); 
	}
*/
	//Slick single slider initialize
	if ($('.product_slider').length > 0){
		$('.product_slider').slick({
		  arrows:false,
		  dots: false,
		  infinite:true,
		  speed:500,
		  autoplay:false,
		  autoplaySpeed: 3000,
		  slidesToShow:1,
		  slidesToScroll:1,
		  adaptiveHeight: false,
		});
		//On click of slider-nav childern,
		//Slick slider navigate to the respective index.
		$('.product_slider_nav > img').click(function() {
		    $('.product_slider').slick('slickGoTo',$(this).index());
		})
	}

	//Slick related ads
	if ($('.related_ads_slider').length > 0){
		$('.related_ads_slider').slick({
		  slidesToShow: 4,
		  slidesToScroll: 1,
		  autoplay: false,
		  autoplaySpeed: 2000,
		  infinite:true,
		  speed:500,
		  arrows:true,
		  responsive: [
		    {
		      breakpoint: 1025,
		      settings: {
		        arrows: false,
		        slidesToShow: 3
		      }
		    },
		    {
		      breakpoint: 769,
		      settings: {
		        arrows: false,
		        slidesToShow: 2
		      }
		    },
		    {
		      breakpoint: 640,
		      settings: {
		        arrows: false,
		        slidesToShow: 1
		      }
		    }
		  ]
		});
	}

	// Add minus icon for collapse element which is open by default
	if ($('.faq_outer_row_wrap').length > 0){
        $(".collapse.show").each(function(){
        	$(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });
        
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });
    }
    // Custom scroll - Chat Listing
    if ($('.chat_listing_inner_wrap').length > 0){
     	$(window).on("load",function(){
            $(".chat_listing_inner_wrap").mCustomScrollbar({
            	autoHideScrollbar: true,
            	alwaysShowScrollbar: 1,
            	theme: "dark",
            });
        });
    }
    // Custom Scroll - Chat Msg
    if ($('.message_box_wrap').length > 0){
     	$(window).on("load",function(){
            $(".message_box_wrap").mCustomScrollbar({
            	autoHideScrollbar: true,
            	alwaysShowScrollbar: 1,
            	theme: "dark",
            });
        });
    }

    // Chat attachement toggle
    // Toggle class for filter responsive
	if ($('#attachement').length > 0){
		$( "#attachement" ).click(function() {
		  $(".attachementbox" ).toggleClass( "active" );
		   $(".msg_suggestion_outer_wrap" ).removeClass( "active" );

		});

		$(document).on("click", function(event){
        var $trigger = $("#attachement");
	        if($trigger !== event.target && !$trigger.has(event.target).length){
	            $(".attachementbox").removeClass("active");
	        }            
    	});
	}

	//Close/Open Suggestion message
	if ($('.toggle_btn_wrap ').length > 0){
		$( ".toggle_btn_wrap " ).click(function() {
		  $(".msg_suggestion_outer_wrap" ).toggleClass( "active" );
		});
		$(document).on("click", function(event){
        var $trigger = $(".toggle_btn_wrap");
	        if($trigger !== event.target && !$trigger.has(event.target).length){
	            $(".msg_suggestion_outer_wrap").removeClass("active");
	        }            
    	});

		// $(".toggle_btn_wrap").click (function(){
		// 	if($(".msg_suggestion_outer_wrap").hasClass("active"))
		// 		$(".arrow_btn_wrap").removeClass("fa-angle-up").addClass("fa-angle-down");
		// 	else
		// 		$(".arrow_btn_wrap").removeClass("fa-angle-down").addClass("fa-angle-up");
		// });
	}

	// New Changes - 24/04
	if ($('.home_search_outer_wrap').length > 0){
		$(document).ready(function(){
        // Show hide popover
        $(".home_search_outer_wrap input").click(function(){
            $(".home_search_outer_wrap").toggleClass("active");
        });
        
    });
    $(document).on("click", function(event){
        var $trigger = $(".home_search_outer_wrap");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $(".home_search_outer_wrap").removeClass("active");
        }            
    });
		
	}

	if ($('.category_drop_wrap').length > 0){
		$(document).ready(function(){
        	$(".category_drop_wrap h3").click(function(){
            	$(".home_search_outer_wrap").removeClass("active");
	        });
	    });
	}

	//Three dot menu btn
	if ($('.menu_three_dots_wrap ').length > 0){
		$( ".menu_three_dots_wrap" ).click(function() {
		  $(".menu_three_dots_wrap" ).toggleClass( "active" );
		});

		$(document).on("click", function(event){
        var $trigger = $(".menu_three_dots_wrap");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $(".menu_three_dots_wrap").removeClass("active");
        }            
    });
	}


	// Timer
	/*if ($('.circle_outer_wrap').length > 0){
		window.onload = function(){
		var progInc = setInterval(incrementProg, 100);
		function incrementProg(){
		progressbar = document.querySelector('div[data-progress]');    
		progress = progressbar.getAttribute('data-progress');
		progressbar.setAttribute('data-progress', parseInt(progress,10)+1);
		setPie();
		if (progress == 99){
		clearInterval(progInc);
		}
		}
		}

		function setPie(){
		var progressbar = document.querySelector('div[data-progress]');
		var quad1 = document.querySelector('.quad1'), 
		quad2 = document.querySelector('.quad2'),
		quad3 = document.querySelector('.quad3'),
		quad4 = document.querySelector('.quad4'); 
		var progress = progressbar.getAttribute('data-progress');
		if(progress <= 25){
		quad1.setAttribute('style', 'transform: skew(' + progress * (-90/25) + 'deg)');
		}
		else if(progress > 25 && progress <=50){
		quad1.setAttribute('style', 'transform: skew(-90deg)');
		quad2.setAttribute('style', 'transform: skewY(' + (progress-25) * (90/25) + 'deg)');
		}
		else if(progress > 50 && progress <=75){
		quad1.setAttribute('style', 'transform: skew(-90deg)');
		quad2.setAttribute('style', 'transform: skewY(90deg)');
		quad3.setAttribute('style', 'transform: skew(' + (progress-50) * (-90/25) + 'deg)');
		}  
		else if(progress > 75 && progress <=100){
		quad1.setAttribute('style', 'transform: skew(-90deg)');
		quad2.setAttribute('style', 'transform: skewY(90deg)');
		quad3.setAttribute('style', 'transform: skew(-90deg)');
		quad4.setAttribute('style', 'transform: skewY(' + (progress-75) * (90/25) + 'deg)');
		}  
		}
	}*/	

	// single image popup
		
		if ($(".single_image_popup a").length > 0){
			$(".single_image_popup a").frydBox({
				borderSize: 2,
			  	borderColor:'white',
			  	borderRadius: 3,
			  	prevImage:false,
			   	nextImage:false,
			   	closeImage:true,
			});
		}
			//Chat highlight selected user
	if ($('ul#search_sorting ').length > 0){
		$( "ul#search_sorting li" ).click(function() {
			$('ul#search_sorting li.active').removeClass('active');
   			 $(this).addClass('active');
		});
	}

	//All ads scroll
	if ($('.product_list_left_col').length > 0){
     	$(window).on("load",function(){
            $(".product_list_left_col").mCustomScrollbar({
            	autoHideScrollbar: true,
            	alwaysShowScrollbar: 1,
            	theme: "minimal",
            });
        });
    }
    // Category Model Scroll
    if ($('.category_scroll_wrap').length > 0){
     	$(window).on("load",function(){
            $(".category_scroll_wrap").mCustomScrollbar({
            	autoHideScrollbar: true,
            	alwaysShowScrollbar: 1,
            	theme: "minimal",
            });
        });
    }

    //price Shhow toggle btn
    if ($('.price_slider_wrap').length > 0){
		$( ".price_slider_wrap h3" ).click(function() {
		  $(".price_slider_wrap" ).toggleClass( "active" );
		});
	}

	// Wallet date picker
	var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
	 if ($('#from_date_pick').length > 0){
		$('#from_date_pick').datepicker({
			uiLibrary: 'bootstrap4',
			format: 'mm/dd/yyyy',
			maxDate: today,
            /*maxDate: function () {
                return $('#to_date_pick').val();
            }*/

		});
	}
	 if ($('#to_date_pick').length > 0){
		$('#to_date_pick').datepicker({
			uiLibrary: 'bootstrap4',
			format: 'mm/dd/yyyy',
			maxDate: today,
			
		});
	}
	
	//wallet table
	/*if ($('#wallet_table_wrap').length > 0){
		$('#wallet_table_wrap').DataTable({responsive:true});
	} */  
	/*if ($('#slider-range').length > 0){
		$("#slider-range").slider({
			range: true,
			orientation: "horizontal",
			min: 0,
			max: 5000000,
			values: [0, 5000000],
			step: 100,

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

	}*/

	if ($('.category_single_wrap ').length > 0){
		$( ".category_single_wrap li" ).click(function() {
			$('.category_single_wrap li.active').removeClass('active');
   			 $(this).addClass('active');
		});
	}
	/*if ($('#active_content').length > 0){
     	$(window).on("load",function(){
            $("#active_content, #sold_content, #favourite_content, #inactive_content, .ads_plan_listing").mCustomScrollbar({
            	autoHideScrollbar: true,
            	alwaysShowScrollbar: 1,
            	theme: "minimal",
            });
        });
    }*/

    
	// Toggle class
	if ($('h4.panel-title a').length > 0){
		$( "h4.panel-title" ).click(function() {
		  $(this ).toggleClass( "active" );
		});
	}
	// Toggle class for filter responsive
	if ($('h2.filter_title_wrap').length > 0){
		$( "h2.filter_title_wrap" ).click(function() {
		  $(".filter_outer_wrap .panel-group" ).toggleClass( "hide" );
		  $(".filter_outer_wrap .price_slider_wrap" ).toggleClass( "hide" );
		});
	}

    //Popup on Plans page
	    if ($('.select_plan_inner_wrap').length > 0){
			$( ".post_more_outer_wrap .select_plan_inner_wrap" ).click(function() {
				$('.post_more_outer_wrap .select_plan_inner_wrap.active').removeClass('active');
	   			 $(this).addClass('active');
			});
		}
	    
	    if ($('.select_plan_inner_wrap').length > 0){
			$( ".feature_ad_max_wrap .select_plan_inner_wrap" ).click(function() {
				$('.feature_ad_max_wrap .select_plan_inner_wrap.active').removeClass('active');
	   			 $(this).addClass('active');
			});
		}

		if ($('.select_plan_inner_wrap').length > 0){
			$( ".feature_ads_min_wrap .select_plan_inner_wrap" ).click(function() {
				$('.feature_ads_min_wrap .select_plan_inner_wrap.active').removeClass('active');
	   			 $(this).addClass('active');
			});
		}

		if ($('.select_plan_inner_wrap').length > 0){
			$( ".boost_ads_outer_wrap .select_plan_inner_wrap" ).click(function() {
				$('.boost_ads_outer_wrap .select_plan_inner_wrap.active').removeClass('active');
	   			 $(this).addClass('active');
			});
		}
	/*if ($('#plans_modal').length > 0){
		$(window).on('load', function () {        
		   	$('#plans_modal').modal('show');
		}); 
	}*/

	//Follower popup scroll
	if ($('.follow_scroll_wrap').length > 0){
     	$(window).on("load",function(){
            $(".follow_scroll_wrap").mCustomScrollbar({
            	autoHideScrollbar: true,
            	alwaysShowScrollbar: 1,
            	theme: "minimal",
            });
        });
    }

    //category_drop_wrap
	if ($('.category_drop_wrap').length > 0){
		$(document).ready(function(){
        // Show hide popover
	        $(".category_drop_wrap h3").click(function(){
	            $(".category_drop_wrap").toggleClass("active");
	        });
   		 });
	    $(document).on("click", function(event){
	        var $trigger = $(".category_drop_wrap");
	        if($trigger !== event.target && !$trigger.has(event.target).length){
	            $(".category_drop_wrap").removeClass("active");
	        }            
	    });
	}
    /*if ($('.profile_info_ads_scroll').length > 0){
     	$(window).on("load",function(){
            $(".profile_info_ads_scroll").mCustomScrollbar({
            	autoHideScrollbar: true,
            	alwaysShowScrollbar: 1,
            	theme: "minimal",
            });
        });
    }*/
    if($('div#login').length >0){
		$('div#login').on('shown.bs.modal', function () {
		    setTimeout(function (){
		        $('input#lemail').focus();
		    }, 50);

		});
	}
	if($('#lpassword').length >0){
		$('#login').keypress(function(event) { 
            if (event.keyCode === 13) { 
                $("#loginsubmit").click(); 
            } 
        }); 
	}
	if($('#logregclosebtn').length >0){
		$('#logregclosebtn').click(function(event) { 
            	$("#Hloginform").show();
                $("#Hregisterform").hide();
                $('#Hotpform').hide();
                $("#regclk").removeClass('show');
                $("#logclk").addClass('show'); 
        }); 
	}
	$(".model_close_logout").on('click', function () {  
			$('#logout-form').submit();      
    });

    //InVoice
	if ($('.invoice_inner_row_wrap').length > 0){
        $(".collapse.show").each(function(){
        	$(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });
        
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });
    }
    if ($('#invoice_from_date_pick').length > 0){
		$('#invoice_from_date_pick').datepicker({
			uiLibrary: 'bootstrap4',
			format: 'mm/dd/yyyy',
			maxDate: today,
		});
	}
	 if ($('#invoice_to_date_pick').length > 0){
		$('#invoice_to_date_pick').datepicker({
			uiLibrary: 'bootstrap4',
			format: 'mm/dd/yyyy',
			maxDate: today,
		});
	}

	if ($('#bought_information_scroll_id').length > 0){
     	$(window).on("load",function(){
            $("#bought_information_scroll_id").mCustomScrollbar({
            	autoHideScrollbar: true,
            	alwaysShowScrollbar: 1,
            	theme: "minimal",
            });
        });
    }

 	//    $(".wallet_nav_outer_wrap .nav-link").click(function() {
	//     $('.ads_manage_search_filter_wrap').find("input[type=text]").val("");
	// });

	

});

//Ads Search
function search_ads_filter_wrap() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("ads_mana_search_id");
    filter = input.value.toUpperCase();
    ul = document.getElementById("ads_management_scroll_id");
    li = ul.getElementsByClassName("ads_management_listing_outer_wrap");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("h4")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

//Chat msg
function search_chat_msg() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("individual_msg");
    filter = input.value.toUpperCase();
    ul = document.getElementById("chat_msg_body");
    li = ul.getElementsByClassName("media");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("p")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

// Ads list location filter
function location_search() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("location_search_wrap");
    filter = input.value.toUpperCase();
    ul = document.getElementById("location_filter_ul");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("label")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}


// Search chat name
function searchFunction() {
	
	if(localStorage.getItem("message_status")!= 3){
		var val = "3";
		var opval;
		var sel = document.getElementById('selectchatfillter');
		var opts = sel.options;
		  for (var opt, j = 0; opt = opts[j]; j++) {
		  		opval=opt.value;
		    if (opt.value == val) {
		      sel.selectedIndex = j;
		      localStorage.setItem("message_status","3");
		      userdata();
		      break;
		    }
		  }
	}
	
	if(localStorage.getItem("message_status")== 3){
		var input, filter, ul, li, a, i, txtValue;
	    input = document.getElementById("search_input");
	    filter = input.value.toUpperCase();
	    ul = document.getElementById("search_sorting");
	    li = ul.getElementsByTagName('li');
	    for (i = 0; i < li.length; i++) {
	        
	        a = li[i].getElementsByTagName("h6")[0];
	        txtValue = a.textContent || a.innerText;
	        if (txtValue.toUpperCase().indexOf(filter) > -1) {
	            li[i].style.display = "";
	        } else {
	            li[i].style.display = "none";
	        }
	    }
	}
	
}	

// Toaster copy profile link
function copyFunction() {
  var x = document.getElementById("share_profile_link");
  x.className = "show";
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

var timeRefreshPage = new Date().getTime();
 $(document.body).bind("mousemove keypress", function(e) {
     timeRefreshPage = new Date().getTime();
 });

 function refreshPage() {
     if(new Date().getTime() - timeRefreshPage >= 300000) 
         window.location.reload(true);
     else 
         setTimeout(refreshPage, 10000);
 }

 // setTimeout(refreshPage, 10000);


var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

$(".toggle-password").click(function() {
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
        $(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
    } else {
        input.attr("type", "password");
        $(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
    }
});