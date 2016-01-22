<?php

class Admin_Form_Partner extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmPartner'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Nume');
        $name->setAttribs(array('maxlength' => '120', 'class' => 'validate[required]', 'style' => 'width:250px;'));
        $name->setRequired(true);

        $url = new Zend_Form_Element_Text('url');
        $url->setLabel('Url');
        $url->setAttribs(array('maxlength' => '120', 'class' => 'validate[required, minSize[3], maxSize[45]]', 'style' => 'width:250px;'));
        $url->addValidator(new Zend_Validate_StringLength(3, 256));
        $url->setRequired(true);

        $nofollow = new Zend_Form_Element_Radio('nofollow');
        $nofollow->setLabel('Nofollow');
        $options = array('da' => 'Da', 'nu' => 'Nu');
        $nofollow->addMultiOptions($options);
        $nofollow->addValidator(new Zend_Validate_InArray(array_keys($options)));
        $nofollow->setValue('da');
        $nofollow->setSeparator('&nbsp;');
        $nofollow->setAttribs(array('class' => 'validate[required]'));
        $nofollow->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Adauga');
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setRequired(false);
        $submit->setIgnore(true);

        $this->addElements(array($name, $url, $nofollow, $submit));
    }

    public function edit(Default_Model_Partner $model)
    {
        $this->name->setValue($model->getName());
        $this->url->setValue($model->getUrl());
        $this->nofollow->setValue($model->getNofollow());
        $this->submit->setValue('Modifica');
    }
}
