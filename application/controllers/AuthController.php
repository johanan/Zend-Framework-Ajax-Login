<?php

class AuthController extends Zend_Controller_Action
{
	protected $_type;
	protected $_auth;
	protected $_adapter;
	
	public function init()
	{
		// Disable the main layout renderer
		$this->_helper->layout->disableLayout();
		// Do not even attempt to render a view
		$this->_helper->viewRenderer->setNoRender(true);
		if($this->getRequest()->getParam('type'))
		{
			$this->_type = $this->getRequest()->getParam('type');
			
			$this->_auth = Zend_Auth::getInstance();
	
			$this->_adapter = Josh_Auth_Adapter_Factory::factory($this->_type, $this->getRequest()->getParams());
		}
	}
	
	protected function jsonResponse($status, $code, $html, $message = null)
	{
		$this->getResponse()
			->setHeader('Content-Type', 'application/json')
			-> setHttpResponseCode($code)
			->setBody(Zend_Json::encode(array("status"=>$status, "html"=>$html, "message"=>$message)))
			->sendResponse();
			exit;
	}
	
	public function oauthAction()
	{
		$result = $this->_auth->authenticate($this->_adapter);
		if($result->isValid())
		{
			$this->_helper->flashMessenger->addMessage(array('success'=>'Login was successful'));
			$this->_auth->getStorage()->write(array("identity"=>$result->getIdentity(), "user"=>new Josh_Auth_User($this->_type, $result->getMessages())));
		}else{
			$errorMessage = $result->getMessages();
			$this->_helper->flashMessenger->addMessage(array('error'=>$errorMessage['error']));
		}
		$this->_redirect('/');
	}
	
	public function ajaxAction()
	{
		$result = $this->_auth->authenticate($this->_adapter);
		if($result->isValid())
		{
			$this->_auth->getStorage()->write(array("identity"=>$result->getIdentity(), "user"=>new Josh_Auth_User($this->_type, $result->getMessages())));
			
			$ident = $this->_auth->getIdentity();
						
			$loggedIn = $this->view->partial('partials/userLoggedIn.phtml', array('userObj'=>$ident['user']));
			$alert = $this->view->partial('partials/alert.phtml', array('alert'=>'Successful Login', 'alertClass'=>'alert-success'));
			
			$html = array("#userButton"=>$loggedIn, "alert"=>$alert);		
			$this->jsonResponse('success', 200, $html);
		}else{
			$errorMessage = $result->getMessages();
			$alert = $this->view->partial('partials/alert.phtml', array('alert'=>$errorMessage['error'], 'alertClass'=>'alert-error'));
			
			$html = array("alert"=>$alert);		
			$this->jsonResponse('error', 401, $html, $errorMessage['error']);
		}
	}
}
	