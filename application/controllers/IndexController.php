<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	//see if we have any access to fb
		
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			$this->view->identity = Zend_Auth::getInstance()->getIdentity();
			$this->view->userObj = $this->view->identity['user'];
		}
    }

}

