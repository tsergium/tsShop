<?php
class Admin_ProductController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
		// BEGIN: DISPLAY SEARCH FORM
		$form = new Admin_Form_ProductIndexSearch();
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/productIndexSearch.phtml'))));
		$this->view->searchForm = $form;
		// END: DISPLAY SEARCH FORM

		$txtSearch = $this->getRequest()->getParam('txtHeaderSearch');
		if($this->getRequest()->getPost('txtHeaderSearch')){
			$txtSearch = $this->getRequest()->getPost('txtHeaderSearch');
		}
		$type= '';
		if($this->getRequest()->getParam('type')){
			$type = $this->getRequest()->getParam('type');
		}
		
		if($txtSearch){
			$this->view->search = $txtSearch;
		}

		$model = new Default_Model_Product();
		$select = $model->getMapper()->getDbTable()->select();
			if($txtSearch){
				$select->where("name LIKE '%".$txtSearch."%' OR description LIKE '%".$txtSearch."%'");
			}
			if($type == 'inactive'){
				$select->where('status = ?','0');
			}
			$select->order('created DESC');
		$products = $model->fetchAll($select);

		if(null != $products){
			$paginator = Zend_Paginator::factory($products);
			$paginator->setItemCountPerPage(25);
			$paginator->setCurrentPageNumber($this->_getParam('page'));
			$paginator->setPageRange(5);
			$this->view->result = $paginator;
			$this->view->itemCountPerPage = $paginator->getItemCountPerPage();
			$this->view->totalItemCount = $paginator->getTotalItemCount();
			$param = array();
			if($txtSearch){
				$param = array('txtHeaderSearch' => $txtSearch);
			}
			if($type){
				$param = array('type' => $type);
			}

			Zend_Paginator::setDefaultScrollingStyle('Sliding');
			Zend_View_Helper_PaginationControl::setDefaultViewPartial(array('_pagination.phtml', $param));
		}
    }

	public function addAction()
	{
	    $form = new Admin_Form_Product();
	    $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/ProductAdd.phtml'))));
		$this->view->form = $form;
	    if($this->getRequest()->isPost()){
			$post = $this->getRequest()->getPost();		
			if($form->isValid($post)){
				$model = new Default_Model_Product();
				$model->setOptions($post);

				// BEGIN: SAVE IMAGE
				if($form->image->receive()){
					if($form->image->getFileName()) {
						$tmp = pathinfo($form->image->getFileName());
						$extension = (!empty($tmp['extension']))?$tmp['extension']:null;
						$filename = md5(uniqid(mt_rand(), true)).".".$extension;
						if(copy($form->image->getFileName(), APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename)){
						require_once APPLICATION_PUBLIC_PATH.'/library/App/tsThumb/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename);
						$thumb->resize(980)->tsWatermark(APPLICATION_PUBLIC_PATH."/images/watermark.png")->save(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename);
						$thumb->resize(200, 314)->save(APPLICATION_PUBLIC_PATH.'/media/products/big/'.$filename);
						$thumb->tsResizeWithFill(250, 250, "FFFFFF")->save(APPLICATION_PUBLIC_PATH.'/media/products/small/'.$filename);
						$model->setImage($filename);
						}
					}
				}
				// END: SAVE IMAGE			

				// BEGIN: SAVE IMAGE
                $this->saveImageShopmania($form, $model);
                // END: SAVE IMAGE


				if(($productId = $model->save())){
					// BEGIN: SAVE PRODUCT TO CATEGORY ASOCIATION
					foreach($post['category'] as $value){
						$model = new Default_Model_Productcategasoc();
						$model->setCategoryId($value);
						$model->setProductId($productId);
						$model->save();
					}
					// END: SAVE PRODUCT TO CATEGORY ASOCIATION
					
					// BEGIN: SAVE PRODUCT TO SUBCATEGORY ASOCIATION
					foreach($post['subcategory'] as $value){
						$model = new Default_Model_Productcategasoc();
						$model->setCategoryId($value);
						$model->setProductId($productId);
						$model->save();
					}
					// END: SAVE PRODUCT TO SUBCATEGORY ASOCIATION
					//
					// BEGIN: SAVE PRODUCT TO PROMOTION ASOCIATION
					foreach($post['promotionId'] as $value){
						$model = new Default_Model_Productspromotionasoc;
						$model->setPromotionId($value);
						$model->setProductId($productId);
						$model->save();
					}
					// END: SAVE PRODUCT TO PROMOTION ASOCIATION
					
					// BEGIN: SAVE PRODUCT ATTRIBUTES - SIZE
					foreach($post['size'] as $value){
						$model = new Default_Model_ProductAttribute();
						$model->setProductId($productId);
						$model->setGroupId(1);
						$model->setValueId($value);
						$model->save();
					}
					// END: SAVE PRODUCT ATTRIBUTES - SIZE
					
					// BEGIN: SAVE PRODUCT ATTRIBUTES - COLOR
					foreach($post['color'] as $value){
						$model = new Default_Model_ProductAttribute();
						$model->setProductId($productId);
						$model->setGroupId(2);
						$model->setValueId($value);
						$model->save();
					}
					// END: SAVE PRODUCT ATTRIBUTES - COLOR
					// 
					// BEGIN: SAVE PRODUCT ATTRIBUTES - INSTRUCTIONS
					foreach($post['instruction'] as $value){
						$model = new Default_Model_ProductInstructionsAssoc;
						$model->setProductId($productId);						
						$model->setInstructionId($value);
						$model->save();
					}
					// END: SAVE PRODUCT ATTRIBUTES - INSTRUCTIONS

					$this->_flashMessenger->addMessage('<div class="mess-true">Produsul a fost adaugat cu succes!</div>');
				} else {
					$this->_flashMessenger->addMessage('<div class="mess-false">Eroare la adaugarea produsului!</div>');
				}
				$this->_redirect('admin/product');
			}
			
		}
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');
		$model = new Default_Model_Product();
		$model->find($id);
		if(null != $model){
			$form = new Admin_Form_Product();
			$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/ProductEdit.phtml'))));
			$form->edit($model);
			$this->view->form = $form;
			if($this->getRequest()->isPost()){
				$post = $this->getRequest()->getPost();
				if($form->isValid($post)){
					$model->setOptions($post);
					
					// BEGIN: SAVE IMAGE
					if($form->image->receive()){
						if($form->image->getFileName()) {
							$oldImage = $model->getImage();
							$tmp = pathinfo($form->image->getFileName());
							$extension = (!empty($tmp['extension']))?$tmp['extension']:null;
							$filename = md5(uniqid(mt_rand(), true)).".".$extension;
							if(copy($form->image->getFileName(), APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename)){
								require_once APPLICATION_PUBLIC_PATH.'/library/App/tsThumb/ThumbLib.inc.php';
								$thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename);
								$thumb->resize(980)->tsWatermark(APPLICATION_PUBLIC_PATH."/images/watermark.png")->save(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename);
								$thumb->resize(200, 314)->save(APPLICATION_PUBLIC_PATH.'/media/products/big/'.$filename);
								$thumb->tsResizeWithFill(250, 250, "FFFFFF")->save(APPLICATION_PUBLIC_PATH.'/media/products/small/'.$filename);
								$model->setImage($filename);

								// BEGIN: DELETE OLD IMAGES
								@unlink(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$oldImage);
								@unlink(APPLICATION_PUBLIC_PATH.'/media/products/big/'.$oldImage);
								@unlink(APPLICATION_PUBLIC_PATH.'/media/products/small/'.$oldImage);
								// END: DELETE OLD IMAGES
							}
						}
					}
					// END: SAVE IMAGE
					
					// BEGIN: SAVE IMAGE SHOPMANIA
					if($form->imageShopmania->receive()){
						if($form->imageShopmania->getFileName()) {
							$oldImage = $model->getImageShopmania();
							$tmp = pathinfo($form->imageShopmania->getFileName());
							$extension = (!empty($tmp['extension']))?$tmp['extension']:null;
							$filename = md5(uniqid(mt_rand(), true)).".".$extension;
							if(copy($form->imageShopmania->getFileName(), APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename)){
								require_once APPLICATION_PUBLIC_PATH.'/library/App/tsThumb/ThumbLib.inc.php';
								$thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename);
								$thumb->resize(980)->save(APPLICATION_PUBLIC_PATH.'/media/products/shopmania/'.$filename);
								$model->setImageShopmania($filename);
								@unlink(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename);

								// BEGIN: DELETE OLD IMAGES
								@unlink(APPLICATION_PUBLIC_PATH.'/media/products/shopmania/'.$oldImage);
								// END: DELETE OLD IMAGES
							}
						}
					}
					// END: SAVE IMAGE
					$model->setPromotionId($post['promotionId']);
					
					if(($productId = $model->save())){
						// BEGIN: SAVE PRODUCT TO CATEGORY ASOCIATION
						
							// BEGIN: CLEAR ALL RECORD MATCHING CURRENT PRODUCT FIRST
							$model = new Default_Model_Productcategasoc();
							$select = $model->getMapper()->getDbTable()->select()
									->where('productId = ?', $productId);
							$asocProducts = $model->fetchAll($select);
							if(null != $asocProducts){
								foreach($asocProducts as $value){
									$model = new Default_Model_Productcategasoc();
									$model->find($value->getId());
									$model->delete();
								}
							}
							// END: CLEAR ALL RECORD MATCHING CURRENT PRODUCT FIRST

							// BEGIN: ADD PRODUCT TO NEW CATEGORIES
							foreach($post['category'] as $value){
								$model = new Default_Model_Productcategasoc();
								$model->setCategoryId($value);
								$model->setProductId($productId);
								$model->save();
							}
							// END: ADD PRODUCT TO NEW CATEGORIES
							
					
							// BEGIN: ADD PRODUCT TO NEW SUBCATEGORIES
							foreach($post['subcategory'] as $value){
								$model = new Default_Model_Productcategasoc();
								$model->setCategoryId($value);
								$model->setProductId($productId);
								$model->save();
							}
							// END: ADD PRODUCT TO NEW SUBCATEGORIES
							
							// BEGIN: CLEAR ALL ATTRIBUTES FOR SELECTED PRODUCT
							$model = new Default_Model_ProductAttribute();
							$select = $model->getMapper()->getDbTable()->select()
									->where('productId = ?', $productId);
							$result = $model->fetchAll($select);
							if(null != $result){
								foreach($result as $value){
									$model = new Default_Model_ProductAttribute();
									$model->find($value->getId());
									$model->delete();
								}
							}
							// END: CLEAR ALL ATTRIBUTES FOR SELECTED PRODUCT
							//
							// BEGIN: CLEAR PROMOTiON FOR SELECTED PRODUCT
							$model = new Default_Model_Productspromotionasoc();
							$select = $model->getMapper()->getDbTable()->select()
									->where('productId = ?', $productId);
							$result = $model->fetchAll($select);
							if(null != $result){
								foreach($result as $value){
									$model = new Default_Model_Productspromotionasoc();
									$model->find($value->getId());
									$model->delete();
								}
							}
							// END: CLEAR ALL Promotion FOR SELECTED PRODUCT

							// BEGIN: SAVE PRODUCT TO PROMOTION ASOCIATION
							foreach($post['promotionId'] as $value){
								$model = new Default_Model_Productspromotionasoc;
								$model->setPromotionId($value);
								$model->setProductId($productId);
								$model->save();
							}
							// END: SAVE PRODUCT TO PROMOTION ASOCIATION
							
							// BEGIN: SAVE NEW SIZE ATTRIBUTES
							foreach($post['size'] as $value){
								$model = new Default_Model_ProductAttribute();
								$model->setProductId($productId);
								$model->setGroupId(1);
								$model->setValueId($value);
								$model->save();
							}
							// END: SAVE NEW SIZE ATTRIBUTES
							
							// BEGIN: SAVE NEW COLOR ATTRIBUTES
							foreach($post['color'] as $value){
								$model = new Default_Model_ProductAttribute();
								$model->setProductId($productId);
								$model->setGroupId(2);
								$model->setValueId($value);
								$model->save();								
							}
							// END: SAVE NEW COLOR ATTRIBUTES
							
							// BEGIN: CLEAR ALL RECORD MATCHING CURRENT PRODUCT IN 
							// PRODUCT ATTRIBUTES - INSTRUCTIONS
							$model = new Default_Model_ProductInstructionsAssoc();
							$select = $model->getMapper()->getDbTable()->select()
									->where('productId = ?', $productId);
							$asocProductsInst = $model->fetchAll($select);
							if(null != $asocProductsInst){
								foreach($asocProductsInst as $value){
									$model = new Default_Model_ProductInstructionsAssoc();
									$model->find($value->getId());
									$model->delete();
								}
							}
							// END: CLEAR ALL RECORD MATCHING CURRENT PRODUCT IN 
							// PRODUCT ATTRIBUTES - INSTRUCTIONS
							
							// BEGIN: SAVE PRODUCT ATTRIBUTES - INSTRUCTIONS
							foreach($post['instruction'] as $value){
								$model = new Default_Model_ProductInstructionsAssoc;
								$model->setProductId($productId);						
								$model->setInstructionId($value);
								$model->save();
							}
							// END: SAVE PRODUCT ATTRIBUTES - INSTRUCTIONS
							
						// END: SAVE PRODUCT TO CATEGORY ASOCIATION
						$this->_flashMessenger->addMessage('<div class="mess-true">Produsul a fost modificat cu succes!</div>');
						$this->_redirect('admin/product');
					} else {
						$this->_flashMessenger->addMessage('<div class="mess-false">Eroare la adaugarea produsului!</div>');
					}
				}
			}
		}else{
			$this->_flashMessenger->addMessage('<div class="mess-false">Produsul selectat nu a fost gasit!</div>');
			$this->_redirect('admin/product');
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		if(null != $id){
			$model = new Default_Model_Product();
			$model->find($id);
			if(null != $model){
				$oldImage = $model->getImage();
				if($model->delete()){
					// BEGIN: DELETE OLD IMAGES
					@unlink(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$oldImage);
					@unlink(APPLICATION_PUBLIC_PATH.'/media/products/big/'.$oldImage);
					@unlink(APPLICATION_PUBLIC_PATH.'/media/products/small/'.$oldImage);
					// END: DELETE OLD IMAGES
					
					// BEGIN: CLEAR ALL RECORD MATCHING CURRENT PRODUCT FIRST
					$model = new Default_Model_Productcategasoc();
					$select = $model->getMapper()->getDbTable()->select()
							->where('productId = ?', $id);
					$asocProducts = $model->fetchAll($select);
					if(null != $asocProducts){
						foreach($asocProducts as $value){
							$model = new Default_Model_Productcategasoc();
							$model->find($value->getId());
							$model->delete();
						}
					}
					// END: CLEAR ALL RECORD MATCHING CURRENT PRODUCT FIRST
					
					// BEGIN: CLEAR ALL ATTRIBUTES FOR SELECTED PRODUCT
					$model = new Default_Model_ProductAttribute();
					$select = $model->getMapper()->getDbTable()->select()
							->where('productId = ?', $id);
					$result = $model->fetchAll($select);
					if(null != $result){
						foreach($result as $value){
							$model = new Default_Model_ProductAttribute();
							$model->find($value->getId());
							$model->delete();
						}
					}
					// END: CLEAR ALL ATTRIBUTES FOR SELECTED PRODUCT
					
					// BEGIN: CLEAR ALL RECORD MATCHING CURRENT PRODUCT IN 
					// PRODUCT ATTRIBUTES - INSTRUCTIONS
					$model = new Default_Model_ProductInstructionsAssoc();
					$select = $model->getMapper()->getDbTable()->select()
							->where('productId = ?', $id);
					$asocProductsInst = $model->fetchAll($select);
					if(null != $asocProductsInst){
						foreach($asocProductsInst as $value){
							$model = new Default_Model_ProductInstructionsAssoc();
							$model->find($value->getId());
							$model->delete();
						}
					}
					// END: CLEAR ALL RECORD MATCHING CURRENT PRODUCT IN 
					// PRODUCT ATTRIBUTES - INSTRUCTIONS
					
					// BEGIN: CLEAR Product Gallery				
					$modelg = new Default_Model_Productgallery();
					$select = $modelg->getMapper()->getDbTable()->select()
							->where('productId = ?', $id);
					$gallery = $modelg->fetchAll($select);
					if(null != $gallery){
						foreach($gallery as $value){
						        $model = new Default_Model_Productgallery();
							$model->find($value->getId());
						        //BEGIN: CLEAR Gallery Pictures	
							// BEGIN: DELETE OLD IMAGES
							@unlink(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$model->getImage());
							@unlink(APPLICATION_PUBLIC_PATH.'/media/products/big/'.$model->getImage());
							@unlink(APPLICATION_PUBLIC_PATH.'/media/products/small/'.$model->getImage());
							// END: DELETE OLD IMAGES
						        //END: CLEAR Gallery Pictures	
						    
							
							$model->delete();
						}
					}					
					// END: CLEAR Product Gallery	
					$this->_flashMessenger->addMessage('<div class="mess-true">Produsul a fost sters!</div>');
				}else{
					$this->_flashMessenger->addMessage('<div class="mess-false">Eroare la stergerea produsului!</div>');
				}
			}else{
				$this->_flashMessenger->addMessage('<div class="mess-false">Produsul selectat nu a fost gasit!</div>');
			}
		}else{
			$this->_flashMessenger->addMessage('<div class="mess-false">Produsul selectat nu a fost gasit!</div>');
		}
		$this->_redirect('admin/product');
	}

	public function nocategAction()
	{
		// BEGIN: CREATE ASOCIATION ARRAY
		$productsAsociated = array();
		$model = new Default_Model_Productcategasoc();
		$select = $model->getMapper()->getDbTable()->select()
				->group('productId');
		$result = $model->fetchAll($select);
		if(null != $result){
			foreach($result as $value){
				$productsAsociated[] = $value->getProductId();
			}
		}
		// END: CREATE ASOCIATION ARRAY
		
		// BEGIN: FETCH ALL PRODUCTS
		$noCategProds = array();
		$model = new Default_Model_Product();
		$select = $model->getMapper()->getDbTable()->select();
		$result = $model->fetchAll($select);
		if(null != $result){
			foreach($result as $value){
				if(!in_array($value->getId(), $productsAsociated)){
					$noCategProds[] = $value;
				}
			}
		}
		if(null != $noCategProds){
			$paginator = Zend_Paginator::factory($noCategProds);
			$paginator->setItemCountPerPage(25);
			$paginator->setCurrentPageNumber($this->_getParam('page'));
			$paginator->setPageRange(5);
			$this->view->result = $paginator;
			$this->view->itemCountPerPage = $paginator->getItemCountPerPage();
			$this->view->totalItemCount = $paginator->getTotalItemCount();
			$param = array();
			

			Zend_Paginator::setDefaultScrollingStyle('Sliding');
			Zend_View_Helper_PaginationControl::setDefaultViewPartial(array('_pagination.phtml', $param));
		}
		// END: FETCH ALL PRODUCTS
	}

