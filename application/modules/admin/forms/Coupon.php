<?php
class Admin_Form_Coupon extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id'=>'frmCoupons'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $name = new Zend_Form_Element_Text('code');
        $name->setLabel('Cod');
        $name->setAttribs(array('maxlength'=>'code', 'class'=>'validate[required,minSize[5],maxSize[10]]', 'style'=>'width:250px;'));
        $name->setRequired(true);
        $this->addElement($name);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Adauga');
        $submit->setAttribs(array('class'=>'button1'));
        $submit->setIgnore(true);
        $this->addElement($submit);

    }

    public function edit(Default_Model_Coupon $model)
    {
        $this->code->setValue($model->getCode());
        $this->submit->setValue('Modifica');
    }

}