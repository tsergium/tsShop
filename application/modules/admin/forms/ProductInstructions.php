<?php
class Admin_Form_ProductInstructions extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
	    $this->setMethod('post');
	    $this->addAttribs(array('id'=>'frmProductInstructions'));
	    $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

	    $name = new Zend_Form_Element_Text('name');
	    $name->setLabel('Nume');
	    $name->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required]', 'style'=>'width:250px;'));
	    $name->setRequired(true);
	    $this->addElement($name);

	    $description = new Zend_Form_Element_Textarea('description');
	    $description->setLabel('Descriere');
	    $description->setAttribs(array('style'=>'height: 150px;'));
	    $description->setRequired(false);
	    $this->addElement($description);

	    $image = new Zend_Form_Element_File('image');
	    $image->setLabel('Imagine');
	    $image->setAttribs(array('maxlength'=>'120', 'style'=>'width:250px;'));
	    $image->setRequired(true);
	    $image->setIgnore(false);
	    $this->addElement($image);

	    $submit = new Zend_Form_Element_Submit('submit');
	    $submit->setValue('Adauga');
	    $submit->setAttribs(array('class'=>'button1'));
	    $submit->setRequired(false);
	    $this->addElement($submit);
	
	}
	
	public function edit(Default_Model_ProductInstructions $model)
	{
	    $this->name->setValue($model->getName());
	    $this->description->setValue($model->getDescription());
	    $this->image->setRequired(false);
	    $image = new Zend_Form_Element_Hidden('imageOld');
	    $this->addElement($image);
	    $this->imageOld->setValue($model->getImage());
	}
}	
