//initialize Facebook
FB.init({
          appId: '276652909615',
          cookie: true,
          xfbml: true,
          oauth: true
});

$(document).ready(function() {
	zfAjax.loginEvents();
});

(function(zfAjax, $, FB){
//initialize our bootstrap js
$('.dropdown-toggle').dropdown();

$('.dropdown-menu').find('input').click(function (e) {
    e.stopPropagation();
});
  	
$(".alert").alert();

zfAjax.addAlert = function(message, type)
{
	messageDiv = $('#messages');
	messageDiv.append('<div class="alert alert-' + type + '"><a class="close" data-dismiss="alert" href="#">x</a><p>' + message + '</p></div>');
};

zfAjax.checkLogin = function(type, email){
	var request = $.ajax({
    	type: "POST",
    	url: "/zf-fb/public/login/ajax",
    	data: {type: type, email: email}
    });
    		
    request.done(function(res){
    	$('#userButton').html(res.html);
    });
    		
    request.fail(function(jqXHR, textStatus) {
		var error = JSON.parse(jqXHR.responseText);
		if(error[0].alert)
		{
			messageDiv = $('#messages');
		    messageDiv.append(error[0].alert);
		}
	});
			
};

zfAjax.loginEvents = function(){
	$('#loginButtons').on('click', 'button', function(ev){
    	ev.preventDefault();
    	ev.stopPropagation();
    	target = $(ev.target);
    	if(target.attr("data-auth-type"))
    	{
    		authType = target.attr("data-auth-type");
    	}
    	if(authType == "fb")
    	{
    		FB.getLoginStatus(function(response){
    			if(response.status === 'connected'){
    				zfAjax.checkLogin(authType);
    			}else{
    				FB.login(function(response){
	    				if(response.status === 'connected'){
			    			zfAjax.checkLogin(authType);
			    		}else{
			    			zfAjax.addAlert('You are not logged into Facebook!', 'error');
			    		}
    				}, {scope: 'email'});
    			}
    		});
    	}else if(typeof target.attr('data-endpoint') !== 'undefined' && target.attr('data-endpoint') !== false){
    		window.location = target.attr('data-endpoint');
    	}else{
    		zfAjax.checkLogin(authType, target.parent().children(':first-child').val());
    	}
    });
};

}( window.zfAjax = window.zfAjax || {}, window.jQuery, window.FB ));
