<?php

class TestController extends Zend_Controller_Action
{
    protected $_keys;

    public function init()
    {
        /* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }


    public function indexAction()
    {
//		$product = new Default_Model_Product();
//	    $select = $product->getMapper()->getDbTable()->select()
//		    	->from(array('p'=>'ts_products'))
//		    	->join(array('pca'=>'ts_products_categ_asoc'), 'p.id = pca.productId' ,array('pcaId'=>'pca.id'))
//		    	->join(array('ppa'=>'ts_products_promotion_asoc'), 'p.id = ppa.productId' ,array('ppaId'=>'ppa.id'))
////				->where('p.status = ?', '1')
////				->where('pca.categoryId = ?', '329')
//				->where('ppa.promotionId = ?', '3')
//				->group('p.id')
//				->order('p.created DESC')
//				->setIntegrityCheck(false);
//		$result = $product->fetchAll($select);
//	    if(null != $result){
//			 foreach ($result as $value) {
////				if($value->getPromotionId() > 0){
//					// BEGIN: SAVE PRODUCT TO PROMOTION ASOCIATION
//						$model = new Default_Model_Productcategasoc();
//						$model->setCategoryId('329');
//						$model->setProductId($value->getId());
//						$model->save();
//					// END: SAVE PRODUCT TO PROMOTION ASOCIATION
//					//
////				}
//			 }
//		}

    }

    public function page1Action()
    {
        // get an instace of Zend_Auth
        $auth = Zend_Auth::getInstance();

        // check if a user is already logged
        if ($auth->hasIdentity()) {
            $this->_helper->FlashMessenger('It seems you are already logged into the system ');
            return $this->_redirect('/index/');
        }

        // if the user is not logged, the do the logging
        // $openid_identifier will be set when users 'clicks' on the account provider
        $openid_identifier = $this->getRequest()->getParam('openid_identifier', null);

        // $openid_mode will be set after first query to the openid provider
        $openid_mode = $this->getRequest()->getParam('openid_mode', null);

        // this one will be set by facebook connect
        $code = $this->getRequest()->getParam('code', null);

        // while this one will be set by twitter
        $oauth_token = $this->getRequest()->getParam('oauth_token', null);


        // do the first query to an authentication provider
        if ($openid_identifier) {

            if ('https://www.twitter.com' == $openid_identifier) {
                $adapter = $this->_getTwitterAdapter();
            } else if ('https://www.facebook.com' == $openid_identifier) {
                $adapter = $this->_getFacebookAdapter();
            } else {
                // for openid
                $adapter = $this->_getOpenIdAdapter($openid_identifier);

                // specify what to grab from the provider and what extension to use
                // for this purpose
                $toFetch = $this->_keys->openid->tofetch->toArray();

                // for google and yahoo use AtributeExchange Extension
                if ('https://www.google.com/accounts/o8/id' == $openid_identifier || 'http://me.yahoo.com/' == $openid_identifier) {
                    $ext = $this->_getOpenIdExt('ax', $toFetch);
                } else {
                    $ext = $this->_getOpenIdExt('sreg', $toFetch);
                }

                $adapter->setExtensions($ext);
            }

            // here a user is redirect to the provider for loging
            $result = $auth->authenticate($adapter);
            // the following two lines should never be executed unless the redirection faild.
            $this->_helper->FlashMessenger('Redirection faild');
            return $this->_redirect('/auth');
        } else if ($openid_mode || $code || $oauth_token) {
            // this will be exectued after provider redirected the user back to us

            if ($code) {
                // for facebook
                $adapter = $this->_getFacebookAdapter();
            } else if ($oauth_token) {
                // for twitter
                $adapter = $this->_getTwitterAdapter()->setQueryData($_GET);
            } else {
                // for openid
                $adapter = $this->_getOpenIdAdapter(null);

                // specify what to grab from the provider and what extension to use
                // for this purpose
                $ext = null;

                $toFetch = $this->_keys->openid->tofetch->toArray();

                // for google and yahoo use AtributeExchange Extension
                if (isset($_GET['openid_ns_ext1']) || isset($_GET['openid_ns_ax'])) {
                    $ext = $this->_getOpenIdExt('ax', $toFetch);
                } else if (isset($_GET['openid_ns_sreg'])) {
                    $ext = $this->_getOpenIdExt('sreg', $toFetch);
                }

                if ($ext) {
                    $ext->parseResponse($_GET);
                    $adapter->setExtensions($ext);
                }
            }

            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                $toStore = array('identity' => $auth->getIdentity());

                if ($ext) {
                    // for openId
                    $toStore['properties'] = $ext->getProperties();
                } else if ($code) {
                    // for facebook
                    $msgs = $result->getMessages();
                    $toStore['properties'] = (array)$msgs['user'];
                } else if ($oauth_token) {
                    // for twitter
                    $identity = $result->getIdentity();
                    // get user info
                    $twitterUserData = (array)$adapter->verifyCredentials();
                    $toStore = array('identity' => $identity['user_id']);
                    if (isset($twitterUserData['status'])) {
                        $twitterUserData['status'] = (array)$twitterUserData['status'];
                    }
                    $toStore['properties'] = $twitterUserData;
                }

                $auth->getStorage()->write($toStore);

                $this->_helper->FlashMessenger('Successful authentication');
                return $this->_redirect('/auth');
            } else {
                $this->_helper->FlashMessenger('Failed authentication');
                $this->_helper->FlashMessenger($result->getMessages());
                return $this->_redirect('/test/page1');
            }
        }
    }

    /**
     * Get My_Auth_Adapter_Facebook adapter
     *
     * @return My_Auth_Adapter_Facebook
     */
    protected function _getFacebookAdapter()
    {
        extract($this->_keys->facebook->toArray());
        return new My_Auth_Adapter_Facebook($appid, $secret, $redirecturi, $scope);
    }

    /**
     * Get My_Auth_Adapter_Oauth_Twitter adapter
     *
     * @return My_Auth_Adapter_Oauth_Twitter
     */
    protected function _getTwitterAdapter()
    {
        extract($this->_keys->twitter->toArray());
//		echo "<pre>", print_r($this->_keys->twitter->toArray(), 1), "</pre>";
//		die();
        return new My_Auth_Adapter_Oauth_Twitter(array(), $appid, $secret, $redirecturi);
    }

    /**
     * Get Zend_Auth_Adapter_OpenId adapter
     *
     * @param string $openid_identifier
     * @return Zend_Auth_Adapter_OpenId
     */
    protected function _getOpenIdAdapter($openid_identifier = null)
    {
        $adapter = new Zend_Auth_Adapter_OpenId($openid_identifier);
        $dir = APPLICATION_PATH . '/../media/tmp';

        if (!file_exists($dir)) {
            if (!mkdir($dir)) {
                throw new Zend_Exception("Cannot create $dir to store tmp auth data.");
            }
        }
        $adapter->setStorage(new Zend_OpenId_Consumer_Storage_File($dir));

        return $adapter;
    }

    /**
     * Get Zend_OpenId_Extension. Sreg or Ax.
     *
     * @param string $extType Possible values: 'sreg' or 'ax'
     * @param array $propertiesToRequest
     * @return Zend_OpenId_Extension|null
     */
    protected function _getOpenIdExt($extType, array $propertiesToRequest)
    {

        $ext = null;

        if ('ax' == $extType) {
            $ext = new My_OpenId_Extension_AttributeExchange($propertiesToRequest);
        } elseif ('sreg' == $extType) {
            $ext = new Zend_OpenId_Extension_Sreg($propertiesToRequest);
        }

        return $ext;
    }

    public function tweeterAction()
    {

    }

    public function ajaxcategAction()
    {
        function top()
        {
            $category = new Default_Model_Categories();
            $select = $category->getMapper()->getDbTable()->select()
                ->where('parentId IS NULL');
            $categories = $category->fetchAll($select);
            if (null != $categories) {
                return $categories;
            } else {
                return false;
            }
        }
    }
}



