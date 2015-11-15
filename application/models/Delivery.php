<?php
class Default_Model_DbTable_Delivery extends Zend_Db_Table_Abstract
{
	protected $_name    = 'ts_delivery';
	protected $_primary = 'id';
}
class Default_Model_Delivery
{
    protected $_id;
	protected $_paymentId;
	protected $_paymentName;
	protected $_courierId;
	protected $_courierName;
	protected $_cost;
	protected $_created;
	protected $_modified;

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
            throw new Exception('Invalid '.$name.' delivery property '.$method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if(('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid '.$name.' delivery property '.$method);
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

    public function setId($value)
    {
        $this->_id = (int) $value;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setPaymentId($value)
    {
        $this->_paymentId = (int) $value;
	$payment = new Default_Model_DeliveryPayment();
    	$payment -> find($value);
    	if(null !== $payment->getId()) {
    		$this->setPaymentName($payment->getName());	       
    	}
        return $this;
    }

    public function getPaymentId()
    {
        return $this->_paymentId;
    }
    
    public function setPaymentName($value)
    {
        $this->_paymentName = (string) $value;
        return $this;
    }

    public function getPaymentName()
    {
        return $this->_paymentName;
    }

    public function setCourierId($value)
    {
        $this->_courierId = (int) $value;
	$cour = new Default_Model_DeliveryCourier();
    	$cour -> find($value);
    	if(null !== $cour->getId()) {
    		$this->setCourierName($cour->getName());	       
    	}
        return $this;
    }

    public function getCourierId()
    {
        return $this->_courierId;
    }

     public function setCourierName($courierName)
    {
        $this->_courierName = (string) $courierName;
        return $this;
    }

    public function getCourierName()
    {
        return $this->_courierName;
    }
    
    public function setCost($value)
    {
        $this->_cost = (float) $value;
        return $this;
    }

    public function getCost()
    {
        return $this->_cost;
    }

	public function setCreated($date)
    {
        $this->_created = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
        return $this;
    }

    public function getCreated()
    {
        return $this->_created;
    }

	public function setModified($date)
    {
        $this->_modified = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
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
        if(null === $this->_mapper) {
            $this->setMapper(new Default_Model_DeliveryMapper());
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
    		throw new Exception('Invalid record selected!');
    	}
        return $this->getMapper()->delete($id);
    }
}

class Default_Model_DeliveryMapper
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
            $this->setDbTable('Default_Model_DbTable_Delivery');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_Delivery $model)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $model -> setOptions($row->toArray());
		return $model;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach($resultSet as $row) {
            $model = new Default_Model_Delivery();
            $model->setOptions($row->toArray())
                 	->setMapper($this);
			$entries[] = $model;
        }
        return $entries;
    }

    public function save(Default_Model_Delivery $model)
    {
        $data = array(
			'paymentId'				=> $model->getPaymentId(),
			'courierId'				=> $model->getCourierId(),
			'cost'					=> $model->getCost(),
        );
        if(null === ($id = $model->getId())) {
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