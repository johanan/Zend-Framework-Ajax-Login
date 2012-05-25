//initialize Facebook
FB.init({
          appId: 'YOUR-APP-ID',
          cookie: true,
          xfbml: true,
          oauth: true
});

window.jQuery(document).ready(function() {
	$('.login-btn').click(function(ev){
		zfAjax.loginEvents(ev);
	});
});

(function(zfAjax, $, FB){
//initialize our bootstrap js
$('.dropdown-toggle').dropdown();

$('#loginButtons').find('input').click(function (e) {
    e.stopPropagation();
});
  	
$(".alert").alert();

zfAjax.addAlert = function(html)
{
	messageDiv = $('#messages');
	messageDiv.append(html);
};

zfAjax.errorAlert = function(message)
{
	messageDiv = $('#messages');
	messageDiv.append('<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">x</a><p>' + message + '</p></div>');
};

zfAjax.checkLogin = function(type, url){
	//see if there is a form for this
	//the form should have an id that is the  auth type
	formTest = $('#' + type);
	
	data = {};
	if(formTest.length)
	{
		//auto grab all the fields to send
		//the find selector allows you to have
		//many different layouts in the form and still
		//get the inputs
		formTest.find('input').each(function(){
			data[$(this).attr('name')] = $(this).val();
		});
	}
	
	var request = $.ajax({
    	type: "POST",
    	url: url,
    	data: data
    });
    		
    request.done(function(res){
    	//loop through the html array
    	//and add it to the page
    	for(html in res.html)
    	{
    		if(html == 'alert')
    		{
    			//special case
    			zfAjax.addAlert(res.html[html]);
    		}else
    		{
    			//the ajax html is passed by id
    			$(html).html(res.html[html]);
    		}
    	}
    });
    		
    request.fail(function(jqXHR, textStatus) {
    	try{
			var error = JSON.parse(jqXHR.responseText);
		}catch(err){
			zfAjax.errorAlert('There was an issue');
			return;
		}
		for(html in error.html)
    	{
    		if(html == 'alert')
    		{
    			//special case
    			zfAjax.addAlert(error.html[html]);
    		}else
    		{
    			//the ajax html is passed by id
    			$(html).html(error.html[html]);
    		}
    	}
	});
			
};

zfAjax.loginEvents = function(ev){
    	ev.preventDefault();
    	ev.stopPropagation();
    	target = $(ev.target);
    	if(target.attr("data-auth-type"))
    	{
    		//get the auth type from the button
    		authType = target.attr("data-auth-type");
    	}
    	if(authType == "fb")
    	{
    		//Facebook is a special case
    		//use it's js SDK to login
    		FB.getLoginStatus(function(response){
    			if(response.status === 'connected'){
    				zfAjax.checkLogin(authType, target.attr('data-endpoint'));
    			}else{
    				FB.login(function(response){
			    		zfAjax.checkLogin(authType, target.attr('data-endpoint'));
    				}, {scope: 'email'});
    			}
    		});
    	}else if(target.attr('data-endpoint') && target.attr('data-ajax') == 'true'){
    		//these attributes tell us to do this through ajax
    		zfAjax.checkLogin(authType, target.attr('data-endpoint'));
    	}else if(typeof target.attr('data-endpoint') !== 'undefined' && target.attr('data-endpoint') !== false){
    		//this attribute tells us to redirect
    		window.location = target.attr('data-endpoint');
    	}else{
    		//let's just try and see if there is a way to login
    		zfAjax.checkLogin(authType, target.attr('data-endpoint'));
    	}
};

}( window.zfAjax = window.zfAjax || {}, window.jQuery, window.FB ));
