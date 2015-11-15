<?php
class Default_Model_DbTable_Voucher extends Zend_Db_Table_Abstract
{
    protected $_name    = 'vouchers';
    protected $_primary = 'id';
}

class Default_Model_Voucher
{
    protected $_id;
    protected $_isProcentual;
    protected $_value;
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
            throw new Exception('Invalid '.$name.' voucher property '.$method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if(('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid voucher property');
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

    public function setIsProcentual($value)
    {
        $this->_isProcentual =  (!empty($value))?$value:0;
        return $this;
    }

    public function getIsProcentual()
    {
        return $this->_isProcentual;
    }

    public function setValue($value)
    {
        $this->_value = (float)$value;
        return $this;
    }

    public function getValue()
    {
        return $this->_value;
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
            $this->setMapper(new Default_Model_VoucherMapper());
        }
        return $this->_mapper;
    }

    public function find($id)
    {
        return $this->getMapper()->find($id, $this);
    }

    public function fetchAll()
    {
        return $this->getMapper()->fetchAll();
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

class Default_Model_VoucherMapper
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
            $this->setDbTable('Default_Model_DbTable_Voucher');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_Voucher $model)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $model -> setOptions($row->toArray());
        return $model;
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        return $resultSet->toArray();
    }

    public function save(Default_Model_Voucher $model)
    {
        $data = array(
            'isProcentual'           => $model->getIsProcentual(),
            'value'                   => $model->getValue(),
            'status'                  => $model->getStatus(),
        );

        if(null === ($id = $model->getId())) {
            $id = $this->getDbTable()->insert($data);

        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));

        }
        return $id;
    }

    /* // Only one voucher one time
    public function delete($id)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id);
        return $this->getDbTable()->delete($where);
    }
    */
}