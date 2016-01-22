<?php

class Default_Form_AuthLogin extends Zend_Form
{
    function init()
    {
        $this->setMethod('post');
        $this->addAttribs(array('id' => 'frmAuthLogIn'));
        $this->setAction('/iframe/login');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Nume utilizator');
        $username->setRequired(true);
        $username->setAttribs(array('class' => 'f2 validate[required]'));

        $password = new Zend_Form_Element_Password('passwordLogare');
        $password->setLabel('Parola');
        $password->setRequired(true);
        $password->setAttribs(array('class' => 'f2 validate[required]'));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('logare');
        $submit->setIgnore(true);
        $submit->setAttribs(array('class' => 'button1'));

        $facebooksession = new Zend_Form_Element_Hidden('facebooksession');

        $this->addElements(array($username, $password, $submit));
    }
}
