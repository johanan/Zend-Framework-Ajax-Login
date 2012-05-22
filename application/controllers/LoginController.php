<?php

class LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
	
	private function badRequest($message, $code = 400)
	{
		$html = $this->view->partial('partials/alert.phtml', array('alert'=>$message, 'alertClass'=>'alert-error'));
		$this->getResponse()
		->setHeader('Content-Type', 'application/json')
		-> setHttpResponseCode($code)
		->setBody(Zend_Json::encode(array(array("status"=>"error",'message'=>$message, "alert"=>$html))))
		->sendResponse();
		exit;
	}
	
    public function indexAction()
    {
    	
    }
	
	public function ajaxAction()
	{
		if($this->getRequest()->getParam('type') || $this->getRequest()->getParam('code'))
		{
			$type = $this->getRequest()->getParam('type');
			
			// Disable the main layout renderer
			$this->_helper->layout->disableLayout();
			// Do not even attempt to render a view
			$this->_helper->viewRenderer->setNoRender(true);
			
			$auth = Zend_Auth::getInstance();
	
			$adapter = Josh_Auth_Adapter_Factory::factory($type, $this->getRequest()->getParams());
	
			if($adapter)
			{
				$result = $auth->authenticate($adapter);
				if($result->isValid())
				{
					//these two methods should look to a database. 
					if($type == 'fb')
					{
						$auth->getStorage()->write(array("identity"=>$result->getIdentity(), "user"=>new Josh_Auth_User($type, $result->getMessages())));
					}else{
						$email = $this->getRequest()->getParam('email');
						$auth->getStorage()->write(array("identity"=>$email, "user"=>new Josh_Auth_User($type, array('first_name'=>$email, 
							'last_name'=>'No One', 'id'=>null, 'email' => $email, 'gender' => null))));
					}
					
					$ident = $auth->getIdentity();
						
					$html = $this->view->partial('partials/userLoggedIn.phtml', array('userObj'=>$ident['user']));
						
					$this->getResponse()
					->setHeader('Content-Type', 'application/json')
					-> setHttpResponseCode(200)
					->setBody(Zend_Json::encode(array("status"=>"success", "html"=>$html)))
					->sendResponse();
					exit;
				}else{
					$errorMessage = $result->getMessages();
					$this->badRequest($errorMessage['error'], 401);
				}
				
			}else{
				$this->badRequest('You did not use a valid type');
			}
			
		}else{
			$this->badRequest('You did not send a type');
		}
		
	}

	public function oauthAction()
	{
		$this->_helper->layout->disableLayout();
		// Do not even attempt to render a view
		$this->_helper->viewRenderer->setNoRender(true);
		
		//first check to see if there was an error
		if($this->getRequest()->getQuery('error'))
		{
			$this->_helper->flashMessenger->addMessage(array('error'=>$this->getRequest()->getQuery('error_reason')));
			$this->_redirect('/');
		}
		
		//next we check the request for identifying factors
		if($this->getRequest()->getQuery('code') || $this->getRequest()->getQuery('authtype'))
		{
			if($this->getRequest()->getQuery('code'))
			$type = 'fb';
			else {
				$type = $this->getRequest()->getQuery('authtype');
			}
		}
		
		 $adapter = Josh_Auth_Adapter_Factory::factory($type, $this->getRequest()->getParams());
		 $auth = Zend_Auth::getInstance();
		 $request = $auth->authenticate($adapter);
		 
		if($request->isValid())
		{
			$this->_helper->flashMessenger->addMessage(array('success'=>'Login was successful'));
			$auth->getStorage()->write(array("identity"=>$request->getIdentity(), "user"=>new Josh_Auth_User($type, $request->getMessages())));
		}else{
			$errorMessage = $request->getMessages();
			$this->_helper->flashMessenger->addMessage(array('error'=>$errorMessage['error']));
		}
		$this->_redirect('/');
	}

	public function logoutAction()
	{
		$this->_helper->flashMessenger->addMessage(array('success'=>'Logout was successful'));
		$auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
		Josh_Facebook::killSession();
		$this->_redirect('/');
	}


}