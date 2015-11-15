<?php
class Admin_Form_OrderSearch extends Zend_Form
{
	function init()
	{
	// Set the method for the display form to POST
			$this->setMethod('post');
			$this->addAttribs(array('id'=>'frmSearchCustomer'));

			$txtHeaderSearch = new Zend_Form_Element_Text('txtHeaderSearch');
			$txtHeaderSearch->setLabel('Cautare Comenzi');
			$txtHeaderSearch->setAttribs(array(
				'class'=>'validate[required,minSize[1],maxSize[120]]',
				'style'=>'width:200px'
				));
			$txtHeaderSearch->setRequired(true);
			$this->addElement($txtHeaderSearch);

			$submit = new Zend_Form_Element_Submit('submit');
			$submit->setValue(Zend_Registry::get('translate')->_('customers_search_table_button_view'));
			$submit->setAttribs(array('class'=>'button1'));
			$submit->setIgnore(true);
			$this->addElement($submit);
	}
}
