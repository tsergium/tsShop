<?php

class Admin_ClientController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $status = $this->getRequest()->getParam('status');
        $model = new Default_Model_Clients();
        $select = $model->getMapper()->getDbTable()->select();
        if ($status != null) {
            $select->where('status = ?', $status);
        }
        $select->order(array('created DESC'));
        if (($result = $model->fetchAll($select))) {
            $paginator = Zend_Paginator::factory($result);
            $paginator->setItemCountPerPage(25);
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

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $action = $this->getRequest()->getParam('newaction');
        $search = $this->getRequest()->getParam('txtHeaderSearch');
        $page = $this->getRequest()->getParam('page');
        $model = new Default_Model_Clients();
        if ($model->find($id)) {
            $form = new Admin_Form_Client();
            $form->customerAdd();
            $form->customerEdit($model);
            $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/client/customerEdit.phtml'))));
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->getRequest()->getPost())) {
                    $post = $this->getRequest()->getPost();
                    $model->setOptions($form->getValues());
                    if ($model->save()) {
                        $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('customers_edit_success') . '</div>');
                        $this->_redirect('/admin/client' . ($page ? '/' . ($action ? $action : 'index') . '/page/' . $page : ($search ? '/' . $action . '/txtHeaderSearch/' . $search : ($action ? '/' . $action : ''))));
                    } else {
                        $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('customers_edit_error_1') . '</div>');
                        $this->_redirect('/admin/client/edit/id/' . $model->getId());
                    }
                }
            }
        } else {
            $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('customers_edit_error_2') . '</div>');
            $this->_redirect('/admin/client' . ($page ? '/' . ($action ? $action : 'index') . '/page/' . $page : ($search ? '/' . $action . '/txtHeaderSearch/' . $search : ($action ? '/' . $action : ''))));
        }
    }

    public function detailsAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Clients();
        if ($model->find($id)) {
            $this->view->customer = $model;
        }
    }

    public function visibleAction()
    {
        $id = $this->getRequest()->getParam('id');
        $action = $this->getRequest()->getParam('newaction');
        $search = $this->getRequest()->getParam('txtHeaderSearch');
        $page = $this->getRequest()->getParam('page');
        $model = new Default_Model_Clients();
        if ($model->find($id)) {
            $model->setStatus($model->getStatus() ? '0' : '1');
            if ($model->save()) {
                $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('customers_visible_success') . '</div>');
            } else {
                $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('customers_visible_error') . '</div>');
            }
        }
        $this->_redirect('/admin/client' . ($page ? '/' . ($action ? $action : 'index') . '/page/' . $page : ($search ? '/' . $action . '/txtHeaderSearch/' . $search : ($action ? '/' . $action : ''))));
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $action = $this->getRequest()->getParam('newaction');
        $search = $this->getRequest()->getParam('txtHeaderSearch');
        $page = $this->getRequest()->getParam('page');
        $model = new Default_Model_Clients();
        if ($model->find($id)) {
            if ($model->delete()) {
                $this->_flashMessenger->addMessage('<div class="mess-true">' . Zend_Registry::get('translate')->_('customers_delete_success') . '</div>');
            } else {
                $this->_flashMessenger->addMessage('<div class="mess-false">' . Zend_Registry::get('translate')->_('customers_delete_error') . '</div>');
            }
        }
        $this->_redirect('/admin/client' . ($page ? '/' . ($action ? $action : 'index') . '/page/' . $page : ($search ? '/' . $action . '/txtHeaderSearch/' . $search : ($action ? '/' . $action : ''))));
    }

    public function searchAction()
    {
        // Begin: Display form
        $form = new Admin_Form_Client();
        $form->search();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/client/search.phtml'))));
        $this->view->form = $form;
        // End: Display form

        if ($this->getRequest()->getParam('txtHeaderSearch') != null) {
            $searchTerm = $this->getRequest()->getParam('txtHeaderSearch');
            $this->view->searchTerm = $searchTerm;
            $array = array();
            $array = explode(' ', $searchTerm);
            $model = new Default_Model_Clients();
            $select = $model->getMapper()->getDbTable()->select();
            foreach ($array as $value) {
                $select->where("firstname LIKE '%" . $value . "%' OR lastname LIKE '%" . $value . "%' OR username LIKE '%" . $value . "%'");
            }
            if (($result = $model->fetchAll($select))) {
                $this->view->result = $result;
            } else {
                $this->_flashMessenger->addMessage('<div class="mess-false">Nu a fost gasit nici un client.</div>');
                $this->_redirect('/admin/client/search');
            }
        }
    }
}