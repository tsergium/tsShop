<?php

class Admin_Form_Groups extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmFeeds'));
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name');
        $name->setAttribs(array('maxlength' => '120', 'class' => 'validate[required,minSize[1],maxSize[120]]', 'style' => 'width:250px;'));
        $name->setRequired(true);

        $tax = new Zend_Form_Element_Select('taxId');
        $tax->setLabel('Tax');
        $tax->setRegisterInArrayValidator(false);
        $taxOptions = array(null => 'Select');

        $taxes = new Default_Model_Taxes();
        $select = $taxes->getMapper()->getDbTable()->select()
            ->order(array('name ASC'));
        if ($taxe = $taxes->fetchAll($select)) {
            foreach ($taxe as $taxes) {
                $taxOptions[$taxes->getId()] = $taxes->getName();
            }
            $tax->addMultiOptions($taxOptions);
            $tax->setAttribs(array('class' => 'select validate[required]', 'style' => 'width:258px;'));
            $tax->setRequired(true);
        }

        $discount = new Zend_Form_Element_Select('discountId');
        $discount->setLabel('Discount');
        $discount->setRegisterInArrayValidator(false);
        $discountOptions = array(null => 'Select');

        $discounts = new Default_Model_Discounts();
        $select = $discounts->getMapper()->getDbTable()->select()
            ->order(array('name ASC'));
        if ($disc = $discounts->fetchAll($select)) {
            foreach ($disc as $discounts) {
                $discountOptions[$discounts->getId()] = $discounts->getName();
            }
            $discount->addMultiOptions($discountOptions);
            $discount->setAttribs(array('class' => 'select validate[required]', 'style' => 'width:258px;'));
            $discount->setRequired(true);
        }

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Submit');
        $submit->setAttribs(array('class' => 'button1'));
        $submit->setIgnore(true);

        $this->addElements(array($name, $tax, $discount, $submit));
    }

    public function edit(Default_Model_Groups $group)
    {
        $this->name->setValue($group->getName());
        $this->taxId->setValue($group->getTaxId());
        $this->discountId->setValue($group->getDiscountId());
        $this->submit->setValue('Modifica');
    }

}
