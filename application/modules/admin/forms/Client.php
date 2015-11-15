<?php
class Admin_Form_Client extends Zend_Form
{
	function init()
	{
		
	}

	public function customerAdd()
	{
		// Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id'=>'frmAccount'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        //Customer information
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Nume utilizator');
        $username->setRequired(true);
        $username->addFilter('StringTrim');
        $usernameStringAlnum = new Zend_Validate_Alnum();
        $usernameValidateDbNotExists = new Zend_Validate_Db_NoRecordExists(array('table'=>'ts_clients', 'field'=>'username'));
        $username->setAttribs(array('maxlength'=>'120', 'class'=>'f4 validate[required,custom[onlyLetterNumber],minSize[3],maxSize[120]] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $username->addValidator(new Zend_Validate_StringLength(3,120));
        $username->addValidators(array($usernameStringAlnum, $usernameValidateDbNotExists));
        $this->addElement($username);

	$password = new Zend_Form_Element_Password('password');
        $password->setLabel('Parola');
        $password->setRequired(true);
        $password->addFilter('StringTrim');
        $validPasswordLength = new Zend_Validate_StringLength(6,20);
        $password->addValidators(array($validPasswordLength));
        $password->setAttribs(array('class'=>'f4 validate[required,minSize[6],maxSize[20]] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $password->setDescription('Nota: Parola trebuie să fie între 6 şi 20 de caractere');
        $password->setIgnore(true);
        $this->addElement($password);

        $tbRePassword = new Zend_Form_Element_Password('tbRePassword');
        $tbRePassword->setLabel('Confirma parola');
        $tbRePassword->setAllowEmpty(false);
        $tbRePassword->setRequired(true);
        $tbRePassword->addFilter('StringTrim');
        $tbRePassword->setAttribs(array('class'=>'f4 validate[equals[password]] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $validatePasswordIdenticalField = new App_Validate_IdenticalField('password', 'Password');
        $tbRePassword->addValidators(array($validatePasswordIdenticalField));
        $tbRePassword->setIgnore(true);
        $this->addElement($tbRePassword);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email');
        $email->setRequired(true);
        $email->addFilter('StringTrim');
        $validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
        try {$validateEmailAddress->setValidateMx(true);} catch (Exception $e) {}
        $emailValidateDbNotExists = new Zend_Validate_Db_NoRecordExists(array('table'=>'ts_clients', 'field'=>'email'));
        $email->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required,custom[email]] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $email->addValidators(array($validateEmailAddress, $emailValidateDbNotExists));
        $this->addElement($email);

        $tbReEmail = new Zend_Form_Element_Text('tbReEmail');
        $tbReEmail->setLabel('Confirmare Email');
        $tbReEmail->addFilter('StringTrim');
        $tbReEmail->setRequired(true);
        $tbReEmail->setAllowEmpty(false);
        $validateEmailIdenticalField = new App_Validate_IdenticalField('email', 'Email');
        $tbReEmail->addValidators(array($validateEmailIdenticalField));
        $tbReEmail->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[equals[email]] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $tbReEmail->setIgnore(true);
        $this->addElement($tbReEmail);

        //Basic information
        $clienttype = new Zend_Form_Element_Radio('clienttype');
        $clienttype->setLabel('');
        $optionclienttype = array('0'=>'Persoana Fizica', '1'=>'Companie');
        $clienttype->addMultiOptions($optionclienttype);
        $clienttype->addValidator(new Zend_Validate_InArray(array_keys($optionclienttype)));
        $clienttype->setValue("0");
        $clienttype->setSeparator('');
        $clienttype->setAttribs(array('class'=>'validate[required]'));
        $clienttype->setRequired(true);
        $this->addElement($clienttype);

		$firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel('Prenume');
        $firstname->setRequired(true);
        $firstname->addFilter('StringTrim');
//        $firstname->addValidator(new Zend_Validate_Alpha(true));
        $firstname->addValidator(new Zend_Validate_StringLength(3,45));
        $firstname->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required,minSize[3],maxSize[100]] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $this->addElement($firstname);

        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel('Nume');
        $lastname->setRequired(true);
        $lastname->addFilter('StringTrim');
//        $lastname->addValidator(new Zend_Validate_Alpha(true));
        $lastname->addValidator(new Zend_Validate_StringLength(3,45));
        $lastname->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required,minSize[3],maxSize[100]] f3', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $this->addElement($lastname);

        $birthday = new Zend_Form_Element_Text('birthday');
        $birthday->setLabel('Data nasterii');
        $birthday->setAttribs(array('maxlength'=>'50', 'class'=>'f4 validate[required,custom[date]]'));
        $birthday->setDescription('ex.1990-05-25');
        $birthday->setRequired(true);
        $this->addElement($birthday);


        $gender = new Zend_Form_Element_Radio('gender');
        $gender->setLabel('Sex');
        $options = array('0'=>'Feminin', '1'=>'Masculin');
        $gender->addMultiOptions($options);
        $gender->addValidator(new Zend_Validate_InArray(array_keys($options)));
        $gender->setValue('0');
        $gender->setSeparator('');
        $gender->setAttribs(array('class'=>'validate[required]'));
        $gender->setRequired(true);
        $this->addElement($gender);

        $address = new Zend_Form_Element_Text('address');
        $address->setLabel('Adresa');
        $address->setAttribs(array('maxlength'=>'300', 'class'=>'f4 validate[required,minSize[3],maxSize[300]]'));
        $address->setRequired(true);
        $address->setFilters(array(new Zend_Filter_StringTrim(),));
        $this->addElement($address);

        $county = new Zend_Form_Element_Text('county');
        $county->setLabel('Judet');
        $county->setAttribs(array('class'=>'f4 validate[required]','autocomplete'=>'off'));
        $county->setRequired(true);
        $this->addElement($county);

        $city = new Zend_Form_Element_Text('city');
        $city->setLabel('Oras');
        $city->setAttribs(array('maxlength'=>'120', 'class'=>'f4 validate[required,minSize[2],maxSize[120]] f3','autocomplete'=>'off'));
        $city->setRequired(true);
        $this->addElement($city);

        $zip = new Zend_Form_Element_Text('zip');
        $zip->setLabel('Cod postal');
        $zip->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required,minSize[2],maxSize[100]] f3'));
        $zip->setRequired(true);
        $this->addElement($zip);

        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Nr. telefon');
        $phone->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required,minSize[3],maxSize[100]] f3'));
        $phone->setRequired(true);
        $this->addElement($phone);

        $fax = new Zend_Form_Element_Text('fax');
        $fax->setLabel('Nr. fax');
        $fax->setAttribs(array('maxlength'=>'100', 'class'=>'f4'));
        $fax->setRequired(false);
        $this->addElement($fax);

        $companyname = new Zend_Form_Element_Text('companyname');
        $companyname->setLabel('Denumire Companie');
        $companyname->setAttribs(array('maxlength'=>'200', 'class'=>'f4 validate[required,minSize[3],maxSize[200]] f3'));
        $companyname->setRequired(false);
        $this->addElement($companyname);

        $fiscalcode = new Zend_Form_Element_Text('fiscalcode');
        $fiscalcode->setLabel('Cod fiscal');
        $fiscalcode->setAttribs(array('maxlength'=>'120', 'class'=>'f4 validate[required,minSize[3],maxSize[120]] f3'));
        $fiscalcode->setRequired(false);
        $this->addElement($fiscalcode);

        $traderegister = new Zend_Form_Element_Text('traderegister');
        $traderegister->setLabel('Cod Reg. Com.');
        $traderegister->setAttribs(array('maxlength'=>'120', 'class'=>'f4 validate[required,minSize[2],maxSize[120]] f3'));
        $traderegister->setRequired(false);
        $this->addElement($traderegister);

        $bank = new Zend_Form_Element_Text('bank');
        $bank->setLabel('Banca');
        $bank->setAttribs(array('maxlength'=>'120', 'class'=>'f4 validate[minSize[2],maxSize[120]] f3'));
        $bank->setRequired(false);
        $this->addElement($bank);

        $bankaccount = new Zend_Form_Element_Text('bankaccount');
        $bankaccount->setLabel('Cont bancar');
        $bankaccount->setAttribs(array('maxlength'=>'120', 'class'=>'f4 validate[minSize[3],maxSize[120]] f3'));
        $bankaccount->setRequired(false);
        $this->addElement($bankaccount);

		$status = new Zend_Form_Element_Radio('status');
		$status->setLabel(Zend_Registry::get('translate')->_('customers_create_table_status'));
		$optionStatus = array(Zend_Registry::get('translate')->_('customers_create_table_inactive'), Zend_Registry::get('translate')->_('customers_create_table_active'));
		$status->addMultiOptions($optionStatus);
		$status->addValidator(new Zend_Validate_InArray(array_keys($optionStatus)));
		$status->setValue('0');
		$status->setSeparator('');
		$status->setAttribs(array('class'=>'validate[required]'));
		$status->setDescription(Zend_Registry::get('translate')->_('customers_create_table_status_description'));
		$status->setRequired(true);
		$this->addElement($status);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Trimite');
        $submit->setAttribs(array('class'=>'button1'));
        $submit->setIgnore(true);
        $this->addElement($submit);
	}

	public function customerEdit(Default_Model_Clients $client)
	{		
		$this->status->setValue(strip_tags($client->getStatus()));
//		// Set the method for the display form to POST
		$this->clienttype->setValue($client->getClienttype());

		$this->firstname->setValue($client->getFirstname());
		$this->lastname->setValue($client->getLastname());
		$this->birthday->setValue(date('Y-m-d',$client->getBirthday()));
		$this->gender->setValue($client->getGender());

		$this->address->setValue($client->getAddress());
		$this->county->setValue($client->getCounty());
		$this->city->setValue($client->getCity());
		$this->zip->setValue($client->getZip());
		$this->phone->setValue($client->getPhone());
		$this->fax->setValue($client->getFax());
		$this->email->setValue($client->getEmail());
//
		$this->companyname->setValue($client->getCompanyname());
		$this->fiscalcode->setValue($client->getFiscalcode());
		$this->traderegister->setValue($client->getTraderegister());
		$this->bank->setValue($client->getBank());
		$this->bankaccount->setValue($client->getBankaccount());
//
		$usernameValue = new Zend_Form_Element_Text('usernameValue');
		$usernameValue->setValue($client->getUsername());
		$this->addElement($usernameValue);

		$this->submit->setValue('Modifica');
//
		$emailValidateDbNotExists = $this->email->getValidator('Zend_Validate_Db_NoRecordExists');
		$emailValidateDbNotExists->setExclude(array('field'=>'email', 'value' => $client->getEmail()));
		$this->removeElement('password');
		$this->removeElement('tbRePassword');
		$this->removeElement('tbReEmail');
		$this->removeElement('newsletter');
		$this->removeElement('username');
	}

	public function search()
	{
		// Set the method for the display form to POST
		$this->setMethod('post');
		$this->addAttribs(array('id'=>'frmSearchCustomer'));

		$txtHeaderSearch = new Zend_Form_Element_Text('txtHeaderSearch');
        $txtHeaderSearch->setLabel(Zend_Registry::get('translate')->_('customers_search_table_search_client'));
        $txtHeaderSearch->setAttribs(array(
			'class'=>'validate[required,minSize[3],maxSize[120]]',
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
