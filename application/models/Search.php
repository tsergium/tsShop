<?php

class Default_Model_DbTable_Search extends Zend_Db_Table_Abstract
{
    protected $_name = 'fp_search_data';
    protected $_primary = 'id';
}

class Default_Model_Search
{
    protected $_id;
    protected $_term;
    protected $_usage;
    protected $_results;
    protected $_date;
    protected $_datetime;
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
            throw new Exception('Invalid ' . $name . ' property ' . $method);
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

    public function setTerm($term)
    {
        $this->_term = (string)$term;
        return $this;
    }

    public function getTerm()
    {
        return $this->_term;
    }

    public function setUsage($usage)
    {
        $this->_usage = (!empty($usage)) ? (int)$usage : "1";
        return $this;
    }

    public function getUsage()
    {
        return $this->_usage;
    }

    public function setResults($results)
    {
        $this->_results = (!empty($results)) ? (int)$results : "0";
        return $this;
    }

    public function getResults()
    {
        return $this->_results;
    }

    public function setDate($date)
    {
        $this->_date = (!empty($date) && strtotime($date) > 0) ? strtotime($date) : null;
        return $this;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function setDatetime($date)
    {
        $this->_datetime = (!empty($date) && strtotime($date) > 0) ? strtotime($date) : null;
        return $this;
    }

    public function getDatetime()
    {
        return $this->_datetime;
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
            $this->setMapper(new Default_Model_SearchMapper());
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
}

class Default_Model_SearchMapper
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
            $this->setDbTable('Default_Model_DbTable_Search');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_Search $val)
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
            $val = new Default_Model_Search();
            $val->setOptions($row->toArray())
                ->setMapper($this);
            $entries[] = $val;
        }
        return $entries;
    }

    public function save(Default_Model_Search $val)
    {
        $data = array(
            'term' => $val->getTerm(),
            'usage' => $val->getUsage(),
            'results' => $val->getResults(),
        );
        if (null === ($id = $val->getId())) {
            $data['date'] = new Zend_Db_Expr('CURDATE()');
            $data['datetime'] = new Zend_Db_Expr('NOW()');
            $data['modified'] = new Zend_Db_Expr('NOW()');
            $id = $this->getDbTable()->insert($data);
        } else {
            $data['modified'] = new Zend_Db_Expr('NOW()');
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        return $id;
    }
}