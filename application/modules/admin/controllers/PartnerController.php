<?php
class Admin_PartnerController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
		$model = new Default_Model_Partner();
		$select = $model->getMapper()->getDbTable()->select()
				->order('created DESC');
		$partners = $model->fetchAll($select);
		if(null != $partners){
			$paginator = Zend_Paginator::factory($partners);
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

	public function addAction()
	{
		$form = new Admin_Form_Partner();
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/partner/PartnerAdd.phtml'))));
		if($this->getRequest()->isPost()) {
			if($form->isValid($this->getRequest()->getPost())) {
				$post = $this->getRequest()->getPost();
				$model = new Default_Model_Partner();
				$model->setOptions($post);
            	if($model->save()) {
	           		$this->_flashMessenger->addMessage('<div class="mess-true">Partenerul a fost adaugat cu succes!</div>');
            	} else {
	           		$this->_flashMessenger->addMessage('<div class="mess-false">Eroare la adaugarea partenerul!</div>');
            	}
			}
	    	$this->_redirect('admin/partner');
		}
		$this->view->form = $form;
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$model = new Default_Model_Partner();
		$model->find($id);
		if(null != $model){
			$form = new Admin_Form_Partner();
			$form->edit($model);
			$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/partner/PartnerEdit.phtml'))));
			$this->view->form = $form;
			if($this->getRequest()->isPost()) {
			    if($form->isValid($this->getRequest()->getPost())) {
				$post = $this->getRequest()->getPost();
				$model->setOptions($post);					
				
				if($model->save()) {
					    $this->_flashMessenger->addMessage('<div class="mess-true">Partenerul a fost modificat cu succes!</div>');
				} else {
					    $this->_flashMessenger->addMessage('<div class="mess-false">Eroare la modificarea partenerului!</div>');
				}
				$this->_redirect('admin/partner');
			    }
		      }	
		}else{
			$this->_flashMessenger->addMessage('<div class="mess-false">Partenerul selectat nu a fost gasit!</div>');
			$this->_redirect('admin/partner');
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$model = new Default_Model_Partner();
		$model->find($id);
		if(null != $model){
			if($model->delete()){
				$this->_flashMessenger->addMessage('<div class="mess-true">Partenerul a fost sters cu succes!</div>');
			}else{
				$this->_flashMessenger->addMessage('<div class="mess-true">Eroare stergere partener!</div>');
			}
		}else{
			$this->_flashMessenger->addMessage('<div class="mess-true">Partenerul selectat nu a fost gasit!</div>');
		}
		$this->_redirect('admin/partner');
	}
}