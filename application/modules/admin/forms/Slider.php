<?php
class Admin_Form_Slider extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id'=>'frmSlider'));
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
		
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nume');
        $name->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required]', 'style'=>'width:250px;'));
        $name->setRequired(true);

        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Imagine');
        $image->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required, minSize[3], maxSize[45]]', 'style'=>'width:250px;'));
        $image->addValidator(new Zend_Validate_StringLength(3,45));
        $image->setRequired(true);

        $url = new Zend_Form_Element_Text('url');
        $url->setLabel('Url');
        $url->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required, minSize[1], maxSize[45]]', 'style'=>'width:250px;'));
        $url->addValidator(new Zend_Validate_StringLength(1, 256));
        $url->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Adauga');
        $submit->setAttribs(array('class'=>'button1'));
		$submit->setRequired(false);
        $submit->setIgnore(true);

		$this->addElements(array($name, $image, $url, $submit));
	}

	function edit(Default_Model_Slider $model)
	{
		$this->name->setValue($model->getName());
		$this->url->setValue($model->getUrl());
		$this->image->setRequired(false);
		$image = new Zend_Form_Element_Hidden('imageOld');
		$this->addElement($image);
		$this->imageOld->setValue($model->getImage());
		
		$this->submit->setValue('Modifica');
	}
}
