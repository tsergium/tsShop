<?php
class Default_Model_DbTable_Role extends Zend_Db_Table_Abstract
{
	protected $_name    = 'ts_acl_role';
	protected $_primary = 'id';
    protected $_dependentTables = array('Default_Model_DbTable_AdminUser');
}

class Default_Model_Role
{
    protected $_id;
    protected $_name;
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
            throw new Exception('Invalid '.$name.' property');
        }
        $this->$method($value);
    }
    public function __get($name)
    {
        $method = 'get' . $name;
        if(('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid '.$name.' property');
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
        $this->_name = (string) $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if(null === $this->_mapper) {
			$this->setMapper(new Default_Model_RoleMapper());
        }
        return $this->_mapper;
    }
    
    public function save()
    {
        return $this->getMapper()->save($this);
    }

    public function find($id)
    {
        return $this->getMapper()->find($id, $this);
    }

    public function fetchAll($select=null)
    {
        return $this->getMapper()->fetchAll($select);
    }

    public function delete()
    {
    	if(null === ($id = $this->getId())) {
    		throw new Exception("Invalid record selected!");
    	}
        return $this->getMapper()->delete($id);
    }
}

class Default_Model_RoleMapper
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
            $this->setDbTable('Default_Model_DbTable_Role');
        }
        return $this->_dbTable;
    }
    
    public function save(Default_Model_Role $role)
    {
        $data = array(
            'name'				=> $role->getName(),
        );
        if(null === ($id = $role->getId())) {
            $this->getDbTable()->insert($data);
            $id = $this->getDbTable()->lastInsertId();
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        return $id;
    }
    
    public function find($id, Default_Model_Role $role)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $role->setOptions($row->toArray());
		return $role;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);
        
        $entries = array();
        foreach($resultSet as $row) {
            $role = new Default_Model_Role();
            $role->setOptions($row->toArray())
					->setMapper($this);
            $entries[] = $role;
        }
        return $entries;
    }
    
    public function delete($id)
    {
    	$where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id);
        return $this->getDbTable()->delete($where);
    }
}