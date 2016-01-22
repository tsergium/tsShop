<?php

class Admin_Form_Login extends Zend_Form
{
    function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

        // Add an email element
        $this->addElement('text', 'tbUser', array(
            'label' => 'Your username',
            'required' => true,
            'filters' => array('StringTrim'),
            'attribs' => array(),
            'validators' => array(
                'alnum',
                array('stringLength', false, array(3, 20)),
            )
        ));

        // Add an password element
        $this->addElement('password', 'tbPass', array(
            'label' => 'Your password',
            'attribs' => array(),
            'required' => true,
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'attribs' => array('style' => 'margin-left: 41px;'),
            'label' => 'Sign in',
        ));

    }
}
