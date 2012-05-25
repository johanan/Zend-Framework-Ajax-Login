<?php

class Josh_Auth_Adapter_Google implements Zend_Auth_Adapter_Interface
{
	protected $_mode;
	
	public function __construct()
	{
		$this->_mode = $_GET['openid_mode'];
	}
	
	public function authenticate()
	{
		$options = Zend_Registry::get('config')->openid->tofetch->toArray();
		$ext = new Cbisnett_AttributeExchange($options);
		
		if(!$this->_mode)
		{
			$openid = new Zend_Auth_Adapter_OpenId('https://www.google.com/accounts/o8/id');

			$openid->setExtensions($ext);
			$openid->authenticate();
		}elseif($this->_mode == 'id_res'){

			$ext->parseResponse($_GET);
			$props = $ext->getProperties();
			return new Zend_Auth_Result( Zend_Auth_Result::SUCCESS, $_GET['openid_identity'] , array('first_name'=>$props['firstName'], 'last_name'=>$props['lastName'], 'id'=>$_GET['openid_identity'], 'email' => $props['email'], 'gender' => null));
						
		}elseif($this->_mode == 'cancel'){
			return new Zend_Auth_Result( Zend_Auth_Result::FAILURE, null, array('error'=>'You denied access') );
		}else{
			return new Zend_Auth_Result( Zend_Auth_Result::FAILURE, null, array('error'=>'You denied access') );
		}
	}
}
?>