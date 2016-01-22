<?php

class Admin_Form_Voucher extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmVouchers'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $isProcentual = new Zend_Form_Element_Checkbox('isProcentual');
        $isProcentual->setLabel('Este procentual?');
        $isProcentual->setCheckedValue('1');
        $isProcentual->setUncheckedValue('0');
        $this->addElement($isProcentual);

        $value = new Zend_Form_Element_Text('value');
        $value->setLabel('Valoare');
        $value->setAttribs(array('maxlength' => 'value', 'class' => 'validate[required,minSize[2],maxSize[6]]', 'style' => 'width:250px;'));
        $value->setRequired(true);
        $this->addElement($value);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Adauga');
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setIgnore(true);
        $this->addElement($submit);

    }

    public function edit(Default_Model_Voucher $model)
    {
        $this->isProcentual->setValue($model->getIsProcentual());
        $this->value->setValue($model->getValue());
        $this->submit->setValue('Modifica');
    }

}