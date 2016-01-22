<?php

class Admin_Form_Marketing extends Zend_Form
{
    function init()
    {

    }

    public function newsletter()
    {
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmSendNewsletter'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel(Zend_Registry::get('translate')->_('marketing_newsletter_table_title'));
        $title->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $title->setRequired(true);
        $this->addElement($title);

        $message = new Zend_Form_Element_Textarea('message');
        $message->setLabel(Zend_Registry::get('translate')->_('marketing_newsletter_table_message'));
        $message->setAttribs(array('style' => 'width:250px; height:100px;'));
        $message->setRequired(false);
        $this->addElement($message);

        $status = new Zend_Form_Element_Checkbox('status');
        $status->setLabel(Zend_Registry::get('translate')->_('marketing_newsletter_table_to_email'));
        $status->setRequired(false);
        $this->addElement($status);

        $mail = $this->getView()->company[0]->getEmail();
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('');
        $email->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,maxSize[120]]', 'style' => 'width:250px;'));
        $email->setValue($mail);
        $email->setRequired(true);
        $this->addElement($email);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('marketing_newsletter_table_send'));
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setIgnore(true);
        $this->addElement($submit);
    }


}