<?php

class Admin_Form_SkuImport extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmSearchBySku'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $file = new Zend_Form_Element_File('file');
        $file->setLabel(Zend_Registry::get('translate')->_('products_import_sku_table_file'));
        $file->setAttribs(array());
        $file->setRequired(true);
        $file->setIgnore(false);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('customers_import_sku_table_upload'));
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setIgnore(true);

        $this->addElements(array($file, $submit));
    }
}
