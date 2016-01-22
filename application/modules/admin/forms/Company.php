<?php

class Admin_Form_Company extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmCompany'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $institution = new Zend_Form_Element_Text('institution');
        $institution->setLabel(Zend_Registry::get('translate')->_('settings_add_company_table_company'));
        $institution->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]'));
        $institution->addValidator(new Zend_Validate_StringLength(1, 120));
        $institution->setRequired(true);
        $this->addElement($institution);

        $address = new Zend_Form_Element_Text('address');
        $address->setLabel(Zend_Registry::get('translate')->_('settings_add_company_table_address'));
        $address->setAttribs(array('class' => 'validate[required]'));
        $address->addValidator(new Zend_Validate_StringLength(1, 120));
        $address->setRequired(true);
        $this->addElement($address);

        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel(Zend_Registry::get('translate')->_('settings_add_company_table_phone'));
        $phone->setAttribs(array('maxlength' => '16', 'class' => 'validate[required,minSize[1],maxSize[16]]'));
        $phone->addValidator(new Zend_Validate_StringLength(1, 16));
        $phone->setRequired(true);
        $this->addElement($phone);

        $phone2 = new Zend_Form_Element_Text('phone2');
        $phone2->setLabel(Zend_Registry::get('translate')->_('settings_add_company_table_phone2'));
        $phone2->setAttribs(array('maxlength' => '16'));
        $phone2->addValidator(new Zend_Validate_StringLength(1, 16));
        $phone2->setRequired(false);
        $this->addElement($phone2);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel(Zend_Registry::get('translate')->_('settings_add_company_table_email'));
        $email->setAttribs(array('maxlength' => '120', 'class' => 'validate[required]'));
        $email->addValidator(new Zend_Validate_StringLength(1, 120));
        $email->setRequired(true);
        $this->addElement($email);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Adauga');
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setIgnore(true);
        $this->addElement($submit);
    }

    public function editcompany(Default_Model_Company $model)
    {
        // Set the method for the display form to POST
        $this->institution->setValue($model->getInstitution());
        $this->address->setValue($model->getAddress());
        $this->phone->setValue($model->getPhone());
        $this->phone2->setValue($model->getPhone2());
        $this->email->setValue($model->getEmail());
        $this->submit->setValue('Modifica');
    }
}