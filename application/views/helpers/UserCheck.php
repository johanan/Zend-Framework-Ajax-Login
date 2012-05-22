<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * ProfileLink helper
 *
 * Call as $this->profileLink() in your layout script
 */
class My_View_Helper_UserCheck extends Zend_View_Helper_Abstract {


     /**
     * View instance
     *
     * @var  Zend_View_Interface
     */
    public $view;


    public function userCheck()  {

        $baseUrl = $this->view->baseUrl();
        
        $auth = Zend_Auth::getInstance();

        $html = $this->view->partial('partials/userNotLoggedIn.phtml');

        if ($auth->hasIdentity()) {
        	$ident = Zend_Auth::getInstance()->getIdentity();         
            $html = $this->view->partial('partials/userLoggedIn.phtml', array('userObj'=>$ident['user']));
        }        

        return $html;
    }


     /**
     * Get Zend_View instance
     *
     * @param Zend_View_Interface $view
     */
    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

}
?>