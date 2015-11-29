<?php
/**
 * Created by PhpStorm.
 * User: tsergium
 * Date: 11/22/2015
 * Time: 2:57 PM
 */

class Default_Model_ProductMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if(is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if(!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if(null === $this->_dbTable) {
            $this->setDbTable('Default_Model_DbTable_Product');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_Product $model)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $model -> setOptions($row->toArray());
        return $model;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach($resultSet as $row) {
            $model = new Default_Model_Product();
            $model->setOptions($row->toArray())
                ->setMapper($this);
            $entries[] = $model;
        }
        return $entries;
    }

    public function save(Default_Model_Product $model)
    {
        $data = array(
            'promotionId'		=> $model->getPromotionId(),
            'name'				=> $model->getName(),
            'oldprice'			=> $model->getOldprice(),
            'price'				=> $model->getPrice(),
            'image'				=> $model->getImage(),
            'imageShopmania'	=> $model->getImageShopmania(),
            'description'		=> $model->getDescription(),
            'composition'		=> $model->getComposition(),
            'stockNelimitat'	=> $model->getStockNelimitat(),
            'stock'				=> $model->getStock(),
            'status'			=> $model->getStatus(),
        );
        if(null === ($id = $model->getId())) {
            $data['created']	 = new Zend_Db_Expr('NOW()');
            $id = $this->getDbTable()->insert($data);
        } else {
            $data['modified']	 = new Zend_Db_Expr('NOW()');
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        return $id;
    }

    public function delete($id)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id);
        return $this->getDbTable()->delete($where);
    }
}