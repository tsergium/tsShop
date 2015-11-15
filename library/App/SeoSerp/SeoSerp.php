<?php
class SeoSerp{
	// search for tsPlugins to find used functions
	public $_title;
	public $_description;
	public $_keywords;

    public function setTitle($title)
    {
        $this->_title = (string) $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setDescription($description)
    {
        $this->_description = (string) $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setKeywords($keywords)
    {
        $this->_keywords = (string) $keywords;
        return $this;
    }

    public function getKeywords()
    {
        return $this->_keywords;
    }

	public function setSeo($controller = null, $action = null, $id = null)
	{
		switch ($controller) {
			case "products":
				// PRODUCTS
				switch ($action) {
					// INDEX
					case "index":
						$this->productsIndex();
					break;
					// DETAILS
					case "details":
						$this->productDetails($id);
					break;
					// DETAILS
					case "categories":
						$this->productCategories($id);
					break;
					
					// PROMOTII
//					case "promotii":
//						$this->productPromotii();
//					break;
					
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
			// NEWS
			case "news":
				switch ($action) {
				// INDEX
				case "index":
					$this->newsIndex();
				break;
				// DETAILS
				case "details":
					$this->newsDetails($id);
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
			case "contactus":
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
		$this->setTitle("Shoprange.org");
		$this->setDescription("Shoprange.org");
		$this->setKeywords("shoprange, shop, range");
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
			$categorie = ucfirst(getProdCateg($id));
			$product = new Default_Model_Product();
			$product->find($id);
			if(null != $product){
				$this->setTitle($product->getName()." - ".$categorie);
				$this->setDescription(strip_tags($product->getDescription()));
				$this->setKeywords($product->getName().", ".$categorie.", lenjerie intima,  lenjerie de lux, lenjerie intima de dama, haine femei, haine dama, haine de femei, haine de dama, bluze, pantaloni,corsete, rochite de vara, rochite scurte, rochite sexi,costume craciunite, rochite, stockings, costum craciunita, lenjerii intime, uniforme sexy, costume sexy, lenjerie, costume de baie, bikini, babydolls, corsete ,craciunite");
			}else{
				$this->defaultSeo();
				return false;
			}
		}else{
			$this->defaultSeo();
			return false;
		}
	}

	public function productCategories($id = null)
	{
		if(null != $id){
			$category = new Default_Model_Category();
			$category->find($id);
			if(null != $category){
				$this->setTitle(implode(" | ", $this->recursiveCategories($id)));
				$this->setDescription(implode(" ", $this->recursiveCategories($id)));
				$this->setKeywords(implode(", ", $this->recursiveCategories($id)));
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
		$this->setTitle('Promotii Lenjerie de lux | Lenjerie | Haine femei | Haine dama | Costum craciunita');
		$this->setDescription('Promotii, Lenjerie intima de dama oferita de Lenjerie-de-lux.ro contine Lenjerie intima, stockings, uniforme sexy, babydolls, corsete, costume sexy, cracinite,  haine femei, haine dama, haine de femei, haine de dama, bluze, pantaloni,corset de zi, rochite de vara, rochite scurte, corset de noapte, rochite sexi,costume craciunite, Costum craciunita, costume de baie');
		$this->setKeywords("Lenjerie de Lux, lenjerie intima, Costum craciunita, costume craciunite, lenjerie intime, lenjerii intime, Lenjerie intima, Lenjerie, haine femei, haine dama, haine de femei, haine de dama, bluze, pantaloni,corsete, rochite de vara, rochite scurte, rochite sexi, Costume de baie, Lenjerie sexi, Lenjerie intima de dama, Rochite, stockings, uniforme sexy, corset de noapte, costume sexy, lenjerie, bikini, babydolls, corset de zi, Valentine's day lenjerie, Cadou Valentine's, craciunite");
	}

	public function productReduceri()
	{
		$this->setTitle('Reduceri Lenjerie de lux | Lenjerie | Haine femei | Haine dama | Costum craciunita');
		$this->setDescription('Lenjerie intima de dama oferita de Lenjerie-de-lux.ro contine Lenjerie intima, stockings, uniforme sexy, babydolls, corsete, costume sexy, cracinite,  haine femei, haine dama, haine de femei, haine de dama, bluze, pantaloni,corset de zi, rochite de vara, rochite scurte, corset de noapte, rochite sexi,costume craciunite, Costum craciunita, costume de baie');
		$this->setKeywords("Lenjerie de Lux, lenjerie intima, Costum craciunita, costume craciunite, lenjerie intime, lenjerii intime, Lenjerie intima, Lenjerie, haine femei, haine dama, haine de femei, haine de dama, bluze, pantaloni,corsete, rochite de vara, rochite scurte, rochite sexi, Costume de baie, Lenjerie sexi, Lenjerie intima de dama, Rochite, stockings, uniforme sexy, corset de noapte, costume sexy, lenjerie, bikini, babydolls, corset de zi, Valentine's day lenjerie, Cadou Valentine's, craciunite");
	}

	public function productHainefemei()
	{
		$this->setTitle('Haine Femei Lenjerie de lux | Lenjerie | Haine femei | Haine dama | Costum craciunita');
		$this->setDescription('Lenjerie intima de dama oferita de Lenjerie-de-lux.ro contine Lenjerie intima, stockings, uniforme sexy, babydolls, corsete, costume sexy, cracinite,  haine femei, haine dama, haine de femei, haine de dama, bluze, pantaloni,corset de zi, rochite de vara, rochite scurte, corset de noapte, rochite sexi,costume craciunite, Costum craciunita, costume de baie');
		$this->setKeywords("Lenjerie de Lux, lenjerie intima, Costum craciunita, costume craciunite, lenjerie intime, lenjerii intime, Lenjerie intima, Lenjerie, haine femei, haine dama, haine de femei, haine de dama, bluze, pantaloni,corsete, rochite de vara, rochite scurte, rochite sexi, Costume de baie, Lenjerie sexi, Lenjerie intima de dama, Rochite, stockings, uniforme sexy, corset de noapte, costume sexy, lenjerie, bikini, babydolls, corset de zi, Valentine's day lenjerie, Cadou Valentine's, craciunite");
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
				$this->setTitle($cmsa[0]->getTitle());
				$this->setDescription($cmsa[0]->getTitle());
				$this->setKeywords(str_replace(" ", ", ", strtolower($cmsa[0]->getTitle())));
			}else{
				$this->defaultSeo();
				return false;
			}
		}else{
			$this->defaultSeo();
			return false;
		}
	}

	public function contactusIndex()
	{
		$this->setTitle("Contact");
		$this->setDescription("Contact");
		$this->setKeywords("contact");
	}

	// START: RECURSIVE CATEGORIES
	public function recursiveCategories($id)
	{
		if(null != $id){
			$category = new Default_Model_Category();
			$category->find($id);

			if(null != $category) {
				return $this->breadcrumbs($category->getId());
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function branch($categoryId)
	{
		$categories = array();
		$category = new Default_Model_Category();
		$select = $category->getMapper()->getDbTable()->select()
				->where('id = ?', $categoryId)
				;
		if($categoryx = $category->fetchAll($select)) {
			foreach($categoryx as $key => $value) {
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
		$stack = $this->branch($categoryId);
		if(null != $stack){
			$category = $this->getObject($categoryId);
			$parentId = $category->getParentId();
			if(null != $parentId) {
				$categs[$category->getId()] = ucfirst($category->getName());
				$this->breadcrumbs($parentId);
			} else {
				$categs[$category->getId()] = ucfirst($category->getName());
			}
		}
		return array_reverse($categs, $category->getId());
	}
	
	public function getObject($categoryId)
	{
		$category = new Default_Model_Category();
		if($category->find($categoryId)) {
			return $category;
		} else {
			return null;
		}
	}
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
								return $model3->getName();
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