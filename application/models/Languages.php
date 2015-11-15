<?php
class Default_Model_DbTable_Languages extends Zend_Db_Table_Abstract
{
	protected $_name    = 'ts_languages';
	protected $_primary = 'id';
}

class Default_Model_Languages
{
    protected $_id;
	protected $_language;
	protected $_file;
    protected $_status;
    protected $_modify;

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
	
      public function setLanguage($language)
    {
        $this->_language = (string) $language;
        return $this;
    }
    public function getLanguage()
    {
        return $this->_language;
    }

	 public function setFile($file)
    {
        $this->_file = (string) $file;
        return $this;
    }
    public function getFile()
    {
        return $this->_file;
    }

	public function setStatus($status)
	{
		$this->_status = (int) $status;
		return $this;
	}

	public function getStatus()
	{
		return $this->_status;
	}
   
    public function setModify($date)
    {
        $this->_modify = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
        return $this;
    }
    public function getModify()
    {
        return $this->_modify;
    }
    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }
    public function getMapper()
    {
        if(null === $this->_mapper) {
            $this->setMapper(new Default_Model_LanguagesMapper());
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

    public function setactivelang()
    {
        $this->getMapper()->setactivelang($this);
    }
}

class Default_Model_LanguagesMapper
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
            $this->setDbTable('Default_Model_DbTable_Languages');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_Languages $languages)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $languages -> setOptions($row->toArray());
		return $languages;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries   = array();
        foreach ($resultSet as $row) {
            $languages = new Default_Model_Languages();
            $languages->setOptions($row->toArray())
					->setMapper($this);
            $entries[] = $languages;
        }
        return $entries;
    }

    public function setactivelang(Default_Model_Languages $languages)
    {
        $data = array(
			'status'	 => $languages->getStatus(),
            'modified'	 => new Zend_Db_Expr('NOW()'),
        );
        if(null === ($id = $languages->getId())) {
        	throw new Exception("Invalid language selected!");
        } else {
			$this->getDbTable()->update(array('status' => '0'));
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        return $id;
    }
}