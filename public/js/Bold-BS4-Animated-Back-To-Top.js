$(document).ready(function(){ 
    $(window).scroll(function(){ 
        if ($(this).scrollTop() > 100) { 
            $('#scrolltop').fadeIn(); 
        } else { 
            $('#scrolltop').fadeOut(); 
        } 
    }); 
    $('#scrolltop').click(function(){ 
        $("html, body").animate({ scrollTop: 0 }, 150); 
        return false; 
    }); 
});