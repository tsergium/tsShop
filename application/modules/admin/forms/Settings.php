<?php
class Admin_Form_Settings extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
	}

	public function rambus()
	{
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmRamburs'));

		$rambus = new Zend_Form_Element_Hidden('rambus');
		$rambus->setValue('rambus');
		$this->addElement($rambus);

		$price = new Zend_Form_Element_Text('price');
		$price->setLabel(Zend_Registry::get('translate')->_('settings_payments_table_price'));
		$price->setAttribs(array('style'=>'width:60px;'));
		$price->setRequired(true);
		$this->addElement($price);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setValue(Zend_Registry::get('translate')->_('settings_payments_table_update'));
		$submit->setAttribs(array('class'=>'button1'));
		$submit->setIgnore(true);
		$this->addElement($submit);
	}

	public function rambusPercentage()
	{
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmRambusPercentage'));

		$rambus = new Zend_Form_Element_Hidden('rambus');
		$rambus->setValue('rambusPercentage');
		$this->addElement($rambus);

		$price = new Zend_Form_Element_Text('price');
		$price->setLabel(Zend_Registry::get('translate')->_('settings_payments_table_price'));
		$price->setAttribs(array('style'=>'width:60px;'));
		$price->setRequired(true);
		$this->addElement($price);
		
		$percent = new Zend_Form_Element_Select('percent');
		$percent->setLabel(Zend_Registry::get('translate')->_('settings_payments_table_percentage'));
		$percent->setRegisterInArrayValidator(false);
		$percentOptions = array();
		for($i=0; $i<=100; $i++){
			$percentOptions[$i] = $i.'%';
		}
		$percent->addMultiOptions($percentOptions);
		$percent->setAttribs(array('class'=>'select', 'style'=>'width:50px;'));
		$percent->setRequired(true);
		$this->addElement($percent);
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setValue(Zend_Registry::get('translate')->_('settings_payments_table_update'));
		$submit->setAttribs(array('class'=>'button1'));
		$submit->setIgnore(true);
		$this->addElement($submit);
	}

	

	
	function vatEdit(Default_Model_ProductsTaxes $value)
	{
//	   $this->name->setValue($value->getName());
	   $this->name->setValue(Zend_Registry::get('translate')->_('settings_add_tax_table_'.strtolower(str_replace(" ", "_", $value->getName()))));
	   $this->value->setValue($value->getValue());
	   $this->status->setValue($value->getStatus());
	   $this->submit->setValue(Zend_Registry::get('translate')->_('products_add_table_button_modify'));
	   $this->removeElement('type');	 
	}
}
