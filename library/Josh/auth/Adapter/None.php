<?php

class Josh_Auth_Adapter_None implements Zend_Auth_Adapter_Interface
{
	public function authenticate()
    {
		return new Zend_Auth_Result( Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS, null, array('error'=>'This is not implemented'));
    }
}