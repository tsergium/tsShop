<?php

class Default_Model_DbTable_ProductsAttributes extends Zend_Db_Table_Abstract
{
    protected $_name = 'fp_products_attributes';
    protected $_primary = 'id';
}

class Default_Model_ProductsAttributes
{
    protected $_id;
    protected $_position;
    protected $_name;
    protected $_status;
    protected $_created;
    protected $_modified;

    protected $_prodnr;

    protected $_mapper;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid ' . $name . ' product property ' . $method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid ' . $name . ' product property ' . $method);
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function setId($id)
    {
        $this->_id = (int)$id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setPosition($value)
    {
        $this->_position = (!empty($value)) ? (string)$value : null;
        return $this;
    }

    public function getPosition()
    {
        return $this->_position;
    }

    public function setName($name)
    {
        $this->_name = (!empty($name)) ? (string)$name : null;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setStatus($value)
    {
        $this->_status = (!empty($value)) ? (string)$value : '0';
        return $this;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setCreated($date)
    {
        $this->_created = (!empty($date) && strtotime($date) > 0) ? strtotime($date) : null;
        return $this;
    }

    public function getCreated()
    {
        return $this->_created;
    }

    public function setModified($date)
    {
        $this->_modified = (!empty($date) && strtotime($date) > 0) ? strtotime($date) : null;
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
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_ProductsAttributesMapper());
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
        if (null === ($id = $this->getId())) {
            throw new Exception("Invalid record selected!");
        }
        return $this->getMapper()->delete($id);
    }
}

class Default_Model_ProductsAttributesMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Default_Model_DbTable_ProductsAttributes');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_ProductsAttributes $val)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
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
        foreach ($resultSet as $row) {
            $val = new Default_Model_ProductsAttributes();
            $val->setOptions($row->toArray())
                ->setMapper($this);
//			$products->setId($row->productsid);
            $entries[] = $val;
        }
        return $entries;
    }

    public function save(Default_Model_ProductsAttributes $val)
    {
        $data = array(
            'position' => $val->getPosition(),
            'name' => $val->getName(),
            'status' => $val->getStatus(),
        );
        if (null === ($id = $val->getId())) {
            $data['created'] = new Zend_Db_Expr('NOW()');
            $id = $this->getDbTable()->insert($data);
        } else {
            $data['modified'] = new Zend_Db_Expr('NOW()');
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