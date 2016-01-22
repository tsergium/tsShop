<?php

class Admin_Form_ProductAttributeSize extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmPartner'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nume');
        $name->setAttribs(array('maxlength' => '120', 'class' => 'validate[required]', 'style' => 'width:250px;'));
        $name->setRequired(true);
        $this->addElement($name);

        $order = new Zend_Form_Element_Text('order');
        $order->setLabel('Ordinea');
        $order->setAttribs(array('maxlength' => '120', 'class' => 'validate[required]', 'style' => 'width:250px;'));
        $order->setRequired(true);
        $this->addElement($order);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Adauga');
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setRequired(false);
        $this->addElement($submit);
    }

    public function edit(Default_Model_ProductAttributeValue $model)
    {
        $this->name->setValue($model->getName());
        $this->order->setValue($model->getOrder());
        $this->submit->setValue('Modifica');
    }
}
