<?php
class Default_Form_Unsubscribe extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmUnsubscribe'));
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

		$unsubscribe = new Zend_Form_Element_Text('unsubscribe');
		$unsubscribe->setLabel(Zend_Registry::get('translate')->_("news_unsubscribe"));
		$unsubscribe->setAttribs(array());
		$unsubscribe->setRequired(true);
		$unsubscribe->setIgnore(true);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setValue(Zend_Registry::get('translate')->_("news_button_send"));
		$submit->setAttribs(array('class'=>'newslettenewsletter-fieldr-button'));
		$submit->setIgnore(true);

		$this->addElements(array($unsubscribe, $submit));
	}
}
