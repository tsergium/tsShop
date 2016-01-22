<?php

class Default_Model_DbTable_ProductsSpecificationsModel extends Zend_Db_Table_Abstract
{
    protected $_name = 'fp_products_specifications_model';
    protected $_primary = 'id';
}

class Default_Model_ProductsSpecificationsModel
{
    protected $_id;
    protected $_specificationId;
    protected $_specification;
    protected $_groupId;
    protected $_group;
    protected $_name;
    protected $_created;
    protected $_modified;

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
            throw new Exception('Invalid ' . $name . ' category property ' . $method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid category property');
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

    public function setSpecificationId($id)
    {
        $val = new Default_Model_ProductsSpecifications();
        $val->find($id);
        if (null !== $val->getId()) {
            $this->setSpecification($val);
            $this->_specificationId = $val->getId();
        }
        return $this;
    }

    public function getSpecificationId()
    {
        return $this->_specificationId;
    }

    public function setSpecification($value)
    {
        $this->_specification = $value;
        return $this;
    }

    public function getSpecification()
    {
        return $this->_specification;
    }

    public function setGroupId($groupId)
    {
        $group = new Default_Model_ProductsSpecificationsGroup();
        $group->find($groupId);
        if (null !== $group->getId()) {
            $this->setGroup($group);
            $this->_groupId = $group->getId();
        }
        return $this;
    }

    public function getGroupId()
    {
        return $this->_groupId;
    }

    public function setGroup(Default_Model_ProductsSpecificationsGroup $group)
    {
        $this->_group = $group;
        return $this;
    }

    public function getGroup()
    {
        return $this->_group;
    }

    public function setName($name)
    {
        $this->_name = (string)$name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
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
            $this->setMapper(new Default_Model_ProductsSpecificationsModelMapper());
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

class Default_Model_ProductsSpecificationsModelMapper
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
            $this->setDbTable('Default_Model_DbTable_ProductsSpecificationsModel');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_ProductsSpecificationsModel $value)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $value->setOptions($row->toArray());
        return $value;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach ($resultSet as $row) {
            $value = new Default_Model_ProductsSpecificationsModel();
            $value->setOptions($row->toArray())
                ->setMapper($this);
            $entries[] = $value;
        }
        return $entries;
    }

    public function save(Default_Model_ProductsSpecificationsModel $value)
    {
        $data = array(
            'specificationId' => $value->getSpecificationId(),
            'groupId' => $value->getGroupId(),
            'name' => $value->getName(),
        );
        if (null === ($id = $value->getId())) {
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