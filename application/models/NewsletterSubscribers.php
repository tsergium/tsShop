<?php

class Default_Model_DbTable_NewsletterSubscribers extends Zend_Db_Table_Abstract
{
    protected $_name = 'ts_newsletter_subscribers';
    protected $_primary = 'id';
}

class Default_Model_NewsletterSubscribers
{
    protected $_id;
    protected $_email;
    protected $_unsubscribe;
    protected $_status;
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

    public function setEmail($email)
    {
        $this->_email = (string)$email;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setUnsubscribe($unsubscribe)
    {
        $this->_unsubscribe = (string)$unsubscribe;
        return $this;
    }

    public function getUnsubscribe()
    {
        return $this->_unsubscribe;
    }

    public function setStatus($status)
    {
        $this->_status = (!empty($status)) ? (string)$status : '0';
        return $this;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setCreated($created)
    {
        $this->_created = (!empty($created) && strtotime($created) > 0) ? strtotime($created) : null;
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
            $this->setMapper(new Default_Model_NewsletterSubscribersMapper());
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

class Default_Model_NewsletterSubscribersMapper
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
            $this->setDbTable('Default_Model_DbTable_NewsletterSubscribers');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_NewsletterSubscribers $newsletter)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $newsletter->setOptions($row->toArray());
        return $newsletter;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach ($resultSet as $row) {
            $value = new Default_Model_NewsletterSubscribers();
            $value->setOptions($row->toArray())
                ->setMapper($this);
            $entries[] = $value;
        }
        return $entries;
    }

    public function save(Default_Model_NewsletterSubscribers $value)
    {
        $data = array(
            'email' => $value->getEmail(),
            'unsubscribe' => $value->getUnsubscribe(),
            'status' => $value->getStatus(),
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