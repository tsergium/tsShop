<?php

class Default_Form_Search extends Zend_Form
{
    function init()
    {
        $this->setMethod('post');
        $this->setAction(WEBPAGE_ADDRESS . '/products/search');
        $this->addAttribs(array('id' => 'frmSearch'));

        $term = new Zend_Form_Element_Text('term');
        $term->setValue('Cauta pe Lenjerie de Lux');
        $term->setAttribs(array('onfocus' => "javascript:if(this.value=='Cauta pe Lenjerie de Lux') this.value=''; this.style.color='#818181';", 'onblur' => "javascript:if(this.value=='') this.value='Cauta pe Lenjerie de Lux'; this.style.color='#818181';", 'class' => 'f1'));

        $submit = new Zend_Form_Element_Image('submit');
        $submit->setValue(WEBPAGE_ADDRESS . '/images/bt_cauta.gif');
        $submit->setIgnore(true);

        $this->addElements(array($term, $submit));
    }
}
