<?php

class Admin_TemplateController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $lang = $this->view->language;
        $const = $this->getRequest()->getParam('const');
        $model = new Default_Model_Templates();
        if (!empty($const) && $model->find($const)) {
            $form = new Admin_Form_Template();
            $form->subject->setValue($model->getSubject());
            $form->value->setValue($model->getValue());

            $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/template/email.phtml'))));
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->getRequest()->getPost())) {
                    $post = $this->getRequest()->getPost();
                    $model->setSubject($post['subject']);
                    $model->setValue($post['value']);
                    if ($model->save()) {
                        $this->_flashMessenger->addMessage('<div class="mess-true">Modificarile au fost efectuate</div>');
                    } else {
                        $this->_flashMessenger->addMessage('<div class="mess-false">Eroare! Modificarile nu au putut fi efectuate.</div>');
                    }
                    $this->_redirect('/admin/template/index/const/' . $const);
                }
            }
            $this->view->const = $const;
        }
    }
}