<?php
/**
 * Created by PhpStorm.
 * User: sergiu
 * Date: 1/12/16
 * Time: 4:43 PM
 */

$ctrl  = Zend_Controller_Front::getInstance();
$router = $ctrl->getRouter();

/**
 * Product Details Route
 */
$product = new Zend_Controller_Router_Route_Regex(
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
$router->addRoute('product', $product);

/**
 * Product Category Route
 */
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

/**
 * Product Category Route with Pagination
 */
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

/**
 * Discounted Products
 */
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

/**
 * Women Clothes Products
 */
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

/**
 * Product Promotions
 */
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