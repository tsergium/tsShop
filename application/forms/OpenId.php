<?php

class Default_Form_OpenId extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
//		$this->setAction('/example-1_2.php');
        $this->addAttribs(array('id' => 'frmOpenId'));

        $openid_identifier = new Zend_Form_Element_Text('openid_identifier');
        $openid_identifier->setLabel('OpenID Login:');
        $openid_identifier->setRequired(true);
        $openid_identifier->setAttribs(array('style' => 'width:255px;'));


        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue('Log in');
        $submit->setIgnore(true);

        $this->addElements(array($openid_identifier, $submit));

    }
}
