<?php
class ContactController extends Zend_Controller_Action
{
    public function init()
    {
    	/* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

	public function indexAction()
    {
		$institution = $this->view->company[0]->getInstitution();
		$emailcompany = $this->view->company[0]->getEmail();

		$form = new Default_Form_ContactUs();
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/ContactUs.phtml'))));
		$this->view->form = $form;

		if($this->getRequest()->isPost()) {			
            if($form->isValid($this->getRequest()->getPost())) {				
				$message = '<table border="0" cellpadding="0" cellspacing="0">';
				$message.=		'<tr>';
				$message.=			'<th align="left">Nume: </th>';
				$message.=			'<td align="left">'.$this->getRequest()->getPost('fullname').'</td>';
				$message.=		'</tr>';
			
				$message.=		'<tr>';
				$message.=			'<th align="left">Email: </th>';
				$message.=			'<td align="left">'.$this->getRequest()->getPost('email').'</td>';
				$message.=		'</tr>';
				$message.=		'<tr>';
				$message.=			'<th align="left">Subiect: </th>';
				$message.=			'<td align="left">'.$this->getRequest()->getPost('subject').'</td>';
				$message.=		'</tr>';
				$message.=		'<tr>';
				$message.=			'<th align="left">Mesaj: </th>';
				$message.=			'<td align="left">'.$this->getRequest()->getPost('comments').'</td>';
				$message.=		'</tr>';
				$message.=	'</table>';


				$mail = new Zend_Mail();
				$mail->setFrom($emailcompany, $institution);
				$mail->setSubject(Zend_Registry::get('translate')->_('contact_us_table_comments'));
				$mail->setBodyHtml($message);
				$mail->addTo($emailcompany);
				if($mail->send()) {
					$this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>".Zend_Registry::get('translate')->_('contact_us_index_email_success').'</p></div>');
				} else {
					$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('contact_us_index_email_error').'</p></div>');
				}
            }
	    	$this->_redirect('/');
        }
	}
}