<?php
class Admin_OrderController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
		$type = $this->getRequest()->getParam('type');
		$this->view->type = 'index';
		if(!empty($type)){
			$this->view->type = $type;
		}
		$model = new Default_Model_Orders();
		$select = $model->getMapper()->getDbTable()->select();
				if($type){
					$select->where('status = ?', $type);
				}
				$select->order('id DESC');
		$result = $model->fetchAll($select);
		if(null != $result){
			$paginator = Zend_Paginator::factory($result);
			$paginator->setItemCountPerPage(25);
			$paginator->setCurrentPageNumber($this->_getParam('page'));
			$paginator->setPageRange(5);
			$this->view->result = $paginator;
			$this->view->curPage = $this->_getParam('page');
			$this->view->itemCountPerPage = $paginator->getItemCountPerPage();
			$this->view->totalItemCount = $paginator->getTotalItemCount();

			Zend_Paginator::setDefaultScrollingStyle('Sliding');
			Zend_View_Helper_PaginationControl::setDefaultViewPartial('_pagination.phtml');
		}
    }


	public function statusAction()
	{
		$orderId = $this->getRequest()->getParam('id');
		$action = $this->getRequest()->getParam('newaction');
		$status = $this->getRequest()->getParam('status');
		$page = $this->getRequest()->getParam('page');

		$order = new Default_Model_Orders();
		if($order->find($orderId)) {
			$oldstatus = $order->getStatus();
			$order->setStatus($status);
			if($oldstatus == 'pending' && $status == 'accepted') {
				$orderStatusChangeVerify = $this->orderStatusChangeVerify($orderId);
				if($orderStatusChangeVerify == 'true') {
					$orderProducts = new Default_Model_OrderProducts();
					$select = $orderProducts->getMapper()->getDbTable()->select()
							->where('orderId = ?', $orderId);
					if(($result = $orderProducts->fetchAll($select))) {
						foreach($result as $value) {							
							$productId = $value->getProductId();
							
							$product = new Default_Model_Product();
							if($product->find($productId)) {
								if($product->getStockNelimitat() == 0) {
									$product->setStock($product->getStock() - $value->getQuantity());
									$product->save();
								}
							}
						}
					}
					$order->save();
					$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				} elseif($orderStatusChangeVerify == 'false') {
					$order->save();
					$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				} elseif(is_array($orderStatusChangeVerify)) {
					foreach($orderStatusChangeVerify as $value) {
						$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_status_error_4').' '.$value.'</div>');
					}
					$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_status_error_2').'</div>');
				}
				$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
			} elseif($oldstatus == 'rejected' && $status == 'accepted') {
				$orderStatusChangeVerify = $this->orderStatusChangeVerify($orderId);
				if($orderStatusChangeVerify == 'true') {
					$orderProducts = new Default_Model_OrderProducts();
					$select = $orderProducts->getMapper()->getDbTable()->select()
							->where('orderId = ?', $orderId)
							;
					if(($result = $orderProducts->fetchAll($select))) {
						foreach($result as $value) {
							$productId = $value->getProductId();
							$product = new Default_Model_Product();
							if($product->find($productId)) {
								if($product->getStockNelimitat() == 0) {
									$product->setStock($product->getStock() - $value->getQuantity());
									$product->save();
								}
							}
						}
					}
					$order->save();
					$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				} elseif($orderStatusChangeVerify == 'false') {
					$order->save();
					$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				} elseif($orderStatusChangeVerify) {
					foreach($orderStatusChangeVerify as $value) {
						$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_status_error_4').' '.$value.'</div>');
					}
					$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_status_error_2').'</div>');
				}
				$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
			} elseif($oldstatus == 'pending' && $status == 'completed') {
				$orderStatusChangeVerify = $this->orderStatusChangeVerify($orderId);
				if($orderStatusChangeVerify == 'true') {
					$orderProducts = new Default_Model_OrderProducts();
					$select = $orderProducts->getMapper()->getDbTable()->select()
							->where('orderId = ?', $orderId)
							;
					if(($result = $orderProducts->fetchAll($select))) {
						foreach($result as $value) {
							$productId = $value->getProductId();
							$product = new Default_Model_Product();
							if($product->find($productId)) {
								if($product->getStockNelimitat() == 0) {
									$product->setStock($product->getStock() - $value->getQuantity());
									$product->save();
								}
							}
						}
					}
					$order->save();
					$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				} elseif($orderStatusChangeVerify == 'false') {
					$order->save();
					$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				} elseif($orderStatusChangeVerify) {
					foreach($orderStatusChangeVerify as $value) {
						$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_status_error_4').' '.$value.'</div>');
					}
					$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_status_error_2').'</div>');
				}
				$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
			} elseif($oldstatus == 'rejected' && $status == 'completed') {
				$orderStatusChangeVerify = $this->orderStatusChangeVerify($orderId);
				if($orderStatusChangeVerify == 'true') {
					$orderProducts = new Default_Model_OrderProducts();
					$select = $orderProducts->getMapper()->getDbTable()->select()
							->where('orderId = ?', $orderId)
							;
					if(($result = $orderProducts->fetchAll($select))) {
						foreach($result as $value) {
							$productId = $value->getProductId();
							$product = new Default_Model_Product();
							if($product->find($productId)) {
								if($product->getStockNelimitat() == 0) {
									$product->setStock($product->getStock() - $value->getQuantity());
									$product->save();
								}
							}
						}
					}
					$order->save();
					$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				} elseif($orderStatusChangeVerify == 'false') {
					$order->save();
					$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				} elseif($orderStatusChangeVerify) {
					foreach($orderStatusChangeVerify as $value) {
						$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_status_error_4').' '.$value.'</div>');
					}
					$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_status_error_2').'</div>');
				}
				$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
			} elseif($oldstatus == 'accepted' && $status == 'pending') {
				$orderProducts = new Default_Model_OrderProducts();
				$select = $orderProducts->getMapper()->getDbTable()->select()
						->where('orderId = ?', $orderId)
						->order(array('id DESC'));
				if(($result = $orderProducts->fetchAll($select))) {
					foreach($result as $value) {
						$productId = $value->getProductId();
						$product = new Default_Model_Product();
						if($product->find($productId)) {
							if($product->getStockNelimitat() == 0) {
								$product->setStock($product->getStock() + $value->getQuantity());
								$product->save();
							}
						}
					}
				}
				$order->save();
				$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
			} elseif($oldstatus == 'accepted' && $status == 'rejected') {
				$orderProducts = new Default_Model_OrderProducts();
				$select = $orderProducts->getMapper()->getDbTable()->select()
						->where('orderId = ?', $orderId)
						->order(array('id DESC'));
				if(($result = $orderProducts->fetchAll($select))) {
					foreach($result as $value) {
						$productId = $value->getProductId();
						$product = new Default_Model_Product();
						if($product->find($productId)) {
							if($product->getStockNelimitat() == 0) {
								$product->setStock($product->getStock() + $value->getQuantity());
								$product->save();
							}
						}
					}
				}
				$order->save();
				$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
			} elseif($oldstatus == 'completed' && $status == 'pending') {
				$orderProducts = new Default_Model_OrderProducts();
				$select = $orderProducts->getMapper()->getDbTable()->select()
						->where('orderId = ?', $orderId)
						->order(array('id DESC'));
				if(($result = $orderProducts->fetchAll($select))) {
					foreach($result as $value) {
						$productId = $value->getProductId();
						$product = new Default_Model_Product();
						if($product->find($productId)) {
							if($product->getStockNelimitat() == 0) {
								$product->setStock($product->getStock() + $value->getQuantity());
								$product->save();
							}
						}
					}
				}
				$order->save();
				$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
			} elseif($oldstatus == 'completed' && $status == 'rejected') {
				$orderProducts = new Default_Model_OrderProducts();
				$select = $orderProducts->getMapper()->getDbTable()->select()
						->where('orderId = ?', $orderId)
						->order(array('id DESC'));
				if(($result = $orderProducts->fetchAll($select))) {
					foreach($result as $value) {
						$productId = $value->getProductId();
						
						$products = new Default_Model_Product();
						if($products->find($productId)) {
							if($products->getStockNelimitat() == 0) {
								$products->setStock($products->getStock() + $value->getQuantity());
								$products->save();
							}
						}
					}
				}
				$order->save();
				$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
			} else {
				$order->save();
				$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_status_success').'</div>');
				$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
			}
		} else {
			$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_status_error_2').'</div>');
			$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
		}
	}

	public function deleteordersAction()
	{
		$id = $this->getRequest()->getParam('id');
		$action = $this->getRequest()->getParam('newaction');
		$page = $this->getRequest()->getParam('page');
		$model = new Default_Model_Orders();
		if($model->find($id)) {
			if($model->delete()) {
				$this->_flashMessenger->addMessage('<div class="mess-true">'.Zend_Registry::get('translate')->_('sales_delete_success').'</div>');
			} else {
				$this->_flashMessenger->addMessage('<div class="mess-false">'.Zend_Registry::get('translate')->_('sales_delete_error').'</div>');
			}
		}
		$this->_redirect('/admin/order'.($page?'/'.($action?$action:'index').'/page/'.$page:($action?'/'.$action:'')));
	}

	public function detailsAction()
	{
		$id = $this->getRequest()->getParam('orderId');
		
		$model = new Default_Model_Orders();
		if($model->find($id)) {
			$this->view->order = $model;
			$model2 = new Default_Model_OrderProducts();
			$select = $model2->getMapper()->getDbTable()->select()
					->where('orderId = ?', $model->getId());
			if(($result = $model2->fetchAll($select))) {
				$this->view->orderproducts = $result;
			}
		}
	}

	public function searchAction()
	{
		$form = new Admin_Form_OrderSearch();
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/order/search.phtml'))));
		$this->view->form = $form;

		if($this->getRequest()->getParam('txtHeaderSearch') != null) {
			$searchTerm = $this->getRequest()->getParam('txtHeaderSearch');
			$this->view->searchTerm = $searchTerm;
			$array = explode(' ', $searchTerm);
			$model = new Default_Model_Orders();
			$select = $model->getMapper()->getDbTable()->select();
					foreach($array as $value) {
						$select->where("id LIKE '%".$value."%' OR email LIKE '%".$value."%' OR firstname LIKE '%".$value."%'  OR lastname LIKE '%".$value."%'");
					}
			if(($result = $model->fetchAll($select))) {
				$this->view->result = $result;
			} else {
				$this->_flashMessenger->addMessage('<div class="mess-false">Nu a fost gasita nici o comanda.</div>');
				$this->_redirect('/admin/order/search');
			}
		}
	}

    /**
     * ToDo: find what the fuck this is
     * @param $orderId
     * @return array|string
     */
    protected function orderStatusChangeVerify($orderId)
    {
        $array = array();
        $model = new Default_Model_OrderProducts();
        $select = $model->getMapper()->getDbTable()->select()
            ->where('orderId = ?', $orderId)
        ;
        if(($result = $model->fetchAll($select))) {
            foreach($result as $value) {
                $productId = $value->getProductId();
                $product = new Default_Model_Product();
                if($product->find($productId)) {
                    if($product->getStockNelimitat() == 0) {
                        $oldqty = $product->getStock();
                        if($oldqty - $value->getQuantity() < 0) {
                            $array[] = $product->getName();
                        }
                    }
                }
            }
            if(null != $array) {
                return $array;
            } else {
                return 'true';
            }
        } else {
            return 'false';
        }
    }
}