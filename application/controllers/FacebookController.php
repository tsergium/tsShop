<?php
class FacebookController extends Zend_Controller_Action
{
	public function init()
    {
		/* Initialize action controller here */
		$bootstrap = $this->getInvokeArg('bootstrap');
		if($bootstrap->hasResource('db')) {
			$this->db = $bootstrap->getResource('db');
		}
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->message = $this->_flashMessenger->getMessages();
    }

	public function indexAction()
	{

	}
}