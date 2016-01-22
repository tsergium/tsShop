<?php

class Default_Model_DbTable_Company extends Zend_Db_Table_Abstract
{
    protected $_name = 'ts_company';
    protected $_primary = 'id';
}

class Default_Model_Company
{
    protected $_id;
    protected $_institution;
    protected $_phone;
    protected $_address;
    protected $_phone2;
    protected $_email;
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
            throw new Exception('Invalid company property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid company property');
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

    public function setInstitution($institution)
    {
        $this->_institution = (!empty($institution)) ? (string)$institution : null;
        return $this;
    }

    public function getInstitution()
    {
        return $this->_institution;
    }

    public function setAddress($address)
    {
        $this->_address = (!empty($address)) ? (string)$address : null;
        return $this;
    }

    public function getAddress()
    {
        return $this->_address;
    }

    public function setPhone($phone)
    {
        $this->_phone = (!empty($phone)) ? (string)$phone : null;
        return $this;
    }

    public function getPhone()
    {
        return $this->_phone;
    }

    public function setPhone2($phone2)
    {
        $this->_phone2 = (!empty($phone2)) ? (string)$phone2 : null;
        return $this;
    }

    public function getPhone2()
    {
        return $this->_phone2;
    }

    public function setEmail($email)
    {
        $this->_email = (!empty($email)) ? (string)$email : null;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
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
            $this->setMapper(new Default_Model_CompanyMapper());
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

class Default_Model_CompanyMapper
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
            $this->setDbTable('Default_Model_DbTable_Company');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_Company $company)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $company->setOptions($row->toArray());
        return $company;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach ($resultSet as $row) {
            $company = new Default_Model_Company();
            $company->setOptions($row->toArray())
                ->setMapper($this);
            $entries[] = $company;
        }
        return $entries;
    }

    public function save(Default_Model_Company $company)
    {
        $data = array(
            'institution' => $company->getInstitution(),
            'address' => $company->getAddress(),
            'phone' => $company->getPhone(),
            'phone2' => $company->getPhone2(),
            'email' => $company->getEmail(),
        );

        if (null === ($id = $company->getId())) {
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