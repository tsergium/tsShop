<?php

class Default_Form_User extends Zend_Form
{
    function init()
    {
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmUser'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
    }

    function orderStatus()
    {
        $status = new Zend_Form_Element_Select('status');
        $status->setLabel(Zend_Registry::get('translate')->_('user_salutation'));
        $status->setRegisterInArrayValidator(false);
        $option = array(null => 'Selectati', 'pending' => 'in asteptare', 'accepted' => 'acceptate', 'completed' => 'complete', 'rejected' => 'respinse');
        $status->addMultiOptions($option);
        $status->setAttribs(array('class' => 'select validate[required]'));
        $status->setRequired(true);
        $this->addElement($status);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Afisaza');
        $submit->setAttribs(array());
        $submit->setIgnore(true);
        $this->addElement($submit);
    }
}