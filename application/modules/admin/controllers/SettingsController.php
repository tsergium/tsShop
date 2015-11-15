<?php
class Admin_SettingsController extends Zend_Controller_Action
{
    public function init()
    {
    	/* Initialize action controller here */
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
		// BEGIN: SLIDE EFFECT
		$model = new Default_Model_Setting();
		$select = $model->getMapper()->getDbTable()->select()
				->where('const = ?', 'slideEffect');
		$result = $model->fetchAll($select);
		if(null != $result){
			$this->view->slideEffect = $result[0];
			
			$model = new Default_Model_SettingValue();
			$select = $model->getMapper()->getDbTable()->select()
					->where('settingId = ?', $result[0]->getId())
					->where('value != ?', $result[0]->getValue());
			$result = $model->fetchAll($select);
			if(null != $result){
				$this->view->slideEffectValues = $result;
			}
		}
		// END: SLIDE EFFECT
    }
    
    public function slideAction()
    {
    	$slideEffectValue = $this->getRequest()->getParam('slideEffectValue');
    	if(null != $slideEffectValue){
    		$model = new Default_Model_Setting();
    		$model->find(9); // Hardcoded id
    		$model->setValue($slideEffectValue);
    		if($model->save()){
    			$this->_flashMessenger->addMessage('<div class="mess-true">Setarile au fost salvate!</div>');
    		}else{
    			$this->_flashMessenger->addMessage('<div class="mess-false">Setarile nu au fost salvate!</div>');
    		}
    	}
    	$this->_redirect('/admin/settings');
    }
    
    public function paymentmethodsAction()
    {
	$model  = new Default_Model_Delivery();
	$select = $model->getMapper()->getDbTable()->select()
							;
	$result = $model->fetchAll($select);
	if(null != $result){
	    $this->view->result = $result;
	}   
	
	if($this->getRequest()->isPost()) {
	   $post = $this->getRequest()->getPost();
	   if(!empty($post['delivery'])){
	       $err = false;
	       foreach ($post['delivery'] as $key => $value) {
		   $delivery = new Default_Model_Delivery();
		   $delivery->find($key);
		   $delivery->setCost($value);
		    if($delivery->save()){
			   ; 
		    }else{
		       $err = true;
		    }		   
	       }
	       if($err == false){
    			$this->_flashMessenger->addMessage('<div class="mess-true">Setarile au fost salvate!</div>');
    		}else{
    			$this->_flashMessenger->addMessage('<div class="mess-false">Setarile au fost salvate!</div>');
    		}
	   }
	   $this->_redirect('admin/settings/paymentmethods');
	}
    }
}