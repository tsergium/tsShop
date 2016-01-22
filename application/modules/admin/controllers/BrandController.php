<?php

class Admin_BrandController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $model = new Default_Model_Brand();
        $select = $model->getMapper()->getDbTable()->select();
        $result = $model->fetchAll($select);
        if ($result) {
            $this->view->brands = $result;
        }
    }
}