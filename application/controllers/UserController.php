<?php
class UserController extends Zend_Controller_Action
{
		
	public function init()
	{
		/* Initialize action controller here */
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->message = $this->_flashMessenger->getMessages();
		
		
	}

	public function indexAction()
	{
         
	}

	public function newAction()
	{
        $form = new Default_Form_Clienti();
        $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/clients/ClientsAdd.phtml'))));
        $this->view->form = $form;
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                $result=$this->getRequest()->getPost();
                $account=new Default_Model_Clients();
                $account->setOptions($form->getValues());
                $account->setPassword(md5($form->getValue('password')));
                $account->setCounty($result['county']);
                $account->setBankaccount($result['bankaccount']);
                try {
					$signupTemplate = new Default_Model_Templates();
					$signupTemplate->find('signUp');
					$activation_code = substr(md5(uniqid(mt_rand(), true)),0,10);
					$account->setActivationcode($activation_code);
					$account->setStatus('0');
					//
					$username 	= $account->getUsername();
					$password 	= $account->getPassword();
					$email 		= $account->getEmail();
					$firstname 	= $account->getFirstname();
					$lastname 	= $account->getLastname();
					$link 		= "<a href='http://".$_SERVER['SERVER_NAME']."/auth/activation?code=".$activation_code."'>Activare</a>";
					$signup		= $signupTemplate->getValue();


					$signup = str_replace("{"."$"."firstname}", $firstname, $signup);
					$signup = str_replace("{"."$"."lastname}", $lastname, $signup);
					$signup = str_replace("{"."$"."username}", $username, $signup);
					$signup = str_replace("{"."$"."password}", $password, $signup);
					$signup = str_replace("{"."$"."email}", $email, $signup);
					$signup = str_replace("{"."$"."activationlink}", $link, $signup);

					$companyEmail = $this->view->company[0]->getEmail();
					$companyName = $this->view->company[0]->getInstitution();
					
					$mail = new Zend_Mail();
					$mail->setFrom($companyEmail, $companyName.'('.$_SERVER['HTTP_HOST'].')');
					$mail->setSubject($signupTemplate->getSubject());
					$mail->setBodyHtml($signup);
					$mail->addTo($account->getEmail(), $account->getFirstname()." ".$account->getLastname());
					 try {
						$mail->send();
					 } catch(Zend_Exception $e) {
						$this->_flashMessenger->addMessage($e->getMessage());
					 }
					//news letter
					if(!empty($result['newsletter'])){

						$model = new Default_Model_NewsletterSubscribers();
						$model->setOptions($form->getValues());
						$model->setEmail($account->getEmail());
						$model->setStatus('0');
						$unsubscribe = substr(md5(uniqid(mt_rand(), true)),0,10);
						$model->setUnsubscribe($unsubscribe);

						//send an activation email
						$subscribersEmail = new Default_Model_Templates();
						$subscribersEmail->find('subscribersEmail');
						$subject = $subscribersEmail->getSubject();
						$message = $subscribersEmail->getValue();

						$activationlink='<a href="'.$_SERVER['SERVER_NAME'].'/index/activation?code='.$unsubscribe.'">'.Zend_Registry::get('translate')->_('index_activate').'</a>';
						$unsubscribelink='<a href="'.$_SERVER['SERVER_NAME'].'/index/unsubscribe?code='.$unsubscribe.'">Dezabonare</a>';

						$message = str_replace('{'.'$'.'email}', $email, $message);
						$message = str_replace('{'.'$'.'activationlink}', $activationlink, $message);
						$message = str_replace('{'.'$'.'unsubscribelink}', $unsubscribelink, $message);

						$mail2 = new Zend_Mail();
						$mail2->setFrom($companyEmail, $companyName.'('.$_SERVER['HTTP_HOST'].')');
						$mail2->setSubject($subject);
						$mail2->setBodyHtml($message);
						$mail2->addTo($account->getEmail());
						try {
							$mail2->send();
						 } catch(Zend_Exception $e) {
							$this->_flashMessenger->addMessage($e->getMessage());
						 }

						if($model->save()) {
							$this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>".Zend_Registry::get('translate')->_('index_newsletter_success').'</p></div>');
						} else {
							$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('index_newsletter_error_1').'</p></div>');
						}
					}
                        
                    if ($account->save()) {
                            $this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>Contul dumneavostra a fost creat cu succes. Va rugam verificati casuta postala pentru email-ul de confirmare.</p></div>");
                    } else {
                            $this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare creare cont!</p></div>");
                    }
                } catch(Zend_Exception $e) {
                        $this->_flashMessenger->addMessage($e->getMessage());
                }
                $this->_redirect('/user/new');
            }
        }       
	}

	public function myaccountAction()
	{
		$auth = Zend_Auth::getInstance();
		$authAccount = $auth->getStorage()->read();
		if(null != $authAccount) {
			if(null != $authAccount->getId()) {
				$user = new Default_Model_Clients();
				$user->find($authAccount->getId());
				$this->view->user = $user;
			}
		} else {
			$this->_redirect('/user/new');
		}
	}

	public function editaccountAction()
	{
		$redirPage = $this->getRequest()->getParam('page');
		$auth = Zend_Auth::getInstance();
		$authAccount = $auth->getStorage()->read();
		if(null != $authAccount) {
			if(null != $authAccount->getId()) {
				$user = new Default_Model_Clients();
				$user->find($authAccount->getId());
				$this->view->user = $user;
				$form = new Default_Form_Clienti();
				$form->edit($user);
				if(null != $redirPage){
					$form->setAction('/user/editaccount/page/order');
				}
				$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/clients/ClientsEdit.phtml'))));
				$this->view->formuser = $form;
				if($this->getRequest()->isPost()) {
					if($this->getRequest()->getParam('changepass') ==  null){
						if($form->isValid($this->getRequest()->getPost())) {
							$post = $this->getRequest()->getPost();
							$user->setOptions($form->getValues());
							$user->setBankaccount($post['bankaccount']);
							if($user->save()) {
								$this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>".Zend_Registry::get('translate')->_('user_edit_account_success').'</p></div>');
							} else {
								$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('user_edit_account_error').'</p></div>');
							}
							if(null != $redirPage){
								$this->_redirect('/cart');
							}else{
								$this->_redirect('/user/editaccount');
							}
						}
					}
				}

				$formpass = new Default_Form_Editpassword;
				$formpass->editpassword($user);
				$formpass->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/EditPassword.phtml'))));
				$this->view->formEditPassword = $formpass;
				if($this->getRequest()->isPost()) {
					if($this->getRequest()->getParam('changepass') != null && $this->getRequest()->getParam('changepass')=='yes') {
						if($formpass->isValid($this->getRequest()->getPost())) {
							$oldpassword = md5($this->getRequest()->getParam('oldPassword'));
							$new_password = md5($this->getRequest()->getParam('password'));
							if($oldpassword == $user->getPassword()) {
								$user->setPassword($new_password);
								if($user->save()){
									$this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>".Zend_Registry::get('translate')->_('user_edit_password_success').'</p></div>');
								} else {
									$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('user_edit_password_error').'</p></div>');
								}
								$this->_redirect('/user/editaccount');
							} else {
								$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('user_edit_old_password_error').'</p></div>');
								$this->_redirect('/user/editaccount');
							}
						}
					}
				}
			} else {
				$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('user_invalid_account_selected').'</p></div>');
			}
		} else {
			$this->_redirect('/user/new');
		}
	}

	public function ordersAction()
	{
		$auth = Zend_Auth::getInstance();
		$authAccount = $auth->getStorage()->read();
		if(null != $authAccount) {
			if(null != $authAccount->getId()) {
				$type = $this->getRequest()->getParam('type');
				$model = new Default_Model_Orders();
				$select = $model->getMapper()->getDbTable()->select();
						if($type){
							$select->where('status = ?', $type);
						}
						$select->where('customerId = ?', $authAccount->getId())
						->order('id DESC');
				if(($result = $model->fetchAll($select))) {				
					$paginator = Zend_Paginator::factory($result);
					$paginator->setItemCountPerPage(15);
					$paginator->setCurrentPageNumber($this->_getParam('page'));
					$paginator->setPageRange(5);
					$this->view->result = $paginator;
					$this->view->itemCountPerPage = $paginator->getItemCountPerPage();
					$this->view->totalItemCount = $paginator->getTotalItemCount();

					Zend_Paginator::setDefaultScrollingStyle('Sliding');
					Zend_View_Helper_PaginationControl::setDefaultViewPartial('_pagination.phtml');
				}
			}
		} else {
			$this->_redirect('/user/new');
		}
	}

	public function orderdetailsAction()
	{
		$auth = Zend_Auth::getInstance();
		$authAccount = $auth->getStorage()->read();
		if(null != $authAccount) {
			if(null != $authAccount->getId()) {
				$orderIdd = $this->getRequest()->getParam('orderId');
				$model = new Default_Model_Orders();
				$select = $model->getMapper()->getDbTable()->select()					
						->where('id = ?', $orderIdd)
						->where('customerId = ?', $authAccount->getId())
						->order('id DESC');
				if(($result = $model->fetchAll($select))) {
					$this->view->result = $result[0];
					//SELECT ORDER PRODUCTS
					$model = new Default_Model_OrderProducts();
					$select = $model->getMapper()->getDbTable()->select()
							->where('orderId = ?', $orderIdd)
							;
					if(($result = $model->fetchAll($select))) {
						$this->view->orderproducts = $result;
					}
				}
			}
		} else {
			$this->_redirect('/user/new');
		}
	}
}