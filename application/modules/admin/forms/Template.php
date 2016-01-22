<?php

class Admin_Form_Template extends Zend_Form
{
    function init()
    {
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmEmail'));

        $subject = new Zend_Form_Element_Text('subject');
        $subject->setLabel('Subiect');
        $subject->setAttribs(array('class' => 'input'));
        $subject->setRequired(false);
        $this->addElement($subject);

        $value = new Zend_Form_Element_Textarea('value');
        $value->setLabel('Mesaj');
        $value->setAttribs(array('class' => 'input', 'rows' => '30', 'cols' => '100'));
        $value->setRequired(false);
        $this->addElement($value);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Salveaza');
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setIgnore(true);
        $this->addElement($submit);
    }

}