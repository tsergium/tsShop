<?php
class Admin_VoucherController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $model = new Default_Model_Voucher();
        $select = $model->getMapper()->getDbTable()->select();
        $vouchers = $model->fetchAll($select);

        if(null != $vouchers){
            $paginator = Zend_Paginator::factory($vouchers);
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

    /* // Only one voucher one time
    public function addAction()
    {
        $form = new Admin_Form_Voucher();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/voucher/VoucherAdd.phtml'))));
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                $post = $this->getRequest()->getPost();
                $post['status'] = 'active';
                $model = new Default_Model_Voucher();
                $model->setOptions($post);
                if($model->save()) {
                       $this->_flashMessenger->addMessage('<div class="mess-true">Reducerea a fost adaugata cu succes!</div>');
                } else {
                       $this->_flashMessenger->addMessage('<div class="mess-false">Eroare la adaugarea reducerii!</div>');
                }
            }
            $this->_redirect('admin/voucher');
        }
        $this->view->form = $form;
    }
    */

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Voucher();
        $model->find($id);
        if(null != $model){
            $form = new Admin_Form_Voucher();
            $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/voucher/VoucherEdit.phtml'))));
            $form->edit($model);
            $this->view->form = $form;
            $post = $this->getRequest()->getPost();

            if(null != $post) {
                if($form->isValid($post)) {
                    $post['isProcentual'] = (isset($post['isProcentual']))?1:0;
                    $post['status'] = $model->getStatus();
                    $model->setOptions($post);
                    if($model->save()) {
                        $this->_flashMessenger->addMessage('<div class="mess-true">Reducerea a fost modificata cu succes!</div>');
                    } else {
                        $this->_flashMessenger->addMessage('<div class="mess-false">Eroare la modificarea reducerii!</div>');
                    }
                }
                $this->_redirect('admin/voucher');
            }
        }else{
            $this->_flashMessenger->addMessage('<div class="mess-false">Reducerea selectata nu a fost gasita!</div>');
            $this->_redirect('admin/voucher');
        }
    }

    public function activateAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Voucher();
        $model->find($id);
        if(null != $model){
            $options = array('isProcentual' => $model->getIsProcentual(), 'value' => $model->getValue(),'status' => 'active');
            $model->setOptions($options);
            if($model->save()){
                $this->_flashMessenger->addMessage('<div class="mess-true">Reducerea a fost activata!</div>');
            }else{
                $this->_flashMessenger->addMessage('<div class="mess-false">Eroare activare reducere!</div>');
            }
        }else{
            $this->_flashMessenger->addMessage('<div class="mess-true">Reducerea selectata nu a fost gasita!</div>');
        }
        $this->_redirect('/admin/voucher');
    }

    public function dezactivateAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Voucher();
        $model->find($id);
        if(null != $model){
            $options = array('isProcentual' => $model->getIsProcentual(), 'value' => $model->getValue(),'status' => 'inactive');
            $model->setOptions($options);
            if($model->save()){
                $this->_flashMessenger->addMessage('<div class="mess-true">Reducerea a fost dezactivata!</div>');
            }else{
                $this->_flashMessenger->addMessage('<div class="mess-false">Eroare dezactivare reducere!</div>');
            }
        }else{
            $this->_flashMessenger->addMessage('<div class="mess-true">Reducerea selectata nu a fost gasita!</div>');
        }
        $this->_redirect('/admin/voucher');
    }

    /* // Only one voucher one time
    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Voucher();
        $model->find($id);
        if(null != $model){
            if($model->delete()){
                $this->_flashMessenger->addMessage('<div class="mess-true">Reducerea a fost stearsa!</div>');
            }else{
                $this->_flashMessenger->addMessage('<div class="mess-false">Eroare stergere reducere!</div>');
            }
        }else{
            $this->_flashMessenger->addMessage('<div class="mess-true">Reducerea selectata nu a fost gasita!</div>');
        }
        $this->_redirect('/admin/voucher');
    }
    */
}