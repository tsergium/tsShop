<?php

class Default_Form_Customeropinion extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmCustomeropinion'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel(Zend_Registry::get('translate')->_('customer_opinion_name'));
        $name->setRequired(true);
        $name->addFilter('StringTrim');
        $name->setAttribs(array('maxlength' => '45', 'class' => 'validate[required]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $name->addValidator(new Zend_Validate_Alpha(true));
        $name->addValidator(new Zend_Validate_StringLength(3, 45));

        $city = new Zend_Form_Element_Text('city');
        $city->setLabel(Zend_Registry::get('translate')->_('customer_opinion_city'));
        $city->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'autocomplete' => 'off'));
        $city->setRequired(true);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel(Zend_Registry::get('translate')->_('customer_opinion_email'));
        $email->setRequired(true);
        $email->addFilter('StringTrim');
        $validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
        try {
            $validateEmailAddress->setValidateMx(true);
        } catch (Exception $e) {
        }
        $email->setAttribs(array('maxlength' => '100', 'class' => 'validate[required,custom[email]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $email->addValidators(array($validateEmailAddress));

        $opinion = new Zend_Form_Element_Textarea('opinion');
        $opinion->setLabel(Zend_Registry::get('translate')->_('customer_opinion_opinion'));
        $opinion->setAttribs(array('maxlength' => '500', 'class' => 'validate[required,minSize[1],maxSize[500]', 'style' => 'height:180px;'));
        $opinion->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('customer_opinion_submit'));
        $submit->setAttribs(array());
        $submit->setIgnore(true);

        $this->addElements(array($name, $city, $email, $opinion, $submit));
    }
}
