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
        parent::init();
        $this->ajaxInit();
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
            ->from(array('p'=>'ts_products'))
            ->joinLeft(array('pca'=>'ts_products_categ_asoc'), 'p.id = pca.productId', array('composition' => new Zend_Db_Expr('GROUP_CONCAT(pca.categoryId)')))
            ->where('status = ?', '1')
            ->group('p.id')
            ->order('created DESC')
            ->setIntegrityCheck(false);
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
        $zendView = new Zend_View;
        $productData = [];
        if (!empty($products)) {
            foreach ($products as $product) {
                $link   = $zendView->url(array('id' => $product->getId(),'categAndName' => preg_replace('/[^a-zA-Z0-9]+/','-', strtolower(getProdCateg($product->getId())."-".$product->getName())),),'product');
                $image  = (null != $product->getImage())?"/media/products/small/".$product->getImage():"/images/no-pic-small.jpg";

                $productData[] = [
                    'id'            => $product->getId(),
                    'category'      => explode(',', $product->getComposition()),
                    'name'          => $product->getName(),
                    'price'         => $product->getPrice(),
                    'link'          => WEBPAGE_ADDRESS . $link,
                    'image'         => WEBPAGE_ADDRESS . $image,
                    'description'   => $product->getDescription()
                ];
            }
        }
        return $productData;
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