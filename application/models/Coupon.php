<?php
class Default_Model_DbTable_Coupon extends Zend_Db_Table_Abstract
{
    protected $_name    = 'coupons';
    protected $_primary = 'id';
}

class Default_Model_Coupon
{
    protected $_id;
    protected $_code;
    protected $_timestamp;
    protected $_status;

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
            throw new Exception('Invalid '.$name.' coupon property '.$method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if(('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid coupon property');
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

    public function setCode($value)
    {
        $this->_code =  (!empty($value))?$value:null;
        return $this;
    }

    public function getCode()
    {
        return $this->_code;
    }

    public function setStatus($status)
    {
        $this->_status = (string)$status;
        return $this;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if(null === $this->_mapper) {
            $this->setMapper(new Default_Model_CouponMapper());
        }
        return $this->_mapper;
    }

    public function find($id)
    {
        return $this->getMapper()->find($id, $this);
    }

    public function findByCode($code)
    {
        return $this->getMapper()->findByCode($code, $this);
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

class Default_Model_CouponMapper
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
            $this->setDbTable('Default_Model_DbTable_Coupon');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_Coupon $model)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $model -> setOptions($row->toArray());
        return $model;
    }

    public function findByCode($code, Default_Model_Coupon $model)
    {
        $result = $this->getDbTable()->fetchRow("lower(code) like lower('" . $code . "') and status like 'active'");
        if(0 == count($result)) {
            return;
        }

        return $result->toArray();
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach ($resultSet as $row) {
            $model = new Default_Model_Coupon();
            $model->setOptions($row->toArray())
                      ->setMapper($this);
            $entries[] = $model;
        }
        return $entries;
    }

    public function save(Default_Model_Coupon $model)
    {
        $data = array(
            'code'                    => $model->getCode(),
            'status'                  => $model->getStatus(),
        );

        if(null === ($id = $model->getId())) {
            $id = $this->getDbTable()->insert($data);

        } else {
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