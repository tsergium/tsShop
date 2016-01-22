<?php

class Default_Model_DbTable_SettingValue extends Zend_Db_Table_Abstract
{
    protected $_name = 'ts_setting_values';
    protected $_primary = 'id';
}

class Default_Model_SettingValue
{
    protected $_id;
    protected $_settingId;
    protected $_setting;
    protected $_value;

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
            throw new Exception('Invalid ' . $name . ' property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid ' . $name . ' property');
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

    public function setSettingId($valId)
    {
        $var = new Default_Model_Setting();
        $var->find($valId);
        if (null !== $var->getId()) {
            $this->setSetting($var);
            $this->_settingId = $var->getId();
        }
        return $this;
    }

    public function getSettingId()
    {
        return $this->_settingId;
    }

    public function setSetting(Default_Model_Setting $var)
    {
        $this->_setting = $var;
        return $this;
    }

    public function getSetting()
    {
        return $this->_setting;
    }

    public function setValue($value)
    {
        $this->_value = (!empty($value)) ? (string)$value : null;
        return $this;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_SettingValueMapper());
        }
        return $this->_mapper;
    }

    public function delete()
    {
        if (null === ($id = $this->getConst())) {
            throw new Exception("Invalid record selected!");
        }
        return $this->getMapper()->delete($id);
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
}

class Default_Model_SettingValueMapper
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
            $this->setDbTable('Default_Model_DbTable_SettingValue');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_SettingValue $var)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $var->setOptions($row->toArray());
        return $var;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);
        $entries = array();
        foreach ($resultSet as $row) {
            $var = new Default_Model_SettingValue();
            $var->setOptions($row->toArray())
                ->setMapper($this);
            $entries[] = $var;
        }
        return $entries;
    }

    public function save(Default_Model_SettingValue $model)
    {
        $data = array(
            'settingId' => $model->getSettingId(),
            'value' => $model->getValue(),
        );
        if (null === ($id = $model->getId())) {
            ;
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