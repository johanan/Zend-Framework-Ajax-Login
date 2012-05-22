<?php

class My_View_Helper_FlashAlerts extends Zend_View_Helper_Abstract 
{
	public function flashAlerts()
	{
		$messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
		$html = '';
			
		if (!empty($messages)) {
			
			foreach ($messages as $message) {
               $html .= $this->view->partial('partials/alert.phtml', array('alert'=>current($message), 'alertClass'=>'alert-' . key($message)));
            }
             
        }
		
		return $html;
	}
}
