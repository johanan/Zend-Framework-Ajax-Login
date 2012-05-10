<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{	
	 protected function _initConfig()
	{
	    $config = new Zend_Config($this->getOptions(), true);
	    Zend_Registry::set('config', $config);
	    return $config;
	}
}

