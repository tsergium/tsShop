<?php
class Product
{
	// START ALL
	public function vatPrice($price) {
		$model = new Default_Model_Company();
		$select = $model->getMapper()->getDbTable()->select();
		if($result = $model->fetchAll($select)) {
			if(($result[0]->getVatPayer() == '1')) {
				$model2 = new Default_Model_ProductsTaxes();
				$select = $model2->getMapper()->getDbTable()->select()
						->where('id = ?', '1')
						->where('status = ?', '1')
						;
				if(($result2 = $model2->fetchAll($select))) {
					$vatValue = $result2[0]->getValue()/100;
					$priceVat = $price * $vatValue;
					$priceVat += $price;
					return $priceVat;
				} else {
					return $price;
				}
			} else {
				return $price;
			}
		}
	}

	// start front
	public function prodDescSummary($description, $limit)
	{
		if(null != $description && null != $limit) {
			$summary = strip_tags($description);
			if(strlen($summary) > $limit) {
				$summary = substr($summary,0,strrpos(substr($summary,0,$limit),' ')).'&nbsp;...&nbsp;';
			}
			return $summary;
		} else {
			return null;
		}
	}

	public function offer($oferId)
	{
		if($oferId == 1) {
			return '<div class="prod-icon-nou" title="'.Zend_Registry::get('translate')->_('products_new').'">'.Zend_Registry::get('translate')->_('products_new').'</div>';
		} elseif($oferId == 2) {
			return '<div class="prod-icon-lichidare" title="'.Zend_Registry::get('translate')->_('products_stock_clearance').'">'.Zend_Registry::get('translate')->_('products_stock_clearance').'</div>';
		} elseif($oferId == 3) {
			return '<div class="prod-icon-promo" title="'.Zend_Registry::get('translate')->_('products_promotion').'">'.Zend_Registry::get('translate')->_('products_promotion').'</div>';
		}
	}
	
	public function promoByCateg($categId = null)
	{
		$array = array();
		$model = new Default_Model_Products();
		$select = $model->getMapper()->getDbTable()->select()
				->from(array('p' => 'fp_products'))
				->joinLeft(array('c' => 'fp_categories'), 'p.categoryId = c.id', array('cid' => 'id', 'cparentId' => 'parentId', 'tampon' => 'tampon'))
				->where('p.status = ?', '1')
				->where('p.offertId = ?', '3')
				->where('c.tampon != ?', '1');
				if($categId) {
					$select->where('c.id = ?', $categId);
				}
				$select->order('RAND()')
					->limit('5')
					->setIntegrityCheck(false);
		if(($result = $model->fetchAll($select))) {
			return $result;
		} else {
			$model2 = new Default_Model_Products();
			$select = $model2->getMapper()->getDbTable()->select()
					->from(array('p' => 'fp_products'))
					->joinLeft(array('c' => 'fp_categories'), 'p.categoryId = c.id', array('cid' => 'id', 'cparentId' => 'parentId', 'tampon' => 'tampon'))
					->where('p.status = ?', '1')
					->where('p.offertId = ?', '3')
					->where('c.tampon != ?', '1')
					->order('RAND()')
					->limit('5')
					->setIntegrityCheck(false);
			if(($result2 = $model2->fetchAll($select))) {
				return $result2;
			}
		}
	}
	// end front
	// END ALL

	// START PRODUCT
	// start front / admin
	public function productPT($productId)
	{
		$totalTaxValue = 0;
		$product = new Default_Model_Products();
		if($product->find($productId)) {
			$var2 = new Default_Model_ProductsTaxes();
			$select = $var2->getMapper()->getDbTable()->select()
					->where('id != ?', '1')
					->where('status = ?', '1')
					;
			if(($var2x = $var2->fetchAll($select))) {
				foreach($var2x as $value) {
					if($value->getType() == '%') {
						$taxValue = $product->getSellprice() * $value->getValue()/100;
					} else {
						$taxValue = $value->getValue();
					}
					$totalTaxValue += $taxValue;
				}
			}
			$price = $this->vatPrice($product->getSellprice()) + $totalTaxValue;
		}
		return $price;
	}
	// end front / admin

