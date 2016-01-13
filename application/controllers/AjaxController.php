<?php
class AjaxController extends Base_Controller_Action
{
    public function init()
    {
		parent::init();
		$this->ajaxInit();
    }

    /**
     * provides a validation for username being used
     */
	public function checkUsernameAction()
	{		
		$validateId		 = $this->getRequest()->getParam('fieldId');
		$validateValue	 = $this->getRequest()->getParam('fieldValue');		

		/* RETURN VALUE */
		$arrayToJs = array();
		$arrayToJs[0] = $validateId;

		$model = new Default_Model_Clients();
		$select = $model->getMapper()->getDbTable()->select()
				->where('username = ?', $validateValue);
		if($model->fetchAll($select)) {
			$arrayToJs[1] = false;
		} else {
			$arrayToJs[1] = true;
		}
		echo Zend_Json_Encoder::encode($arrayToJs);
	}

	public function checkPasswordAction()
	{
		$return = array();
		$auth = Zend_Auth::getInstance();
		$authAccount = $auth->getStorage()->read();
		if($authAccount->getId() !== null) {
			$id				= $authAccount->getId();
			$validateId		 = $this->getRequest()->getParam('fieldId');
			$validateValue	 = $this->getRequest()->getParam('fieldValue');
			
			/* RETURN VALUE */
			$arrayToJs = array();
			$arrayToJs[0] = $validateId;
			
			$model = new Default_Model_AdminUser();
			$select = $model->getMapper()->getDbTable()->select()
					->where('password = ?', md5($validateValue))
					->where('id = ?', $id);
			if(($result = $model->fetchAll($select))) {
				$arrayToJs[1] = true;
			} else {
				$arrayToJs[1] = false;
			}
			echo Zend_Json_Encoder::encode($arrayToJs);
		} else {
			echo Zend_Json_Encoder::encode($return);
		}
	}

	public function checkEmailAction()
	{
		$validateId		 = $this->getRequest()->getParam('fieldId');
		$validateValue	 = $this->getRequest()->getParam('fieldValue');

		/* RETURN VALUE */
		$arrayToJs = array();
		$arrayToJs[0] = $validateId;

		$model = new Default_Model_Clients();
		$select = $model->getMapper()->getDbTable()->select()
				->where('email = ?', $validateValue);
		if(($result = $model->fetchAll($select))) {
			$arrayToJs[1] = false;
		} else {
			$arrayToJs[1] = true;
		}
		echo Zend_Json_Encoder::encode($arrayToJs);
	}

	public function attributesAction()
	{
		$response = '';
		$productId = $this->getRequest()->getParam('productId');
		$option = $this->getRequest()->getParam('option');
		if($productId) {
			$model = new Default_Model_Products();
			$select = $model->getMapper()->getDbTable()->select()
					->where('parentId = ?', $productId)
					->where('status = ?', '1')
					;
			if(($result = $model->fetchAll($select))) {
				$model2 = new Default_Model_ProductAttributes();
				$select = $model2->getMapper()->getDbTable()->select()
						->where('productId = ?', $result[0]->getId())
						->where('position = ?', '2')
						;
				if(($result2 = $model2->fetchAll($select))) {
					$response.= '<div>'.$result2[0]->getAttribute()->getName().'</div>';
					$response.= '<div><select id="selectAttributes2" class="attribs">';
					foreach($result as $value) {
						$model2 = new Default_Model_ProductAttributes();
						$select = $model2->getMapper()->getDbTable()->select()
								->where('productId = ?', $value->getId())
								->where('value = ?', $option);
						if(($result2 = $model2->fetchAll($select))) {
							$model3 = new Default_Model_ProductAttributes();
							$select = $model3->getMapper()->getDbTable()->select()
									->where('productId = ?', $value->getId())
									->where('position = ?', '2');
							if(($result3 = $model3->fetchAll($select))) {
								foreach($result3 as $value3) {
									if($value->getStockNelimitat() == 1) {
										$stock = 'unlimited';
									} else {
										$stock = $value->getStock();
									}
									$response.= '<option class="jsAttributes2" price="'.$value3->getPrice().'" stock="'.$stock.'" productId="'.$productId.'" option="'.$value3->getProductId().'" value="'.$value3->getValue().'">'.$value3->getValue().'</option>';
								}
							}
						}
					}
					$response.= '</select></div>';
				} else {
					$response.= 'false';
				}
			}
		}
		echo Zend_Json_Encoder::encode($response);
	}

	public function fetchdeliveryAction()
	{
		$payment = $this->getRequest()->getParam('payment');
		$delivery = $this->getRequest()->getParam('delivery');
		$response = '0';
		if(null != $payment && null != $delivery){
			
			$model = new Default_Model_Delivery();
			$select = $model->getMapper()->getDbTable()->select()
					->where('paymentId = ?', $payment)
					->where('courierId = ?', $delivery);
			$result = $model->fetchAll($select);
			if(null != $result){
				if($result[0]->getCost() > 0){
					$response = $result[0]->getCost();
				}
			}
		}
		echo Zend_Json_Encoder::encode($response);
	}
}