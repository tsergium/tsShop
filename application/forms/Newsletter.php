<?php
class Default_Form_Newsletter extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->setAction('/index/newsletter');
		$this->addAttribs(array('id'=>'frmNewsletter'));

		$email = new Zend_Form_Element_Text('emailNewsletter');
		$email->setRequired(false);
		$email->addFilter('StringTrim');
		$validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
		try {
			$validateEmailAddress->setValidateMx(true);
		} catch (Exception $e) {}
		$emailValidateDbNotExists = new Zend_Validate_Db_NoRecordExists(array('table'=>'ts_newsletter_subscribers', 'field'=>'email'));
		$email->addValidators(array($emailValidateDbNotExists, $validateEmailAddress));
		$email->setAttribs(array('placeholder'=>'Email','maxlength'=>'100', 'class'=>'validate[custom[email] inputField', ));


		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Send');
		$submit->setIgnore(true);
		$submit->setAttribs(array('class'=>'submitBtn'));

		$this->addElements(array($email, $submit));
	}
}
