<?php
class Default_Model_DbTable_Orders extends Zend_Db_Table_Abstract
{
	protected $_name    = 'ts_orders';
	protected $_primary = 'id';
}

class Default_Model_Orders
{
    protected $_id;
    protected $_customerId;
	protected $_paymentId;
	protected $_paymentName;
	protected $_courierId;
	protected $_courierName;
	protected $_deliveryCost;
	protected $_productscost;
	protected $_totalcost;

	protected $_clienttype;
	protected $_institution;
	protected $_fiscalcode;
	protected $_traderegister;
	protected $_bank;
	protected $_ibancode;
	protected $_function;
	protected $_department;

	protected $_firstname;
	protected $_lastname;
	protected $_county;
	protected $_address;
	protected $_city;
	protected $_zip;
	protected $_phone;
	protected $_fax;
	protected $_email;

	protected $_firstnameS;
	protected $_lastnameS;
	protected $_stateS;
	protected $_addressS;
	protected $_zipcodeS;

	protected $_comments;
	protected $_status;

	protected $_created;
	protected $_modified;
    protected $_couponId;

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
            throw new Exception('Invalid '.$name.' property '. $method);
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

    public function setCustomerId($customerId)
    {
        $this->_customerId = (!empty($customerId))?(string) $customerId:'0';
        return $this;
    }

    public function getCustomerId()
    {
        return $this->_customerId;
    }

