<?php
/**
 * Created by PhpStorm.
 * User: tsergium
 * Date: 11/29/2015
 * Time: 8:35 PM
 */
class ApiController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();
        $bootstrap = $this->getInvokeArg('bootstrap');
        if($bootstrap->hasResource('db')) {
            $this->db = $bootstrap->getResource('db');
        }

        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->message = $this->_flashMessenger->getMessages();
    }

    public function indexAction()
    {
    }

    /**
     * provides the list of product categories
     */
    public function categoriesAction()
    {
        $modelCategory = new Default_Model_Category();
        $select = $modelCategory->getMapper()->getDbTable()->select()
            ->where('parentId IS NULL');
        $categories = $modelCategory->fetchAll($select);
        $categoriesData = $this->parseCategoriesToJson($categories);
        $this->printJson($categoriesData, 200);
    }

    public function productsAction()
    {
        $modelProduct = new Default_Model_Product();
        $select = $modelProduct->getMapper()->getDbTable()->select()
            ->where('status IS NOT NULL');
        $products = $modelProduct->fetchAll($select);
        $productsData = $this->parseProductsToJson($products);
        $this->printJson($productsData, 200);
    }

    protected function parseCategoriesToJson($categories)
    {
        $categoryData = [];
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $categoryData[] = [
                    'id'    => $category->getId(),
                    'name'  => $category->getName()
                ];
            }
        }

        return $categoryData;
    }

    protected function parseProductsToJson($products)
    {
        $productData = [];
        if (!empty($products)) {
            foreach ($products as $product) {
                $productData[] = [
                    'id'    => $product->getId(),
                    'name'  => $product->getName()
                ];
            }
        }
    }

    /**
     * print json response
     * @deprecated
     * @param array $response
     * @param integer $httpCode
     * @throws Zend_Controller_Response_Exception
     */
    protected function printJson($response, $httpCode) {
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setHttpResponseCode($httpCode);

        echo Zend_Json_Encoder::encode($response);
    }
}