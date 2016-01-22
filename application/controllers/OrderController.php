<?php

class OrderController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
        // BEGIN: FETCH DATA FROM CART FORM
        $courierTax = 0;
        $payment = $this->getRequest()->getParam('payment');
        $courier = $this->getRequest()->getParam('courier');
        $coupon = $this->getRequest()->getParam('coupon');
        $this->view->coupon = $coupon;
        if (null == $payment || null == $courier) {
            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Selectati metoda de plata si livrare!</p></div>");
            $this->_redirect('/cart'); // REDIRECT IF NOT AUTHENTICATED
        } else {
            $this->view->payment = $payment;
            $this->view->courier = $courier;
            $model = new Default_Model_Delivery();
            $select = $model->getMapper()->getDbTable()->select()
                ->where('paymentId = ?', $payment)
                ->where('courierId = ?', $courier);
            $result = $model->fetchAll($select);
            if (null != $result) {
                if (isset($result[0])) {
                    $this->view->modelTaxeLivrare = $result[0];
                    $this->view->taxeLivrare = $result[0]->getCost();
                } else {
                    $this->view->taxeLivrare = 0;
                    $this->view->modelTaxeLivrare = $result[0];
                }
                // BEGIN: SET DELIVERY COST
                $_SESSION['delivery_tax'] = $this->view->taxeLivrare;
                // END: SET DELIVERY COST
            }
        }
        // END: FETCH DATA FROM CART FORM

        // BEGIN: FETCH USER DETAILS
        $auth = Zend_Auth::getInstance();
        $authAccount = $auth->getStorage()->read();
        if (null != $authAccount) {
            if (null != $authAccount->getId()) {
                $user = new Default_Model_Clients();
                $user->find($authAccount->getId());
                $this->view->user = $user;
            }
        } else {
            //$this->_flashMessenger->addMessage('<div class="mess-false">Va rugam sa va autentificati!</div>');
            //$this->_redirect('/cart'); // REDIRECT IF NOT AUTHENTICATED
        }
        // END: FETCH USER DETAILS

        // BEGIN: FETCH PRODUCTS FROM CART
        $var = new Default_Model_Cart();
        $select = $var->getMapper()->getDbTable()->select()
            ->where('cookie = ?', $_SESSION['cartId']);
        if ($result = $var->fetchAll($select)) {
            $this->view->result = $result;
        }
        // END: FETCH PRODUCTS FROM CART

        //	BEGIN:SHIPPING DETAILS FORM
        $form = new Default_Form_ShippingDetails();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/ShippingDetails.phtml'))));
        $this->view->form = $form;
        //	END:SHIPPING DETAILS FORM
    }
}