    public function setPaymentId($value)
    {
        $this->_paymentId = (int) $value;
    	$model = new Default_Model_DeliveryPayment();
    	$model->find($value);
    	if(null !== $model->getId()) {
    		$this->setPaymentName($model->getName());
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
    	$model = new Default_Model_DeliveryCourier();
    	$model -> find($value);
    	if(null !== $model->getId()) {
    		$this->setCourierName($model->getName());
    	}
        return $this;
    }

    public function getCourierId()
    {
        return $this->_courierId;
    }

    public function setCourierName($value)
    {
    	$this->_courierName = (string) $value;
    	return $this;
    }

    public function getCourierName()
    {
    	return $this->_courierName;
    }

	public function setDeliveryCost($value)
    {
        $this->_deliveryCost = (float) $value;
        return $this;
    }

    public function getDeliveryCost()
    {
        return $this->_deliveryCost;
    }

	public function setProductscost($value)
    {
        $this->_productscost = (float) $value;
        return $this;
    }

    public function getProductscost()
    {
        return $this->_productscost;
    }

	public function setTotalcost($value)
    {
        $this->_totalcost = (float) $value;
        return $this;
    }

    public function getTotalcost()
    {
        return $this->_totalcost;
    }

	public function setClienttype($value)
    {
        $this->_clienttype = (!empty($value))?(string) $value:'0';
        return $this;
    }

    public function getClienttype()
    {
        return $this->_clienttype;
    }

	public function setInstitution($value)
    {
        $this->_institution = (!empty($value))?(string) $value:null;
        return $this;
    }

    public function getInstitution()
    {
        return $this->_institution;
    }

	public function setFiscalcode($value)
    {
        $this->_fiscalcode = (!empty($value))?(string) $value:null;
        return $this;
    }

    public function getFiscalcode()
    {
        return $this->_fiscalcode;
    }

	public function setTraderegister($value)
    {
        $this->_traderegister = (!empty($value))?(string) $value:null;
        return $this;
    }

    public function getTraderegister()
    {
        return $this->_traderegister;
    }

	public function setBank($value)
    {
        $this->_bank = (!empty($value))?(string) $value:null;
        return $this;
    }

    public function getBank()
    {
        return $this->_bank;
    }

	public function setIbancode($value)
    {
        $this->_ibancode = (!empty($value))?(string) $value:null;
        return $this;
    }

    public function getIbancode()
    {
        return $this->_ibancode;
    }

	public function setFunction($value)
    {
        $this->_function = (!empty($value))?(string) $value:null;
        return $this;
    }

    public function getFunction()
    {
        return $this->_function;
    }

	public function setDepartment($value)
    {
        $this->_department = (!empty($value))?(string) $value:null;
        return $this;
    }

    public function getDepartment()
    {
        return $this->_department;
    }

	public function setFirstname($value)
    {
        $this->_firstname = (string) $value;
        return $this;
    }

    public function getFirstname()
    {
        return $this->_firstname;
    }

	public function setLastname($value)
    {
        $this->_lastname = (string) $value;
        return $this;
    }

    public function getLastname()
    {
        return $this->_lastname;
    }

	public function setAddress($value)
    {
        $this->_address = (string) $value;
        return $this;
    }

    public function getAddress()
    {
        return $this->_address;
    }

	public function setCounty($value)
    {
        $this->_county = (string) $value;
        return $this;
    }

    public function getCounty()
    {
        return $this->_county;
    }

	public function setCity($value)
    {
        $this->_city = (string) $value;
        return $this;
    }

    public function getCity()
    {
        return $this->_city;
    }

	public function setZip($value)
    {
        $this->_zip = (string) $value;
        return $this;
    }

    public function getZip()
    {
        return $this->_zip;
    }

	public function setPhone($value)
    {
        $this->_phone = (string) $value;
        return $this;
    }

    public function getPhone()
    {
        return $this->_phone;
    }

	public function setFax($value)
    {
        $this->_fax = (!empty($value))?(string) $value:null;
        return $this;
    }

    public function getFax()
    {
        return $this->_fax;
    }

	public function setFirstnameS($firstnameS)
    {
        $this->_firstnameS = (!empty($firstnameS))?(string) $firstnameS:null;
        return $this;
    }

    public function getFirstnameS()
    {
        return $this->_firstnameS;
    }

	public function setLastnameS($lastnameS)
    {
        $this->_lastnameS = (!empty($lastnameS))?(string) $lastnameS:null;
        return $this;
    }

    public function getLastnameS()
    {
        return $this->_lastnameS;
    }

	public function setAddressS($addressS)
    {
        $this->_addressS = (!empty($addressS))?(string) $addressS:null;
        return $this;
    }

    public function getAddressS()
    {
        return $this->_addressS;
    }

	public function setStateS($stateS)
    {
        $this->_stateS = (!empty($stateS))?(string) $stateS:null;
        return $this;
    }

    public function getStateS()
    {
        return $this->_stateS;
    }
	public function setZipcodeS($zipcodeS)
    {
        $this->_zipcodeS = (!empty($zipcodeS))?(string) $zipcodeS:null;
        return $this;
    }

    public function getZipcodeS()
    {
        return $this->_zipcodeS;
    }

	public function setEmail($value)
    {
        $this->_email = (string) $value;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

	public function setComments($value)
    {
        $this->_comments = (!empty($value))?(string) $value:null;
        return $this;
    }

    public function getComments()
    {
        return $this->_comments;
    }

	public function setStatus($status)
    {
        $this->_status = (string) $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->_status;
    }

	public function setCreated($value)
    {
        $this->_created = (!empty($value) && strtotime($value)>0)?strtotime($value):null;
        return $this;
    }

    public function getCreated()
    {
        return $this->_created;
    }

	public function setModified($value)
    {
        $this->_modified = (!empty($value) && strtotime($value)>0)?strtotime($value):null;
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

    public function getCouponId()
    {
        return $this->_couponId;
    }

    public function getCouponCode()
    {
        return $this->getMapper()->getCouponCode($this->_couponId);
    }

    public function setCouponId($couponId)
    {
        $this->_couponId = $couponId;
        return $this;
    }

    public function getMapper()
    {
        if(null === $this->_mapper) {
            $this->setMapper(new Default_Model_OrdersMapper());
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

class Default_Model_OrdersMapper
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

    public function getCouponCode($id)
    {
        $couponM = new Default_Model_Coupon();
        $couponM->find($id);

        return $couponM->getCode();
    }

    public function getDbTable()
    {
        if(null === $this->_dbTable) {
            $this->setDbTable('Default_Model_DbTable_Orders');
        }
        return $this->_dbTable;
    }

	public function find($id, Default_Model_Orders $orders)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $orders->setOptions($row->toArray());
		return $orders;
    }

	public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries   = array();
        foreach ($resultSet as $row) {
            $val = new Default_Model_Orders();
            $val->setOptions($row->toArray())
				->setMapper($this);
            $entries[] = $val;
        }
        return $entries;
    }

	public function save(Default_Model_Orders $val)
    {
        $data = array(
			'customerId'		=> $val->getCustomerId(),
			'paymentId'			=> $val->getPaymentId(),
			'courierId'			=> $val->getCourierId(),
			'productscost'		=> $val->getProductscost(),
			'totalcost'			=> $val->getTotalcost(),
			'deliveryCost'		=> $val->getDeliveryCost(),

			'clienttype'		=> $val->getClientType(),
			'institution'		=> $val->getInstitution(),
			'fiscalcode'		=> $val->getFiscalcode(),
			'traderegister'		=> $val->getTraderegister(),
			'bank'				=> $val->getBank(),
			'ibancode'			=> $val->getIbancode(),
			'function'			=> $val->getFunction(),
			'department'		=> $val->getDepartment(),

			'firstname'			=> $val->getFirstname(),
			'lastname'			=> $val->getLastname(),
			'address'			=> $val->getAddress(),
			'county'			=> $val->getCounty(),
			'city'				=> $val->getCity(),
			'zip'				=> $val->getZip(),
			'phone'				=> $val->getPhone(),
			'fax'				=> $val->getFax(),
			'email'				=> $val->getEmail(),

			'firstnameS'		=> $val->getFirstnameS(),
			'lastnameS'			=> $val->getLastnameS(),
			'addressS'			=> $val->getAddressS(),
			'stateS'			=> $val->getStateS(),
			'zipcodeS'			=> $val->getZipcodeS(),
            'couponId'          => $val->getCouponId(),

			'comments'			=> $val->getComments(),
			'status'			=> $val->getStatus(),
		);

        if (null === ($id = $val->getId())) {
			$data['created']	 = new Zend_Db_Expr('NOW()');
            $id = $this->getDbTable()->insert($data);
        } else {
        	$data['modified']	 = new Zend_Db_Expr('NOW()');
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