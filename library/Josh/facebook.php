<?php

class Josh_Facebook
{
	
	private static $fb;
	
	private static function getFB()
	{
		if(self::$fb)
		{
			return self::$fb;
		}
		
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		
		$options = $bootstrap->getOptions();
		
		
		$fb = New Facebook_Facebook(array(
				'appId' => $options['facebook']['appid'],
				'secret' => $options['facebook']['appsecret'],
				));
		
		self::$fb = $fb;
		
		return self::$fb;
	}
	
	public static function __callStatic ( $name, $args ) 
	{

        $callback = array ( self::getFB(), $name ) ;
        return call_user_func_array ( $callback , $args ) ;
    }
}
?>
