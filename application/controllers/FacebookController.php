<?php
class FacebookController extends Zend_Controller_Action
{
    const FACEBOOK_APP_ID       = 630609053747471;
    const FACEBOOK_APP_SECRET   = '75765bc7970ba43328c8bfc753ca7dc1';

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
		require_once APPLICATION_PUBLIC_PATH . '/library/Facebook/autoload.php';
		$fb = new Facebook\Facebook([
			'app_id' => self::FACEBOOK_APP_ID,
			'app_secret' => self::FACEBOOK_APP_SECRET,
			'default_graph_version' => 'v2.2',
		]);
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes']; // optional
        $loginUrl = $helper->getLoginUrl('http://{your-website}/login-callback.php', $permissions);

        $this->view->loginUrl = $loginUrl;
	}
}