<?php

class AuthController extends Zend_Controller_Action
{
    protected $_flashMessenger = null;

    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $bootstrap = $this->getInvokeArg('bootstrap');
        if ($bootstrap->hasResource('db')) {
            $this->db = $bootstrap->getResource('db');
        }
    }

    public function indexAction()
    {
        $formAuth = new Default_Form_Auth();
        $formAuth->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/Auth.phtml')),));
        $this->view->formAuth = $formAuth;

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
                            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Datele de conectare sunt invalide.</p></div>");
                            break;

                        default:
                            /** do stuff for other failure **/
                            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare de conectare!</p></div>");
                            break;
                    }
                    $this->_redirect(WEBPAGE_ADDRESS . '/user/new');
                } else {
                    $accountId = $dbAdapter->getResultRowObject();
                    $account = new Default_Model_Clients();
                    $account->find($accountId->id);
                    $storage = $auth->getStorage();
                    $storage->write($account);
                }
                $this->_redirect(WEBPAGE_ADDRESS . '/');
            }
        }

        $this->view->message = $this->_flashMessenger->getMessages();
        $this->view->formAuth = $formAuth;
    }

    public function logoutAction()
    {
        $this->_helper->layout->disableLayout();

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
        }
        $this->_redirect('/index');
    }

    public function activationAction()
    {
        $account = new Default_Model_Clients();
        $activationcode = $this->getRequest()->getParam('code');

        $form = new Default_Form_Auth();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/Auth.phtml')),));
        $this->view->formAuth = $form;

        if (null != $activationcode) {
            $select = $account->getMapper()->getDbTable()->select()
                ->where('`activationcode` = ?', $activationcode);
            if (($accounts = $account->fetchAll($select))) {
                $accountActivation = $accounts[0];
                $accountActivation->setStatus("1");
                $accountActivation->setActivationcode('');
                if ($accountActivation->save()) {
                    $this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>Contul a fost activat. Va rugam sa va autentificati!</p></div>");
                } else {
                    $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare activare cont!</p></div>");
                }
                $this->_redirect('/index/');
            } else {
                $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare activare cont!</p></div>");
                $this->_redirect('/index/');
            }
        }
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    /**
     * Forgot password
     *
     * @throws Zend_Form_Exception
     * @throws Zend_Mail_Exception
     */
    public function remindAction()
    {
        $form = new Default_Form_Remind();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/Remind.phtml')),));
        $this->view->formRemind = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $mail = $this->getRequest()->getParam('email');

                $accountModel = new Default_Model_Clients();
                $select = $accountModel->getMapper()->getDbTable()->select()
                    ->where('email = ?', $mail);
                $account = $accountModel->fetchRow($select);
                if ($account) {
                    $username = $account->getUsername();
                    $email = $account->getEmail();
                    $firstname = $account->getFirstname();
                    $lastname = $account->getLastname();
                    $id = $account->getId();

                    $new_password = substr(md5(uniqid(mt_rand(), true)), 0, 10);
                    $password = md5($new_password);
                    $forgotPassTemplate = new Default_Model_Templates();
                    $forgotPassTemplate->find('forgotPassTemplate');
                    $forgotPass = $forgotPassTemplate->getValue();

                    $forgotPass = str_replace("{" . "$" . "firstname}", $firstname, $forgotPass);
                    $forgotPass = str_replace("{" . "$" . "lastname}", $lastname, $forgotPass);
                    $forgotPass = str_replace("{" . "$" . "username}", $username, $forgotPass);
                    $forgotPass = str_replace("{" . "$" . "password}", $new_password, $forgotPass);
                    $forgotPass = str_replace("{" . "$" . "email}", $email, $forgotPass);

                    $mail = new Zend_Mail();
                    $mail->setFrom('no-reply@' . $_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST']);
                    $mail->setSubject($forgotPassTemplate->getSubject());
                    $mail->setBodyHtml($forgotPass);
                    $mail->addTo($email, $firstname . " " . $lastname);
                    try {
                        $account->find($id);
                        $account->setPassword($password);
                        if ($account->save()) {
                            if ($mail->send()) {
                                $this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>Un email cu noua parola a fost trimis!</p></div>");
                            } else {
                                $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare resetare parola!</p></div>");
                            }
                        } else {
                            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare resetare parola!</p></div>");
                        }
                    } catch (Zend_Exception $e) {
                        $this->_flashMessenger->addMessage($e->getMessage());
                    }
                } else {
                    $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare resetare parola!</p></div>");
                }
                $this->_redirect('/auth/remind');
            }
        }
        $this->view->message = $this->_flashMessenger->getMessages();
    }
}