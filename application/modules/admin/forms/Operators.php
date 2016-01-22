<?php

class Admin_Form_Operators extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmOperator'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        //Customer information
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel(Zend_Registry::get('translate')->_('customers_create_table_username'));
        $username->setRequired(true);
        $username->addFilter('StringTrim');
        $usernameStringAlnum = new Zend_Validate_Alnum();
        $usernameValidateDbNotExists = new Zend_Validate_Db_NoRecordExists(array('table' => 'fp_admin_user', 'field' => 'username'));
        $username->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,custom[onlyLetterNumber],minSize[3],maxSize[120],ajax[ajaxAdminUser]]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $username->setDescription(Zend_Registry::get('translate')->_('customers_create_table_username_description'));
        $username->addValidator(new Zend_Validate_StringLength(3, 120));
        $username->addValidators(array($usernameStringAlnum, $usernameValidateDbNotExists));
        $this->addElement($username);

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel(Zend_Registry::get('translate')->_('customers_create_table_password'));
        $password->setRequired(true);
        $password->addFilter('StringTrim');
        $password->setAttribs(array('maxlength' => '20', 'class' => 'validate[required,minSize[6],maxSize[120]]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $password->setDescription(Zend_Registry::get('translate')->_('customers_create_table_password_description'));
        $validPasswordLength = new Zend_Validate_StringLength(6, 20);
        $password->addValidators(array($validPasswordLength));
        $this->addElement($password);

        $tbRePassword = new Zend_Form_Element_Password('tbRePassword');
        $tbRePassword->setLabel(Zend_Registry::get('translate')->_('customers_create_table_password_confirmation'));
        $tbRePassword->setAllowEmpty(false);
        $tbRePassword->setRequired(true);
        $tbRePassword->addFilter('StringTrim');
        $tbRePassword->setAttribs(array('class' => 'validate[equals[password]]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $validatePasswordIdenticalField = new App_Validate_IdenticalField('password', 'Password');
        $tbRePassword->addValidators(array($validatePasswordIdenticalField));
        $this->addElement($tbRePassword);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel(Zend_Registry::get('translate')->_('customers_create_table_email'));
        $email->setRequired(true);
        $email->addFilter('StringTrim');
        $validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
        try {
            $validateEmailAddress->setValidateMx(true);
        } catch (Exception $e) {
        }
        $emailValidateDbNotExists = new Zend_Validate_Db_NoRecordExists(array('table' => 'fp_admin_user', 'field' => 'email'));
        $email->setAttribs(array('maxlength' => '100', 'class' => 'validate[required,custom[email],ajax[ajaxAdminEmail]]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $email->addValidators(array($validateEmailAddress, $emailValidateDbNotExists));
        $this->addElement($email);

        $tbReEmail = new Zend_Form_Element_Text('tbReEmail');
        $tbReEmail->setLabel(Zend_Registry::get('translate')->_('customers_create_table_email_confirmation'));
        $tbReEmail->addFilter('StringTrim');
        $tbReEmail->setRequired(true);
        $tbReEmail->setAllowEmpty(false);
        $validateEmailIdenticalField = new App_Validate_IdenticalField('email', 'Email');
        $tbReEmail->addValidators(array($validateEmailIdenticalField));
        $tbReEmail->setAttribs(array('maxlength' => '100', 'class' => 'validate[equals[email]] ', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $this->addElement($tbReEmail);

        $status = new Zend_Form_Element_Radio('status');
        $status->setLabel(Zend_Registry::get('translate')->_("customers_create_table_status"));
        $status->setRequired(true);
        $optionStatus = array(Zend_Registry::get('translate')->_('customers_create_table_inactive'), Zend_Registry::get('translate')->_('customers_create_table_active'));
        $status->addMultiOptions($optionStatus);
        $status->setValue('0');
        $status->addValidator(new Zend_Validate_InArray(array_keys($optionStatus)));
        $status->setSeparator('');
        $status->setAttribs(array('class' => 'validate[required]'));
        $status->setDescription(Zend_Registry::get('translate')->_('customers_create_table_status_description'));
        $this->addElement($status);

        $salutation = new Zend_Form_Element_Select('salutation');
        $salutation->setLabel(Zend_Registry::get('translate')->_('customers_create_table_salutation'));
        $salutation->setRegisterInArrayValidator(false);
        $salutationOptions = array(null => 'Select');
        $sal = new Default_Model_Customerssalutation();
        $select = $sal->getMapper()->getDbTable()->select()
            ->order(array('id ASC'));
        if ($salut = $sal->fetchAll($select)) {
            foreach ($salut as $sal) {
                $salutationOptions[$sal->getName()] = $sal->getName();
            }
        }
        $salutation->addMultiOptions($salutationOptions);
        $salutation->setAttribs(array('class' => 'select validate[required]', 'style' => 'width:100px;'));
        $salutation->setRequired(true);
        $this->addElement($salutation);

        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel(Zend_Registry::get('translate')->_('customers_create_table_first_name'));
        $firstname->setRequired(true);
        $firstname->addFilter('StringTrim');
        $firstname->addValidator(new Zend_Validate_Alpha(true));
        $firstname->addValidator(new Zend_Validate_StringLength(3, 45));
        $firstname->setAttribs(array('maxlength' => '45', 'class' => 'validate[required]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $this->addElement($firstname);

        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel(Zend_Registry::get('translate')->_('customers_create_table_last_name'));
        $lastname->setRequired(true);
        $lastname->addFilter('StringTrim');
        $lastname->addValidator(new Zend_Validate_Alpha(true));
        $lastname->addValidator(new Zend_Validate_StringLength(3, 45));
        $lastname->setAttribs(array('maxlength' => '45', 'class' => 'validate[required]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $this->addElement($lastname);

        $cnp = new Zend_Form_Element_Text('cnp');
        $cnp->setLabel(Zend_Registry::get('translate')->_('customers_create_table_cnp'));
        $cnp->setAttribs(array('maxlength' => '16'));
        $cnp->setRequired(false);
        $this->addElement($cnp);

        $seria = new Zend_Form_Element_Text('seria');
        $seria->setLabel(Zend_Registry::get('translate')->_('customers_create_table_serie'));
        $seria->setAttribs(array('maxlength' => '16'));
        $seria->setRequired(false);
        $this->addElement($seria);

        //Contact information
        $address = new Zend_Form_Element_Text('address');
        $address->setLabel(Zend_Registry::get('translate')->_('customers_create_table_address'));
        $address->addFilter('StringTrim');
        $address->setAttribs(array('maxlength' => '255', 'class' => 'validate[required]'));
        $address->setRequired(true);
        $this->addElement($address);

        $county = new Zend_Form_Element_Text('county');
        $county->setLabel(Zend_Registry::get('translate')->_('customers_create_table_county'));
        $county->setAttribs(array('class' => 'validate[required]', 'autocomplete' => 'off'));
        $county->setRequired(true);
        $this->addElement($county);

        $city = new Zend_Form_Element_Text('city');
        $city->setLabel(Zend_Registry::get('translate')->_('customers_create_table_city'));
        $city->addFilter('StringTrim');
        $city->setAttribs(array('maxlength' => '45', 'class' => 'validate[required]'));
        $city->setRequired(true);
        $this->addElement($city);

        $zip = new Zend_Form_Element_Text('zip');
        $zip->setLabel(Zend_Registry::get('translate')->_('customers_create_table_zip'));
        $zip->addFilter('StringTrim');
        $zip->setAttribs(array('maxlength' => '10', 'class' => 'validate[required]'));
        $zip->setRequired(true);
        $this->addElement($zip);

        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel(Zend_Registry::get('translate')->_('customers_create_table_phone'));
        $phone->addFilter('StringTrim');
        $phone->setAttribs(array('maxlength' => '25', 'class' => 'validate[required]'));
        $phone->setDescription(Zend_Registry::get('translate')->_('customers_create_table_phone_description'));
        $phone->setRequired(true);
        $this->addElement($phone);

        $phone2 = new Zend_Form_Element_Text('phone2');
        $phone2->setLabel(Zend_Registry::get('translate')->_('customers_create_table_phone_2'));
        $phone2->addFilter('StringTrim');
        $phone2->setAttribs(array('maxlength' => '25'));
        $phone2->setDescription(Zend_Registry::get('translate')->_('customers_create_table_phone_2_description'));
        $this->addElement($phone2);

        $fax = new Zend_Form_Element_Text('fax');
        $fax->setLabel(Zend_Registry::get('translate')->_('customers_create_table_fax'));
        $fax->addFilter('StringTrim');
        $fax->setAttribs(array('maxlength' => '25'));
        $fax->setDescription(Zend_Registry::get('translate')->_('customers_create_table_fax_description'));
        $this->addElement($fax);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('customers_create_table_button_create'));
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setIgnore(true);
        $this->addElement($submit);
    }

    public function edit(Default_Model_AdminUser $customer)
    {
        $this->salutation->setValue($customer->getSalutation());
        $this->firstname->setValue($customer->getFirstname());
        $this->lastname->setValue($customer->getLastname());
        $this->cnp->setValue($customer->getCnp());
        $this->seria->setValue($customer->getSeria());
        $this->address->setValue($customer->getAddress());
        $this->county->setValue($customer->getCounty());
        $this->city->setValue($customer->getCity());
        $this->zip->setValue($customer->getZip());
        $this->phone->setValue($customer->getPhone());
        $this->phone2->setValue($customer->getPhone2());
        $this->fax->setValue($customer->getFax());
        $this->email->setValue($customer->getEmail());
        $this->status->setValue($customer->getStatus());
        $this->submit->setValue(Zend_Registry::get('translate')->_('customers_edit_table_button_modify'));

        $usernameValidateDbNotExists = $this->username->getValidator('Zend_Validate_Db_NoRecordExists');
        $usernameValidateDbNotExists->setExclude(array('field' => 'id', 'value' => $customer->getId()));
        $emailValidateDbNotExists = $this->email->getValidator('Zend_Validate_Db_NoRecordExists');
        $emailValidateDbNotExists->setExclude(array('field' => 'email', 'value' => $customer->getEmail()));
        $this->removeElement('username');
        $this->removeElement('password');
        $this->removeElement('tbRePassword');
        $this->removeElement('tbReEmail');
    }
}
