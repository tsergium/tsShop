<?php

class Default_Model_DbTable_Cms extends Zend_Db_Table_Abstract
{
    protected $_name = 'ts_cms';
    protected $_primary = 'id';
}

class Default_Model_Cms
{
    protected $_id;
    protected $_title;
    protected $_headline;
    protected $_url;
    protected $_content;
    protected $_status;
    protected $_date;
    protected $_datetime;
    protected $_modify;
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

    public function setTitle($title)
    {
        $this->_title = (!empty($title)) ? (string)$title : null;
        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setHeadline($headline)
    {
        $this->_headline = (!empty($headline)) ? (string)$headline : null;
        return $this;
    }

    public function getHeadline()
    {
        return $this->_headline;
    }

    public function setUrl($url)
    {
        $this->_url = (!empty($url)) ? (string)$url : null;
        return $this;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function setContent($content)
    {
        $this->_content = (string)$content;
        return $this;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function setStatus($status)
    {
        $this->_status = (!empty($status)) ? true : false;
        return $this;
    }

    public function getStatus()
    {
        return $this->_status;
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

    public function setModify($date)
    {
        $this->_modify = (!empty($date) && strtotime($date) > 0) ? strtotime($date) : null;
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
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_CmsMapper());
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

    public function fetchAll($select = null)
    {
        return $this->getMapper()->fetchAll($select);
    }

    public function delete()
    {
        if (null === ($id = $this->getId())) {
            throw new Exception("Invalid record selected!");
        }
        return $this->getMapper()->delete($id);
    }
}

class Default_Model_CmsMapper
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
            $this->setDbTable('Default_Model_DbTable_Cms');
        }
        return $this->_dbTable;
    }

    public function save(Default_Model_Cms $model)
    {
        $data = array(
            'title' => $model->getTitle(),
            'headline' => $model->getHeadline(),
            'url' => $model->getUrl(),
            'content' => $model->getContent(),
            'status' => $model->getStatus() ? '1' : '0',
        );

        if (null === ($id = $model->getId())) {
            $data['date'] = new Zend_Db_Expr('CURDATE()');
            $data['datetime'] = new Zend_Db_Expr('NOW()');
            $id = $this->getDbTable()->insert($data);
        } else {
            $data['modify'] = new Zend_Db_Expr('NOW()');
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        return $id;
    }

    public function find($id, Default_Model_Cms $cms)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $cms->setOptions($row->toArray());
        return $cms;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach ($resultSet as $row) {
            $cms = new Default_Model_Cms();
            $cms->setOptions($row->toArray())
                ->setMapper($this);
            $entries[] = $cms;
        }
        return $entries;
    }

    public function delete($id)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id);
        return $this->getDbTable()->delete($where);
    }
}