// BEGIN: PRODUCT ATTRIBUTES
	public function attributesAction() // Manage attributes
	{
		// BEGIN: FETCH MARIMI
		$model = new Default_Model_ProductAttributeValue();
		$select = $model->getMapper()->getDbTable()->select()
				->where('groupId = ?', '1')
				->order('order ASC');
		$result = $model->fetchAll($select);
		if(null != $result){
			$this->view->marimi = $result;
		}
		// END: FETCH MARIMI
		
		// BEGIN: FETCH CULORI
		$model = new Default_Model_ProductAttributeValue();
		$select = $model->getMapper()->getDbTable()->select()
				->where('groupId = ?', '2');
		$result = $model->fetchAll($select);
		if(null != $result){
			$this->view->culori = $result;
		}		
		// END: FETCH CULORI
	}
	
	public function addattributesizeAction()
	{
		$form = new Admin_Form_ProductAttributeSize();
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/productAttributeSize.phtml'))));
		$this->view->form = $form;
		
		if($this->getRequest()->isPost()){
			$post = $this->getRequest()->getPost();
			if($form->isValid($post)){
				$model = new Default_Model_ProductAttributeValue();
				$model->setOptions($post);
				$model->setGroupId(1);
				if($model->save()){
					$this->_flashMessenger->addMessage('<div class="mess-true">Marimea a fost adaugata!</div>');
				}else{
					$this->_flashMessenger->addMessage('<div class="mess-false">Eroare salvare marime!</div>');
				}
				$this->_redirect('admin/product/attributes');
			}
		}
	}
	
	public function addattributecolorAction()
	{
		$form = new Admin_Form_ProductAttribute();
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/productAttribute.phtml'))));
		$this->view->form = $form;
		
		if($this->getRequest()->isPost()){
			$post = $this->getRequest()->getPost();
			if($form->isValid($post)){
				$model = new Default_Model_ProductAttributeValue();
				$model->setOptions($post);
				$model->setGroupId(2);
				if($model->save()){
					$this->_flashMessenger->addMessage('<div class="mess-true">Culoarea a fost adaugata!</div>');
				}else{
					$this->_flashMessenger->addMessage('<div class="mess-false">Eroare salvare culoare!</div>');
				}
			}
			$this->_redirect('admin/product/attributes');
		}
	}
	
	public function editattributeAction()
	{
		$id = $this->getRequest()->getParam('id');
		if(null != $id){
			$model = new Default_Model_ProductAttributeValue();
			if($model->find($id)){
				$groupId = $model->getGroupId();
				$form = new Admin_Form_ProductAttributeSize();
				$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/productAttributeSize.phtml'))));
				$form->edit($model);
				$this->view->form = $form;
				if($this->getRequest()->isPost()){
					$post = $this->getRequest()->getPost();
					if($form->isValid($post)){						
						$model->setOptions($post);
						$model->setGroupId($groupId);
						if($model->save()){
							$this->_flashMessenger->addMessage('<div class="mess-true">Atributul a fost modificat!</div>');
						}else{
							$this->_flashMessenger->addMessage('<div class="mess-false">Eroare modificare atribut!</div>');
						}
					}
					$this->_redirect('admin/product/attributes');
				}
			}else{
				$this->_flashMessenger->addMessage('<div class="mess-false">Atributul selectat nu a fost gasit!</div>');
				$this->_redirect('/admin/product/attributes');				
			}
		}else{
			$this->_flashMessenger->addMessage('<div class="mess-false">Atributul selectat nu a fost gasit!</div>');
			$this->_redirect('/admin/product/attributes');
		}
	}
	
	public function deleteattributeAction()
	{
		$id = $this->getRequest()->getParam('id');
		if(null != $id){
			$model = new Default_Model_ProductAttributeValue();
			if($model->find($id)){
				if($model->delete()){
					$this->_flashMessenger->addMessage('<div class="mess-true">Atributul a fost sters!</div>');
				}else{
					$this->_flashMessenger->addMessage('<div class="mess-false">Eroare la stergerea atributului!</div>');
				}
			}else{
				$this->_flashMessenger->addMessage('<div class="mess-false">Atributul selectat nu a fost gasit!</div>');
			}
		}else{
			$this->_flashMessenger->addMessage('<div class="mess-false">Atributul selectat nu a fost gasit!</div>');
		}
		$this->_redirect('/admin/product/attributes');
	}
