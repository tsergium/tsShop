<?php
class Default_Form_Remind extends Zend_Form 
{
	function init()
	{
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmRemind'));
	
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel(Zend_Registry::get('translate')->_('user_email'));
		//$validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
		//try { $validateEmailAddress->setValidateMx(true); } catch (Exception $e) {}
		//$email->addValidator($validateEmailAddress);
		$email->setAttribs(array('autocomplete'=>'off', 'maxlength'=>'100', 'class'=>'validate[required,custom[email]] inputField'));

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel(Zend_Registry::get('translate')->_('user_send_email'));
		$submit->setAttribs(array('class'=>'button1'));
		$submit->setIgnore(true);

		$this->addElements(array($email, $submit));
	}
}
