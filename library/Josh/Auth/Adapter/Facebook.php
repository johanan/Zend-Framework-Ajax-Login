<?php

class Josh_Auth_Adapter_Facebook implements Zend_Auth_Adapter_Interface
{
	/**
     * Authenticates the user against facebook
     * Defined by Zend_Auth_Adapter_Interface.
     *
     * @throws Zend_Auth_Adapter_Exception If answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
		//first see if the we have any sort of user
		$user = Josh_Facebook::getUser();
		
		if($user)
		{
			//now let's get the current user logged into facebook
			$apiMe = Josh_Facebook::api('/me');
			
			if($apiMe)
			{
				return new Zend_Auth_Result( Zend_Auth_Result::SUCCESS, $apiMe['id'], $apiMe );
			}
		}else{
			
			return new Zend_Auth_Result( Zend_Auth_Result::FAILURE, null, array('error'=>'You are not authenticated to Facebook') );
		}
        

        
    }
}
?>