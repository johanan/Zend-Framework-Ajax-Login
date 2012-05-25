<?php

class Josh_Auth_Adapter_Db implements Zend_Auth_Adapter_Interface
{
	protected $_email;
	
	public function __construct($email)
	{
		$this->_email = $email;	
	}
	
	public function authenticate()
    {
    	//you should check in the database here
    	if($this->_email == null)
		{
			return new Zend_Auth_Result( Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS, $this->_email, array('error'=>'No email supplied'));
		}else{
			return new Zend_Auth_Result( Zend_Auth_Result::SUCCESS, $this->_email, array('first_name'=>$this->_email, 
						'last_name'=>'None', 'id'=>null, 'email' => $this->_email, 'gender' => null));
		}
    }
}
	