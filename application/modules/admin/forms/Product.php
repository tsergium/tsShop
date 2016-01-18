<?php
class Admin_Form_Product extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id'=>'frmPartner'));
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

		$name = new Zend_Form_Element_Text('urlOrigin');
		$name->setLabel('urlOrigin');
		$name->setAttribs(['style'=>'width:250px;']);
		$name->setRequired(true);
		$this->addElement($name);

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nume');
        $name->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required]', 'style'=>'width:250px;'));
        $name->setRequired(true);
        $this->addElement($name);

        $oldprice = new Zend_Form_Element_Text('oldprice');
        $oldprice->setLabel('Pret Vechi');
        $oldprice->setAttribs(array('maxlength'=>'120', 'style'=>'width:250px;'));
        $oldprice->setRequired(false);
        $oldprice->setIgnore(true);
        $this->addElement($oldprice);

        $price = new Zend_Form_Element_Text('price');
        $price->setLabel('Pret');
        $price->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required]', 'style'=>'width:250px;'));
        $price->setRequired(true);
        $this->addElement($price);

		$category = new Zend_Form_Element_Select('category');
		$category->setLabel('Adauga in categoriile');
		$category->setRegisterInArrayValidator(false);
		$categoryOptions = array();
		$model = new Default_Model_Category();
		$select = $model->getMapper()->getDbTable()->select()
				->where('parentId IS NULL');
		$result = $model->fetchAll($select);
		if(null != $result) {
			foreach($result as $value) {
				$categoryOptions[$value->getId()] = $value->getName();
			}
			$category->addMultiOptions($categoryOptions);
			$category->setAttribs(array('multiple'=>'multiple', 'class'=>'select', 'style'=>'width:258px; height: 120px;'));
			$category->setRequired(false);
			$this->addElement($category);
		}
		
		$subcategory = new Zend_Form_Element_Multiselect('subcategory');
		$subcategory->setLabel('Adauga in subcategoriile');
		$subcategory->setRegisterInArrayValidator(false);
		$options = array();
		
		$model2 = new Default_Model_Category();
		$select2 = $model2->getMapper()->getDbTable()->select()
				->where('parentId IS NOT NULL');					
		$result2 = $model2->fetchAll($select2);
		if(null != $result2) {
			foreach($result2 as $value2) {
				$parentC = new Default_Model_Category();
				$parentC->find($value2->getParentId());
				
				$options[$parentC->getName()][$value2->getId()] = $value2->getName();
			}
		}
		
		
		$subcategory->setMultiOptions($options);
		$subcategory->setAttribs(array('multiple'=>'multiple', 'class'=>'select', 'style'=>'width:258px; height: 120px;'));
		$subcategory->setRequired(false);
		$this->addElement($subcategory);
		
		$size = new Zend_Form_Element_Select('size');
		$size->setLabel('Marime');
		$size->setRegisterInArrayValidator(false);
		$sizeOptions = array();
		$model = new Default_Model_ProductAttributeValue();
		$select = $model->getMapper()->getDbTable()->select()
				->where('groupId = ?', '1')
				->order('order ASC');
		$result = $model->fetchAll($select);
		if(null != $result) {
			foreach($result as $value) {
				$sizeOptions[$value->getId()] = $value->getName();
			}
			$size->addMultiOptions($sizeOptions);
			$size->setAttribs(array('multiple'=>'multiple', 'class'=>'select validate[required]', 'style'=>'width:258px; height: 120px;'));
			$size->setRequired(true);
			$this->addElement($size);
		}
		
		$color = new Zend_Form_Element_Select('color');
		$color->setLabel('Culoare');
		$color->setRegisterInArrayValidator(false);
		$colorOptions = array();
		$model = new Default_Model_ProductAttributeValue();
		$select = $model->getMapper()->getDbTable()->select()
				->where('groupId = ?', '2')
				->order('name ASC');
		$result = $model->fetchAll($select);
		if(null != $result) {
			foreach($result as $value) {
				$colorOptions[$value->getId()] = $value->getName();
			}			
			$color->addMultiOptions($colorOptions);
			$color->setAttribs(array('multiple'=>'multiple', 'class'=>'select validate[required]', 'style'=>'width:258px; height: 120px;'));
			$color->setRequired(true);
			$this->addElement($color);
		}
		
		$instruction = new Zend_Form_Element_Select('instruction');
		$instruction->setLabel('Instructiuni de folosire');
		$instruction->setRegisterInArrayValidator(false);
		$instructionOptions = array();
		$model = new Default_Model_ProductInstructions;
		$select = $model->getMapper()->getDbTable()->select()				
				->order('id ASC');
		$result = $model->fetchAll($select);
		if(null != $result) {
			foreach($result as $value) {
				$instructionOptions[$value->getId()] = $value->getName();
			}			
			$instruction->addMultiOptions($instructionOptions);
			$instruction->setAttribs(array('multiple'=>'multiple', 'class'=>'select', 'style'=>'width:258px; height: 120px;'));
			$instruction->setRequired(FALSE);			
		}
		$this->addElement($instruction);

		$composition = new Zend_Form_Element_Text('composition');
		$composition->setLabel('Compozitie');
		$composition->setAttribs(array( 'class'=>'', 'style'=>'width:250px;'));
		$composition->setRequired(FALSE);
		$this->addElement($composition);
		
		$image = new Zend_Form_Element_File('image');
		$image->setLabel('Imagine');
		$image->setAttribs(array('maxlength'=>'120', 'style'=>'width:250px;'));
		$image->setRequired(true);
		$image->setIgnore(false);
		$this->addElement($image);
		
		$imageShopmania = new Zend_Form_Element_File('imageShopmania');
		$imageShopmania->setLabel('Imagine Shopmania');
		$imageShopmania->setAttribs(array('maxlength'=>'120', 'style'=>'width:250px;'));
		$imageShopmania->setRequired(true);
		$imageShopmania->setIgnore(false);
		$this->addElement($imageShopmania);

		$description = new Zend_Form_Element_Textarea('description');
		$description->setLabel('Descriere');
		$description->setAttribs(array('style'=>'height: 150px;'));
		$description->setRequired(false);
		$this->addElement($description);
        
		$promotion = new Zend_Form_Element_Multiselect('promotionId');
		$promotion->setLabel('Promotie');
		$promotion->setRegisterInArrayValidator(false);
		$options = array('null'=>'Produs obisnuit');
		$model = new Default_Model_ProductPromotion();
		$select = $model->getMapper()->getDbTable()->select();
		$fetch = $model->fetchAll($select);
		if(null != $fetch){
			foreach($fetch as $value) {
				$options[$value->getId()] = ucfirst($value->getName());
			}
			$promotion->addMultiOptions($options);
			$promotion->setAttribs(array('class'=>'select', 'style'=>'width:258px;'));
			$promotion->setRequired(false);
			$this->addElement($promotion);
		}

		$unlimitedStock = new Zend_Form_Element_Radio('stockNelimitat');
        $unlimitedStock->setLabel('Stock nelimitat');
        $options = array('0'=>'nu', '1'=>'da');
        $unlimitedStock->addMultiOptions($options);
        $unlimitedStock->addValidator(new Zend_Validate_InArray(array_keys($options)));
		$unlimitedStock->setValue('no');
        $unlimitedStock->setSeparator('&nbsp;');
        $unlimitedStock->setAttribs(array('class'=>'validate[required]'));
		$unlimitedStock->setRequired(true);
		$this->addElement($unlimitedStock);

        $stock = new Zend_Form_Element_Text('stock');
        $stock->setLabel('Stoc');
        $stock->setAttribs(array('maxlength'=>'4', 'style'=>'width:50px;'));
        $stock->setRequired(false);
        $this->addElement($stock);
		
		$status = new Zend_Form_Element_Radio('status');
        $status->setLabel('Status');
        $options = array('inactiv', 'activ');
        $status->addMultiOptions($options);
        $status->addValidator(new Zend_Validate_InArray(array_keys($options)));
		$status->setValue('0');
        $status->setSeparator('&nbsp;');
        $status->setAttribs(array('class'=>'validate[required]'));
		$status->setRequired(true);
		$this->addElement($status);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Adauga');
        $submit->setAttribs(array('class'=>'button1'));
		$submit->setRequired(false);
		$this->addElement($submit);
	}

	public function edit(Default_Model_Product $model)
	{
		$this->urlOrigin->setValue($model->getUrlOrigin());
		$this->name->setValue($model->getName());
		$this->composition->setValue($model->getComposition());
		$this->oldprice->setValue($model->getOldprice());
		$this->price->setValue($model->getPrice());
		$this->image->setRequired(false);
		$this->imageShopmania->setRequired(false);
		$this->description->setValue($model->getDescription());
		$this->promotionId->setValue($model->getPromotionId());
		$this->stockNelimitat->setValue($model->getStockNelimitat());
		$this->stock->setValue($model->getStock());
		$this->status->setValue($model->getStatus());
		$this->submit->setValue('Modifica');
		
		$categoryArray = array();
		$asoc = new Default_Model_Productcategasoc();
		$select = $asoc->getMapper()->getDbTable()->select()
				->from(array('as'=>'ts_products_categ_asoc'))
				->join(array('c'=>'ts_categories'), 'as.categoryId = c.id')
				->where('as.productId = ?', $model->getId())
				->where('c.parentId IS NULL')
				->setIntegrityCheck(false);
		$fetch = $asoc->fetchAll($select);
		if(null != $fetch){
			foreach($fetch as $value){
				$categoryArray[] = $value->getCategoryId();
			}
		}
		$this->category->setValue($categoryArray);
		
		$subcategoryArray = array();
		$asoc = new Default_Model_Productcategasoc();
		$select = $asoc->getMapper()->getDbTable()->select()
				->from(array('as'=>'ts_products_categ_asoc'))
				->join(array('c'=>'ts_categories'), 'as.categoryId = c.id')
				->where('as.productId = ?', $model->getId())
				->where('c.parentId IS NOT NULL')
				->setIntegrityCheck(false);
		$fetch = $asoc->fetchAll($select);
		if(null != $fetch){
			foreach($fetch as $value){
				$subcategoryArray[] = $value->getCategoryId();
			}
		}
		$this->subcategory->setValue($subcategoryArray);

		$promotionArray = array();
		$asoc = new Default_Model_Productspromotionasoc();
		$select = $asoc->getMapper()->getDbTable()->select()
				->where('productId = ?', $model->getId())
				->setIntegrityCheck(false);
		$fetch = $asoc->fetchAll($select);
		if(null != $fetch){
			foreach($fetch as $value){
				$promotionArray[] = $value->getPromotionId();
			}
		}
		$this->promotionId->setValue($promotionArray);

		$sizeArray = array();
		$model2 = new Default_Model_ProductAttribute();
		$select = $model2->getMapper()->getDbTable()->select()
				->where('groupId = ?', '1')
				->where('productId = ?', $model->getId());
		$result = $model2->fetchAll($select);
		if(null != $result){
			foreach($result as $value){
				$sizeArray[] = $value->getValueId();
			}
		}
		$this->size->setValue($sizeArray);
		
		$colorArray = array();
		$model3 = new Default_Model_ProductAttribute();
		$select = $model3->getMapper()->getDbTable()->select()
				->where('groupId = ?', '2')
				->where('productId = ?', $model->getId());
		$result = $model3->fetchAll($select);
		if(null != $result){
			foreach($result as $value){
				$colorArray[] = $value->getValueId();
			}
		}
		$this->color->setValue($colorArray);
		
		$instArray = array();
		$model4 = new Default_Model_ProductInstructionsAssoc();
		$select = $model4->getMapper()->getDbTable()->select()				
				->where('productId = ?', $model->getId());
		$result = $model4->fetchAll($select);
		if(null != $result){
			foreach($result as $value){
				$instArray[] = $value->getInstructionId();	
			}
		}
		$this->instruction->setValue($instArray);
		
		$image = new Zend_Form_Element_Hidden('imageOld');
		$this->addElement($image);
		$this->imageOld->setValue($model->getImage());
		$imageShopmania = new Zend_Form_Element_Hidden('imageShopmaniaOld');
		$this->addElement($imageShopmania);
		$this->imageShopmaniaOld->setValue($model->getImageShopmania());
	}
}
