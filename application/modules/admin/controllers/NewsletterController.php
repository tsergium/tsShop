<?php

class Admin_NewsletterController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $model = new Default_Model_NewsletterSubscribers();
        $select = $model->getMapper()->getDbTable()->select()
            ->order(array('created DESC'));
        if (($result = $model->fetchAll($select))) {
            $paginator = Zend_Paginator::factory($result);
            $paginator->setItemCountPerPage(20);
            $paginator->setCurrentPageNumber($this->_getParam('page'));
            $paginator->setPageRange(5);
            $this->view->result = $paginator;
            $this->view->curPage = $this->_getParam('page');
            $this->view->itemCountPerPage = $paginator->getItemCountPerPage();
            $this->view->totalItemCount = $paginator->getTotalItemCount();

            Zend_Paginator::setDefaultScrollingStyle('Sliding');
            Zend_View_Helper_PaginationControl::setDefaultViewPartial('_pagination.phtml');
        }
    }

    public function newsletterAction()
    {
        $form = new Admin_Form_Newsletter();
        $form->newsletter();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/NewsletterSend.phtml'))));
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $fromEmail = $form->getValue('emailFrom');
                $fromName = $form->getValue('nameFrom');
                $subject = $form->getValue('title');
                $message = $form->getValue('message');
                if ($form->getValue('status') == 0) {
                    $abonati = new Default_Model_NewsletterSubscribers();
                    $select = $abonati->getMapper()->getDbTable()->select()
                        ->where('status = ?', '1');
                    if (($result = $abonati->fetchAll($select))) {
                        $i = 0;
                        foreach ($result as $value) {

                            $email = $value->getEmail();

                            $mail = new Zend_Mail('UTF-8');
                            $mail->setFrom($fromEmail, $fromName);
                            $mail->setSubject($subject);
                            $mail->setBodyHtml($message);
                            $mail->addTo($email);
                            if ($mail->send()) {
                                if ($i == 0) {
                                    $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('marketing_newsletter_success') . '</div>');
                                }
                            } else {
                                if ($i == 0) {
                                    $this->_flashMessenger->addMessage("<div class='mess-false'>" . Zend_Registry::get('translate')->_('marketing_newsletter_error') . '</div>');
                                }
                            }
                            $i++;
                        }
                    }
                } else {
                    if ($form->getValue('email')) {
                        $email = $form->getValue('email');

                        $mail = new Zend_Mail('UTF-8');
                        $mail->setFrom($fromEmail, $fromName);
                        $mail->setSubject($subject);
                        $mail->setBodyHtml($message);
                        $mail->addTo($email);
                        if ($mail->send()) {
                            $this->_flashMessenger->addMessage("<div class='mess-true'>Newsletter-ul de test a fost trimis cu succes!</div>");
                        } else {
                            $this->_flashMessenger->addMessage("<div class='mess-false'>Eroare la trimiterea newsletter-ului de test</div>");
                        }
                    } else {
                        $this->_flashMessenger->addMessage("<div class='mess-false'>Eroare la trimiterea newsletter-ului de test</div>");
                    }
                }
                $this->_redirect('/admin/newsletter/newsletter');
            }
        }
    }

    public function subscribersstatuschangeAction()
    {
        $id = $this->getRequest()->getParam('id');
        $page = $this->getRequest()->getParam('page');
        $model = new Default_Model_NewsletterSubscribers();
        if ($model->find($id)) {
            $model->setStatus($model->getStatus() ? '0' : '1');
            if ($model->save()) {
                $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('marketing_visible_success') . '</div>');
            } else {
                $this->_flashMessenger->addMessage("<div class='mess-false'>" . Zend_Registry::get('translate')->_('marketing_visible_error') . '</div>');
            }
        }
        $this->_redirect('/admin/newsletter/index' . ($page ? '/page/' . $page : ''));
    }

    public function deleteemailAction()
    {
        $id = $this->getRequest()->getParam('id');
        $page = $this->getRequest()->getParam('page');
        $model = new Default_Model_NewsletterSubscribers();
        if ($model->find($id)) {
            if ($model->delete()) {
                $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('marketing_delete_email_success') . '</div>');
            } else {
                $this->_flashMessenger->addMessage("<div class='mess-false'>" . Zend_Registry::get('translate')->_('marketing_delete_email_error') . '</div>');
            }
        }
        $this->_redirect('/admin/newsletter/index' . ($page ? '/page/' . $page : ''));
    }
}



