<?php
class Default_Form_ContactUs extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmContactUs'));

		$fullname = new Zend_Form_Element_Text('fullname');
		$fullname->setLabel('Nume');
		$fullname->setRequired(true);
		$fullname->addFilter('StringTrim');
		$fullname->addValidator(new Zend_Validate_Alpha(true));
		$fullname->addValidator(new Zend_Validate_StringLength(3,45));
		$fullname->setAttribs(array('maxlength'=>'150', 'class'=>'validate[required] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
		$this->addElement($fullname);


		$subject = new Zend_Form_Element_Text('subject');
		$subject->setLabel('Subiect');
		$subject->setRequired(true);
		$subject->addFilter('StringTrim');
		$subject->addValidator(new Zend_Validate_Alpha(true));
		$subject->addValidator(new Zend_Validate_StringLength(3,45));
		$subject->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
		$this->addElement($subject);

		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email');
		$email->setRequired(true);
		$email->addFilter('StringTrim');
		$validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
		try {$validateEmailAddress->setValidateMx(true);} catch (Exception $e) {}
		$email->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,custom[email] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
		$email->addValidators(array($validateEmailAddress));
		$this->addElement($email);

		$comments = new Zend_Form_Element_Textarea('comments');
		$comments->setLabel('Mesaj');
		$comments->setAttribs(array('class'=>'validate[required] txt'));
		$comments->setRequired(true);
		$this->addElement($comments);

		$submit = new Zend_Form_Element_Image('submit');
		$submit->setValue('/images/bt_trimitemesaj.gif');
		$submit->setIgnore(true);

		$this->addElement($submit);
	}
}
