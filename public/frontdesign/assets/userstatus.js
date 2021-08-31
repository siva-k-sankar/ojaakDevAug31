$(document).ready(function() {
	var UserStatus;
	var ojaaktoken = $('meta[name=csrf-token]').attr("content");
    //UserStatus = "http://localhost/ojaak/useronlinestatus"; // local
	//UserStatus = "http://www.ojaak.com/demo/useronlinestatus"; //live
	UserStatus = "http://ojaak.com/demo/useronlinestatus"; //live
	setInterval(function(){ 
		$.ajax({
		    type: "post",
		    url: UserStatus,
		    data: "_token="+ojaaktoken,
		    success: function(data){
		    	if(data!=1){
		    		//document.getElementById('logout-form').submit();
		    		location.reload();
		    		//alert(data)
		    	}
		    	
		    }
		});
	}, 30000);
});