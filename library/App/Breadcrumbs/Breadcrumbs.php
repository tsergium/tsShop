<?php
class Breadcrumbs{
	public $_breadcrumbs;

    public function setBreadcrumbs($breadcrumbs)
    {
        $this->_breadcrumbs = (string) $breadcrumbs;
        return $this;
    }

    public function getBreadcrumbs()
    {
        return (null != $this->_breadcrumbs)?"Sunteti in:&nbsp;&nbsp;&nbsp;&nbsp;".$this->_breadcrumbs:null;
    }

	public function setConstructor($controller = null, $action = null, $id = null)
	{
		switch ($controller) {
			case "products":
				// PRODUCTS
				switch ($action) {
					// INDEX
//					case "index":
//						$this->productsIndex();
//					break;
					// DETAILS
					case "details":
						$this->productDetails($id);
					break;
					// DETAILS
					case "categories":
						$this->productCategories($id);
					break;
					
					// PROMOTII
					case "promotii":
						$this->productPromotii();
					break;
					
					// LICHIDARI
					case "reduceri":
						$this->productReduceri();
					break;
					
					// HAINE FEMEI
					case "hainefemei":
						$this->productHainefemei();
					break;
					// DEFAULT
					default:
						$this->defaultSeo();
					break;
				}
			break;
			// CMS
			case "cms":
				switch($action){
					// VIEW
					case "view":
						$this->cmsView($id);
					break;
					// DEFAULT
					default:
						$this->defaultSeo();
					break;
				}
			break;
			// CONTACTUS
			case "tabelmarimi":
				switch($action){
					case "index":
						$this->tabelmarimi();
					break;
					default:
						$this->defaultSeo();
					break;
				}
			break;
			case "contact":
				switch($action){
					case "index":
						$this->contactusIndex();
					break;
					default:
						$this->defaultSeo();
					break;
				}
			break;
			// DEFAULT
			default:
				$this->defaultSeo();
			break;
		}
	}
	

	public function defaultSeo()
	{
		$this->setBreadcrumbs(null);
		return false;
	}

	public function productsIndex()
	{
		$this->setTitle("Produse");
		$this->setDescription("Produse");
		$this->setKeywords("Produse");
	}

