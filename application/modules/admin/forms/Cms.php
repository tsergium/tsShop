<?php
class Admin_Form_Cms extends Zend_Form
{
	function init()
	{
		// Set the method for the display form to POST
	}

	function pageAdd()
	{
        $this->setMethod('post');
        $this->addAttribs(array('id'=>'frmPage'));
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

//		$title = new Zend_Form_Element_Text('title');
//        $title->setLabel(Zend_Registry::get('translate')->_('cms_add_page_table_title'));
//        $title->setAttribs(array('maxlength'=>'120', 'class'=>'validate[required]'));
//		$title->setRequired(true);
//		$this->addElement($title);

//		$url = new Zend_Form_Element_Text('url');
//        $url->setLabel(Zend_Registry::get('translate')->_('cms_add_page_table_url'));
//        $url->setAttribs(array('maxlength'=>'40', 'class'=>'validate[required]'));
//		$url->setRequired(true);
//		$this->addElement($url);

		$headline = new Zend_Form_Element_Text('headline');
        $headline->setLabel(Zend_Registry::get('translate')->_('cms_add_page_table_head_line'));
        $headline->setAttribs(array());
		$this->addElement($headline);

		$content = new Zend_Form_Element_Textarea('content');
        $content->setLabel(Zend_Registry::get('translate')->_('cms_add_page_table_content'));
        $content->setAttribs(array());
		$this->addElement($content);


		$submit = new Zend_Form_Element_Submit('submit');
        $submit->setValue(Zend_Registry::get('translate')->_('cms_add_page_table_submit'));
        $submit->setAttribs(array('class'=>'button1'));
        $submit->setIgnore(true);
		$this->addElement($submit);
	}

	function pageEdit(Default_Model_Cms $value)
	{		
		$this->headline->setValue($value->getHeadline());
		$this->content->setValue($value->getContent());
		$this->submit->setValue(Zend_Registry::get('translate')->_('cms_add_page_table_modify'));
	}

}
