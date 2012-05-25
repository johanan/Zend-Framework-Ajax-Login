<?php

class Josh_Auth_Adapter_Factory
{
	public static function factory($type, $options = null)
	{
		if($type == 'fb' || $type == 'fb-oauth')
			return new Josh_Auth_Adapter_Facebook();
		elseif($type == 'twitter')
			return new Josh_Auth_Adapter_Twitter();
		elseif($type == 'userpass')
			return new Josh_Auth_Adapter_Db($options['email']);
		elseif($type == 'google')
			return new Josh_Auth_Adapter_Google();
		else 
			return new Josh_Auth_Adapter_None();
	}
}
