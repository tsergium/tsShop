<?php

class Admin_CmsController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $id = $this->getRequest()->getParam('id');
        $page = $this->getRequest()->getParam('page');
        $model = new Default_Model_Cms();
        if (!empty($id) && $model->find($id)) {
            $form = new Admin_Form_Cms();
            $form->pageAdd();
            $form->pageEdit($model);
            $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/cms/page.phtml'))));
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->getRequest()->getPost())) {
                    $model->setOptions($form->getValues());
                    if ($model->save()) {

                        $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('cms_edit_success') . '</div>');
                    } else {
                        $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('cms_edit_error_1') . '</div>');
                    }
                    $this->_redirect('/admin/cms/index/id/' . $id);
                }
            }
        } else {
            $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('cms_edit_error_2') . '</div>');
        }
    }

    public function addAction()
    {
        $form = new Admin_Form_Cms();
        $form->pageAdd();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/cms/page.phtml'))));
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $model = new Default_Model_Cms();
                $model->setOptions($form->getValues());
                if ($model->save()) {
                    $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('cms_add_success') . '</div>');
                } else {
                    $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('cms_add_error') . '</div>');
                }
                $this->_redirect('/admin/cms/');
            }
        }
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $page = $this->getRequest()->getParam('page');
        $model = new Default_Model_Cms();
        if ($model->find($id)) {
            $form = new Admin_Form_Cms();
            $form->pageAdd();
            $form->pageEdit($model);
            $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/cms/page.phtml'))));
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->getRequest()->getPost())) {
                    $model->setOptions($form->getValues());
                    if ($model->save()) {
                        $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('cms_edit_success') . '</div>');
                    } else {
                        $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('cms_edit_error_1') . '</div>');
                    }
                    $this->_redirect('/admin/cms' . ($page ? '/index/page/' . $page : ''));
                }
            }
        } else {
            $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('cms_edit_error_2') . '</div>');
        }
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $page = $this->getRequest()->getParam('page');
        $model = new Default_Model_Cms();
        if ($model->find($id)) {
            if ($model->delete()) {
                $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('cms_delete_success') . '</div>');
            } else {
                $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('cms_delete_error') . '</div>');
            }
        }
        $this->_redirect('/admin/cms' . ($page ? '/index/page/' . $page : ''));
    }
}