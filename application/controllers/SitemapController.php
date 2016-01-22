<?php

class SitemapController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $cms = new Default_Model_Cms();
        $select = $cms->getMapper()->getDbTable()->select()
            ->where('status = ?', '1');
        $cmsArray = $cms->fetchAll($select);
        if (null != $cmsArray) {
            $this->view->cmsArray = $cmsArray;
        }

        $product = new Default_Model_Products();
        $select = $product->getMapper()->getDbTable()->select()
            ->where('status = ? ', '1');
        $productArray = $product->fetchAll($select);
        if (null != $productArray) {
            $this->view->productArray = $productArray;
        }
    }
}