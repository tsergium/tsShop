<?php
class Menu
{
	public $_subcategory;

    public function setSubcategory($title)
    {
        $this->_subcategory = $title;
        return $this;
    }

    public function getSubcategory()
    {
        return $this->_subcategory;
    }

	public function getObject($categoryId)
	{
		$model = new Default_Model_Categories();
		if($model->find($categoryId)) {
			return $model;
		} else {
			return null;
		}
	}

	public function branch($categoryId)
	{
		$categories = array();
		$model = new Default_Model_Categories();
		$select = $model->getMapper()->getDbTable()->select()
				->where('parentId = ?', $categoryId)
				;
		if(($result = $model->fetchAll($select))) {
			foreach($result as $key => $value) {
				$categories[] = $value->getId();
			}
			return $categories;
		} else {
			return null;
		}
	}

	public function branchCategory($categoryId)
	{
		$categories = array();
		$model = new Default_Model_Categories();
		$select = $model->getMapper()->getDbTable()->select()
				->where('parentId IS null')
				;
		if(($result = $model->fetchAll($select))) {
			foreach($result as $key => $value) {
				$categories[] = $value->getId();
			}
			return $categories;
		} else {
			return null;
		}
	}

	public function subcategory($categoryId)
	{
		if(!isset($subcategx)) {
			$subcategx = array();
		}
		$stack = array();
		$stack = $this->branch($categoryId);
		if(null != $stack) {
			foreach($stack as $value) {
				$category = $this->getObject($value);
				$parentId = $category->getParentId();
				if(null != $this->branch($value)) {
					if(null != $this->branch($parentId)) {
						$subcategx[$category->getId().'-x'] = $category->getName();
					}
				} else {
					$subcategx[$category->getId().'-y'] = $category->getName();
				}
				$_aux = array();
				$_aux2 = array();
				if(null != $this->getSubcategory()) {
					$_aux = $this->getSubcategory();
				}
				$_aux2 = array_diff($subcategx, $_aux);
				$this->setSubcategory(array_merge($_aux, $_aux2));
				$this->subcategory($value);
			}
		}
		return false;
	}

	public function countProduct($categoryId)
	{
		$product = new Default_Model_Products();
		$select = $product->getMapper()->getDbTable()->select()
				->from('fp_products',array('categoryId'=>'categoryId', 'prodnr'=>'COUNT(id)'))
				->where('categoryId = ?', $categoryId)
				->where('deleted = ?', '1')
				->group(array('categoryId'))
				;
		if($productx = $product->fetchAll($select)) {
			return $productx;
		} else {
			return null;
		}
	}

	public function subcategoryAdmin($categoryId)
	{
		$x = '';
		$stack = array();
		$stack = $this->branch($categoryId);
		if(null != $stack) {
			foreach($stack as $value) {
				echo '<tr>';
				$category = $this->getObject($value);
				$parentId = $category->getParentId();
				$product = $this->countProduct($value);
				if(null != $product) {
					foreach($product as $val) {
						if(null != $this->branch($value)) {
							if(null != $this->branch($parentId)) {
								echo '<td align="left"><img class="fL" src="/images/folder.png" alt="folder" /><a href="/admin/products/all/type/category/id/'.$category->getId().'" title="'.$category->getName().'">'.$category->getName().'</a></td>';
							}
						} else {
							echo '<td align="left"><img class="fL" src="/images/folder.png" alt="folder" /><a href="/admin/products/all/type/category/id/'.$category->getId().'" title="'.$category->getName().'">'.$category->getName().'</a></td>';
						}
						echo '<td align="center">'.$val->getProdnr().'</td>';
						echo '<td align="center"><a href="/admin/products/all/type/category/id/'.$category->getId().'" title="'.$category->getName().'">'.Zend_Registry::get('translate')->_('products_index_table_view').'</a></td>';
					}
				}
				echo '</tr>';
				$this->subcategoryAdmin($value);
			}
		}
	}

	function last($array)
	{
		$int = array_keys($array, end($array));
		if(null != $int) {
			return $int[0];
		} else {
			return null;
		}
	}

	public function hasSubcategory($categoryId)
	{
		$stack = array();
		$stack = $this->branch($categoryId);
		if(null != $stack) {
			return $stack;
		}
	}

