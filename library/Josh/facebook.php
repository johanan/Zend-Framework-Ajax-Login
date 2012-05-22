<?php

class Josh_Facebook
{
	
	private static $fb;
	
	private static function getFB()
	{
		if(self::$fb)
		{
			return self::$fb;
			print_r(self::$fb);
		}

		$options = Zend_Registry::get('config')->facebook;
		
		$fb = new Facebook_Facebook(array(
				  'appId'  => $options->appid,
				  'secret' => $options->secret,
				));
		
		self::$fb = $fb;
		
		return self::$fb;
	}
	
	public static function __callStatic ( $name, $args ) 
	{

        $callback = array ( self::getFB(), $name ) ;
        return call_user_func_array ( $callback , $args ) ;
    }
	
	public static function killSession ()
	{
		$options = Zend_Registry::get('config')->facebook;
		$fbAppid = 'fb_' . $options->appid;
		
		setcookie('fbs_'.$options->appid, '', time()-100, '/', 'domain.com');
		unset($_SESSION[$fbAppid . '_user_id']);
		unset($_SESSION[$fbAppid . '_code']);
		unset($_SESSION[$fbAppid . '_access_token']);
	}
}
?>
