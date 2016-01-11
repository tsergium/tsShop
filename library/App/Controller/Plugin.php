<?php
class App_Controller_Plugin extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
		// GET MODULE/CONTROLLER/ACTION
		$module		 = $request->getModuleName();
		$controller	 = $request->getControllerName();
		$action		 = $request->getActionName();

		// SET AUTH SESSION
		if($module == 'admin'){
		    $auth = Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('admin'));
		}else{
		    $auth = Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('user'));
		}
		// SET LAYOUT
	    	$layout = Zend_Layout::getMvcInstance();

		// SEND MODULE/CONTROLLER/ACTION
		$layout->getView()->module = $module;
		$layout->getView()->controller = $controller;
		$layout->getView()->action = $action;

		// BEGIN: SLIDER
		$model = new Default_Model_Slider();
		$select = $model->getMapper()->getDbTable()->select();
		$slides = $model->fetchAll($select);
		if(null != $slides){
		    $layout->getView()->slides = $slides;
		}
		// END: SLIDER

		// BEGIN: ACTIVE CATEGORIES
		$model = new Default_Model_Category();
		$select = $model->getMapper()->getDbTable()->select()
				->where('parentId IS NULL')
				->where('id != ?', '329')
				->order('name ASC');
		$categories = $model->fetchAll($select);
		if(null != $categories){
			$layout->getView()->categories = $categories;
		}
		// END: ACTIVE CATEGORIES

		// BEGIN: ACTIVE PARTNERS
		$model = new Default_Model_Partner();
		$select = $model->getMapper()->getDbTable()->select()
				->order('created ASC');
		$partners = $model->fetchAll($select);
		if(null != $partners){
			$layout->getView()->partners = $partners;
		}
		// END: ACTIVE PARTNERS

		// BEGIN: TRANSLATE
		$translate = new Zend_Translate('csv', 'languages/en.csv', 'en');
		$translate->addTranslation('languages/ro.csv', 'ro');

		$language = new Default_Model_Languages();
		$select_lang = $language->getMapper()->getDbTable()->select()
    			->where('status = ?', '1');
    	if(($select_lang_sel = $language->fetchAll($select_lang))) {
			//include('languages/'.$select_lang_sel[0]->getFile().'.php');
			$aux = explode('_', $select_lang_sel[0]->getFile());
			$validLang = $aux[1];
			$layout->getView()->language = $validLang;
			Zend_Registry::set('lang', $validLang);
			$translate->setLocale($aux[1]);

		} else {
			//include('languages/lang_en.php');
			$validLang = 'en';
			$layout->getView()->language = $validLang;
			Zend_Registry::set('lang', $validLang);
			$translate->setLocale('en');
		}

		Zend_Registry::set('translate', $translate);
		// END: TRANSLATE
		
		// BEGIN: GET SUBCATEGORIES FOR CATEGORY
		if(!function_exists('getSubcatForCat')){
			function getSubcatForCat($categId)
			{
				if(null != $categId){
					$model = new Default_Model_Category();
					$select = $model->getMapper()->getDbTable()->select()
							->where('parentId = ?', $categId)
							->order('name ASC');
					$result = $model->fetchAll($select);
					if(null != $result){
						return $result;
					}else{
						return null;
					}
				}else{
					return null;
				}
			}
		}

		if($module == 'default'){
			// BEGIN: FETCH PRODUCT GALLERY FOR PRODUCT
			if(!function_exists('getProductGallery')){
				function getProductGallery($productId){
					if(null != $productId){
						$model = new Default_Model_Productgallery();
						$select = $model->getMapper()->getDbTable()->select()
								->where('productId = ?', $productId);
						$result = $model->fetchAll($select);
						if(null != $result){
							return $result;
						}else{
							return null;
						}
					}else{
						return null;
					}
				}
			}
			// END: FETCH PRODUCT GALLERY FOR PRODUCT

			// BEGIN: GET SIZES FOR PRODUCT
			if(!function_exists('prodGetSize')){
				function prodGetSize($productId){
					if(null != $productId){
						$idArray = array();
						$model = new Default_Model_ProductAttribute();
						$select = $model->getMapper()->getDbTable()->select()
								->where('groupId = ?', '1')
								->where('productId = ?', $productId);
						$result = $model->fetchAll($select);
						if(null != $result){
							foreach($result as $value){
								$idArray[] = $value->getValueId();
							}
							if(null != $idArray){
								$model = new Default_Model_ProductAttributeValue();
								$select = $model->getMapper()->getDbTable()->select()
										->where('id IN (?)', $idArray)
										->order('order ASC');
								$result = $model->fetchAll($select);
								if(null != $result){
									return $result;
								}else{
									return null;
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
			}

			// BEGIN: CHECK IF PRODUCTS IN CART ARE DELETED
			if(!function_exists('checkDeletedProductsInCart')){
				function checkDeletedProductsInCart($cookie){
					$model = new Default_Model_Cart();
					$select = $model->getMapper()->getDbTable()->select()
							->where('cookie = ?', $cookie);
					$result = $model->fetchAll($select);
					foreach($result as $value){
						$prodModel = new Default_Model_Product();
						if(!$prodModel->find($value->getProductId())){
							$value->delete();
						}
					}
				}
			}
			// END: CHECK IF PRODUCTS IN CART ARE DELETED

			// BEGIN: GET CATEGORY MODEL BY NAME
			if(!function_exists('getCategModelByName')){
				function getCategModelByName($name)
				{
					if(null != $name){
						$model = new Default_Model_Category();
						$select = $model->getMapper()->getDbTable()->select()
								->where('name = ?', $name)
								->order('RAND()');
						$result = $model->fetchAll($select);
						if(null != $result){
							if(isset($result[0])){
								return $result[0];
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
			}

			// END: GET CATEGORY MODEL BY NAME

			// END: GET COLORS FOR PRODUCT
			if(!function_exists('prodGetColor')){
				function prodGetColor($productId){
					if(null != $productId){
						$idArray = array();
						$model = new Default_Model_ProductAttribute();
						$select = $model->getMapper()->getDbTable()->select()
								->where('groupId = ?', '2')
								->where('productId = ?', $productId);
						$result = $model->fetchAll($select);
						if(null != $result){
							foreach($result as $value){
								$idArray[] = $value->getValueId();
							}
							if(null != $idArray){
								$model = new Default_Model_ProductAttributeValue();
								$select = $model->getMapper()->getDbTable()->select()
										->where('id IN (?)', $idArray);
								$result = $model->fetchAll($select);
								if(null != $result){
									return $result;
								}else{
									return null;
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
			}
			// END: GET COLORS FOR PRODUCT

			// BEGIN: GET PRODUCT CATEGORIES
			if(!function_exists('getProdCateg')){
				function getProdCateg($productId){
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
			}
			// END: GET PRODUCT CATEGORIES

			// BEGIN: SET COOKIE
			if(empty($_SESSION['cartId'])) {
				function GetCartId() {
					global $_COOKIE;
					if(isset($_COOKIE['cartId'])) return $_COOKIE['cartId'];
					else {
						$tt = uniqid(rand());
						setcookie('cartId',$tt, time() + ((3600 * 24) * 30));
						return $tt;
					}
				}
				$_SESSION['cartId'] = GetCartId();
			}
			// END: SET COOKIE

			// BEGIN: SHOW PRODUCTS IN CART
			$prodCartNr = 0;
			$prodCartTotal = 0;
			$var = new Default_Model_Cart();
			$select = $var->getMapper()->getDbTable()->select()
					->where('cookie = ?', $_SESSION['cartId']);
			$result = $var->fetchAll($select);
			if(null != $result){
				foreach($result as $value){
					$prodCartNr += $value->getQuantity();
					$module = new Default_Model_Product();
					$module->find($value->getProductId());
					if(null != $module){
						$prodCartTotal += $module->getPrice()*$value->getQuantity();
					}
				}
				$layout->getView()->prodCartNr = $prodCartNr;
				$layout->getView()->prodCartTotal = $prodCartTotal;
			}
			// END: SHOW PRODUCTS IN CART
		}


		// END: GET SUBCATEGORIES FOR CATEGORY

    	$acl = new Zend_Acl();
    	$acl->add(new Zend_Acl_Resource('admin:auth'))
			->add(new Zend_Acl_Resource('admin:index'))
			->add(new Zend_Acl_Resource('admin:slider'))
			->add(new Zend_Acl_Resource('admin:brand'))
			->add(new Zend_Acl_Resource('admin:category'))
			->add(new Zend_Acl_Resource('admin:product'))
			->add(new Zend_Acl_Resource('admin:order'))
			->add(new Zend_Acl_Resource('admin:client'))
			->add(new Zend_Acl_Resource('admin:partner'))
			->add(new Zend_Acl_Resource('admin:dashboard'))
			->add(new Zend_Acl_Resource('admin:sales'))
			->add(new Zend_Acl_Resource('admin:operators'))
			->add(new Zend_Acl_Resource('admin:customers'))
			->add(new Zend_Acl_Resource('admin:products'))
			->add(new Zend_Acl_Resource('admin:marketing'))
			->add(new Zend_Acl_Resource('admin:reports'))
			->add(new Zend_Acl_Resource('admin:cms'))
            ->add(new Zend_Acl_Resource('admin:coupon'))
            ->add(new Zend_Acl_Resource('admin:voucher'))
			->add(new Zend_Acl_Resource('admin:settings'))
			->add(new Zend_Acl_Resource('admin:template'));
    	$acl->add(new Zend_Acl_Resource('default:auth'));

		$acl->addRole(new Zend_Acl_Role('guest'))
		    ->addRole(new Zend_Acl_Role('admin'))
		    ->addRole(new Zend_Acl_Role('operator'))
			->addRole(new Zend_Acl_Role('customer'))
		    ->addRole(new Zend_Acl_Role('company'));

		$acl->allow('guest', 'default:auth', 'login');
		$acl->allow('guest', 'admin:auth', 'login');

		$acl->deny('admin', 'default:auth', 'login');
		$acl->deny('admin', 'admin:auth', 'login');
		$acl->allow('admin');

		$acl->deny('operator', 'default:auth', 'login');
		$acl->deny('operator', 'admin:auth', 'login');
		$acl->deny('operator', 'admin:operators', 'operators');
		$acl->deny('operator', 'admin:customers', 'customers');
		$acl->deny('operator', 'admin:products', 'products');
		$acl->deny('operator', 'admin:marketing', 'marketing');
		$acl->deny('operator', 'admin:cms', 'cms');
		$acl->deny('operator', 'admin:settings', 'settings');
		$acl->deny('operator', 'admin:template', 'template');
		$acl->allow('operator');

	    $accountRole = 'guest';

	    if($auth->hasIdentity()){
		$accountAuth = $auth->getStorage()->read();
		if(is_object($accountAuth)){
		    if($validLang == 'en') {
			$month = array('1'=>'January', '2'=>'February', '3'=>'March', '4'=>'April', '5'=>'May', '6'=>'June', '7'=>'July', '8'=>'August', '9'=>'September', '10'=>'Octomber', '11'=>'November', '12'=>'December');
			$day = array('1'=>'Monday', '2'=>'Tuesday', '3'=>'Wednesday', '4'=>'Thursday', '5'=>'Friday', '6'=>'Saturday', '7'=>'Sunday');
		    } else {
			$month = array('1'=>'Ianuarie', '2'=>'Februarie', '3'=>'Martie', '4'=>'Aprilie', '5'=>'Mai', '6'=>'Iunie', '7'=>'Iulie', '8'=>'August', '9'=>'Septembrie', '10'=>'Octombrie', '11'=>'Noiembrie', '12'=>'Decembrie');
			$day = array('1'=>'Luni', '2'=>'Marti', '3'=>'Miercuri', '4'=>'Joi', '5'=>'Vineri', '6'=>'Sambata', '7'=>'Duminica');
		    }
		    $currentMonth = 1;
		    $currentDay = 1;

		    if($module == 'admin'){
			$currentMonth = date('n', $accountAuth->getLastlogin());
			$currentDay = date('N', $accountAuth->getLastlogin());

			if(null !== $accountAuth->getRole()) {
			    $accountRole = $accountAuth->getRole()->getName();
			}
		    }

		    $layout->getView()->currentMonth = $month[$currentMonth];
		    $layout->getView()->currentDay = $day[$currentDay];
		    $layout->getView()->authAccount = $accountAuth;
		}
	    }

		// Begin: Site settings
		$tmpDisplay = '';
		$tmpProducts = '';
		$adminProdsPage = '';
		$model = new Default_Model_Setting();
		$select = $model->getMapper()->getDbTable()->select();
		if(($result = $model->fetchAll($select))) {
			$layout->getView()->pricetype = $result[2]->getValue();
			$layout->getView()->weighttype = $result[3]->getValue();
			$layout->getView()->warrantytype = $result[4]->getValue();
			$layout->getView()->pgt = $result[6]->getValue();
			foreach($result as $value) {
				defined($value->getConst())
					|| define($value->getConst(), $value->getValue());
				$aux = $value->getConst();
				if($aux == 'products') {
					$tmpProducts = $value->getValue();
				} elseif($aux == 'display') {
					$tmpDisplay = $value->getValue();
				} elseif($aux == 'adminProdsPage') {
					$adminProdsPage = $value->getValue();
				}
			}
		}

		if(empty($_SESSION['display'])) {
			$_SESSION['display'] = $tmpDisplay;
		}
		if(empty($_SESSION['products'])) {
			$_SESSION['products'] = $tmpProducts;
		}
		Zend_Registry::set('products', $tmpProducts);


		if(empty($_SESSION['adminProdsPage'])) { $_SESSION['adminProdsPage'] = $adminProdsPage; }
		Zend_Registry::set('adminProdsPage', $adminProdsPage);

		if(!empty($_SESSION['adminProdsPage'])) {
			$layout->getView()->adminProdsPage = $_SESSION['adminProdsPage'];
		} else {
			$layout->getView()->adminProdsPage = Zend_Registry::get('adminProdsPage');
		}

		$model = new Default_Model_SettingValue();
    	$select = $model->getMapper()->getDbTable()->select()
				->where('settingId = ?', '8');
    	if(($result = $model->fetchAll($select))) {
			$layout->getView()->adminProdsPageValues = $result;
		}
		// End: Site settings

		// Begin: company
		$model = new Default_Model_Company();
		$select = $model->getMapper()->getDbTable()->select();
		if(($result = $model->fetchAll($select))) {
			$layout->getView()->company = $result;
			$companyName = $result[0]->getInstitution();
		}
		// End: company

		$productId = null;
		if($module == 'admin' && $controller == 'products') {
			$productId = $this->getRequest()->getParam('id');
		}

		// BEGIN: SLIDER EFFECT
		$model = new Default_Model_Setting();
		$select = $model->getMapper()->getDbTable()->select()
				->where('const = ?', 'slideEffect');
		$result = $model->fetchAll($select);
		if(null != $result){
			$layout->getView()->slideEffect = $result[0]->getValue();
		}
		// END: SLIDER EFFECT

	    // BEGIN: BREADCRUMBS ADMIN
	    $prodDetAdmin = "";
	    if($module == 'admin' && $controller == 'products' && ($action == "details" || $action == "addproductimage" || $action == "editproducts" || $action == "addproductspecifications" || $action == "editproductspecifications" || $action == "addproductattributes" || $action == "editproductattributes")) {
		$productId = $this->getRequest()->getParam('id');
		$product = new Default_Model_Products();
		$product->find($productId);
			if(null != $product) {
			    $prodDetAdmin = $product->getName();
			}
	    }
	    // END: BREADCRUMBS ADMIN

	    switch($module) {
		case 'admin' :
		    $layout->getView()->headLink()->prependStylesheet('/css/admin/style.css')
			    ->appendStylesheet('/js/validationEngine/validationEngine.css')
			    ->appendStylesheet('/js/jquery-ui/css/smoothness/jquery-ui.css')
			    ->appendStylesheet('/js/fancybox/jquery.fancybox-1.3.1.css');

				$layout->getView()->headScript()->prependFile('/js/jquery-1.6.1.min.js')
												->appendFile('/js/jquery-ui/js/jquery-ui.js')
												->appendFile('/js/validationEngine/'.$validLang.'.js')
//												->appendFile('/js/validationEngine/validationEngine.js')
												->appendFile('/js/validationEngine/validationEngine.js')
												->appendFile('/js/textarea_autoresize.js')
												->appendFile('/js/easySlider.packed.js')
												->appendFile('/js/fancybox/jquery.fancybox-1.3.1.js')
												->appendFile('/js/printArea.js');

				$pages = array(
					//////INDEX//////
					array(
						'label'      => '<b><b>Profil Admin</b></b>',
						'title'      => 'Profil Admin',
						'module'     => 'admin',
						'controller' => 'index',
						'resource'   => 'admin:index',
						'pages'		 => array(
							array(
								'label'      => '<b><b>Editare Detalii Companie</b></b>',
								'title'      => 'Editare Detalii Companie',
								'module'     => 'admin',
								'controller' => 'index',
								'action'	 => 'editcompany',
								'visible'	 => false,
							),
						),
					),
					//////SLIDER//////
					array(
						'label'      => '<b><b>Slider</b></b>',
						'title'      => 'Slider',
						'module'     => 'admin',
						'controller' => 'slider',
						'resource'   => 'admin:slider',
						'pages'		 => array(
							array(
								'label'      => '<b><b>Slider administration</b></b>',
								'title'      => 'Slider administration',
								'module'     => 'admin',
								'controller' => 'slider',
								'action'	 => 'index',
							),
							array(
								'label'      => '<b><b>Add Slide</b></b>',
								'title'      => 'Add Slide',
								'module'     => 'admin',
								'controller' => 'slider',
								'action'	 => 'add',
							),
							array(
								'label'      => '<b><b>Modify Slide</b></b>',
								'title'      => 'Modify Slide',
								'module'     => 'admin',
								'controller' => 'slider',
								'action'	 => 'edit',
								'visible'	 => false,
							),
						),
					),
					//////CATEGORY//////
					array(
						'label'      => '<b><b>Product Categories</b></b>',
						'title'      => 'Product Categories',
						'module'     => 'admin',
						'controller' => 'category',
						'resource'   => 'admin:category',
						'pages'		 => array(
							array(
								'label'      => '<b><b>Category Administration</b></b>',
								'title'      => 'Administrare Categorii',
								'module'     => 'admin',
								'controller' => 'category',
								'action' => 'index',
							),
							array(
								'label'      => '<b><b>Add Category</b></b>',
								'title'      => 'Add Category',
								'module'     => 'admin',
								'controller' => 'category',
								'action' => 'add',
							),
						),
					),
					array(
						'label'      => '<b><b>Brands</b></b>',
						'title'      => 'Brands',
						'module'     => 'admin',
						'controller' => 'brand',
						'resource'   => 'admin:brand',
						'pages'		 => array(
						),
					),
					//////PRODUCT//////
					array(
						'label'      => '<b><b>Products</b></b>',
						'title'      => 'Products',
						'module'     => 'admin',
						'controller' => 'product',
						'resource'   => 'admin:product',
						'pages'		 => array(
							array(
								'label'      => '<b><b>Product Administration</b></b>',
								'title'      => 'Product administration',
								'module'     => 'admin',
								'controller' => 'product',
								'action'	 => 'index',
							),
							array(
								'label'      => '<b><b>Add products</b></b>',
								'title'      => 'Add products',
								'module'     => 'admin',
								'controller' => 'product',
								'action'	 => 'add',
							),
							array(
								'label'      => '<b><b>Modify products</b></b>',
								'title'      => 'Modify Products',
								'module'     => 'admin',
								'controller' => 'product',
								'action'	 => 'edit',
								'visible'	 		=> false,
							),
							array(
								'label'      		=> '<b><b>Products without categories</b></b>',
								'title'      		=> 'Products without categories',
								'module'     		=> 'admin',
								'controller' 		=> 'product',
								'action'	 		=> 'nocateg',
							),
							array(
								'label'      		=> '<b><b>Product attributes</b></b>',
								'title'      		=> 'Product attributes',
								'module'     		=> 'admin',
								'controller' 		=> 'product',
								'action'	 		=> 'attributes',
								'pages'		 		=> array(
									array(
										'label'		    => 'Add sizes',
										'title'		    => 'Add sizes',
										'module'	    => 'admin',
										'controller'	=> 'product',
										'action'    	=> 'addattributesize'
									),
									array(
										'label'	    	=> 'Add colors',
										'title'	    	=> 'Add colors',
										'module'    	=> 'admin',
										'controller'	=> 'product',
										'action'    	=> 'addattributecolor'
									),
									array(
										'label'		    => 'Washing instructions',
										'title'		    => 'Washing instructions',
										'module'    	=> 'admin',
										'controller'	=> 'product',
										'action'    	=> 'productintructions'
									),
									array(
										'label'	    	=> 'Modify Washing instructions',
										'title'	    	=> 'Modify Washing instructions',
										'module'    	=> 'admin',
										'controller'	=> 'product',
										'action'    	=> 'editinstructions',
										'visible'   	=> false,
									),
									array(
										'label'	    	=> 'Edit attributes',
										'title'	    	=> 'Edit attributes',
										'module'	    => 'admin',
										'controller'	=> 'product',
										'action'    	=> 'editattribute',
										'visible'   	=> false,
									),
								),
							),
							array(
								'label'      			=> '<b><b>Photo gallery</b></b>',
								'title'      			=> 'Photo gallery',
								'module'     			=> 'admin',
								'controller' 			=> 'product',
								'action'	 			=> 'gallery',
								'pages'					=> array(
									array(
										'label'     	=> '<b><b>Add pictures</b></b>',
										'title'      	=> 'Add pictures',
										'module'     	=> 'admin',
										'controller' 	=> 'product',
										'action'	 	=> 'addgallery',
										'visible'		=> false,
									),
								),
							),
						),
					),
					//////ORDER//////
					array(
						'label'      => '<b><b>Orders</b></b>',
						'title'      => 'Orders',
						'module'     => 'admin',
						'controller' => 'order',
						'resource'   => 'admin:order',
						'pages'		 => array(
							array(
								'label'      => '<b><b>Orders Administration</b></b>',
								'title'      => 'Orders Administration',
								'module'     => 'admin',
								'controller' => 'order',
								'action' => 'index',
							),
							array(
								'label'      => '<b><b>Search Orders</b></b>',
								'title'      => 'Search Orders',
								'module'     => 'admin',
								'controller' => 'order',
								'action'	 => 'search',
							),
							array(
								'label'      => '<b><b>Details</b></b>',
								'title'      => 'Details',
								'module'     => 'admin',
								'controller' => 'order',
								'action'	 => 'details',
								'visible'	 => false,
							),
						),
					),
					//////CLIENTS//////
					array(
						'label'      => '<b><b>Clients</b></b>',
						'title'      => 'Clients',
						'module'     => 'admin',
						'controller' => 'client',
						'resource'   => 'admin:client',
						'pages'		 => array(
							array(
								'label'      => '<b><b>Client Administration</b></b>',
								'title'      => 'Client Administration',
								'module'     => 'admin',
								'controller' => 'client',
								'action' => 'index',
								'pages'		=> array(
									array(
										'label'      => '<b><b>Modify Clients</b></b>',
										'title'      => 'Modify Clients',
										'module'     => 'admin',
										'controller' => 'client',
										'action' => 'edit',
										'visible'	 => false,
									),
									array(
										'label'      => '<b><b>Client Details</b></b>',
										'title'      => 'Client Details',
										'module'     => 'admin',
										'controller' => 'client',
										'action' => 'details',
										'visible'	 => false,
									),
								),

							),
							array(
								'label'      => '<b><b>Client Search</b></b>',
								'title'      => 'Client Search',
								'module'     => 'admin',
								'controller' => 'client',
								'action'	 => 'search',
							),
						),
					),
					//////PARTNERS//////
					array(
						'label'      => '<b><b>Partners</b></b>',
						'title'      => 'Partners',
						'module'     => 'admin',
						'controller' => 'partner',
						'resource'   => 'admin:partner',
						'pages'		 => array(
							array(
								'label'      => '<b><b>Partners Administration</b></b>',
								'title'      => 'Partners Administration',
								'module'     => 'admin',
								'controller' => 'partner',
								'action'	 => 'index',
							),
							array(
								'label'      => '<b><b>Add Partener</b></b>',
								'title'      => 'Add Partener',
								'module'     => 'admin',
								'controller' => 'partner',
								'action'	 => 'add',
							),
							array(
								'label'      => '<b><b>Modify Partner</b></b>',
								'title'      => 'Modify Partner',
								'module'     => 'admin',
								'controller' => 'partner',
								'action'	 => 'edit',
								'visible'	 => false,
							),
						),
					),
					//////EMAIL TEMPLATES//////
					array(
						'label'      => '<b><b>Email Templates</b></b>',
						'title'      => 'Email Templates',
						'module'     => 'admin',
						'controller' => 'template',
						'resource'   => 'admin:template',
						'action'	 => 'index',
						'params'	 => array('const'=>'signUp'),
						'pages'		 => array(
							array(
								'label'      => '<b><b>Registration Email</b></b>',
								'title'      => 'Registration Email',
								'module'     => 'admin',
								'controller' => 'template',
								'action'	 => 'index',
								'params'	 => array('const'=>'signUp')
							),
							array(
								'label'      => '<b><b>Forgot Password Email</b></b>',
								'title'      => 'Forgot Password Email',
								'module'     => 'admin',
								'controller' => 'template',
								'action'     => 'index',
								'params'	 => array('const'=>'forgotPassTemplate')
							),
							array(
								'label'      => '<b><b>Activation email for newsetter subscribtion</b></b>',
								'title'      => 'Activation email for newsetter subscribtion',
								'module'     => 'admin',
								'controller' => 'template',
								'action' => 'index',
								'params'	 => array('const'=>'subscribersEmail')
							),
							array(
								'label'      => '<b><b>Order Email</b></b>',
								'title'      => 'Order Email',
								'module'     => 'admin',
								'controller' => 'template',
								'action' => 'index',
								'params'	 => array('const'=>'gotSaleEmail')
							),
						),
					),
					array(
						'label'      => '<b><b>CMS</b></b>',
						'title'      => 'CMS',
						'module'     => 'admin',
						'controller' => 'cms',
						'resource'   => 'admin:cms',
						'action'	 => 'index',
						'params'	 => array('id'=>'1'),
						'pages'		 => array(
							array(
								'label'      => '<b><b>FAQ</b></b>',
								'title'      => 'FAQ',
								'module'     => 'admin',
								'controller' => 'cms',
								'resource'   => 'admin:cms',
								'action'	 => 'index',
								'params'	 => array('id'=>'1'),
							),
							array(
								'label'      => '<b><b>Terms and conditions</b></b>',
								'title'      => 'Terms and conditions',
								'module'     => 'admin',
								'controller' => 'cms',
								'resource'   => 'admin:cms',
								'action'	 => 'index',
								'params'	 => array('id'=>'5'),
							),
						),
					),
					array(
						'label'      => '<b><b>Newsletter</b></b>',
						'title'      => 'Newsletter',
						'module'     => 'admin',
						'controller' => 'newsletter',
						'action'     => 'newsletter',
						'pages'		 => array(
							array(
								'label'      => '<b><b>Send Mail</b></b>',
								'title'      => 'Send Mail',
								'module'     => 'admin',
								'controller' => 'newsletter',
								'action'     => 'newsletter',
							),
							array(
								'label'      => '<b><b>Subscribers</b></b>',
								'title'      => 'Subscribers',
								'module'     => 'admin',
								'controller' => 'newsletter',
								'action'	 => 'index',
							),
						),
					),
                    //////COUPON//////
                    array(
                        'label'      => '<b><b>Coupons</b></b>',
                        'title'      => 'Coupons',
                        'module'     => 'admin',
                        'controller' => 'coupon',
                        'resource'   => 'admin:coupon',
                        'pages'		 => array(
                            array(
                                'label'      => '<b><b>Coupons Administration</b></b>',
                                'title'      => 'Coupon Administration',
                                'module'     => 'admin',
                                'controller' => 'coupon',
                                'action' => 'index',
                            ),
                            array(
                                'label'      => '<b><b>Adauga Cupon</b></b>',
                                'title'      => 'Adauga Cupon',
                                'module'     => 'admin',
                                'controller' => 'coupon',
                                'action' => 'add',
                            ),
                        ),
                    ),
                    //////VOUCHER//////
                    array(
                        'label'      => '<b><b>Cart Price Rules</b></b>',
                        'title'      => 'Cart Price Rules',
                        'module'     => 'admin',
                        'controller' => 'voucher',
                        'resource'   => 'admin:voucher',
                        'pages'         => array(
                            array(
                                'label'      => '<b><b>Cart Price Rules Administration</b></b>',
                                'title'      => 'Cart Price Rules Administration',
                                'module'     => 'admin',
                                'controller' => 'voucher',
                                'action' => 'index',
                            ),
                        ),
					),
					array(
						'label'      => '<b><b>Settings</b></b>',
						'title'      => 'Settings',
						'module'     => 'admin',
						'controller' => 'settings',
						'action'     => 'index',
					        'pages'		 => array(
							array(
								'label'      => '<b><b>Slider</b></b>',
								'title'      => 'Slider',
								'module'     => 'admin',
								'controller' => 'settings',
								'action'     => 'index',
							),
							array(
								'label'      => '<b><b>Payment Methods and Delivery</b></b>',
								'title'      => 'Payment Methods and Delivery',
								'module'     => 'admin',
								'controller' => 'settings',
								'action'     => 'paymentmethods',
							),
							array(
								'label'      => '<b><b>General settings</b></b>',
								'title'      => 'General Settings',
								'module'     => 'admin',
								'controller' => 'settings',
								'action'     => 'general',
							),
						 ),
					),
					array(
						'label'      => '<b><b>'.Zend_Registry::get('translate')->_('admin_menu_logout').'</b></b>',
						'title'      => Zend_Registry::get('translate')->_('admin_menu_logout'),
						'module'     => 'admin',
						'controller' => 'auth',
						'action'     => 'logout',
						'resource'	 => 'admin:auth',
						'privilege'	 => 'logout',
					),

				);

				// Create container from array
				$container = new Zend_Navigation($pages);
				$layout->getView()->navigation($container)
								  ->setAcl($acl)
								  ->setRole($accountRole);
				$layout->getView()->setEscape('trim');
				$layout->getView()->headTitle('Administration Panel', 'SET');

				switch ($controller) {
					case 'print':
						$layout->setLayout('layout_print');
						break;
					case 'csv':
						$layout->setLayout('layout_csv');
						break;
					case 'error':
						switch ($action) {
							case 'error' :
								$layout->setLayout('layout_error');
								break;
							default:
								break;
						}
						break;
					case 'auth':
						$layout->setLayout('admin_auth');
						switch ($action) {
							case 'login' :
								$layout->getView()->headTitle('Login', 'SET');
								if(!$acl->isAllowed($accountRole,'admin:auth','login')) {
									$this->_response->setRedirect('/admin/index');
								}
								break;
							default:
								break;
						}
						break;
					default :
						$layout->setLayout('admin');
						if(!$acl->isAllowed($accountRole,'admin:index')) {
							$this->_response->setRedirect('/admin/auth/login');
						}
						break;
				}
				break;

			default:

//				if($controller == 'products' && $action == "details"){
//					$breadcrumbsCategName = null;
//					$breadcrumbsSubcategName = null;
//					$breadcrumbsProdName = null;
//					$breadcrumbsCategoryId = null;
//
//					// BEGIN: GET PRODUCT NAME
//					$prodId = $this->getRequest()->getParam('id');
//					$model = new Default_Model_Product();
//					if($model->find($prodId)){
//						$breadcrumbsProdName = $model->getName();
//					}
//					// END: GET PRODUCT NAME
//
//					// BEGIN: GET CATEGORY NAME
//					$breadcrumbsCategName = getProdCateg($prodId);
//					// END: GET CATEGORY NAME
//				}
//
//				if($controller == 'products' && $action == "categories"){
//					$breadcrumbsCategName = null;
//					$breadcrumbsSubcategName = null;
//					$breadcrumbsCategoryId = $this->getRequest()->getParam('id');
//
//					$model = new Default_Model_Category();
//					if($model->find($breadcrumbsCategoryId)){
//						$breadcrumbsCategName = $model->getName();
//					}
//				}

				$cmsPagePlugin = NULL;
//				if($controller == "cms" && $action == "view"){
//					$cmsPagePlugin = null;
//					$pageNamePlugin = $this->getRequest()->getParam("page");
//					if(null != $pageNamePlugin){
//						$model = new Default_Model_Cms();
//						$select = $model->getMapper()->getDbTable()->select()
//								->where('title = ?', $pageNamePlugin)
//								->limit(1);
//						$result = $model->fetchAll($select);
//						if(null != $result){
//							$cmsPagePlugin = $result[0]->getTitle();
//						}
//					}
//				}

				$pages2 = array(
					//////INDEX//////
					array(
						'label'      => '<b><b>Home</b></b>',
						'title'      => 'Home',
						'module'     => 'default',
						'controller' => 'index',
						'action'	 => 'index',
						'pages'		 => array(
//							array(
//								'label'		=> $breadcrumbsCategName,
//								'title'		=> $breadcrumbsCategName,
//								'module'	=> 'default',
//								'controller'=> 'products',
//								'action'	=> 'categories',
//								'param'		=> array('id'=>$breadcrumbsCategoryId),
//								'pages'		=> array(
//									array(
//										'label'		=> $breadcrumbsProdName,
//										'title'		=> $breadcrumbsProdName,
//										'module'	=> 'default',
//										'controller'=> 'products',
//										'action'	=> 'details',
//									),
//								),
//							),
							array(
								'label'		=> 'Haine femei',
								'title'		=> 'Haine femei',
								'module'	=> 'default',
								'controller'=> 'products',
								'action'	=> 'hainefemei',
								'param'		=> array('page'=>1),
							),
							array(
								'label'		=> 'Reduceri',
								'title'		=> 'Reduceri',
								'module'	=> 'default',
								'controller'=> 'products',
								'action'	=> 'reduceri',
							),
							array(
								'label'		=> 'Tabel marimi',
								'title'		=> 'Tabel marimi',
								'module'	=> 'default',
								'controller'=> 'tabelmarimi',
								'action'	=> 'index',
							),
							array(
								'label'		=> $cmsPagePlugin,
								'title'		=> $cmsPagePlugin,
								'module'	=> 'default',
								'controller'=> 'cms',
								'action'	=> 'view',
							),
							array(
								'label'		=> 'Contact',
								'title'		=> 'Contact',
								'module'	=> 'default',
								'controller'=> 'contact',
								'action'	=> 'index',
							),
						),
					),
				);

				$container2 = new Zend_Navigation($pages2);
				$layout->getView()->navigation($container2)
								  ->setAcl($acl)
								  ->setRole($accountRole);
				$layout->getView()->setEscape('trim');

				$layout->setLayout('layout');
				switch($controller) {
					case 'index' :
						switch($action) {
							case 'index' :
								$layout->setLayout('layout');
								break;
							default :
								break;
						}
						break;
					case 'iframe' :
						switch($action) {
							case 'index' :
								$layout->setLayout('iframe');
								break;
							default :
								$layout->setLayout('iframe');
								break;
						}
						break;
					case 'error':
						switch($action) {
							case 'error' :
								$layout->setLayout('layout_error');
								break;
							default:
								break;
						}
						break;
					case 'auth':
						switch($action) {
							case 'index' :
								if(!$acl->isAllowed($accountRole,'default:auth','login')) {
									$this->_response->setRedirect('/index/home');
								}
								break;
							default :
								break;
						}
						break;
					default :
						break;
				}

				//CHECK IF IS LOGED IN. IF NOT SHOW THE LOGED IN FORM
				if($auth->hasIdentity()) {
					$accountAuth = $auth->getStorage()->read();
					$layout->getView()->authClient = $accountAuth;
				}else{
//					$formAuth = new Default_Form_Auth();
//					$formAuth->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/Auth.phtml')),));
//					$layout->getView()->formAuth = $formAuth;
				}
				//CHECK IF IS LOGED IN. IF NOT SHOW THE LOGED IN FORM

				//SEARCH FORM
				$formSearch = new Default_Form_Search();
				$formSearch->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/Search.phtml')),));
				$layout->getView()->formSearch = $formSearch;
				//SEARCH FORM

				//NEWSLETTER FORM
				$formNewletter = new Default_Form_Newsletter();
				$formNewletter->setDecorators(array('ViewScript', array('ViewScript', array('viewScript' => 'forms/Newsletter.phtml'))));
				$layout->getView()->formNewletter = $formNewletter;
				//NEWSLETTER FORM

				// Begin: Showcart
				$model = new Default_Model_Cart();
				$select = $model->getMapper()->getDbTable()->select()
						->where('cookie = ?', $_SESSION['cartId']);
				if(($result = $model->fetchAll($select))) {
					$_SESSION['cart'] = $result;
				}
				// End: Showcart
				//
				// Begin: Display top sales
				$modelpp= new Default_Model_OrderProducts();
				$select = $modelpp->getMapper()->getDbTable()->select()
						->from(array('o' => 'ts_orders'), array('o.id', 'o.status'))
						->join(array('op' => 'ts_order_products'), 'o.id = op.orderId', array('quantity' => 'SUM(quantity)', 'op.productId', 'op.datetime'))
						->join(array('p' => 'ts_products'), 'op.productId = p.id', array('p.id', 'p.status','p.name','p.price'))
						->where("o.status = 'accepted' OR o.status = 'completed' OR o.status = 'card_confirmed'")
						->where('p.status = ?', '1')
						->group('op.productId')
						->order('quantity DESC')
						->limit(5)
						->setIntegrityCheck(FALSE);
				if(($resultpp = $modelpp->fetchAll($select))) {
					$prodspp = array();
					foreach($resultpp as $valuepp) {
						$productspp = new Default_Model_Product();
						if($productspp->find($valuepp->getProductId())) {
							$prodspp[] = $productspp;
						}
					}
					if(!empty($prodspp)){
					    if(count($prodspp) > 1){
							$layout->getView()->bestSellers = $prodspp[rand(0, count($prodspp)-1)];
					    }else{
							$layout->getView()->bestSellers =  $prodspp[0];
					    }
					}
								}
				// End: Display top sales

				// End: Index modules



			switch ($controller){
                case 'facebook':
                    $layout->setLayout('facebook');
                    break;
				case 'index':
					switch ($action) {
						case 'index' :
							$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/css/style.css');
							$layout->setLayout('layout');
							break;
						default:
							$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/css/style.css');
							$layout->setLayout('layout');
							break;
					}
					break;
				case 'iframe':
					$layout->setLayout('iframe');
					$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/css/iframe.css');
					break;
				case 'print':
					$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/css/style.css');
					$layout->setLayout('layout_print');
					break;
				case 'error':
					switch ($action) {
						case 'error' :
							$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/css/style.css');
							$layout->setLayout('layout_error');
							break;
						default:
							$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/css/style.css');
							$layout->setLayout('layout');
							break;
					}
					break;
				case 'auth':
					switch ($action) {
						case 'index' :
							if(!$acl->isAllowed($accountRole,'default:auth', 'login')) {
								$this->_response->setRedirect('/index');
							}
							$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/css/style.css');
							$layout->setLayout('layout');
							break;
						default:
							$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/css/style.css');
							$layout->setLayout('layout');
							break;
					}
					break;
				default :
						$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/css/style.css');
						$layout->setLayout('layout');
					break;
			}
				$layout->getView()->headLink()->appendStylesheet(WEBPAGE_ADDRESS.'/theme/blackandwhite/css/main.css')
												->appendStylesheet(WEBPAGE_ADDRESS.'/theme/blackandwhite/scripts/validationEngine/validationEngine.css')
												->appendStylesheet(WEBPAGE_ADDRESS.'/js/jquery-ui/css/smoothness/jquery-ui.css');
				$layout->getView()->headScript()->prependFile(WEBPAGE_ADDRESS.'/theme/blackandwhite/scripts/jquery-1.8.0.min.js')
												->appendFile(WEBPAGE_ADDRESS.'/theme/blackandwhite/scripts/functions.js')
												->appendFile(WEBPAGE_ADDRESS.'/theme/blackandwhite/scripts/validationEngine/'.$validLang.'.js')
												->appendFile(WEBPAGE_ADDRESS.'/theme/blackandwhite/scripts/validationEngine/validationEngine.js');


			break;
		}
	}
}