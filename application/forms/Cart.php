<?php

class Default_Form_Cart extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmCart'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $cookie = new Zend_Form_Element_Text('cookie');
        $cookie->setLabel('Cookie');
        $cookie->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]'));
        $cookie->setRequired(true);

        $quantity = new Zend_Form_Element_Text('quantity');
        $quantity->setLabel(Zend_Registry::get('translate')->_('cart_form_quantity'));
        $quantity->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]'));
        $quantity->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('cart_button_add'));
        $submit->setAttribs(array());
        $submit->setIgnore(true);

        $this->addElements(array($cookie, $quantity, $submit));
    }

    public function editcart(Default_Model_Cart $cart)
    {
        // Set the method for the display form to POST
        $this->cookie->setValue($cart->getCookie());
        $this->quantity->setValue($cart->getQuantity());
        $this->submit->setValue(Zend_Registry::get('translate')->_('cart_button_edit'));
    }
}