	public function productDetails($id = null)
	{
		if(null != $id){
			$model = new Default_Model_Product();
			if($model->find($id)){
				
				$categ = $this->getProdCateg($id);
				$categname = preg_replace('/[^a-zA-Z0-9]+/','_', strtolower($categ->getName()));
				
				if(null != $categ){
					$this->setBreadcrumbs("
						<a href='/' title='Home'>Home</a>
						<a href='c".$categ->getId()."-".$categname."/pagina-1.html' title='".$categ->getName()."'><strong>".$categ->getName()."</strong></a>
						<a href='#' title='".$model->getName()."'><strong>".$model->getName()."</strong></a>
					");
				}else{
					$this->setBreadcrumbs("
						<a href='/' title='Home'>Home</a>
						<a href='#' title='".$model->getName()."'><strong>".$model->getName()."</strong></a>
					");					
				}
				return  false;
			}
		}else{
			$this->defaultSeo();
			return false;
		}		
	}

	public function productCategories($id = null)
	{
		if(null != $id){
			$model = new Default_Model_Category();
			$model->find($id);
			if(null != $model){
				$this->setBreadcrumbs("
					<a href='/' title='Home'>Home</a>
					<a href='#' title='".$model->getName()."'><strong>".$model->getName()."</strong></a>
				");
				return false;
			}else{
				$this->defaultSeo();
				return false;				
			}
		}else{
			$this->defaultSeo();
			return false;
		}
	}

	public function productPromotii()
	{
		$this->setBreadcrumbs("
			<a href='/' title='Home'>Home</a>
			<a href='#' title='Promotii'><strong>Promotii</strong></a>
		");
		return false;
	}

	public function productReduceri()
	{
		$this->setBreadcrumbs("
			<a href='/' title='Home'>Home</a>
			<a href='#' title='Reduceri'><strong>Reduceri</strong></a>
		");
		return false;
	}

	public function productHainefemei()
	{
		$this->setBreadcrumbs("
			<a href='/' title='Home'>Home</a>
			<a href='#' title='Haine Femei'><strong>Haine Femei</strong></a>
		");
		return false;
	}

	public function newsIndex(){
		$this->setTitle("Stiri");
		$this->setDescription("Stiri");
		$this->setKeywords("Stiri");
	}

	public function newsDetails($id)
	{
		if(null != $id){
			$news = new Default_Model_News();
			$news->find($id);
			if(null != $news){
				$this->setTitle($news->getTitle());
				$this->setDescription($news->getTitle());
				$this->setKeywords(str_replace(" ", ", ", strtolower($news->getTitle())));
			}else{
				$this->defaultSeo();
				return false;
			}
		}else{
			$this->defaultSeo();
			return false;
		}
	}

	public function cmsView($id)
	{
		if(null != $id){
			$cms = new Default_Model_Cms();
			$select = $cms->getMapper()->getDbTable()->select()
					->where('url = ?', $id)
					->limit(1);
			$cmsa = $cms->fetchAll($select);
			if(null != $cmsa){
				$this->setBreadcrumbs("
					<a href='/' title='Home'>Home</a>
					<a href='#' title='".$cmsa[0]->getTitle()."'><strong>".$cmsa[0]->getTitle()."</strong></a>
				");
			}else{
				$this->defaultSeo();
				return false;
			}
		}else{
			$this->defaultSeo();
			return false;
		}
	}

	public function tabelmarimi()
	{
		$this->setBreadcrumbs("
			<a href='/' title='Home'>Home</a>
			<a href='#' title='Tabel marimi'><strong>Tabel marimi</strong></a>
		");
		return false;
	}

	public function contactusIndex()
	{
		$this->setBreadcrumbs("
			<a href='/' title='Home'>Home</a>
			<a href='#' title='Contact'><strong>Contact</strong></a>
		");
		return false;
	}

	// START: RECURSIVE CATEGORIES
//	public function recursiveCategories($id)
//	{
//		if(null != $id){
//			$category = new Default_Model_Category();
//			$category->find($id);
//
//			if(null != $category) {
//				return $this->breadcrumbs($category->getId());
//			}else{
//				return false;
//			}
//		}else{
//			return false;
//		}
//	}
//
//	public function branch($categoryId)
//	{
//		$categories = array();
//		$category = new Default_Model_Category();
//		$select = $category->getMapper()->getDbTable()->select()
//				->where('id = ?', $categoryId);
//		$categoryx = $category->fetchAll($select);
//		if(null != $categoryx){
//			foreach($categoryx as $key => $value){
//				$categories[] = $value->getId();
//			}
//			return $categories;
//		} else {
//			return null;
//		}
//	}
//
//	public function breadcrumbs($categoryId)
//	{
//		global $categs;
//		if(!isset($categs)) {
//			$categs = array();
//		}
//		$stack = array();
//		$stack = $this->branch($categoryId);
//		if(null != $stack){
//			$category = $this->getObject($categoryId);
//			$parentId = $category->getParentId();
//			if(null != $parentId) {
//				$categs[$category->getId()] = ucfirst($category->getName());
//				$this->breadcrumbs($parentId);
//			} else {
//				$categs[$category->getId()] = ucfirst($category->getName());
//			}
//		}
//		return array_reverse($categs, $category->getId());
//	}
//	
//	public function getObject($categoryId)
//	{
//		$category = new Default_Model_Categories();
//		if($category->find($categoryId)) {
//			return $category;
//		} else {
//			return null;
//		}
//	}
	// END: RECURSIVE CATEGORIES
	
	// BEGIN: tsPlugins
		// BEGIN: GET PRODUCT CATEGORIES
		public function getProdCateg($productId){
			if(null != $productId){
				$model = new Default_Model_Product();
				$model->find($productId);
				if(null != $model){
					$model2 = new Default_Model_Productcategasoc();
					$select = $model2->getMapper()->getDbTable()->select()
							->where('productId = ?', $model->getId())
							->limit(1);
					$result2 = $model2->fetchAll($select);
					if(null != $result2){
						foreach($result2 as $value){
							$model3 = new Default_Model_Category();
							$model3->find($value->getCategoryId());
							if(null != $model3){
								return $model3;
							}
						}
					}else{
						return null;
					}
				}else{
					return null;
				}
			}else{
				return null;
			}
		}
		// END: GET PRODUCT CATEGORIES
	// END: tsPlugins
}
?>