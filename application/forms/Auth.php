<?php
class Default_Form_Auth extends Zend_Form 
{
	function init()
	{
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmAuthLogIn'));
		$this->setAction('/auth/index');

		$username = new Zend_Form_Element_Text('username');
		$username->setLabel('Your Username:');
		$username->setRequired(true);
		$username->setAttribs(array('class'=>'inputField validate[required]'));


		$password = new Zend_Form_Element_Password('passwordLogare');
		$password->setLabel('Password');
		$password->setRequired(true);
		$password->setAttribs(array('class'=>'inputField validate[required]'));

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setValue('Login');
		$submit->setIgnore(true);
		$submit->setAttribs(array('class'=>'sendBtn'));

		$facebooksession = new Zend_Form_Element_Hidden('facebooksession');

		$this->addElements(array($username, $password, $submit));
	}
}
