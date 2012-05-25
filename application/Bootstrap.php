<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{	
	 protected function _initConfig()
	{
	    $config = new Zend_Config($this->getOptions(), true);
	    Zend_Registry::set('config', $config);
	    return $config;
	}
	
	public function _initRoutes()
	{
		$front = Zend_Controller_Front::getInstance();
 
		// Get Router
		$router = $front->getRouter();
		 
		$oauth = new Zend_Controller_Router_Route(
		    'oauth/:type/:method',
		    array(
		        'controller' => 'auth',
		        'action'     => 'oauth',
		        'type'		=> 'none',
		        'method'	=> 'redirect'
		    )
		);
		
		$ajax = new Zend_Controller_Router_Route(
		    'ajax/:type/:method',
		    array(
		        'controller' => 'auth',
		        'action'     => 'ajax',
		        'type'		=> 'none',
		        'method'	=> 'redirect'
		    )
		);
		 
		$router->addRoute('oauth', $oauth);
		$router->addRoute('ajax', $ajax);
	}
}

