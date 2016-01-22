<?php

class IframeController extends Zend_Controller_Action
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

    public function loginAction()
    {
        // BEGIN: FETCH USER DETAILS
        $auth = Zend_Auth::getInstance();
        $authAccount = $auth->getStorage()->read();
        if (null != $authAccount) {
            $this->view->authUser = 'yes';
        }
        $formAuth = new Default_Form_AuthLogin();
        $formAuth->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/AuthLogin.phtml')),));
        $this->view->formLogin = $formAuth;

        if ($this->getRequest()->isPost()) {
            if ($formAuth->isValid($this->getRequest()->getPost())) {

                $dbAdapter = new Zend_Auth_Adapter_DbTable($this->db, 'ts_clients', 'username', 'password', 'MD5(?) AND status = "1"');
                $dbAdapter->setIdentity($this->getRequest()->getPost('username'))
                    ->setCredential($this->getRequest()->getPost('passwordLogare'));

                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($dbAdapter);
                if (!$result->isValid()) {
                    switch ($result->getCode()) {
                        case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                            $this->_flashMessenger->addMessage("<div class='mess-false'>Invalid username or password</div>");
                            break;

                        default:
                            /** do stuff for other failure **/
                            $this->_flashMessenger->addMessage("Some error has ocurred!");
                            break;
                    }
                    $this->_redirect('/iframe/login');
                } else {
                    $accountId = $dbAdapter->getResultRowObject();
                    $account = new Default_Model_Clients();
                    $account->find($accountId->id);
//					$last_login = $account->getLastlogin();
//					$account ->saveLastLogin();
                    $storage = $auth->getStorage();
                    $storage->write($account);
                }
                $this->_redirect('/iframe/login');
            }
        }

        $this->view->message = $this->_flashMessenger->getMessages();

    }

//	


}