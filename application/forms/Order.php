<?php

class Default_Form_Order extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setAction('/submit');
        $this->addAttribs(array('id' => 'frmOrder'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $formorder = new Zend_Form_Element_Hidden('formorder');
        $formorder->setValue('formorder');

        $productsCost = new Zend_Form_Element_Hidden('productsCost');
        $productsCost->setLabel('productsCost');
        $productsCost->setAttribs(array());
        $productsCost->setRequired(true);

//		$model = new Default_Model_Company();
//		$select = $model->getMapper()->getDbTable()->select();
//		if(($result = $model->fetchAll($select))) {
//			if($result[0]->getVatPayer() == '1') {
//				$model2 = new Default_Model_ProductsTaxes();
//				$select = $model2->getMapper()->getDbTable()->select()
//						->where('id = ?', '1')
//						->where('status = ?', '1')
//						;
//				if($result2 = $model2->fetchAll($select)) {
//					$vatValue = $result2[0]->getValue();
//				}
//			} else {
//				$vatValue = '0';
//			}
//		}

//		$vat = new Zend_Form_Element_Hidden('vat');
//		$vat->setLabel('vat');
//		$vat->setValue($vatValue);
//		$vat->setAttribs(array());
//		$vat->setRequired(false);

        $tax = new Zend_Form_Element_Hidden('tax');
        $tax->setLabel('tax');
        $tax->setAttribs(array());
        $tax->setRequired(false);

        $discount = new Zend_Form_Element_Hidden('discount');
        $discount->setLabel('discount');
        $discount->setAttribs(array());
        $discount->setRequired(false);

        $ramburs = new Zend_Form_Element_Hidden('ramburs');
        $ramburs->setLabel('ramburs');
        $ramburs->setAttribs(array());
        $ramburs->setRequired(false);

        $totalCost = new Zend_Form_Element_Hidden('totalCost');
        $totalCost->setLabel('totalCost');
        $totalCost->setAttribs(array());
        $totalCost->setRequired(true);

        $payment = new Zend_Form_Element_Hidden('payment');
        $payment->setLabel('payment');
        $payment->setAttribs(array());
        $payment->setRequired(false);

//		$email = new Zend_Form_Element_Text('email');
//        $email->setLabel(Zend_Registry::get('translate')->_('user_email'));
//        $email->setRequired(true);
//        $email->addFilter('StringTrim');
//        $validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
//        try {$validateEmailAddress->setValidateMx(true);} catch (Exception $e) {}
//        $emailValidateDbNotExists = new Zend_Validate_Db_NoRecordExists(array('table'=>'fp_customers', 'field'=>'email'));
//        $email->setAttribs(array('maxlength'=>'100', 'class'=>'validate[required,custom[email]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
//        $email->addValidators(array($validateEmailAddress, $emailValidateDbNotExists));
//
//        $tbReEmail = new Zend_Form_Element_Text('tbReEmail');
//        $tbReEmail->setLabel(Zend_Registry::get('translate')->_('user_email_confirmation'));
//        $tbReEmail->addFilter('StringTrim');
//        $tbReEmail->setRequired(true);
//        $tbReEmail->setAllowEmpty(false);
//		$validateEmailIdenticalField = new App_Validate_IdenticalField('email', 'Email');
//		$tbReEmail->addValidators(array($validateEmailIdenticalField));
//        $tbReEmail->setAttribs(array('maxlength'=>'100', 'class'=>'validate[equals[email]] ', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
//		$tbReEmail->setIgnore(true);
//
//		$password = new Zend_Form_Element_Password('password');
//        $password->setLabel(Zend_Registry::get('translate')->_('user_password'));
//        $password->setRequired(true);
//		$password->addFilter('StringTrim');
//		$validPasswordLength = new Zend_Validate_StringLength(6,20);
//		$password->addValidators(array($validPasswordLength));
//        $password->setAttribs(array('class'=>'validate[required,minSize[6],maxSize[20]]', 'class'=>'psw', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
//        $password->setDescription(Zend_Registry::get('translate')->_('user_password_note'));
//		$password->setIgnore(true);
//
//		$tbRePassword = new Zend_Form_Element_Password('tbRePassword');
//        $tbRePassword->setLabel(Zend_Registry::get('translate')->_('user_password_confirmation'));
//        $tbRePassword->setAllowEmpty(false);
//        $tbRePassword->setRequired(true);
//		$tbRePassword->addFilter('StringTrim');
//        $tbRePassword->setAttribs(array('class'=>'validate[equals[password]]', 'class'=>'psw', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
//		$validatePasswordIdenticalField = new App_Validate_IdenticalField('password', 'Password');
//		$tbRePassword->addValidators(array($validatePasswordIdenticalField));
//		$tbRePassword->setIgnore(true);       
//
//		$person = new Zend_Form_Element_Radio('person');
//		$person->setLabel('');
//		$person->setRequired(true);
//		$optionPerson = array(Zend_Registry::get('translate')->_('user_individual_person'),Zend_Registry::get('translate')->_('user_legal_person'));
//		$person->addMultiOptions($optionPerson);
//		$person->setValue("0");
//		$person->addValidator(new Zend_Validate_InArray(array_keys($optionPerson)));
//		$person->setSeparator('');
//		$person->setAttribs(array('class'=>'validate[required]'));
//


        $firstnameb = new Zend_Form_Element_Text('firstnameb');
        $firstnameb->setLabel(Zend_Registry::get('translate')->_('cart_first_name'));
        $firstnameb->setRequired(true);
        $firstnameb->addFilter('StringTrim');
        $firstnameb->addValidator(new Zend_Validate_Alpha(true));
        $firstnameb->addValidator(new Zend_Validate_StringLength(3, 45));
        $firstnameb->setAttribs(array('maxlength' => '45', 'class' => 'validate[required,minSize[3],maxSize[45]]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));

        $lastnameb = new Zend_Form_Element_Text('lastnameb');
        $lastnameb->setLabel(Zend_Registry::get('translate')->_('cart_last_name'));
        $lastnameb->setRequired(true);
        $lastnameb->addFilter('StringTrim');
        $lastnameb->addValidator(new Zend_Validate_Alpha(true));
        $lastnameb->addValidator(new Zend_Validate_StringLength(3, 45));
        $lastnameb->setAttribs(array('maxlength' => '45', 'class' => 'validate[required,minSize[3],maxSize[45]]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));

//		$cnpb = new Zend_Form_Element_Text('cnpb');
//		$cnpb->setLabel(Zend_Registry::get('translate')->_('cart_cnp'));
//		$cnpb->setAttribs(array('maxlength'=>'16'));
//		$cnpb->setRequired(false);
//		$cnpb->setIgnore(true);
//
//		$seriab = new Zend_Form_Element_Text('seriab');
//		$seriab->setLabel(Zend_Registry::get('translate')->_('cart_serie'));
//		$seriab->setAttribs(array('maxlength'=>'16'));
//		$seriab->setRequired(false);
//		$seriab->setIgnore(true);
//
//		$institution = new Zend_Form_Element_Text('institution');
//		$institution->setLabel(Zend_Registry::get('translate')->_('order_institution'));
//		$institution->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$institution->setRequired(true);
//
//		$fiscalcode = new Zend_Form_Element_Text('fiscalcode');
//		$fiscalcode->setLabel(Zend_Registry::get('translate')->_('order_fiscal_code'));
//		$fiscalcode->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$fiscalcode->setRequired(true);
//
//		$traderegister = new Zend_Form_Element_Text('traderegister');
//		$traderegister->setLabel(Zend_Registry::get('translate')->_('order_trade_register'));
//		$traderegister->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$traderegister->setRequired(true);
//
//		$bank = new Zend_Form_Element_Text('bank');
//		$bank->setLabel(Zend_Registry::get('translate')->_('order_bank'));
//		$bank->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$bank->setRequired(true);
//
//		$ibancode = new Zend_Form_Element_Text('ibancode');
//		$ibancode->setLabel(Zend_Registry::get('translate')->_('order_iban_code'));
//		$ibancode->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$ibancode->setRequired(true);
//
//		$function = new Zend_Form_Element_Text('function');
//		$function->setLabel(Zend_Registry::get('translate')->_('order_function'));
//		$function->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$function->setRequired(true);
//
//		$department = new Zend_Form_Element_Text('department');
//		$department->setLabel(Zend_Registry::get('translate')->_('order_department'));
//		$department->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$department->setRequired(true);

        $addressb = new Zend_Form_Element_Text('addressb');
        $addressb->setLabel(Zend_Registry::get('translate')->_('cart_address'));
        $addressb->setAttribs(array('maxlength' => '120', 'class' => 'validate[maxSize[120]]'));
        $addressb->setRequired(false);

//		$countyb = new Zend_Form_Element_Text('countyb');
//		$countyb->setLabel(Zend_Registry::get('translate')->_('cart_county'));
//		$countyb->setAttribs(array('class'=>'validate[required]', 'autocomplete'=>'off'));
//		$countyb->setRequired(true);

        $cityb = new Zend_Form_Element_Text('cityb');
        $cityb->setLabel(Zend_Registry::get('translate')->_('cart_town_city'));
        $cityb->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'autocomplete' => 'off'));
        $cityb->setRequired(true);

//		$zipb = new Zend_Form_Element_Text('zipb');
//		$zipb->setLabel(Zend_Registry::get('translate')->_('cart_zip_postal'));
//		$zipb->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$zipb->setRequired(true);

        $phoneb = new Zend_Form_Element_Text('phoneb');
        $phoneb->setLabel(Zend_Registry::get('translate')->_('cart_phone'));
        $phoneb->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]'));
        $phoneb->setRequired(true);

//		$faxb = new Zend_Form_Element_Text('faxb');
//		$faxb->setLabel(Zend_Registry::get('translate')->_('cart_fax'));
//		$faxb->setAttribs(array('maxlength'=>'120'));
//		$faxb->setRequired(false);

        $emailb = new Zend_Form_Element_Text('emailb');
        $emailb->setLabel(Zend_Registry::get('translate')->_('user_email'));
        $emailb->setRequired(true);
        $emailb->addFilter('StringTrim');
        $validateEmailAddress = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS);
        try {
            $validateEmailAddress->setValidateMx(true);
        } catch (Exception $e) {
        }
        $emailb->setAttribs(array('maxlength' => '100', 'class' => 'validate[required,custom[email]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $emailb->addValidators(array($validateEmailAddress));

        $comments = new Zend_Form_Element_Textarea('comments');
        $comments->setLabel(Zend_Registry::get('translate')->_('cart_comments'));
        $comments->setAttribs(array());
        $comments->setRequired(false);

//		$salutations = new Zend_Form_Element_Select('salutations');
//		$salutations->setLabel(Zend_Registry::get('translate')->_('cart_salutation'));
//		$salutations->setRegisterInArrayValidator(false);
//		$salutationsOptions = array(null => 'Select');
//		$model = new Default_Model_Customerssalutation();
//		$select = $model->getMapper()->getDbTable()->select()
//				->order(array('id ASC'))
//				;
//		if(($result = $model->fetchAll($select))) {
//			foreach($result as $value) {
//				$salutationsOptions[$value->getName()] = $value->getName();
//			}
//			$salutations->addMultiOptions($salutationsOptions);
//			$salutations->setAttribs(array('class'=>'select validate[required]'));
//			$salutations->setRequired(true);
//			$salutations->setIgnore(true);
//		}
//
//		$firstnames = new Zend_Form_Element_Text('firstnames');
//		$firstnames->setLabel(Zend_Registry::get('translate')->_('cart_first_name'));
//		$firstnames->setRequired(true);
//		$firstnames->addFilter('StringTrim');
//		$firstnames->addValidator(new Zend_Validate_Alpha(true));
//		$firstnames->addValidator(new Zend_Validate_StringLength(3,45));
//		$firstnames->setAttribs(array('maxlength'=>'45', 'class'=>'validate[required]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
//
//		$lastnames = new Zend_Form_Element_Text('lastnames');
//		$lastnames->setLabel(Zend_Registry::get('translate')->_('cart_last_name'));
//		$lastnames->setRequired(true);
//		$lastnames->addFilter('StringTrim');
//		$lastnames->addValidator(new Zend_Validate_Alpha(true));
//		$lastnames->addValidator(new Zend_Validate_StringLength(3,45));
//		$lastnames->setAttribs(array('maxlength'=>'45', 'class'=>'validate[required]', 'autocomplete'=>'off', 'oncontextmenu'=>'return false', 'ondrop'=>'return false', 'onpaste'=>'return false'));
//
//		$addresss = new Zend_Form_Element_Text('addresss');
//		$addresss->setLabel(Zend_Registry::get('translate')->_('cart_address'));
//		$addresss->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$addresss->setRequired(true);
//
//		$countys = new Zend_Form_Element_Text('countys');
//		$countys->setLabel(Zend_Registry::get('translate')->_('cart_county'));
//		$countys->setAttribs(array('class'=>'validate[required]', 'autocomplete'=>'off'));
//		$countys->setRequired(true);
//
//		$citys = new Zend_Form_Element_Text('citys');
//		$citys->setLabel(Zend_Registry::get('translate')->_('cart_town_city'));
//		$citys->setAttribs(array('class'=>'validate[required,minSize[1],maxSize[120]]', 'autocomplete'=>'off'));
//		$citys->setRequired(true);		
//
//		$zips = new Zend_Form_Element_Text('zips');
//		$zips->setLabel(Zend_Registry::get('translate')->_('cart_zip_postal'));
//		$zips->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$zips->setRequired(true);
//
//		$phones = new Zend_Form_Element_Text('phones');
//		$phones->setLabel(Zend_Registry::get('translate')->_('cart_phone'));
//		$phones->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required,minSize[1],maxSize[120]]'));
//		$phones->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('cart_continue'));
        $submit->setAttribs(array('class' => 'blue-big mb20'));
        $submit->setIgnore(true);

//		$this->addElements(array($formorder, $productsCost, $vat, $tax, $discount, $ramburs, $totalCost, $payment,
//			$password, $tbRePassword, $email, $tbReEmail,
//			$person, $institution, $fiscalcode, $traderegister, $bank, $ibancode, $function, $department, $comments,
//			$salutationb, $firstnameb, $lastnameb, $cnpb, $seriab, $addressb, $countyb, $cityb, $zipb, $phoneb, $faxb, $emailb,
//			$salutations, $firstnames, $lastnames, $addresss, $countys, $citys, $zips, $phones,
//			$submit
//		));
        $this->addElements(array($formorder, $productsCost, $vat, $tax, $discount, $ramburs, $totalCost, $payment, $comments,
            $firstnameb, $lastnameb, $addressb, $cityb, $phoneb, $emailb,
            $submit
        ));
    }

    public function plugincomplete($properties)
    {
        if ($properties != null) {
            if (!empty($properties['email'])) {
                $this->emailb->setValue($properties['email']);
//				$this->tbReEmail->setValue($properties['email']);
            }
            if (!empty($properties['first_name'])) {
//			   $this->firstnames->setValue($properties['first_name']);
                $this->firstnameb->setValue($properties['first_name']);
            }
            if (!empty($properties['last_name'])) {
//				$this->lastnames->setValue($properties['last_name']);
                $this->lastnameb->setValue($properties['last_name']);
            }
        }
    }


    public function setting()
    {
        $auth = Zend_Auth::getInstance();
        $authAccount = $auth->getStorage()->read();
        if (null != $authAccount && is_object($authAccount)) {
            $customerId = $authAccount->getId();
            if (null != $customerId) {
                $customer = new Default_Model_AdminUser();
                $customer->find($customerId);
                if (null != $customer->getId()) {
//					$this->person->setValue($customer->getPerson());
//					$this->salutationb->setValue($customer->getSalutation());
                    $this->firstnameb->setValue($customer->getFirstname());
                    $this->lastnameb->setValue($customer->getLastname());
//					$this->cnpb->setValue($customer->getCnp());
//					$this->seriab->setValue($customer->getSeria());
                    $this->addressb->setValue($customer->getAddress());
//					$this->countyb->setValue($customer->getCounty());
                    $this->cityb->setValue($customer->getCity());
//					$this->zipb->setValue($customer->getZip());
                    $this->phoneb->setValue($customer->getPhone());
//					$this->faxb->setValue($customer->getFax());
                    $this->emailb->setValue($customer->getEmail());

//					$this->institution->setValue($customer->getInstitution());
//					$this->fiscalcode->setValue($customer->getFiscalcode());
//					$this->traderegister->setValue($customer->getTraderegister());
//					$this->bank->setValue($customer->getBank());
//					$this->ibancode->setValue($customer->getIbancode());
//					$this->function->setValue($customer->getFunction());
//					$this->department->setValue($customer->getDepartment());

//					$this->addresss->setValue($customer->getAddressS());
//					$this->countys->setValue($customer->getCountyS());
//					$this->citys->setValue($customer->getCityS());
//					$this->zips->setValue($customer->getZipS());
//					$this->phones->setValue($customer->getPhoneS());
                }
            }
        }
    }
}
