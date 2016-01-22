<?php

class Admin_CouponController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $model = new Default_Model_Coupon();
        $select = $model->getMapper()->getDbTable()->select();
        $coupons = $model->fetchAll($select);

        if (null != $coupons) {
            $paginator = Zend_Paginator::factory($coupons);
            $paginator->setItemCountPerPage($this->view->adminProdsPage);
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

    public function addAction()
    {
        $form = new Admin_Form_Coupon();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/coupon/CouponAdd.phtml'))));
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $post = $this->getRequest()->getPost();
                $post['status'] = 'active';
                $model = new Default_Model_Coupon();
                $model->setOptions($post);
                if ($model->save()) {
                    $this->_flashMessenger->addMessage('<div class="mess-true">Cuponul a fost adaugat cu succes!</div>');
                } else {
                    $this->_flashMessenger->addMessage('<div class="mess-false">Eroare la adaugarea cuponului!</div>');
                }
            }
            $this->_redirect('admin/coupon');
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Coupon();
        $model->find($id);
        if (null != $model) {
            $form = new Admin_Form_Coupon();
            $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/coupon/CouponEdit.phtml'))));
            $form->edit($model);
            $this->view->form = $form;
            $post = $this->getRequest()->getPost();
            if (null != $post) {
                if ($form->isValid($post)) {
                    $post['status'] = $model->getStatus();
                    $model->setOptions($post);
                    if ($model->save()) {
                        $this->_flashMessenger->addMessage('<div class="mess-true">Cuponul a fost modificat cu succes!</div>');
                    } else {
                        $this->_flashMessenger->addMessage('<div class="mess-false">Eroare la modificarea cuponului!</div>');
                    }
                }
                $this->_redirect('admin/coupon');
            }
        } else {
            $this->_flashMessenger->addMessage('<div class="mess-false">Cuponul selectat nu a fost gasit!</div>');
            $this->_redirect('admin/coupon');
        }
    }

    public function activateAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Coupon();
        $model->find($id);
        if (null != $model) {
            $options = array('code' => $model->getCode(), 'status' => 'active');
            $model->setOptions($options);
            if ($model->save()) {
                $this->_flashMessenger->addMessage('<div class="mess-true">Cuponul a fost activat!</div>');
            } else {
                $this->_flashMessenger->addMessage('<div class="mess-false">Eroare activare cupon!</div>');
            }
        } else {
            $this->_flashMessenger->addMessage('<div class="mess-true">Cuponul selectat nu a fost gasit!</div>');
        }
        $this->_redirect('/admin/coupon');
    }

    public function dezactivateAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Coupon();
        $model->find($id);
        if (null != $model) {
            $options = array('code' => $model->getCode(), 'status' => 'inactive');
            $model->setOptions($options);
            if ($model->save()) {
                $this->_flashMessenger->addMessage('<div class="mess-true">Cuponul a fost dezactivat!</div>');
            } else {
                $this->_flashMessenger->addMessage('<div class="mess-false">Eroare dezactivare cupon!</div>');
            }
        } else {
            $this->_flashMessenger->addMessage('<div class="mess-true">Cuponul selectat nu a fost gasit!</div>');
        }
        $this->_redirect('/admin/coupon');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Coupon();
        $model->find($id);
        if (null != $model) {
            if ($model->delete()) {
                $this->_flashMessenger->addMessage('<div class="mess-true">Cuponul a fost sters!</div>');
            } else {
                $this->_flashMessenger->addMessage('<div class="mess-false">Eroare stergere cupon!</div>');
            }
        } else {
            $this->_flashMessenger->addMessage('<div class="mess-true">Cuponul selectat nu a fost gasit!</div>');
        }
        $this->_redirect('/admin/coupon');
    }
}