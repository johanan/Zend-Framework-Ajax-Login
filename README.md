# Zend Framework Ajax login
This is a basic ZF install with a framework to log in either using ajax, redirects, or pop-ups. It uses Zend_Auth and Zend_Auth_Adapters so it is easy to extend.

This was inspired by and uses code from 

*  https://github.com/marcinwol/zfopenid 
*  uses My_OpenId_Extension_AttributeExchange by Chris Bisnett http://framework.zend.com/issues/browse/ZF-7328
*  Patched Zend_OpenId_Consumer (http://framework.zend.com/issues/browse/ZF-6905)
*  [Bootstrap](http://twitter.github.com/bootstrap/)
*  Super Hero Theme from [Bootswatch](http://bootswatch.com/superhero/)

## Button Setup
To setup each button you need to add configurations in your application.ini (I have included a sample and add a button element to application/views/scripts/partials/loginButtons.phtml. Each button needs a method, an endpoint, and an auth type. 

<table>
<tr><td>Methods</td>		<td>Example Endpoints</td></tr>
<tr><td>data-ajax</td> 		<td>/ajax/fb</td></tr>
<tr><td>data-endpoint</td>		<td>/oauth/fb</td></tr>
<tr><td>data-popup</td>		<td>/oauth/fb/popup</td></tr>
</table>

There are routes in the application that map the type and the method based on what you put here. These methods and types are pulled in javascript and the appropriate action is taken. 

* data-ajax makes an ajax call with optional form inputs
* data-endpoint redirects the page to (most likely) an oauth endpoint
* data-popup creates a javascript popup which will go to the endpoint and reload the main page when done

The auth type is needed to find a form(if needed) and technically to create the auth adapter, but that is pulled from the URL and not this attribute.

One thing to remember - if you use a popup you have to set the method at the end of the endpoint (for example /oauth/google/popup). If you do not do this the application will reload the page in the popup instead of the main page. The user is still authenticated as the session is set, but you will not get the user experience you are expecting.

## Auth Adapter Setup
You can easily extend this to use any type of service or method you can think of as it utilizes Zend Framework’s Auth Adapter. 

First create an adapter and then modify Josh_Auth_Adapter_Factory to add the new auth adapter. You have access to the type sent and the parameters sent (either GET or POST as it sends the full ZF params). Finally just send a Zend_Auth_Result::SUCCESS or a Zend_Auth_Result::FAILURE and the controller will take care of sending the JSON or redirecting the page. 

What you have to do. Right now the success result just creates an array and sends it back to be put in a user object. You will most likely want to create something that checks for the existence of the user in a database and create your user from there.

## Javascript
It uses jQuery and some of the javascript from Bootstrap. If needed you can easily setup jQuery to run in no conflict mode as every use of the $ is inside of a self executing function. 

### Event Bindings
The javascript binds to the click event of any element with the class of login-btn. It calls a zfAjax.loginEvents which figures out how to handle the click

### loginEvents
This is where the data attributes that we added earlier come in. We grab the data-auth-type to figure out the type. We first have to check for FB ajax login as it has a unique process. Otherwise it goes through each different method(data-ajax, data-endpoint, or data-popup) to determine how to proceed. I know I could have added some regex to figure out the method, but I like the fact that it is definitively defined here.

The redirects are very straightforward. It either redirects the current page or creates a popup. After that it is all the Auth Adapter.

The ajax method has a couple more steps. If you need extra data from the user (like a username and password) you can create a form with the data-auth-type as the id of the form(for example db auth type would have a form #db). The ajax method will find the form and pull out all the inputs and add it to the POST. Finally it will handle the response based on how it returned, 200 response is a success while a 401 is failed. The controller will handle sending the correct response.

### How to add html with ajax
In the json response there is a property called html. It is an array with the html that needs to be added to the page. The only special case is an alert as it is appended and it has its own small javascript function to be added. Everything else should sent with its jQuery selector and the html that will replace what is currently on the page. Currently it sends an alert and if the user is logged in the new logged in button view. This is easily setup with partials. You can change the controller to send whatever is needed. 

## AuthController
The AuthController is very simple. It has two actions oauthAction or ajaxAction that eith responds with redirects or json, respectively. It grabs the type from the URL based on a route and then creates and Auth Adapter through the factory. 

This is a great spot to grab the responses and setup your own users. It currently only stores user info in the session. You will want to add the hooks to however you initialize and create users here.

## Demo
You can test things out at http://ejosh.co/demos/zf-ajax/