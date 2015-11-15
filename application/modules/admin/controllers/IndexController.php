<?php
class Admin_IndexController extends Zend_Controller_Action
{
	public function init()
	{
		$auth = Zend_Auth::getInstance();
		$authAccount = $auth->getStorage()->read();
		if(null != $authAccount) {
			if(null != $authAccount->getId()) {
				if($authAccount->getRoleId() != '1' && $authAccount->getRoleId() != '3') {
					$this->_helper->layout->disableLayout();

					$auth = Zend_Auth::getInstance();
					if($auth->hasIdentity()) {
						$auth->clearIdentity();
					}
					$this->_redirect('/admin/auth/login');
				}
			}
		}
		/* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
	}

	public function indexAction()
	{
		//change password
		$userAdmin = new Default_Model_AdminUser();
		$select = $userAdmin->getMapper()->getDbTable()->select()
						->limit('1');
		if(($result = $userAdmin->fetchAll($select))) {
			$user = $result['0'];
			$formpass = new Admin_Form_Editpassword;
			$formpass->editpassword($user);
			$formpass->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/EditPassword.phtml'))));
			$this->view->form = $formpass;
			if($this->getRequest()->isPost()) {
				if($this->getRequest()->getParam('changepass') != null && $this->getRequest()->getParam('changepass')=='yes') {
					if($formpass->isValid($this->getRequest()->getPost())) {
						$oldpassword = md5($this->getRequest()->getParam('oldPassword'));
						$new_password = md5($this->getRequest()->getParam('password'));
						if($oldpassword == $user->getPassword()) {
							$user->setPassword($new_password);
							if($user->save()) {
								$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('user_edit_password_success').'</div>');
							} else {
								$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('user_edit_password_error').'</div>');
							}
							$this->_redirect('/admin/index/index');
						} else {
							$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('user_edit_old_password_error').'</div>');
							$this->_redirect('/admin/index/index');
						}
					}
				}
			}
		}

		//company details
		$company = new Default_Model_Company();
		$company->find('1');
		$this->view->company = $company;
	}

	public function editcompanyAction()
	{
		$id = $this->getRequest()->getParam('id');
    	$model = new Default_Model_Company();
    	if($model->find($id)) {
	    	$form = new Admin_Form_Company();
			$form->editCompany($model);
			$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/Company.phtml'))));
    		$this->view->form = $form;
			if($this->getRequest()->isPost()) {
				 if($form->isValid($this->getRequest()->getPost())) {
	            	$model->setOptions($form->getValues());
	            	if($model->save()) {
		           		$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('settings_edit_company_success').'</div>');
						$this->_redirect('/admin/index/index/companydetails');
	            	} else {
		           		$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('settings_edit_company_error_1').'</div>');
						$this->_redirect('/admin/index/index/editcompany/id/'.$model->getId());
	            	}
	            }
	        }
    	} else {
       		$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('settings_edit_company_error_2').'</div>');
	   		$this->_redirect('/admin/index/index');
    	}
	}
}



