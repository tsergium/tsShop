<?php
class Default_Form_Test extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmCustomeropinion'));
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

		$name = new Zend_Form_Element_Text('name');
        $name->setLabel(Zend_Registry::get('translate')->_('customer_opinion_name'));
        $name->setRequired(true);
        $name->addFilter('StringTrim');
        $name->setAttribs(array('maxlength'=>'45', 'class'=>'validate[required]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $name->addValidator(new Zend_Validate_Alpha(true));
        $name->addValidator(new Zend_Validate_StringLength(3,45));

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setValue(Zend_Registry::get('translate')->_('customer_opinion_submit'));
		$submit->setAttribs(array());
		$submit->setIgnore(true);

		$this->addElements(array($name, $submit));
	}
}
