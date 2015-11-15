<?php
class Default_Model_DbTable_ProductInventory extends Zend_Db_Table_Abstract
{
	protected $_name    = 'fp_product_inventory';
	protected $_primary = 'id';
}

class Default_Model_ProductInventory
{
    protected $_id;
	protected $_productId;
	protected $_product;
	protected $_attributeListId;
	protected $_attributeList;
//	protected $_attributeId;
//	protected $_attribute;
	protected $_quantity;
//	protected $_status;
//	protected $_date;
//	protected $_datetime;
//	protected $_modified;

    protected $_mapper;

    public function __construct(array $options = null)
    {
        if(is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if(('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid '.$name.' product property '.$method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if(('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid '.$name.' product property '.$method);
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if(in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

	public function setProductId($id)
    {
    	$val = new Default_Model_Products();
    	$val->find($id);
    	if(null !== $val->getId()) {
    		$this->setProduct($val);
	        $this->_attributeListId = $val->getId();
    	}
        return $this;
    }

    public function getProductId()
    {
        return $this->_attributeListId;
    }

    public function setProduct(Default_Model_ProductsAttributes $val)
    {
    	$this->_attributeList = $val;
    	return $this;
    }

    public function getProduct()
    {
    	return $this->_attributeList;
    }

//    public function setAttributeListId($attributeListId)
//    {
//        $this->_attributeListId = (int) $attributeListId;
//        return $this;
//    }
//
//    public function getAttributeListId()
//    {
//        return $this->_attributeListId;
//    }

    public function setAttributeListId($attributeListId)
    {
    	$product = new Default_Model_Products();
    	$product -> find($productId);
    	if(null !== $product->getId()) {
    		$this->setProduct($product);
	        $this->_productId = $product->getId();
    	}
        return $this;
    }

    public function getAttributeListId()
    {
        return $this->_attributeListId;
    }

//	public function setAttributeId($attributeId)
//    {
//		$this->_attributeId = $attributeId;
//        return $this;
//    }
//
//    public function getAttributeId()
//    {
//        return $this->_attributeId;
//    }

//    public function setAttribute($attribute)
//    {
//    	$this->_attribute = $attribute;
//    	return $this;
//    }
//
//    public function getAttribute()
//    {
//    	return $this->_attribute;
//    }

    public function setQuantity($value)
    {
        $this->_quantity = (int) $value;
        return $this;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

//	public function setStatus($status)
//    {
//        $this->_status = (!empty($status))?(string) $status:'0';
//        return $this;
//    }
//
//    public function getStatus()
//    {
//        return $this->_status;
//    }

//	public function setDate($date)
//    {
//        $this->_date = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
//        return $this;
//    }
//
//    public function getDate()
//    {
//        return $this->_date;
//    }

//	public function setDatetime($date)
//    {
//        $this->_datetime = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
//        return $this;
//    }
//
//    public function getDatetime()
//    {
//        return $this->_datetime;
//    }

//	public function setModified($date)
//    {
//        $this->_modified = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
//        return $this;
//    }
//
//    public function getModified()
//    {
//        return $this->_modified;
//    }

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_ProductInventoryMapper());
        }
        return $this->_mapper;
    }

    public function find($id)
    {
        return $this->getMapper()->find($id, $this);
    }

    public function fetchAll($select = null)
    {
        return $this->getMapper()->fetchAll($select);
    }

    public function save()
    {
        return $this->getMapper()->save($this);
    }

    public function delete()
    {
    	if(null === ($id = $this->getId())) {
    		throw new Exception("Invalid record selected!");
    	}
        return $this->getMapper()->delete($id);
    }

}

class Default_Model_ProductInventoryMapper
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
            $this->setDbTable('Default_Model_DbTable_ProducInventory');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_ProductInventory $val)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $val->setOptions($row->toArray());
		return $val;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);
        $entries = array();
        foreach($resultSet as $row) {
            $val = new Default_Model_ProductInventory();
            $val->setOptions($row->toArray())
				->setMapper($this);
			$entries[] = $val;
        }
        return $entries;
    }

    public function save(Default_Model_ProductInventory $val)
    {
        $data = array(
			'productId'				=> $val->getProductId(),
			'attributeListId'		=> $val->getAttributeListId(),
//			'attributeId'			=> $val->getAttributeId(),
			'quantity'				=> $val->getQuantity(),
//			'status'				=> $val->getStatus(),
        );
        if(null === ($id = $val->getId())) {
//        	$data['date']		 = new Zend_Db_Expr('CURDATE()');
//			$data['datetime']	 = new Zend_Db_Expr('NOW()');
            $id = $this->getDbTable()->insert($data);
        } else {
//        	$data['modified']	 = new Zend_Db_Expr('NOW()');
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