<?php
class Admin_Form_Searchbysku extends Zend_Form
{
	function init()
	{
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmSearchBySku'));

		$sku = new Zend_Form_Element_Text('sku');
        $sku->setLabel('SKU');
        $sku->setAttribs(array('class'=>'validate[required]', 'style'=>'width:80px'));
        $sku->setRequired(true);
		$this->addElement($sku);

		$submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('products_searchbysku_view'));
        $submit->setAttribs(array('class'=>'button1'));
        $submit->setIgnore(true);
		$this->addElement($submit);
	}

	function changeSkuQuantity($model)
	{
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmChangeSkuQuantity'));

		$productId = new Zend_Form_Element_Hidden('id');
		$productId->setValue($model->getId());
		$this->addElement($productId);
		
		$unlimitedStock = new Zend_Form_Element_Radio('unlimitedStock');
        $unlimitedStock->setLabel(Zend_Registry::get('translate')->_('products_add_table_unlimited_stock'));
        $option = array(Zend_Registry::get('translate')->_('products_add_table_no'), Zend_Registry::get('translate')->_('products_add_table_yes'));
        $unlimitedStock->addMultiOptions($option);
        $unlimitedStock->addValidator(new Zend_Validate_InArray(array_keys($option)));
		$unlimitedStock->setValue($model->getStockNelimitat());
        $unlimitedStock->setSeparator('');
        $unlimitedStock->setAttribs(array('class'=>'validate[required]'));
        $unlimitedStock->setRequired(true);
		$this->addElement($unlimitedStock);

		$stock = new Zend_Form_Element_Text('stock');
        $stock->setLabel('stock');
        $stock->setValue($model->getStock());
        $stock->setAttribs(array('maxlength'=>'10', 'class'=>'validate[required]', 'style'=>'width:80px'));
        $stock->setRequired(true);
		$this->addElement($stock);

		$submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Submit');
        $submit->setAttribs(array('class'=>'button1'));
        $submit->setIgnore(true);
		$this->addElement($submit);
	}
}
