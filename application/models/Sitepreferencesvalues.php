<?php
class Default_Model_DbTable_Sitepreferencesvalues extends Zend_Db_Table_Abstract
{
	protected $_name    = 'fp_site_preferences_values';
	protected $_primary = 'id';
}

class Default_Model_Sitepreferencesvalues
{
	protected $_id;
	protected $_preferencesId;
	protected $_value;

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
            throw new Exception('Invalid '.$name.' property '.$method);
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

    public function setPreferencesId($preferencesId)
    {
        $this->_preferencesId = (int) $preferencesId;
        return $this;
    }

    public function getPreferencesId()
    {
        return $this->_preferences;
    }

	public function setValue($value)
    {
        $this->_value = (string) $value;
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
            $this->setMapper(new Default_Model_SitepreferencesvaluesMapper());
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

class Default_Model_SitepreferencesvaluesMapper
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
        if (null === $this->_dbTable) {
            $this->setDbTable('Default_Model_DbTable_Sitepreferencesvalues');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_Sitepreferencesvalues $value)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
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
        foreach($resultSet as $row) {
            $value = new Default_Model_Sitepreferencesvalues();
            $value->setOptions($row->toArray())
					->setMapper($this);
            $entries[] = $value;
        }
        return $entries;
    }

	public function save(Default_Model_Sitepreferencesvalues $value)
    {
        $data = array(
			'preferencesId'				=> $value->getPreferencesId(),
			'value'						=> $value->getValue(),
        );
		if(null == ($id = $value->getId())) {
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