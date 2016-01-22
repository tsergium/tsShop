<?php

class Admin_Form_ProductIndexSearch extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmSearchProd'));
//		$this->setAction('/admin/products/search');

        $txtHeaderSearch = new Zend_Form_Element_Text('txtHeaderSearch');
        $txtHeaderSearch->setLabel(Zend_Registry::get('translate')->_('products_all_search_product'));
        $txtHeaderSearch->setAttribs(array('class' => 'validate[required]', 'style' => 'width:200px'));
        $txtHeaderSearch->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('products_all_button_search_product'));
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setIgnore(true);

        $this->addElements(array($txtHeaderSearch, $submit));
    }
}
