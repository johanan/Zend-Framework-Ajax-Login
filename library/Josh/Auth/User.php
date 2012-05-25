<?php

class Josh_Auth_User
{
	protected $_fname;
	protected $_lname;
	protected $_fbid;
	protected $_twitterid;
	protected $_email;
	protected $_gender;
	protected $_type;
	protected $_twitterImg;
	
	public function __construct($type, $identity)
	{
		//this is a quick and dirty way to do this
		//you should use a database for this
		//you could use the userid here and then query out 
		//all this info that you have stored
		//I technically am using Facebook as my database here

		$this->_fname = $identity['first_name'];
		$this->_lname = $identity['last_name'];
		$this->_fbid = $identity['id'];
		$this->_email = $identity['email'];
		$this->_gender = $identity['gender'];
		$this->_type = $type;
		if(isset($identity['twitterImg']))
			$this->_twitterImg = $identity['twitterImg'];
		
		//I don't have twitter as is not a field
		//just make sure your database has this and you can grab it
	}
	
	public function getFullName()
	{
		return $this->_fname . " " . $this->_lname;
	}
	
	public function getFirstName()
	{
		return $this->_fname;
	}
	
	public function getFBID()
	{
		return $this->_fbid;
	}
	
	public function getEmail()
	{
		return $this->_email;
	}
	
	public function getGender()
	{
		return $this->_gender;
	}
	
	public function getIMGURL($size = 100)
	{
		if($this->_type == 'fb')
		{
			if ($size >= 200)
			{
				$fbsize = "large";
			}else if ($size >= 100){
				$fbsize = "normal";
			}else{
				$fbsize = "square";
			}
			return "https://graph.facebook.com/" . $this->_fbid . "/picture?return_ssl_resources=1&type=" . $fbsize ;
		}elseif($this->_type == 'twitter'){
			return $this->_twitterImg;
		}else{
			$emailhash = md5( strtolower( trim( $this->_email ) ) );
			return "http://www.gravatar.com/avatar/" . $emailhash ."?s=" . $size . "&d=mm";
		}
	}
}
