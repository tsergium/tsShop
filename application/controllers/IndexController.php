<?php
class IndexController extends Zend_Controller_Action
{
	public function init()
    {
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->message = $this->_flashMessenger->getMessages();
    }

	public function indexAction()
	{
		$model = new Default_Model_Product();
		$select = $model->getMapper()->getDbTable()->select()
		    	->from(array('p'=>'ts_products'))
		    	->join(array('pca'=>'ts_products_categ_asoc'), 'p.id = pca.productId' ,array('pcaId'=>'pca.id'))
		    	->join(array('ppa'=>'ts_products_promotion_asoc'), 'p.id = ppa.productId' ,array('ppaId'=>'ppa.id'))
				->where('p.status = ?', '1')
				->where('ppa.promotionId = ?', '4')
				->group('p.id')
				->order('p.created DESC')
				->limit(9)
				->setIntegrityCheck(false);
		$products = $model->fetchAll($select);
		if(null != $products){
			$this->view->result = $products;
		}
	}

	public function newsletterAction()
	{

		$email = $this->getRequest()->getParam('emailNewsletter');
		$form = new Default_Form_Newsletter();
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/Newsletter.phtml'))));

		if($this->getRequest()->isPost()) {
			if($form->isValid($this->getRequest()->getPost())) {

				$result = $this->getRequest()->getPost();
				$model = new Default_Model_NewsletterSubscribers();
				$model->setOptions($form->getValues());
				$model->setEmail($result['emailNewsletter']);
				$model->setStatus('0');
				$unsubscribe = substr(md5(uniqid(mt_rand(), true)),0,10);
				$model->setUnsubscribe($unsubscribe);

                // Generate coupon code LDL-([0-9]{6})
                $couponCode = $this->generateCouponCode();
                $couponModel = new Default_Model_Coupon();
                $couponModel->setCode($couponCode);
                $couponModel->setStatus('active');
                $saveStatus = $couponModel->save();

				//send an activation email
				$subscribersEmail = new Default_Model_Templates();
				$subscribersEmail->find('subscribersEmail');
				$subject = $subscribersEmail->getSubject();
				$message = $subscribersEmail->getValue();

				//$activationlink='<a href="'.$_SERVER['SERVER_NAME'].'/index/activation?code='.$unsubscribe.'">'.Zend_Registry::get('translate')->_('index_activate').'</a>';
				//$unsubscribelink='<a href="'.$_SERVER['SERVER_NAME'].'/index/unsubscribe?code='.$unsubscribe.'">Dezabonare</a>';
				$activationlink='<a href="http://www.lenjerie-de-lux.ro/index/activation?code='.$unsubscribe.'">'.Zend_Registry::get('translate')->_('index_activate').'</a>';
				$unsubscribelink='<a href="http://www.lenjerie-de-lux.ro/index/unsubscribe?code='.$unsubscribe.'">Dezabonare</a>';

				$message = str_replace('{'.'$'.'email}', $email, $message);
				$message = str_replace('{'.'$'.'activationlink}', $activationlink, $message);
				$message = str_replace('{'.'$'.'unsubscribelink}', $unsubscribelink, $message);
                $message = str_replace('{'.'$'.'coupon}', Zend_Registry::get('translate')->_('index_newsletter_coupon'), $message);
                $message = str_replace('{'.'$'.'couponCode}', $couponCode, $message);

				$mail = new Zend_Mail();
				$mail->setFrom('no-reply@'.$_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST']);
				$mail->setSubject($subject);
				$mail->setBodyHtml($message);
				$mail->addTo($email);
				$mail->send();

				if($model->save()) {
                    $this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>".Zend_Registry::get('translate')->_('index_newsletter_success')."</p><p>" . Zend_Registry::get('translate')->_('index_newsletter_coupon') . ": $couponCode</p></div>");
					$this->_redirect('/index/index/msg/success/#newsl');
				} else {
					$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('index_newsletter_error_1').'</p></div>');
					$this->_redirect('/index/index/msg/fail/#newsl');
				}
			}
			$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('index_newsletter_error_2').'</p></div>');
			$this->_redirect('/index/index/msg/fail/#newsl');
		}
	}

	public function activationAction()
	{
		$activationcode = $this->getRequest()->getParam('code');
		if($activationcode) {
			$model = new Default_Model_NewsletterSubscribers();
			$select = $model->getMapper()->getDbTable()->select()
					->where('`unsubscribe` = ?', $activationcode);
			if(($result = $model->fetchAll($select))) {
				$model2 = new Default_Model_NewsletterSubscribers();
				if($model2->find($result[0]->getId())) {
					$model2->setStatus('1');
					if($model2->save()){
						$this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>Inscrierea la newsletter a fost acivata cu succes</p></div>");
					} else {
						$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare acivare newsletter!</p></div>");
					}
				}
			} else {
				$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>Eroare acivare newsletter!</p></div>");
			}
		}
		$this->_redirect('/');
	}

	public function unsubscribeAction()
	{
		$unsubscribe = $this->getRequest()->getParam('code');
		if($unsubscribe) {
			$model = new Default_Model_NewsletterSubscribers();
			$select = $model->getMapper()->getDbTable()->select()
					->where('`unsubscribe` = ?', $unsubscribe);
			if(($result = $model->fetchAll($select))) {
				$model2 = new Default_Model_NewsletterSubscribers();
				if($model2->find($result[0]->getId())) {
					$model2->setStatus('0');
					if($model2->save()) {
						$this->_flashMessenger->addMessage("<div class='message success'><p><strong></strong>".Zend_Registry::get('translate')->_('index_unsubscribe_success').'</p></div>');
					} else {
						$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('index_unsubscribe_error').'</p></div>');
					}
				}
			} else {
				$this->_flashMessenger->addMessage("<div class='message error'><p><strong></strong>".Zend_Registry::get('translate')->_('index_activation_error').'</p></div>');
			}
		}
		$this->_redirect('/');
	}

    /**
     * Generate coupon code LDL-([0-9]{6})
     */
    private function generateCouponCode()
    {
        $coupon = 'LDL-' . rand(0, 99) . substr(time(), -4);
        return $coupon;
    }
}