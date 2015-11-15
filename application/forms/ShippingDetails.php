<?php
class Default_Form_ShippingDetails extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
		//END: Creat Account
        //Customer information
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Nume utilizator');
        $username->setRequired(false);
        $username->addFilter('StringTrim');
        $usernameStringAlnum = new Zend_Validate_Alnum();
        $usernameValidateDbNotExists = new Zend_Validate_Db_NoRecordExists(array('table'=>'ts_clients', 'field'=>'username'));
        $username->setAttribs(array('maxlength'=>'120', 'class'=>'f4 validate[ajax[ajaxUser]]]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $username->addValidator(new Zend_Validate_StringLength(3,120));
        $username->addValidators(array($usernameStringAlnum, $usernameValidateDbNotExists));
        $this->addElement($username);
        
		$password = new Zend_Form_Element_Password('password');
        $password->setLabel('Parola');
        $password->setRequired(false);
        $password->addFilter('StringTrim');
        $validPasswordLength = new Zend_Validate_StringLength(6,20);
        $password->addValidators(array($validPasswordLength));
        $password->setAttribs(array('class'=>'f4 validate[required,minSize[6],maxSize[20]]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $password->setDescription('Nota: Parola trebuie să fie între 6 şi 20 de caractere');
        $password->setIgnore(true);
        $this->addElement($password);


        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email');
        $email->setRequired(true);
        $email->addFilter('StringTrim');
        $validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
        try {$validateEmailAddress->setValidateMx(true);} catch (Exception $e) {}
        //$email->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required,custom[email],ajax[ajaxEmail]]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $email->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required,custom[email]]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $email->addValidators(array($validateEmailAddress));
        $this->addElement($email);

		$createaccount = new Zend_Form_Element_Checkbox('createaccount');
        $createaccount->setLabel('Creaza cont nou');
        $createaccount->setRequired(false);
		$createaccount->setChecked(true);
        $createaccount->setIgnore(true);
        $this->addElement($createaccount);

		//END: Creat Account
	
		$firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel('Prenume');
        $firstname->setRequired(true);
        $firstname->addFilter('StringTrim');
//        $firstname->addValidator(new Zend_Validate_Alpha(true));
        $firstname->addValidator(new Zend_Validate_StringLength(3,45));
        $firstname->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required,minSize[3],maxSize[100]]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $this->addElement($firstname);
        
        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel('Nume');
        $lastname->setRequired(true);
        $lastname->addFilter('StringTrim');
//        $lastname->addValidator(new Zend_Validate_Alpha(true));
        $lastname->addValidator(new Zend_Validate_StringLength(3,45));
        $lastname->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required,minSize[3],maxSize[100]]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $this->addElement($lastname); 
        
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
        $city->setAttribs(array('maxlength'=>'120', 'class'=>'f4 validate[required,minSize[2],maxSize[120]]','autocomplete'=>'off'));
        $city->setRequired(true);     
        $this->addElement($city);
         
        $zip = new Zend_Form_Element_Text('zip');
        $zip->setLabel('Cod postal');
        $zip->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[maxSize[100]]'));
        $zip->setRequired(FALSE);
        $this->addElement($zip);

        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Nr. telefon');
        $phone->setAttribs(array('maxlength'=>'100', 'class'=>'f4 validate[required]'));
        $phone->setRequired(true);
        $this->addElement($phone);



		$firstnameS = new Zend_Form_Element_Text('firstnameS');
        $firstnameS->setLabel('Prenume');
        $firstnameS->setRequired(true);
        $firstnameS->addFilter('StringTrim');
        $firstnameS->addValidator(new Zend_Validate_Alpha(true));
        $firstnameS->addValidator(new Zend_Validate_StringLength(3,45));
        $firstnameS->setAttribs(array('maxlength'=>'50', 'class'=>'f4 validate[required,minSize[3],maxSize[50]]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $this->addElement($firstnameS);

        $lastnameS = new Zend_Form_Element_Text('lastnameS');
        $lastnameS->setLabel('Nume');
        $lastnameS->setRequired(true);
        $lastnameS->addFilter('StringTrim');
        $lastnameS->addValidator(new Zend_Validate_Alpha(true));
        $lastnameS->addValidator(new Zend_Validate_StringLength(3,45));
        $lastnameS->setAttribs(array('maxlength'=>'50', 'class'=>'f4 validate[required,minSize[3],maxSize[50]]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
        $this->addElement($lastnameS);

        $addressS = new Zend_Form_Element_Text('addressS');
        $addressS->setLabel('Adresa');
        $addressS->setAttribs(array('maxlength'=>'50', 'class'=>'f4 validate[required,minSize[3],maxSize[50]]'));
        $addressS->setRequired(true);
        $addressS->setFilters(array(new Zend_Filter_StringTrim(),));
        $this->addElement($addressS);

        $zipcodeS = new Zend_Form_Element_Text('zipcodeS');
        $zipcodeS->setLabel('Cod postal');
        $zipcodeS->setAttribs(array('maxlength'=>'10', 'class'=>'f4 validate[minSize[2],maxSize[10]]'));
        $zipcodeS->setRequired(FALSE);
        $this->addElement($zipcodeS);

        $stateS = new Zend_Form_Element_Text('stateS');
        $stateS->setLabel('Judet');
        $stateS->setAttribs(array('class'=>'f4 validate[required,maxSize[50]]','autocomplete'=>'off'));
        $stateS->setRequired(true);
        $this->addElement($stateS);


//        $fax = new Zend_Form_Element_Text('fax');
//        $fax->setLabel('Nr. fax');
//        $fax->setAttribs(array('maxlength'=>'100', 'class'=>'f4'));
//        $fax->setRequired(false);
//        $this->addElement($fax);
	
	}
	
}
