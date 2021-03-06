<?php class Admin_AuthController extends Zend_Controller_Action
{
    protected $_flashMessenger = null;

    public function init()
    {        /* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $bootstrap = $this->getInvokeArg('bootstrap');
        if ($bootstrap->hasResource('db')) {
            $this->db = $bootstrap->getResource('db');
        }
    }

    public function firstloginAction()
    {
        $username = $this->getRequest()->getParam('usr-username');
        $password = $this->getRequest()->getParam('usr-password');
        if (null == $username && null == $password) {
            $this->_redirect('/admin/auth/login');
        }
        $dbAdapter = new Zend_Auth_Adapter_DbTable($this->db, 'ts_admin_user', 'username', 'password', ' AND status = "1" AND roleId = "1" OR roleId = "3"');
        $dbAdapter->setIdentity($username)->setCredential($password);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($dbAdapter);
        if (!$result->isValid()) {
            switch ($result->getCode()) {
                case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                    $this->_flashMessenger->addMessage("<div class='mess-false'>" . Zend_Registry::get('translate')->_("auth_login_error_1") . "</div>");
                    break;
                default:
                    /** do stuff for other failure **/
                    $this->_flashMessenger->addMessage("<div class='mess-false'>" . Zend_Registry::get('translate')->_("auth_login_error_2") . "</div>");
                    break;
            }
            $this->_redirect('/admin/auth/login');
        } else {
            $adminUserId = $dbAdapter->getResultRowObject();
            $adminUser = new Default_Model_AdminUser();
            $adminUser->find($adminUserId->id);
            $adminUser->saveLastLogin();
            $storage = $auth->getStorage('admin');
            $storage->write($adminUser);
            $this->_redirect('/admin/dashboard');
        }
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function loginAction()
    {
        $form = new Admin_Form_Login();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $dbAdapter = new Zend_Auth_Adapter_DbTable($this->db, 'ts_admin_user', 'username', 'password', 'MD5(?) AND status = "1" AND roleId = "1" OR roleId = "3"');
                $dbAdapter->setIdentity($this->getRequest()->getPost('tbUser'))->setCredential($this->getRequest()->getPost('tbPass'));
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($dbAdapter);
                if (!$result->isValid()) {
                    switch ($result->getCode()) {
                        case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                            $this->_flashMessenger->addMessage("<div class='mess-false'>" . Zend_Registry::get('translate')->_("auth_login_error_1") . "</div>");
                            break;
                        default:
                            /** do stuff for other failure **/
                            $this->_flashMessenger->addMessage("<div class='mess-false'>" . Zend_Registry::get('translate')->_("auth_login_error_2") . "</div>");
                            break;
                    }
                } else {
                    $adminUserId = $dbAdapter->getResultRowObject();
                    $adminUser = new Default_Model_AdminUser();
                    $adminUser->find($adminUserId->id);
                    $adminUser->saveLastLogin();
                    $storage = $auth->getStorage('admin');
                    $storage->write($adminUser);
                }
                $this->_redirect('/admin/auth/login');
            }
        }
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function logoutAction()
    {
        $this->_helper->layout->disableLayout();
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
        }
        $this->_redirect('/admin/auth/login');
    }
}