	// start front
	public function attribute($productId)
	{
		$model = new Default_Model_ProductAttributes();
		$select = $model->getMapper()->getDbTable()->select()
				->where('productId = ?', $productId)
				->where('position = ?', '1')
				->group('value')
				;
		if(($result = $model->fetchAll($select))) {
			return $result;
		}
	}

	public function findProduct($id)
	{
		$product = new Default_Model_Products();
		if($product->find($id)) {
			return $product;
		}
	}

	public function findAttribute($productId)
	{
		$model = new Default_Model_ProductAttributes();
		$select = $model->getMapper()->getDbTable()->select()
				->where('productId = ?', $productId)
				->where('position = ?', '1')
				->group('value')
				;
		if(($result = $model->fetchAll($select))) {
			return $result;
		}
	}
	// end front

	// start admin
	public function user($id)
	{
		$model = new Default_Model_OrderProducts();
		$select = $model->getMapper()->getDbTable()->select()
				->where('orderId = ?', $id)
				->limit(1)
				;
		if(($result = $model->fetchAll($select))) {
			$model2 = new Default_Model_AdminUser();
			if($model2->find($result[0]->getCustomerId())) {
				return $model2;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	public function productAttributes($productId)
	{
		$model = new Default_Model_ProductAttributes();
		$select = $model->getMapper()->getDbTable()->select()
				->where('productId = ?', $productId)
				->order('position')
				;
		if(($result = $model->fetchAll($select))) {
			$attribute = array();
			foreach($result as $value) {
				$attribute[$value->getValue()] = $value->getPrice();
			}
			return $attribute;
		} else {
			return null;
		}
	}

	function attrNameByIdAndPosition($productId, $position) {
		$return = null;
		$model = new Default_Model_ProductAttributes();
		$select = $model->getMapper()->getDbTable()->select()
				->where('productId = ?', $productId)
				->where('position = ?', $position)
				->limit(1);
		if(($result = $model->fetchAll($select))) {
			foreach($result as $value) {
				$return = $value->getAttribute()->getName();
			}
		}
		return $return;
	}

	public function specifivationValue($listId) {
		$model = new Default_Model_ProductSpecifications();
		$select = $model->getMapper()->getDbTable()->select()
				->where('listId = ?', $listId)
				;
		if(($result = $model->fetchAll($select))) {
			return $result[0]->getValue();
		}
	}

	public function productsNo($orderId)
	{
		$model = new Default_Model_OrderProducts();
		$select = $model->getMapper()->getDbTable()->select()
				->from($model->getMapper()->getDbTable(), array('orderId', 'id' => 'COUNT(id)'))
				->where('orderId = ?', $orderId)
				;
		if(($result = $model->fetchAll($select))) {
			return $result;
		} else {
			return null;
		}
	}
	// end admin
	// END PRODUCT

	// START CART
	public function cartProductAttributes($childrenProductId)
	{
		$response = '';
		if($childrenProductId) {
			$model = new Default_Model_ProductAttributes();
			$select = $model->getMapper()->getDbTable()->select()
					->where('productId = ?', $childrenProductId)
					;
			if(($result = $model->fetchAll($select))) {
				foreach($result as $value) {
					$response.= $value->getAttribute()->getName().': '.$value->getValue().'; ';
				}
				return $response;
			}
		} else {
			return null;
		}
	}

	public function cartProductAttributesPrice($childrenProductId)
	{
		$response = '';
		if($childrenProductId) {
			$model = new Default_Model_ProductAttributes();
			$select = $model->getMapper()->getDbTable()->select()
					->where('productId = ?', $childrenProductId)
					;
			if(($result = $model->fetchAll($select))) {
				foreach($result as $value) {
					$response += $value->getPrice();
				}
				return $response;
			}
		} else {
			return null;
		}
	}

	public function cartProductPT($productId, $childrenProductId)
	{
		$totalTaxValue = 0;
		$product = new Default_Model_Products();
		if($product->find($productId)) {
			$attributePrice = $this->cartProductAttributesPrice($childrenProductId);
			$productPrice = $product->getSellprice();
			$model = new Default_Model_ProductsTaxes();
			$select = $model->getMapper()->getDbTable()->select()
					->where('id != ?', '1')
					->where('status = ?', '1')
					;
			if(($result = $model->fetchAll($select))) {
				foreach($result as $value) {
					if($value->getType() == '%') {
						$taxValue = $productPrice * $value->getValue()/100;
					} else {
						$taxValue = $value->getValue();
					}
					$totalTaxValue += $taxValue;
				}
			}
			$price = $this->vatPrice($productPrice) + $attributePrice + $totalTaxValue;
			return $price;
		}
	}
	// END CART

	// START ORDER
	// start front / admin
	public function orederProductAttributes($childrenProductId)
	{
		$response = '';
		if($childrenProductId) {
			$model = new Default_Model_ProductAttributes();
			$select = $model->getMapper()->getDbTable()->select()
					->where('productId = ?', $childrenProductId)
					;
			if(($result = $model->fetchAll($select))) {
				foreach($result as $value) {
					if(null != $value->getValue()) {
						$response.= $value->getAttribute()->getName().': '.$value->getValue().'; ';
					}
				}
				return $response;
			}
		} else {
			return null;
		}
	}
	// end front / admin

	// start admin
	public function orderStatusChangeVerify($orderId)
	{
		$array = array();
		$model = new Default_Model_OrderProducts();
		$select = $model->getMapper()->getDbTable()->select()
				->where('orderId = ?', $orderId)
				;
		if(($result = $model->fetchAll($select))) {
			foreach($result as $value) {
				if($value->getChildrenProductId()) {
					$productId = $value->getChildrenProductId();
				} else {
					$productId = $value->getProductId();
				}
				$product = new Default_Model_Products();
				if($product->find($productId)) {
					if($product->getStockNelimitat() == 0) {
						$oldqty = $product->getStock();
						if($oldqty - $value->getQuantity() < 0) {
							$attribute = $this->orederProductAttributes($productId);
							$array[] = $product->getName().' '.$attribute;
						}
					}
				}
			}
//			echo "<pre>", print_r($array, 1), "</pre>";
//			die();
			if(null != $array) {
				return $array;
			} else {
				return 'true';
			}
		} else {
			return 'false';
		}
	}
	// end admin
	// END ORDER

	// START WISHLIST
	// start front / admin
	public function wishlistProductAttributesPrice($childrenProductId)
	{
		$response = '';
		if($childrenProductId) {
			$model = new Default_Model_ProductAttributes();
			$select = $model->getMapper()->getDbTable()->select()
					->where('productId = ?', $childrenProductId)
					;
			if(($result = $model->fetchAll($select))) {
				foreach($result as $value) {
					$response += $value->getPrice();
				}
				return $response;
			}
		} else {
			return null;
		}
	}

	public function wishlistProductPT($productId, $childrenProductId)
	{
		$totalTaxValue = 0;
		$product = new Default_Model_Products();
		if($product->find($productId)) {
			$atributePrice = $this->wishlistProductAttributesPrice($childrenProductId);
			$productPrice = $product->getSellprice();
			$model = new Default_Model_ProductsTaxes();
			$select = $model->getMapper()->getDbTable()->select()
					->where('id != ?', '1')
					->where('status = ?', '1')
					;
			if(($result = $model->fetchAll($select))) {
				foreach($result as $value) {
					if($value->getType() == '%') {
						$taxValue = $productPrice * $value->getValue()/100;
					} else {
						$taxValue = $value->getValue();
					}
					$totalTaxValue += $taxValue;
				}
			}
			$price = $this->vatPrice($productPrice) + $atributePrice + $totalTaxValue;
			return $price;
		}
	}

	function wishlistProductAttributes($childrenProductId)
	{
		$response = '';
		if($childrenProductId) {
			$model = new Default_Model_ProductAttributes();
			$select = $model->getMapper()->getDbTable()->select()
					->where('productId = ?', $childrenProductId)
					;
			if(($result = $model->fetchAll($select))) {
				foreach($result as $value) {
					$response.= $value->getAttribute()->getName().': '.$value->getValue().'; ';
				}
				return $response;
			}
		} else {
			return null;
		}
	}
	// end front / admin
	// END WISHLIST
}