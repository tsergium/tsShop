<?php

class Admin_CategoryController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();

//	if(!function_exists('getSubcategoryCategory')){
//		    function getSubcategoryCategory(Default_Model_Category $subcategory)
//		    {
//			$category =  new Default_Model_Category();
//			if($category->find($subcategory->getParentId())){
//			    return $category->getName();
//			}else{
//			   return null; 
//			}	
//			
//		    }
//	    }
    }

    public function indexAction()
    {
        $model = new Default_Model_Category();
        $select = $model->getMapper()->getDbTable()->select()
            ->where('parentId IS NULL')
            ->orWhere('parentId = ?', '0')
            ->order('name ASC');
        $categories = $model->fetchAll($select);

        if (null != $categories) {
            $paginator = Zend_Paginator::factory($categories);
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
        $form = new Admin_Form_Category();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/category/CategoryAdd.phtml'))));
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $post = $this->getRequest()->getPost();
                $model = new Default_Model_Category();
                $model->setOptions($post);
                if ($model->save()) {
                    $this->_flashMessenger->addMessage('<div class="mess-true">Categoria a fost adaugata cu succes!</div>');
                } else {
                    $this->_flashMessenger->addMessage('<div class="mess-false">Eroare la adaugarea categoriei!</div>');
                }
            }
            $this->_redirect('admin/category');
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Category();
        $model->find($id);
        if (null != $model) {
            $form = new Admin_Form_Category();
            $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/category/CategoryEdit.phtml'))));
            $form->edit($model);
            $this->view->form = $form;
            $post = $this->getRequest()->getPost();
            if (null != $post) {
                if ($form->isValid($post)) {
                    $model->setOptions($post);
                    if ($model->save()) {
                        $this->_flashMessenger->addMessage('<div class="mess-true">Categoria a fost modificata cu succes!</div>');
                    } else {
                        $this->_flashMessenger->addMessage('<div class="mess-false">Eroare la modificarea categoriei!</div>');
                    }
                }
                $this->_redirect('admin/category');
            }
        } else {
            $this->_flashMessenger->addMessage('<div class="mess-false">Categoria selectata nu a fost gasita!</div>');
            $this->_redirect('admin/category');
        }
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = new Default_Model_Category();
        $model->find($id);
        if (null != $model) {
            if ($model->delete()) {
                //Set subactegs parentId to null
                $subcategs = getSubcatForCat($id);
                foreach ($subcategs as $value1) {
                    $value1->setParentId('');
                    $value1->save();
                }
                //Delete from product assoc
                $model = new Default_Model_Productcategasoc();
                $select = $model->getMapper()->getDbTable()->select()
                    ->where('categoryId = ?', $id);
                $result = $model->fetchAll($select);
                if (null != $result) {
                    foreach ($result as $value) {
                        $value->delete();
                    }
                }
                $this->_flashMessenger->addMessage('<div class="mess-true">Categoria a fost stearsa!</div>');
            } else {
                $this->_flashMessenger->addMessage('<div class="mess-false">Eroare stergere categorie!</div>');
            }
        } else {
            $this->_flashMessenger->addMessage('<div class="mess-true">Categoria selectata nu a fost gasita!</div>');
        }
        $this->_redirect('/admin/category');
    }
}