	public function genealogy($categoryId)
	{
		$stack = array();
		$stack = $this->branch($categoryId);
		if(null!= $stack) {
			$lastkey = $this->last($stack);
			foreach($stack as $key => $value) {
				$category = $this->getObject($value);
				if($this->branch($value)) {
					if($key == $lastkey) {
						echo '<ul><li class="expandable lastExpandable">';
						echo '<div class="hitarea lastExpandable-hitarea"></div>';
					} else {
						echo '<ul><li class="expandable">';
						echo '<div class="hitarea expandable-hitarea"></div>';
					}
					echo '<div class="item">
						<div class="title fL">'.ucfirst($category->getName()).'</div>
						<div class="actions fL">
							<a href="/admin/products/visiblecategories/id/'.$category->getId().'">'.($category->getStatus()?Zend_Registry::get('translate')->_('products_categories_table_status_active'):Zend_Registry::get('translate')->_('products_categories_table_status_inactive')).'</a>&nbsp;|&nbsp;
							<a href="/admin/products/editcategories/id/'.$category->getId().'">'.Zend_Registry::get('translate')->_('products_categories_table_edit').'</a>&nbsp;|&nbsp;
							<a href="/admin/products/deletecategories/id/'.$category->getId().'" class="confirmDelete">'.Zend_Registry::get('translate')->_('products_categories_table_delete').'</a>
						</div>
						<div class="clear"></div>
					</div>';
				} else {
					if($key == $lastkey) {
						echo '<ul><li class="last">';
					} else {
						echo '<ul><li>';
					}
					echo '<div class="item">
						<div class="title fL">'.ucfirst($category->getName()).'</div>
						<div class="actions fL">
							<a href="/admin/products/visiblecategories/id/'.$category->getId().'">'.($category->getStatus()?Zend_Registry::get('translate')->_('products_categories_table_status_active'):Zend_Registry::get('translate')->_('products_categories_table_status_inactive')).'</a>&nbsp;|&nbsp;
							<a href="/admin/products/editcategories/id/'.$category->getId().'">'.Zend_Registry::get('translate')->_('products_categories_table_edit').'</a>&nbsp;|&nbsp;
							<a href="/admin/products/deletecategories/id/'.$category->getId().'" class="confirmDelete">'.Zend_Registry::get('translate')->_('products_categories_table_delete').'</a>
						</div>
						<div class="clear"></div>
					</div>';
				}
				$this->genealogy($value);
				if($this->branch($value)) {
					echo '</li></ul>';
				} else {
					echo '</li></ul>';
				}
			}
		}
	}

	//START products in categories
	public function selectProduct($categoryId)
	{
		$x = array();
		$model = new Default_Model_Products();
		$select = $model->getMapper()->getDbTable()->select()
				->where('categoryId = ?', $categoryId)
				;
		if(($result = $model->fetchAll($select))) {
			return $result;
		} else {
			return null;
		}
	}

	public function findProduct($id)
	{
		$model = new Default_Model_Products();
		if($model->find($id)) {
			return $model;
		} else {
			return null;
		}
	}

	public function products($categoryId)
	{
		global $prods;
		if(!isset($prods)) {
			$prods = array();
		}
		$stack = array();
		$stack = $this->branch($categoryId);
		if(null != $stack) {
			foreach($stack as $value) {
				$product = $this->selectProduct($value);
				if(null != $product) {
					foreach($product as $val) {
						$prods[] = $val->getId();
					}
				}
				$this->products($value);
			}
		}
		return implode(',', $prods);
	}
	//END products in categories

	public function searchsubcategory($categoryId)
	{
		global $subcategs;
		if(!isset($subcategs)) {
			$subcategs = array();
		}
		$stack = array();
		$stack = $this->branch($categoryId);
		if(null != $stack) {
			foreach($stack as $value) {
				$category = $this->getObject($value);
				$parentId = $category->getParentId();
				if(null != $this->branch($value)) {
					if(null != $this->branch($parentId)) {
						$subcategs[$category->getId()] = $category->getId();
					}
				} else {
					$subcategs[$category->getId()] = $category->getId();
				}
				$this->searchsubcategory($value);
			}
		return $subcategs;
		}
	}

	//START breadcrumbs
	public function branchBack($categoryId)
	{
		$categories = array();
		$model = new Default_Model_Categories();
		$select = $model->getMapper()->getDbTable()->select()
				->where('id = ?', $categoryId)
				;
		if(($result = $model->fetchAll($select))) {
			foreach($result as $key => $value) {
				$categories[] = $value->getId();
			}
			return $categories;
		} else {
			return null;
		}
	}

	public function breadcrumbs($categoryId)
	{
		global $categs;
		if(!isset($categs)) {
			$categs = array();
		}
		$stack = array();
		$stack = $this->branchBack($categoryId);
		if(null != $stack) {
			$category = $this->getObject($categoryId);
			$parentId = $category->getParentId();
			if(null != $parentId) {
				$categs[$category->getId()] = $category->getName();
				$this->breadcrumbs($parentId);
			} else {
				$categs[$category->getId()] = $category->getName();
			}
		}
		return array_reverse($categs, $category->getId());
	}
	//END breadcrumbs

	public function bestSellers($id)
	{
		$model = new Default_Model_Products();
		$select = $model->getMapper()->getDbTable()->select()
				->where('id = ?', $id)
				->where('status = ?', '1')
				->where('deleted = ?', '1')
				;
		if(($result = $model->fetchAll($select))) {
			return $result;
		} else {
			return null;
		}
	}
}