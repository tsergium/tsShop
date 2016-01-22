<?php

class FacebookController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $bootstrap = $this->getInvokeArg('bootstrap');
        if ($bootstrap->hasResource('db')) {
            $this->db = $bootstrap->getResource('db');
        }
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        require_once APPLICATION_PUBLIC_PATH . '/library/Facebook/autoload.php';
        $fb = new Facebook\Facebook([
            'app_id' => FACEBOOK_APP_ID,
            'app_secret' => FACEBOOK_APP_SECRET,
            'default_graph_version' => 'v2.5',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes']; // optional
        $loginUrl = $helper->getLoginUrl(FACEBOOK_REDIRECT_URL, $permissions);

        $this->view->loginUrl = $loginUrl;

        // fetch products
        $modelProducts = new Default_Model_Product();
        $select = $modelProducts->getMapper()->getDbTable()->select()
            ->where('categoryId = ?', FACEBOOK_CATEGORY_ID)
            ->where('status = ?', '1');
        $products = $modelProducts->fetchAll($select);

        $this->view->products = $products;
    }
}