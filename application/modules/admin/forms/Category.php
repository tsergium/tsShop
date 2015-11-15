<?php
class Admin_Form_Category extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmCategories'));
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Nume');
		$name->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]', 'style'=>'width:250px;'));
		$name->setRequired(true);
		$this->addElement($name);
		
		$parentId = new Zend_Form_Element_Select('parentId');
		$parentId->setLabel('Categorie Parinte');
		$parentId->setRegisterInArrayValidator(false);
		$options = array(null=>'Selecteaza categorie parinte');
		$model = new Default_Model_Category();
		$select = $model->getMapper()->getDbTable()->select()
				->where('parentId IS NULL');
		$result = $model->fetchAll($select);
		if(null != $result) {
			foreach($result as $value) {
				$options[$value->getId()] = $value->getName();
			}
			$parentId->addMultiOptions($options);
			$parentId->setAttribs(array('class'=>'select', 'style'=>'width:258px;'));
			$parentId->setRequired(false);
		}
		$this->addElement($parentId);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setValue('Adauga');
		$submit->setAttribs(array('class'=>'button1'));
		$submit->setIgnore(true);
		$this->addElement($submit);

	}

	public function edit(Default_Model_Category $model)
	{
		$this->parentId->setValue($model->getParentId());
		$this->name->setValue($model->getName());
		$this->submit->setValue('Modifica');
	}

}