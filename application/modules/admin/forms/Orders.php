<?php

class Admin_Form_Orders extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmOrder'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $productsCost = new Zend_Form_Element_Text('productsCost');
        $productsCost->setLabel(Zend_Registry::get('translate')->_('sales_add_table_products_cost'));
        $productsCost->setAttribs(array('style' => 'width:250px;'));
        $productsCost->setRequired(false);
        $this->addElement($productsCost);

        $vat = new Zend_Form_Element_Text('vat');
        $vat->setLabel(Zend_Registry::get('translate')->_('sales_add_table_vat'));
        $vat->setAttribs(array('style' => 'width:250px;'));
        $vat->setRequired(false);
        $this->addElement($vat);

        $discount = new Zend_Form_Element_Text('discount');
        $discount->setLabel(Zend_Registry::get('translate')->_('sales_add_table_discount'));
        $discount->setAttribs(array('style' => 'width:250px;'));
        $discount->setRequired(false);
        $this->addElement($discount);

        $ramburs = new Zend_Form_Element_Text('ramburs');
        $ramburs->setLabel(Zend_Registry::get('translate')->_('sales_add_table_repayment'));
        $ramburs->setAttribs(array('style' => 'width:250px;'));
        $ramburs->setRequired(false);
        $this->addElement($ramburs);

        $totalCost = new Zend_Form_Element_Text('totalCost');
        $totalCost->setLabel(Zend_Registry::get('translate')->_('sales_add_table_total_cost'));
        $totalCost->setAttribs(array('style' => 'width:250px;'));
        $totalCost->setRequired(false);
        $this->addElement($totalCost);

