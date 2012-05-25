<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {

    }

    public function profileAction()
    {
    	if(Zend_Auth::getInstance()->hasIdentity())
		{
			$this->view->identity = Zend_Auth::getInstance()->getIdentity();
			$this->view->user = $this->view->identity['user'];
		}else{
			$this->_helper->flashMessenger->addMessage(array('info'=>'Please Log in.'));
			$this->_redirect('/');
		}
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

