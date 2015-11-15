<?php
class Default_Model_DbTable_ProductsOffers extends Zend_Db_Table_Abstract
{
	protected $_name    = 'fp_products_offers';
	protected $_primary = 'id';
}

class Default_Model_ProductsOffers
{
	protected $_id;
	protected $_name;
	protected $_date;
	protected $_datetime;
	protected $_modified;
	
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
            throw new Exception('Invalid '.$name.' product offert property '.$method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if(('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid product offert property');
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

    public function setName($name)
    {
    	$this->_name = $name;
    	return $this;
    }

    public function getName()
    {
    	return $this->_name;
    }

	public function setDate($date)
    {
        $this->_date = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
        return $this;
    }

    public function getDate()
    {
        return $this->_date;
    }

	public function setDatetime($date)
    {
        $this->_datetime = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
        return $this;
    }

    public function getDatetime()
    {
        return $this->_datetime;
    }

	public function setModified($date)
    {
        $this->_modified = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
        return $this;
    }

    public function getModified()
    {
        return $this->_modified;
    }

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if(null === $this->_mapper) {
            $this->setMapper(new Default_Model_ProductsOffersMapper());
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

class Default_Model_ProductsOffersMapper
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
            $this->setDbTable('Default_Model_DbTable_ProductsOffers');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_ProductsOffers $offers)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $offers -> setOptions($row->toArray());
		return $offers;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach($resultSet as $row) {
            $offers = new Default_Model_ProductsOffers();
            $offers->setOptions($row->toArray())
                 	->setMapper($this);
			$entries[] = $offers;
        }
        return $entries;
    }

    public function save(Default_Model_ProductsOffers $offers)
    {
        $data = array(
			'productId'			=> $offers->getProductId(),
			'typeId'			=> $offers->getTypeId(),
        );
        if (null === ($id = $offers->getId())) {
			$data['date']		 = new Zend_Db_Expr('CURDATE()');
        	$data['datetime']	 = new Zend_Db_Expr('NOW()');
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