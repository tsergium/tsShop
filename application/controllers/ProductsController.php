<?php

class ProductsController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function detailsAction()
    {
        $id = $this->getRequest()->getParam('id'); // CATCH ID
        $msg = $this->getRequest()->getParam('msg');

        // BEGIN: FETCH PRODUCT MODEL
        $model = new Default_Model_Product();
        if ($model->find($id)) {
            if (null != $id) { // SeoSerp
                $this->view->seoId = $id;
            }
            $this->view->result = $model;

            // BEGIN: FETCH PRODUCT INSTRUCTIONS
            $model = new Default_Model_ProductInstructions;
            $select = $model->getMapper()->getDbTable()->select()
                ->from(array('i' => 'ts_product_instructions'))
                ->join(array('as' => 'ts_product_instructions_assoc'), 'i.id = as.instructionId', array())
                ->where('as.productId = ?', $id)
                ->order('i.id ASC')
                ->setIntegrityCheck(false);
            $asocProductsInst = $model->fetchAll($select);
            $this->view->instructions = $asocProductsInst;
            // END: FETCH PRODUCT INSTRUCTIONS

            // BEGIN: 3 RECOMENDED PRODUCTS
            $product = new Default_Model_Product();
            $select = $product->getMapper()->getDbTable()->select()
                ->from(array('p' => 'ts_products'))
                ->join(array('pca' => 'ts_products_categ_asoc'), 'p.id = pca.productId', array('pcaId' => 'pca.id'))
                ->where('p.status = ?', '1')
                ->where('p.id != ?', $id)
                ->group('p.id')
                ->order('RAND()')
                ->limit(3)
                ->setIntegrityCheck(false);
            $recomended = $product->fetchAll($select);
            if (null != $recomended) {
                $this->view->recomended = $recomended;
            }
            // END: 3 RECOMENDED PRODUCTS
        } else {
            $this->_flashMessenger->addMessage('<div class="message error"><p><strong>Eroare!</strong> Produsul selectat nu a fost gasit...</p></div>');
            if (null == $msg) {
                $this->_redirect('/products/details/id/' . $id . "/msg/invalid");
            }
        }
        // END: FETCH PRODUCT MODEL
    }

    public function categoriesAction()
    {
        $id = $this->getRequest()->getParam('id');
        if (null != $id) { // SeoSerp
            $this->view->seoId = $id;
        }
        $model = new Default_Model_Category();
        if ($model->find($id)) {
            $this->view->categName = $model->getName();

            $productAsoc = array(); // construct asociation array
            $categoryArray[0] = $id;
            // BEGIN: FETCH CHILD CATEGORIES
            $model = new Default_Model_Category();
            $select = $model->getMapper()->getDbTable()->select()
                ->where('parentId = ?', $id);
            $result = $model->fetchAll($select);
            if (null != $result) {
                foreach ($result as $value) {
                    $categoryArray[] = $value->getId();
                }
            }
            // END: FETCH CHILD CATEGORIES

            // BEGIN: FETCH ALL PRODUCTS IN THIS CATEGORY
            $asoc = new Default_Model_Productcategasoc();
            $select = $asoc->getMapper()->getDbTable()->select()
                ->where('categoryId IN (?)', $categoryArray);
            $result = $asoc->fetchAll($select);
            // BEGIN: FETCH ALL PRODUCTS IN THIS CATEGORY
            if (null != $result) {
                foreach ($result as $value) {
                    $productAsoc[] = $value->getProductId();
                }
            }
            array_unique($productAsoc);

            if (!empty($productAsoc)) {
                $product = new Default_Model_Product();
                $select = $product->getMapper()->getDbTable()->select()
                    ->where('id IN (?)', $productAsoc)
                    ->where('status = ?', '1')
                    ->order('created DESC');
                $result = $product->fetchAll($select);
                if (null != $result) {
                    $paginator = Zend_Paginator::factory($result);
                    $paginator->setItemCountPerPage(12);
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
        } else {
            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Categoria selectata nu a fost gasita!</p></div>");
        }
    }

    public function hainefemeiAction()
    {
        $product = new Default_Model_Product();
        $select = $product->getMapper()->getDbTable()->select()
            ->from(array('p' => 'ts_products'))
            ->join(array('pca' => 'ts_products_categ_asoc'), 'p.id = pca.productId', array('pcaId' => 'pca.id'))
//		    	->join(array('ppa'=>'ts_products_promotion_asoc'), 'p.id = ppa.productId' ,array('ppaId'=>'ppa.id'))
            ->where('p.status = ?', '1')
            ->where('pca.categoryId = ?', '329')
//				->where('ppa.promotionId = ?', '3')
            ->group('p.id')
            ->order('p.created DESC')
            ->setIntegrityCheck(false);
//		echo $select;
        $result = $product->fetchAll($select);
        if (null != $result) {
            $paginator = Zend_Paginator::factory($result);
            $paginator->setItemCountPerPage(12);
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

    public function reduceriAction()
    {
        $product = new Default_Model_Product();
        $select = $product->getMapper()->getDbTable()->select()
            ->from(array('p' => 'ts_products'))
            ->join(array('pca' => 'ts_products_categ_asoc'), 'p.id = pca.productId', array('pcaId' => 'pca.id'))
            ->join(array('ppa' => 'ts_products_promotion_asoc'), 'p.id = ppa.productId', array('ppaId' => 'ppa.id'))
            ->where('p.status = ?', '1')
            ->where('ppa.promotionId = ?', '2')
            ->group('p.id')
            ->order('p.created DESC')
            ->setIntegrityCheck(false);
        $result = $product->fetchAll($select);
        if (null != $result) {
            $paginator = Zend_Paginator::factory($result);
            $paginator->setItemCountPerPage(12);
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

    public function promotiiAction()
    {
        $product = new Default_Model_Product();
        $select = $product->getMapper()->getDbTable()->select()
            ->from(array('p' => 'ts_products'))
            ->join(array('pca' => 'ts_products_categ_asoc'), 'p.id = pca.productId', array('pcaId' => 'pca.id'))
            ->join(array('ppa' => 'ts_products_promotion_asoc'), 'p.id = ppa.productId', array('ppaId' => 'ppa.id'))
            ->where('p.status = ?', '1')
            ->where('ppa.promotionId = ?', '1')
            ->group('p.id')
            ->order('p.created DESC')
            ->setIntegrityCheck(false);
        $result = $product->fetchAll($select);
        if (null != $result) {
            $paginator = Zend_Paginator::factory($result);
            $paginator->setItemCountPerPage(12);
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

    public function searchAction()
    {

        $prodname = '';
        if ($this->getRequest()->getParam('term') != null) {
            $prodname = $this->getRequest()->getParam('term');
        }
        $this->view->search = $prodname;
        $prodarray = explode(' ', $prodname);

        $productsId = array();

        if ($prodarray) {
            foreach ($prodarray as $name) {
                $model = new Default_Model_Product();
                $select = $model->getMapper()->getDbTable()->select()
                    ->from(array('p' => 'ts_products'))
                    ->join(array('pca' => 'ts_products_categ_asoc'), 'p.id = pca.productId', array('pcaId' => 'pca.id'))
                    ->where("p.name LIKE '%" . $name . "%' OR p.description LIKE '%" . $name . "%'")
                    ->where('p.status = ?', '1')
                    ->group('p.id')
                    ->setIntegrityCheck(false);

                if (($productsx = $model->fetchAll($select))) {
                    foreach ($productsx as $value) {
                        $productsId[] = $value->getId();
                    }
                }
            }
        }

        if ($productsId) {
            $model = new Default_Model_Product();
            $select = $model->getMapper()->getDbTable()->select()
                ->where('id IN (?)', $productsId)
                ->where('status = ?', '1')
                ->order('name ASC');
            $select->setIntegrityCheck(FALSE);
            if (($productsx = $model->fetchAll($select))) {
                $paginator = Zend_Paginator::factory($productsx);
                $paginator->setItemCountPerPage(12);
                $paginator->setCurrentPageNumber($this->_getParam('page'));
                $paginator->setPageRange(5);
                $this->view->result = $paginator;
                $this->view->itemCountPerPage = $paginator->getItemCountPerPage();
                $this->view->totalItemCount = $paginator->getTotalItemCount();

                $param = array('term' => $prodname);

                Zend_Paginator::setDefaultScrollingStyle('Sliding');
                Zend_View_Helper_PaginationControl::setDefaultViewPartial(array('_pagination.phtml', $param));
            }
        }
    }
}