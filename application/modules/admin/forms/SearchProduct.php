<?php

class Admin_Form_SearchProduct extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmSearchProd'));

        $txtHeaderSearch = new Zend_Form_Element_Text('txtHeaderSearch');
        $txtHeaderSearch->setLabel(Zend_Registry::get('translate')->_('products_search_label_product_name'));
        $txtHeaderSearch->setAttribs(array('class' => 'validate[required]', 'style' => 'width:200px'));
        $txtHeaderSearch->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('products_search_button_view'));
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setIgnore(true);

        $this->addElements(array($txtHeaderSearch, $submit));
    }
}