<?php
class Default_Form_Moreinfo extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmMoreInfo'));
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

		$id = new Zend_Form_Element_Hidden('id');

		$name = new Zend_Form_Element_Text('name');
        $name->setLabel(Zend_Registry::get('translate')->_('iframe_name'));
        $name->addFilter('StringTrim');
        $name->addValidator(new Zend_Validate_Alpha(true));
        $name->addValidator(new Zend_Validate_StringLength(3,45));
        $name->setAttribs(array('maxlength'=>'45', 'class'=>'validate[required]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
		$name->setRequired(true);
		$name->setIgnore(false);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel(Zend_Registry::get('translate')->_('user_email'));
        $email->addFilter('StringTrim');
        $validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
        try {$validateEmailAddress->setValidateMx(true); } catch(Exception $e) {}
        $email->setAttribs(array('maxlength'=>'100', 'class'=>'validate[required,custom[email]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $email->addValidators(array($validateEmailAddress));
		$email->setRequired(true);
		$email->setIgnore(false);

		$phone = new Zend_Form_Element_Text('phone');
		$phone->setLabel(Zend_Registry::get('translate')->_('user_phone'));
		$phone->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
		$phone->setRequired(true);
		$name->setIgnore(false);

		$comments = new Zend_Form_Element_Textarea('comments');
		$comments->setLabel(Zend_Registry::get('translate')->_('contact_us_table_comments'));
		$comments->setAttribs(array('class'=>'validate[required]'));
		$comments->setRequired(true);
		$comments->setIgnore(false);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setValue(Zend_Registry::get('translate')->_('iframe_button_send'));
		$submit->setAttribs(array('class'=>'blue-small mb20'));
		$submit->setIgnore(true);

		$this->addElements(array($id, $name, $email, $phone, $comments, $submit));
	}
}
