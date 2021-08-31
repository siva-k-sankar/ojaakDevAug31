$(document).ready(function() {
	var Check_user_status= "{{Auth::user()->status}}";
	if(Check_user_status=="2" || Check_user_status=="3" || Check_user_status=="0"){	
			$.ajax({
                        type: 'get',
                        url: APP_URL+'/'+'/ajaxlogout',
                        dataType: 'json',
                        beforeSend:function(){
                            $('.ajax-loader').css("visibility", "visible");
                        },
                        success: function(response)
                        {
                            //location.reload(); // but it is GET request... should be POST request
                            location.href = APP_URL;
                        }
                    });
	}
});




