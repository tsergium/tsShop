<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default_',
            'basePath'  => dirname(__FILE__),
        ));
		
		$ns = new Zend_Session_Namespace('session');
		if ($ns->initialize == '') {
			Zend_Session::start();
			$ns->initialize = true;
			$ns->setExpirationSeconds(604800);
		}
		
        return $autoloader;
    }
}
//BEGIN: ROUTER ROUTE REGEX
	//BEGIN: INITIALIZE ROUTER
	$ctrl  = Zend_Controller_Front::getInstance();
	$router = $ctrl->getRouter();
	//END: INITIALIZE ROUTER

	//BEGIN: PRODUCT DETAILS ROUTE
	$r = new Zend_Controller_Router_Route_Regex(
	'p([^-]*)-([^_]*)\.html',
	array(
			'controller' => 'products',
			'action' => 'details',
			'module' => 'default'
	),
	array(
			1 => 'id',
			2 => 'categAndName',
	),
	'p%d-%s.html'
	);
	$router->addRoute('product', $r);
	//END: PRODUCT DETAILS ROUTE

	//BEGIN: PRODUCT CATEGORY ROUTE
	$categRoute = new Zend_Controller_Router_Route_Regex(
	'c([^-]*)-([^-]*)\.html',
	array(
			'controller' => 'products',
			'action' => 'categories',
			'module' => 'default'
	),
	array(
			1 => 'id',
			2 => 'name'
	),
	'c%d-%s.html'
	);
	$router->addRoute('prodcategroute', $categRoute);
	//END: PRODUCT CATEGORY ROUTE

	//BEGIN: PRODUCT CATEGORY ROUTE WITH PAGINATION
	$testRoute = new Zend_Controller_Router_Route_Regex(
		'c([^-]*)-([^-]*)/pagina-([^-]*)\.html',
		array(
			'module'=>'default',
			'controller'=>'products',
			'action'=>'categories',
			'page'=>'1'
		),
		array(
			1 => 'id',
			2 => 'name',
			3 => 'page'
		),
		'c%d-%s/pagina-%d.html'
	);
	$router->addRoute('prodcategroutepag', $testRoute);
	//END: PRODUCT CATEGORY ROUTE WITH PAGINATION

	//BEGIN: PRODUCT REDUCERI
	$reduceriRoute = new Zend_Controller_Router_Route_Regex(
	'reduceri/pagina-([^-]*)\.html',
	array(
			'controller' => 'products',
			'action' => 'reduceri',
			'module' => 'default',
			'page'=>'1'
	),
	array(
			1 => 'page',
	),
	'reduceri/pagina-%d.html'
	);
	$router->addRoute('reduceriroute', $reduceriRoute);
	//END: PRODUCT REDUCERI

	//BEGIN: PRODUCT REDUCERI
	$hainefemeiRoute = new Zend_Controller_Router_Route_Regex(
	'hainefemei/pagina-([^-]*)\.html',
	array(
			'controller' => 'products',
			'action' => 'hainefemei',
			'module' => 'default',
			'page'=>'1'
	),
	array(
			1 => 'page',
	),
	'hainefemei/pagina-%d.html'
	);
	$router->addRoute('hainefemeiroute', $hainefemeiRoute);
	//END: PRODUCT REDUCERI

	//BEGIN: PRODUCT PROMOTII
	$promotiiRoute = new Zend_Controller_Router_Route_Regex(
	'promotii/pagina-([^-]*)\.html',
	array(
			'controller' => 'products',
			'action' => 'promotii',
			'module' => 'default',
			'page'=>'1'
	),
	array(
			1 => 'page',
	),
	'promotii/pagina-%d.html'
	);
	$router->addRoute('promotiiroute', $promotiiRoute);
	//END: PRODUCT PROMOTII

//END: ROUTER ROUTE REGEX