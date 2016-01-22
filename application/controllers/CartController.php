<?php

class CartController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();

        $bootstrap = $this->getInvokeArg('bootstrap');
        if ($bootstrap->hasResource('db')) {
            $this->db = $bootstrap->getResource('db');
        }
    }

    public function indexAction()
    {
        $form = new Default_Form_Order();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/Order.phtml'))));
        $this->view->formOrder = $form;

        // BEGIN: FETCH PAYMENT METHODS
        $model = new Default_Model_DeliveryPayment();
        $select = $model->getMapper()->getDbTable()->select()
            ->order('name DESC');
        $result = $model->fetchAll($select);
        if (null != $result) {
            $this->view->paymentMethods = $result;
        }
        // END: FETCH PAYMENT METHODS

        // BEGIN: FETCH CURIERES
        $model = new Default_Model_DeliveryCourier();
        $select = $model->getMapper()->getDbTable()->select()
            ->order('created DESC');
        $result = $model->fetchAll($select);
        if (null != $result) {
            $this->view->couriers = $result;
        }
        // END: FETCH CURIERES

        $cookie = $_SESSION['cartId'];
        checkDeletedProductsInCart($cookie);
        $var = new Default_Model_Cart();
        $select = $var->getMapper()->getDbTable()->select()
            ->where('cookie = ?', $cookie);
        if (($result = $var->fetchAll($select))) {
            $this->view->cart = $result;
        }
    }

    public function addproductAction()
    {
        $cookie = $_SESSION['cartId'];
        $productId = $this->getRequest()->getParam('id');
        $sizeId = $this->getRequest()->getParam('prodSize');
        $colorId = $this->getRequest()->getParam('prodColor');
        if ($this->getRequest()->getParam('quantity')) {
            $quantity = $this->getRequest()->getParam('quantity');
        } else {
            $quantity = '1';
        }

        $product = new Default_Model_Product();
        if ($product->find($productId)) {
            if ($product->getStatus() == '1') {
//			CHECK THE STOCK

                $stock = true;
                if ($product->getStockNelimitat() != '1') {
                    $cart1 = new Default_Model_Cart();
                    $select = $cart1->getMapper()->getDbTable()->select()
                        ->from(array('fp_cart'), array('quantity' => 'SUM(quantity)'))
                        ->where('productId = ?', $productId)
                        ->where('cookie = ?', $cookie)
                        ->group('productId');

                    if (($result = $cart1->fetchAll($select))) {
                        $newstock = $result[0]->getQuantity() + $quantity;
                        $requiedStock = ($product->getStock() - (int)$newstock);
                        if ($requiedStock < 0) {
                            $stock = false;
                        }
                    } else {
                        $requiedStock = ($product->getStock() - (int)$quantity);
                        if ($requiedStock < 0) {
                            $stock = false;
                        }
                    }
                }

                if ($stock == true) {
                    $cart = new Default_Model_Cart();
                    $select = $cart->getMapper()->getDbTable()->select()
                        ->where('productId = ?', $productId)
                        ->where('sizeId = ?', $sizeId)
                        ->where('colorId = ?', $colorId)
                        ->where('cookie = ?', $cookie);
                    if (($result = $cart->fetchAll($select))) {
                        $result[0]->setQuantity($result[0]->getQuantity() + $quantity);
                        $cartId = $result[0]->save();
                        $response['save'] = 'true';
                    } else {
                        $cart->setProductId($productId);
                        $cart->setSizeId($sizeId);
                        $cart->setColorId($colorId);
                        $cart->setQuantity($quantity);
                        $cart->setCookie($cookie);
                        $cartId = $cart->save();
                        $response['save'] = 'true';
                    }
                } else {
                    $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Cantitate insuficienta.</p></div>");
                }
            }
        }
        $this->_redirect('/cart');
    }

    public function deleteproductAction()
    {
        $cartId = $this->getRequest()->getParam('id');
        $cart = new Default_Model_Cart();
        $cart->find($cartId);
        if (null != $cart) {
            if ($cart->delete()) {
                $_SESSION['cart'] = '';
            } else {
                $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare stergere produs din cos!</p></div>");
            }
        }
        $this->_redirect('/cart');
    }

    public function onemoreAction()
    {
        $cartId = $this->getRequest()->getParam('id');
        $cart = new Default_Model_Cart();
        $cart->find($cartId);
        if (null != $cart) {
            $stock = true;
            $quantity = 1;
            $product = new Default_Model_Product();
            if ($product->find($cart->getProductId())) {
                if ($product->getStockNelimitat() != '1') {
                    $cookie = $_SESSION['cartId'];
                    $cart1 = new Default_Model_Cart();
                    $select = $cart1->getMapper()->getDbTable()->select()
                        ->from(array('fp_cart'), array('quantity' => 'SUM(quantity)'))
                        ->where('productId = ?', $cart->getProductId())
                        ->where('cookie = ?', $cookie)
                        ->group('productId');

                    if (($result = $cart1->fetchAll($select))) {
                        $newstock = $result[0]->getQuantity() + $quantity;
                        $requiedStock = ($product->getStock() - (int)$newstock);
                        if ($requiedStock < 0) {
                            $stock = false;
                        }
                    } else {
                        $requiedStock = ($product->getStock() - (int)$quantity);
                        if ($requiedStock < 0) {
                            $stock = false;
                        }
                    }
                }

                if ($stock == true) {
                    $cart->setQuantity($cart->getQuantity() + 1);
                    $cart->save();
                } else {
                    $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong> Cantitate insuficienta.</p></div>");
                }
            }

        }
        $this->_redirect('/cart');
    }

    public function onelessAction()
    {
        $cartId = $this->getRequest()->getParam('id');
        $cart = new Default_Model_Cart();
        $cart->find($cartId);
        if (null != $cart) {
            if ($cart->getQuantity() == 1) {
                $cart->delete();
            } else {
                $cart->setQuantity($cart->getQuantity() - 1);
                $cart->save();
            }
        }
        $this->_redirect('/cart');
    }
}