// BEGIN: PRODUCT ATTRIBUTES

// BEGIN: PRODUCT GALLERY
	public function galleryAction()
	{
		  // BEGIN: DISPLAY SEARCH FORM
		$form = new Admin_Form_ProductIndexSearch();
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/productIndexSearch.phtml'))));
		$this->view->searchForm = $form;
		// END: DISPLAY SEARCH FORM  
		
			$txtSearch = $this->getRequest()->getParam('txtHeaderSearch');
		if($this->getRequest()->getPost('txtHeaderSearch')){
			$txtSearch = $this->getRequest()->getPost('txtHeaderSearch');
		}
		if($txtSearch){
			$this->view->search = $txtSearch;
		}

		$model = new Default_Model_Product();
		$select = $model->getMapper()->getDbTable()->select();
			if($txtSearch){
				$select->where("name LIKE '%".$txtSearch."%' OR description LIKE '%".$txtSearch."%'");
			}
			$select->order('created DESC');
		$products = $model->fetchAll($select);

		if(null != $products){
			$paginator = Zend_Paginator::factory($products);
			$paginator->setItemCountPerPage(25);
			$paginator->setCurrentPageNumber($this->_getParam('page'));
			$paginator->setPageRange(5);
			$this->view->result = $paginator;
			$this->view->itemCountPerPage = $paginator->getItemCountPerPage();
			$this->view->totalItemCount = $paginator->getTotalItemCount();
			$param = array();
			if($txtSearch){
				$param = array('txtHeaderSearch' => $txtSearch);
			}

			Zend_Paginator::setDefaultScrollingStyle('Sliding');
			Zend_View_Helper_PaginationControl::setDefaultViewPartial(array('_pagination.phtml', $param));
		}
	}
	
	public function addgalleryAction()
	{
	    $productId = $this->getRequest()->getParam('id');

	    // BEGIN: FETCH IMAGES FOR THIS PRODUCT
	    $model = new Default_Model_Productgallery();
	    $select = $model->getMapper()->getDbTable()->select()
		    ->where('productId = ?', $productId);
	    $result = $model->fetchAll($select);
	    if(null != $result){
		$this->view->result = $result;
	    }
	    // END: FETCH IMAGES FOR THIS PRODUCT

	    $form = new Admin_Form_ProductGallery();
	    $form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/productGallery.phtml'))));
	    $this->view->form = $form;
	    if($this->getRequest()->isPost()){
		$post = $this->getRequest()->getPost();
		if($form->isValid($post)){
		    $model = new Default_Model_Productgallery();
		    $model->setOptions($post);
		    $model->setProductId($productId);

		    // BEGIN: SAVE IMAGE
		    if($form->image->receive()){
				if($form->image->getFileName()){
					$tmp = pathinfo($form->image->getFileName());
					$extension = (!empty($tmp['extension']))?$tmp['extension']:null;
					$filename = md5(uniqid(mt_rand(), true)).".".$extension;
					if(copy($form->image->getFileName(), APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename)){
						require_once APPLICATION_PUBLIC_PATH.'/library/App/tsThumb/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename);
						$thumb->resize(980)->tsWatermark(APPLICATION_PUBLIC_PATH."/images/watermark.png")->save(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$filename);
						$thumb->resize(200, 314)->save(APPLICATION_PUBLIC_PATH.'/media/products/big/'.$filename);
						$thumb->tsResizeWithFill(250, 250, "FFFFFF")->save(APPLICATION_PUBLIC_PATH.'/media/products/small/'.$filename);
						
						$model->setImage($filename);
						
						if($model->save()){
							$this->_flashMessenger->addMessage('<div class="mess-true">Poza a fost adaugata!</div>');
						}else{
							$this->_flashMessenger->addMessage('<div class="mess-false">Eroare salvare poza!</div>');
						}
					}else{
						die("There you are!");
					}
				}
		    }
		    // END: SAVE IMAGE
			$this->_redirect('admin/product/addgallery/id/'.$productId);
		}		
	    }
	}
	
	public function deletegalleryAction()
	{
	    $id = $this->getRequest()->getParam('id');
	    $model = new Default_Model_Productgallery();
	    if($model->find($id)){
		$productId = $model->getProductId();
		$image = $model->getImage();
		if($model->delete()){
		    $this->_flashMessenger->addMessage('<div class="mess-true">Poza a fost stearsa!</div>');
		    // BEGIN: DELETE OLD IMAGES
		    @unlink(APPLICATION_PUBLIC_PATH.'/media/products/full/'.$image);
		    @unlink(APPLICATION_PUBLIC_PATH.'/media/products/big/'.$image);
		    @unlink(APPLICATION_PUBLIC_PATH.'/media/products/small/'.$image);
		    // END: DELETE OLD IMAGES
		    if(null != $productId){
			$this->_redirect('admin/product/addgallery/id/'.$productId);
		    }
		}else{
		    $this->_flashMessenger->addMessage('<div class="mess-false">Eroare stergere poza!</div>');
		}
	    }else{
		$this->_flashMessenger->addMessage('<div class="mess-false">Poza selectata este invalida!</div>');
	    }
	    $this->_redirect('admin/product/gallery/');
	}