//		$payment = new Zend_Form_Element_Select('payment');
//		$payment->setLabel(Zend_Registry::get('translate')->_('sales_add_table_payment_method'));
//		$payment->setRegisterInArrayValidator(false);
//		$paymentOptions = array(null => 'Select');
//		$paym = new Default_Model_Payments();
//		$select = $paym->getMapper()->getDbTable()->select()
//			    ->order(array('id ASC'));
//		if($payments = $paym->fetchAll($select)) {
//			foreach ($payments as $paym) {
//				$paymentOptions[$paym->getMethod()] = Zend_Registry::get('translate')->_('sales_edit_'.strtolower(str_replace(" ", "_", $paym->getMethod())));
//			}
//			$payment->addMultiOptions($paymentOptions);
//			$payment->setAttribs(array('class'=>'select validate[required]', 'style'=>'width:258px;'));
//			$payment->setRequired(true);
//			$payment->setIgnore(true);
//		}
//		$this->addElement($payment);

        $person = new Zend_Form_Element_Radio('person');
        $person->setLabel('');
        $person->setRequired(true);
        $optionPerson = array(Zend_Registry::get('translate')->_('sales_add_table_individual_person'), Zend_Registry::get('translate')->_('sales_add_table_legal_person'));
        $person->addMultiOptions($optionPerson);
        $person->setValue($optionPerson);
        $person->addValidator(new Zend_Validate_InArray(array_keys($optionPerson)));
        $person->setSeparator('');
        $person->setAttribs(array('class' => 'validate[required], personx'));
        $this->addElement($person);

        $salutationb = new Zend_Form_Element_Select('salutationb');
        $salutationb->setLabel(Zend_Registry::get('translate')->_('sales_add_table_salutation'));
        $salutationb->setRegisterInArrayValidator(false);
        $salutationbOptions = array(null => 'Select');
        $salb = new Default_Model_Customerssalutation();
        $select = $salb->getMapper()->getDbTable()->select()
            ->order(array('id ASC'));
        if ($salutb = $salb->fetchAll($select)) {
            foreach ($salutb as $salb) {
                $salutationbOptions[$salb->getName()] = $salb->getName();
            }
            $salutationb->addMultiOptions($salutationbOptions);
            $salutationb->setAttribs(array('class' => 'select validate[required]', 'style' => 'width:258px;'));
            $salutationb->setRequired(true);
            $salutationb->setIgnore(true);
        }
        $this->addElement($salutationb);

        $firstnameb = new Zend_Form_Element_Text('firstnameb');
        $firstnameb->setLabel(Zend_Registry::get('translate')->_('sales_add_table_first_name'));
        $firstnameb->setRequired(true);
        $firstnameb->addFilter('StringTrim');
        $firstnameb->addValidator(new Zend_Validate_Alpha(true));
        $firstnameb->addValidator(new Zend_Validate_StringLength(3, 45));
        $firstnameb->setAttribs(array('maxlength' => '45', 'style' => 'width:250px;', 'class' => 'validate[required]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $this->addElement($firstnameb);

        $lastnameb = new Zend_Form_Element_Text('lastnameb');
        $lastnameb->setLabel(Zend_Registry::get('translate')->_("sales_add_table_last_name"));
        $lastnameb->setRequired(true);
        $lastnameb->addFilter('StringTrim');
        $lastnameb->addValidator(new Zend_Validate_Alpha(true));
        $lastnameb->addValidator(new Zend_Validate_StringLength(3, 45));
        $lastnameb->setAttribs(array('maxlength' => '45', 'style' => 'width:250px;', 'class' => 'validate[required]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $this->addElement($lastnameb);

        $cnpb = new Zend_Form_Element_Text('cnpb');
        $cnpb->setLabel(Zend_Registry::get('translate')->_("sales_add_table_cnp"));
        $cnpb->setAttribs(array('maxlength' => '16', 'class' => 'validate[minSize[1],maxSize[16]]', 'style' => 'width:250px;'));
        $cnpb->setRequired(false);
        $this->addElement($cnpb);

        $seriab = new Zend_Form_Element_Text('seriab');
        $seriab->setLabel(Zend_Registry::get('translate')->_("sales_add_table_serie"));
        $seriab->setAttribs(array('maxlength' => '16', 'class' => 'validate[minSize[1],maxSize[16]]', 'style' => 'width:250px;'));
        $seriab->setRequired(false);
        $this->addElement($seriab);

        $institution = new Zend_Form_Element_Text('institution');
        $institution->setLabel(Zend_Registry::get('translate')->_('sales_add_table_company'));
        $institution->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $institution->setRequired(false);
        $this->addElement($institution);

        $fiscalcode = new Zend_Form_Element_Text('fiscalcode');
        $fiscalcode->setLabel(Zend_Registry::get('translate')->_('sales_add_table_fiscal_code'));
        $fiscalcode->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $fiscalcode->setRequired(false);
        $this->addElement($fiscalcode);

        $traderegister = new Zend_Form_Element_Text('traderegister');
        $traderegister->setLabel(Zend_Registry::get('translate')->_('sales_add_table_trade_register'));
        $traderegister->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $traderegister->setRequired(false);
        $this->addElement($traderegister);

        $bank = new Zend_Form_Element_Text('bank');
        $bank->setLabel(Zend_Registry::get('translate')->_('sales_add_table_bank'));
        $bank->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $bank->setRequired(false);
        $this->addElement($bank);

        $ibancode = new Zend_Form_Element_Text('ibancode');
        $ibancode->setLabel(Zend_Registry::get('translate')->_('sales_add_table_iban_code'));
        $ibancode->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $ibancode->setRequired(false);
        $this->addElement($ibancode);

        $function = new Zend_Form_Element_Text('function');
        $function->setLabel(Zend_Registry::get('translate')->_('sales_add_table_function'));
        $function->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $function->setRequired(false);
        $this->addElement($function);

        $department = new Zend_Form_Element_Text('department');
        $department->setLabel(Zend_Registry::get('translate')->_('sales_add_table_department'));
        $department->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $department->setRequired(false);
        $this->addElement($department);

        $addressb = new Zend_Form_Element_Text('addressb');
        $addressb->setLabel(Zend_Registry::get('translate')->_('sales_add_table_address'));
        $addressb->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $addressb->setRequired(true);
        $this->addElement($addressb);

        $countyb = new Zend_Form_Element_Text('countyb');
        $countyb->setLabel(Zend_Registry::get('translate')->_('sales_add_table_county'));
        $countyb->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $countyb->setRequired(true);
        $this->addElement($countyb);

        $cityb = new Zend_Form_Element_Text('cityb');
        $cityb->setLabel(Zend_Registry::get('translate')->_('sales_add_table_city'));
        $cityb->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $cityb->setRequired(true);
        $this->addElement($cityb);

        $zipb = new Zend_Form_Element_Text('zipb');
        $zipb->setLabel(Zend_Registry::get('translate')->_('sales_add_table_zip'));
        $zipb->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $zipb->setRequired(true);
        $this->addElement($zipb);

        $phoneb = new Zend_Form_Element_Text('phoneb');
        $phoneb->setLabel(Zend_Registry::get('translate')->_('sales_add_table_phone'));
        $phoneb->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $phoneb->setRequired(true);
        $this->addElement($phoneb);

        $faxb = new Zend_Form_Element_Text('faxb');
        $faxb->setLabel(Zend_Registry::get('translate')->_('sales_add_table_fax'));
        $faxb->setAttribs(array('maxlength' => '120', 'style' => 'width:250px;'));
        $faxb->setRequired(false);
        $this->addElement($faxb);

        $emailb = new Zend_Form_Element_Text('emailb');
        $emailb->setLabel(Zend_Registry::get('translate')->_('sales_add_table_email'));
        $emailb->setAttribs(array('maxlength' => '120', 'style' => 'width:250px;'));
        $emailb->setRequired(true);
        $this->addElement($emailb);

        $comments = new Zend_Form_Element_Textarea('comments');
        $comments->setLabel(Zend_Registry::get('translate')->_('sales_add_table_comments'));
        $comments->setAttribs(array('maxlength' => '120', 'style' => 'width:250px; height:100px;'));
        $comments->setRequired(false);
        $this->addElement($comments);

        $salutations = new Zend_Form_Element_Select('salutations');
        $salutations->setLabel(Zend_Registry::get('translate')->_('sales_add_table_salutation'));
        $salutations->setRegisterInArrayValidator(false);
        $salutationsOptions = array(null => 'Select');
        $sals = new Default_Model_Customerssalutation();
        $select = $sals->getMapper()->getDbTable()->select()
            ->order(array('id ASC'));
        if ($saluts = $sals->fetchAll($select)) {
            foreach ($saluts as $sals) {
                $salutationsOptions[$sals->getName()] = $sals->getName();
            }
            $salutations->addMultiOptions($salutationsOptions);
            $salutations->setAttribs(array('class' => 'select validate[required]', 'style' => 'width:258px;'));
            $salutations->setRequired(true);
            $salutations->setIgnore(true);
        }
        $this->addElement($salutations);

        $firstnames = new Zend_Form_Element_Text('firstnames');
        $firstnames->setLabel(Zend_Registry::get('translate')->_('sales_add_table_first_name'));
        $firstnames->setRequired(true);
        $firstnames->addFilter('StringTrim');
        $firstnames->addValidator(new Zend_Validate_Alpha(true));
        $firstnames->addValidator(new Zend_Validate_StringLength(3, 45));
        $firstnames->setAttribs(array('maxlength' => '45', 'style' => 'width:250px;', 'class' => 'validate[required]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $this->addElement($firstnames);

        $lastnames = new Zend_Form_Element_Text('lastnames');
        $lastnames->setLabel(Zend_Registry::get('translate')->_('sales_add_table_last_name'));
        $lastnames->setRequired(true);
        $lastnames->addFilter('StringTrim');
        $lastnames->addValidator(new Zend_Validate_Alpha(true));
        $lastnames->addValidator(new Zend_Validate_StringLength(3, 45));
        $lastnames->setAttribs(array('maxlength' => '45', 'style' => 'width:250px;', 'class' => 'validate[required]', 'autocomplete' => 'off', 'oncontextmenu' => 'return false', 'ondrop' => 'return false', 'onpaste' => 'return false'));
        $this->addElement($lastnames);

        $addresss = new Zend_Form_Element_Text('addresss');
        $addresss->setLabel(Zend_Registry::get('translate')->_("sales_add_table_address"));
        $addresss->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $addresss->setRequired(true);
        $addresss->setFilters(array(new Zend_Filter_StringTrim(),));
        $addresssStringLength = new Zend_Validate_StringLength(1, 120);
        $this->addElement($addresss);

        $countys = new Zend_Form_Element_Text('countys');
        $countys->setLabel(Zend_Registry::get('translate')->_('sales_add_table_county'));
        $countys->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $countys->setRequired(true);
        $this->addElement($countys);

        $citys = new Zend_Form_Element_Text('citys');
        $citys->setLabel(Zend_Registry::get('translate')->_('sales_add_table_city'));
        $citys->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $citys->setRequired(true);
        $this->addElement($citys);

        $zips = new Zend_Form_Element_Text('zips');
        $zips->setLabel(Zend_Registry::get('translate')->_('sales_add_table_zip'));
        $zips->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $zips->setRequired(true);
        $this->addElement($zips);

        $phones = new Zend_Form_Element_Text('phones');
        $phones->setLabel(Zend_Registry::get('translate')->_('sales_add_table_phone'));
        $phones->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $phones->setRequired(true);
        $this->addElement($phones);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('sales_add_button_submit'));
        $submit->setAttribs(array());
        $submit->setIgnore(true);
        $this->addElement($submit);
    }

    public function editorders(Default_Model_Orders $orders)
    {
        // Set the method for the display form to POST
        $this->productsCost->setValue(strip_tags($orders->getProductscost()));
        $this->vat->setValue(strip_tags($orders->getVat()));
        $this->discount->setValue(strip_tags($orders->getDiscount()));
        $this->ramburs->setValue(strip_tags($orders->getRamburs()));
        $this->totalCost->setValue(strip_tags($orders->getTotalcost()));

        $this->payment->setValue(strip_tags($orders->getPayment()));

        $this->person->setValue(strip_tags($orders->getPerson()));

        $this->salutationb->setValue(strip_tags($orders->getSalutationb()));
        $this->firstnameb->setValue(strip_tags($orders->getFirstnameb()));
        $this->lastnameb->setValue(strip_tags($orders->getLastnameb()));
        $this->cnpb->setValue(strip_tags($orders->getCnpb()));
        $this->seriab->setValue(strip_tags($orders->getSeriab()));
        $this->addressb->setValue(strip_tags($orders->getAddressb()));
        $this->countyb->setValue(strip_tags($orders->getCountyb()));
        $this->cityb->setValue(strip_tags($orders->getCityb()));
        $this->zipb->setValue(strip_tags($orders->getZipb()));
        $this->phoneb->setValue(strip_tags($orders->getPhoneb()));
        $this->faxb->setValue(strip_tags($orders->getFaxb()));
        $this->emailb->setValue(strip_tags($orders->getEmailb()));

        $this->institution->setValue(strip_tags($orders->getInstitution()));
        $this->fiscalcode->setValue(strip_tags($orders->getFiscalcode()));
        $this->traderegister->setValue(strip_tags($orders->getTraderegister()));
        $this->bank->setValue(strip_tags($orders->getBank()));
        $this->ibancode->setValue(strip_tags($orders->getIbancode()));
        $this->function->setValue(strip_tags($orders->getFunction()));
        $this->department->setValue(strip_tags($orders->getDepartment()));

        $this->comments->setValue(strip_tags($orders->getComments()));

        $this->salutations->setValue(strip_tags($orders->getSalutations()));
        $this->firstnames->setValue(strip_tags($orders->getFirstnames()));
        $this->lastnames->setValue(strip_tags($orders->getLastnames()));
        $this->addresss->setValue(strip_tags($orders->getAddresss()));
        $this->countys->setValue(strip_tags($orders->getCountys()));
        $this->citys->setValue(strip_tags($orders->getCitys()));
        $this->zips->setValue(strip_tags($orders->getZips()));
        $this->phones->setValue(strip_tags($orders->getPhones()));

        $this->submit->setValue(Zend_Registry::get('translate')->_('sales_add_button_modify'));
    }
}