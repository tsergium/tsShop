<?php

class Admin_Form_ProductGallery extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmPartner'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Imagine');
        $image->setAttribs(array('maxlength' => '120', 'style' => 'width:250px;'));
        $image->setRequired(true);
        $image->setIgnore(false);
        $this->addElement($image);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Adauga');
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setRequired(false);
        $this->addElement($submit);
    }
}
