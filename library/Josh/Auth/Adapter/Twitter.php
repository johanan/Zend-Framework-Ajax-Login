<?php

class Josh_Auth_Adapter_Twitter implements Zend_Auth_Adapter_Interface
{
	/**
     * Authenticates the user against facebook
     * Defined by Zend_Auth_Adapter_Interface.
     *
     * @throws Zend_Auth_Adapter_Exception If answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
	protected $_config;
	protected $_session;
	
	public function __construct()
	{
		$options = Zend_Registry::get('config')->twitter;
		
		$this->_config = array(
              'callbackUrl' => $options->endpoint,
               'siteUrl' => 'http://api.twitter.com/oauth',
               'consumerKey' => $options->consumerKey,
               'consumerSecret' => $options->consumerSecret
        );
		
	}
    
    public function authenticate()
    {
    	$twitterNS = new Zend_Session_Namespace('twitterNS');
		
    	$consumer = new Zend_Oauth_Consumer($this->_config);
		if(!isset($twitterNS->token))
		{
	        $token = $consumer->getRequestToken();
			$twitterNS->token =  serialize($token);
			$consumer->redirect();
		}else{
			 if (isset($_GET['oauth_token']) && isset($twitterNS->token)) {
			 	$token = $consumer->getAccessToken($_GET,unserialize($twitterNS->token));
           		$twitterNS->twitterAccessToken = serialize($token);
				
				unset($twitterNS->token);
				
				//check the token
				$twitter = new Zend_Service_Twitter(array(
    				'username' => $token->getParam('screen_name'),
    				'accessToken' => $token));
					
				$response = $twitter->account->verifyCredentials();
				
				if($response->isSuccess())
				{
					//the token is good
					$results = $response->getIterator();

					return new Zend_Auth_Result( Zend_Auth_Result::SUCCESS, $results->id->__toString() , 
					array('first_name'=>$results->name->__toString(), 
							'last_name'=>null, 'id'=>$results->id->__toString(), 'email' => null, 'gender' => null, 'twitterImg'=>$results->profile_image_url->__toString())
					);
				}else{
					unset($twitterNS->token);
					return new Zend_Auth_Result( Zend_Auth_Result::FAILURE, null, array('error'=>'You are not authenticated to Twitter') );
				}
			 }elseif(isset($_GET['denied']))
			 {
			 	unset($twitterNS->token);
			 	return new Zend_Auth_Result( Zend_Auth_Result::FAILURE, null, array('error'=>'You denied access') );
			 }else{
			 	unset($twitterNS->token);
			 	return new Zend_Auth_Result( Zend_Auth_Result::FAILURE, null, array('error'=>'You are not authenticated to Twitter') );
			 }
			 
		}	
		
    }
}
?>