// END: PRODUCT GALLERY
	
    public function productintructionsAction()
    {
	$form = new Admin_Form_ProductInstructions;
	$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/ProductsIntructions.phtml'))));
	$this->view->form = $form;
	if($this->getRequest()->isPost()){
	    $post = $this->getRequest()->getPost();
	    if($form->isValid($post)){
		    $model = new Default_Model_ProductInstructions();
		    $model->setOptions($post);

		    // BEGIN: SAVE IMAGE
		    if($form->image->receive()){
			    if($form->image->getFileName()) {
				    $tmp = pathinfo($form->image->getFileName());
				    $extension = (!empty($tmp['extension']))?$tmp['extension']:null;
				    $filename = md5(uniqid(mt_rand(), true)).".".$extension;
				    if(copy($form->image->getFileName(), APPLICATION_PUBLIC_PATH.'/media/productsInstructions/'.$filename)){
					require_once APPLICATION_PUBLIC_PATH.'/library/App/tsThumb/ThumbLib.inc.php';
					$thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH.'/media/productsInstructions/'.$filename);
					$thumb->tsResizeWithFill(40, 40, "FFFFFF")->save(APPLICATION_PUBLIC_PATH.'/media/productsInstructions/'.$filename);
					$model->setImage($filename);
				    }
			    }
		    }
		    // END: SAVE IMAGE

		    if(($productId = $model->save())){
			$this->_flashMessenger->addMessage('<div class="mess-true">Datele au fost salvate cu succes!</div>');
		    } else {
			$this->_flashMessenger->addMessage('<div class="mess-false">Eroare salvare date!</div>');
		    }
		    $this->_redirect('admin/product/productintructions/');
	    }
	}
	// BEGIN: select products instructions
	$model = new Default_Model_ProductInstructions();
	$select = $model->getMapper()->getDbTable()->select()
					->order('created DESC');
	$result = $model->fetchAll($select);
	if(null != $result){
	    $this->view->result = $result;
	}
	// END: select products instructions
    }
    
    public function editinstructionsAction()
    {
	$id = $this->getRequest()->getParam('id');	
	if(null != $id){
	    
	    $model = new Default_Model_ProductInstructions();
	    $model->find($id);
	    if(null != $model){		
		$form = new Admin_Form_ProductInstructions();
		$form->edit($model);
		$form->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/product/ProductsInstructionsEdit.phtml'))));
		$this->view->form = $form;
		if($this->getRequest()->isPost()){
		    $post = $this->getRequest()->getPost();
		    if($form->isValid($post)){
			    $oldImg = $model->getImage();
			    $model->setOptions($post);
			    // BEGIN: SAVE IMAGE
			    if($form->image->receive()){
				    if($form->image->getFileName()) {
					    $tmp = pathinfo($form->image->getFileName());
					    $extension = (!empty($tmp['extension']))?$tmp['extension']:null;
					    $filename = md5(uniqid(mt_rand(), true)).".".$extension;
					    if(copy($form->image->getFileName(), APPLICATION_PUBLIC_PATH.'/media/productsInstructions/'.$filename)){
						require_once APPLICATION_PUBLIC_PATH.'/library/App/tsThumb/ThumbLib.inc.php';
						$thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH.'/media/productsInstructions/'.$filename);
						$thumb->tsResizeWithFill(40, 40, "FFFFFF")->save(APPLICATION_PUBLIC_PATH.'/media/productsInstructions/'.$filename);
						$model->setImage($filename);
						// BEGIN: DELETE OLD IMAGES
						@unlink(APPLICATION_PUBLIC_PATH.'/media/productsInstructions/'.$oldImage);				
						// END: DELETE OLD IMAGES
					    }
				    }
			    }
			    // END: SAVE IMAGE
			    
			    if(($productId = $model->save())){
				$this->_flashMessenger->addMessage('<div class="mess-true">Datele au fost salvate cu succes!</div>');
			    } else {
				$this->_flashMessenger->addMessage('<div class="mess-false">Eroare salvare date!</div>');
			    }
			    $this->_redirect('admin/product/productintructions/'); 
		    }
		} 
	    }else{
		$this->_flashMessenger->addMessage('<div class="mess-false">Instructiunea selectata nu a fost gasita in baza de date!</div>');
		$this->_redirect('admin/product/productintructions');
	    }
	    
	}  
    }


    public function deleteinstructionsAction()
    {
	$id = $this->getRequest()->getParam('id');
	if(null != $id){
		$model = new Default_Model_ProductInstructions();
		$model->find($id);
		if(null != $model){
			$oldImage = $model->getImage();
			if($model->delete()){
				// BEGIN: DELETE OLD IMAGES
				@unlink(APPLICATION_PUBLIC_PATH.'/media/productsInstructions/'.$oldImage);				
				// END: DELETE OLD IMAGES
				$this->_flashMessenger->addMessage('<div class="mess-true">Datele au fost sterse cu succes!</div>');
			}else{
				$this->_flashMessenger->addMessage('<div class="mess-false">Eroare stergere date!</div>');
			}
		}else{
			$this->_flashMessenger->addMessage('<div class="mess-false">Instructiunea selectata nu a fost gasita in baza de date!</div>');
		}
	}else{
		$this->_flashMessenger->addMessage('<div class="mess-false">Instructiunea selectata nu a fost gasita in baza de date!</div>');
	}
	$this->_redirect('admin/product/productintructions');
    }

    /**
     * @param $form
     * @param $model
     */
    protected function saveImageShopmania($form, $model)
    {
        if ($form->imageShopmania->receive()) {
            if ($form->imageShopmania->getFileName()) {
                $tmp = pathinfo($form->imageShopmania->getFileName());
                $extension = (!empty($tmp['extension'])) ? $tmp['extension'] : null;
                $filename = md5(uniqid(mt_rand(), true)) . "." . $extension;
                if (copy($form->imageShopmania->getFileName(), APPLICATION_PUBLIC_PATH . '/media/products/full/' . $filename)) {
                    require_once APPLICATION_PUBLIC_PATH . '/library/App/tsThumb/ThumbLib.inc.php';
                    $thumb = PhpThumbFactory::create(APPLICATION_PUBLIC_PATH . '/media/products/full/' . $filename);
                    $thumb->resize(980)->save(APPLICATION_PUBLIC_PATH . '/media/products/shopmania/' . $filename);
                    $model->setImageShopmania($filename);
                    @unlink(APPLICATION_PUBLIC_PATH . '/media/products/full/' . $filename);
                }
            }
        }
    }
}