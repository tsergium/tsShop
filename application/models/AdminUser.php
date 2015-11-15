<?php
class Default_Model_DbTable_AdminUser extends Zend_Db_Table_Abstract
{
    protected $_name		= 'ts_admin_user';
    protected $_primary		= 'id';
    protected $_referenceMap	= array(
	'Roles' => array(
	'columns'           => 'roleId',
	'refTableClass'     => 'Default_Model_DbTable_Role',
	'refColumns'        => 'id',
	),
    );
}

class Default_Model_AdminUser
{
	protected $_id;
	protected $_roleId;
	protected $_role;
	protected $_username;
	protected $_password;
	protected $_salutation;
	protected $_firstname;
	protected $_lastname;
	protected $_cnp;
	protected $_seria;
	protected $_address;
	protected $_county;
	protected $_city;
	protected $_zip;
	protected $_phone;
	protected $_phone2;
	protected $_fax;
	protected $_email;
	protected $_addressS;
	protected $_countyS;
	protected $_cityS;
	protected $_zipS;
	protected $_phoneS;
	protected $_institution;
	protected $_fiscalcode;
	protected $_traderegister;
	protected $_bank;
	protected $_ibancode;
	protected $_function;
	protected $_department;
	protected $_activationcode;
	protected $_campaign;
	protected $_person;
	protected $_status;
	protected $_created;
	protected $_modified;
	protected $_lastlogin;
	protected $_lognum;
	
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

	public function setRoleId($roleId)
    {
    	$role = new Default_Model_Role();
    	$role->find($roleId);
    	if(null !== $role->getId()) {
    		$this->setRole($role);
	        $this->_roleId = $role->getId();
    	}
        return $this;
    }

    public function getRoleId()
    {
        return $this->_roleId;
    }

    public function setRole(Default_Model_Role $role)
    {
    	$this->_role = $role;
    	return $this;
    }

    public function getRole()
    {
    	return $this->_role;
    }

    public function setUsername($username)
    {
        $this->_username = (!empty($username))?(string) $username:null;
        return $this;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function setPassword($password)
    {
        $this->_password = (string) $password;
        return $this;
    }

    public function getPassword()
    {
        return $this->_password;
    }

	public function setFirstname($firstname)
    {
        $this->_firstname = (!empty($firstname))?(string) $firstname:null;
        return $this;
    }
	
	public function getFirstname()
    {
        return $this->_firstname;
    }

	public function setSalutation($salutation)
	{
		$this->_salutation = (!empty($salutation))?(string) $salutation:null;
		return $this;
	}

	public function getSalutation()
	{
		return $this->_salutation;
	}    

    public function setLastname($lastname)
    {
        $this->_lastname = (!empty($lastname))?(string) $lastname:null;
        return $this;
    }

    public function getLastname()
    {
        return $this->_lastname;
    }

	public function setCnp($cnp)
	{
		$this->_cnp = (!empty($cnp))?(string) $cnp:null;
		return $this;
	}

	public function getCnp()
	{
		return $this->_cnp;
	}

	public function setSeria($seria)
	{
		$this->_seria = (!empty($seria))?(string) $seria:null;
		return $this;
	}

	public function getSeria()
	{
		return $this->_seria;
	}

	public function setAddress($address)
	{
		$this->_address = (!empty($address))?(string) $address:null;
		return $this;
	}

	public function getAddress()
	{
		return $this->_address;
	}

	public function setCounty($county)
	{
		$this->_county = (!empty($county))?(string) $county:null;
		return $this;
	}

	public function getCounty()
	{
		return $this->_county;
	}

	public function setCity($city)
	{
		$this->_city = (!empty($city))?(string) $city:null;
		return $this;
	}

	public function getCity()
	{
		return $this->_city;
	}

	public function setZip($zip)
	{
		$this->_zip = (!empty($zip))?(string) $zip:null;
		return $this;
	}

	public function getZip()
	{
		return $this->_zip;
	}

	public function setPhone($phone)
	{
		$this->_phone = (!empty($phone))?(string) $phone:null;
		return $this;
	}

	public function getPhone()
	{
		return $this->_phone;
	}

	public function setPhone2($phone2)
	{
		$this->_phone2 = (!empty($phone2))?(string) $phone2:null;
		return $this;
	}

	public function getPhone2()
	{
		return $this->_phone2;
	}

	public function setFax($fax)
	{
		$this->_fax = (!empty($fax))?(string) $fax:null;
		return $this;
	}

	public function getFax()
	{
		return $this->_fax;
	}

	public function setEmail($email)
    {
        $this->_email = (!empty($email))?(string) $email:null;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
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

	public function setCountyS($countyS)
	{
		$this->_countyS = (!empty($countyS))?(string) $countyS:null;
		return $this;
	}

	public function getCountyS()
	{
		return $this->_countyS;
	}

	public function setCityS($cityS)
	{
		$this->_cityS = (!empty($cityS))?(string) $cityS:null;
		return $this;
	}

	public function getCityS()
	{
		return $this->_cityS;
	}

	public function setZipS($zipS)
	{
		$this->_zipS = (!empty($zipS))?(string) $zipS:null;
		return $this;
	}

	public function getZipS()
	{
		return $this->_zipS;
	}

	public function setPhoneS($phoneS)
	{
		$this->_phoneS = (!empty($phoneS))?(string) $phoneS:null;
		return $this;
	}

	public function getPhoneS()
	{
		return $this->_phoneS;
	}

	public function setInstitution($institution)
	{
		$this->_institution = (!empty($institution))?(string) $institution:null;
		return $this;
	}

	public function getInstitution()
	{
		return $this->_institution;
	}

	public function setFiscalcode($fiscalcode)
	{
		$this->_fiscalcode = (!empty($fiscalcode))?(string) $fiscalcode:null;
		return $this;
	}

	public function getFiscalcode()
	{
		return $this->_fiscalcode;
	}

	public function setTraderegister($traderegister)
	{
		$this->_traderegister = (!empty($traderegister))?(string) $traderegister:null;
		return $this;
	}

	public function getTraderegister()
	{
		return $this->_traderegister;
	}

	public function setBank($bank)
	{
		$this->_bank = (!empty($bank))?(string) $bank:null;
		return $this;
	}

	public function getBank()
	{
		return $this->_bank;
	}

	public function setIbancode($ibancode)
	{
		$this->_ibancode = (!empty($ibancode))?(string) $ibancode:null;
		return $this;
	}

	public function getIbancode()
	{
		return $this->_ibancode;
	}

	public function setFunction($function)
	{
		$this->_function = (!empty($function))?(string) $function:null;
		return $this;
	}

	public function getFunction()
	{
		return $this->_function;
	}

	public function setDepartment($department)
	{
		$this->_department = (!empty($department))?(string) $department:null;
		return $this;
	}

	public function getDepartment()
	{
		return $this->_department;
	}

	public function setActivationcode($activationcode)
	{
	   $this->_activationcode = (!empty($activationcode))?(string) $activationcode:null;
		return $this;
	}

	public function getActivationcode()
	{
		return $this->_activationcode;
	}

	public function setCampaign($value)
	{
	   $this->_campaign = (!empty($value))?(string) $value:null;
		return $this;
	}

	public function getCampaign()
	{
		return $this->_campaign;
	}

	public function setPerson($person)
    {
        $this->_person = (string) $person;
        return $this;
    }

    public function getPerson()
    {
        return $this->_person;
    }

	public function setStatus($status)
    {
        $this->_status = (!empty($status))?(string) $status:"0";
        return $this;
    }

    public function getStatus()
    {
        return $this->_status;
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

    public function setLognum($lognum)
    {
        $this->_lognum = (int) $lognum;
        return $this;
    }

    public function getLognum()
    {
        return $this->_lognum;
    }
   
    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_AdminUserMapper());
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

	public function setLastlogin($date)
    {
        $this->_lastlogin = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
        return $this;
    }

    public function getLastlogin()
    {
        return $this->_lastlogin;
    }

	public function saveLastlogin()
    {
        $this->getMapper()->saveLastlogin($this);
    }
 
    public function delete()
    {
    	if (null === ($id = $this->getId())) {
    		throw new Exception("Invalid record selected!");
    	}
        return $this->getMapper()->delete($id);
    }
}

class Default_Model_AdminUserMapper
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
            $this->setDbTable('Default_Model_DbTable_AdminUser');
        }
        return $this->_dbTable;
    }
    
    public function find($id, Default_Model_AdminUser $adminUser)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $adminUser -> setOptions($row->toArray());
		return $adminUser;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);
        
        $entries = array();
        foreach($resultSet as $row) {
            $adminUser = new Default_Model_AdminUser();
            $adminUser->setOptions($row->toArray())
                  	->setMapper($this);
            $entries[] = $adminUser;
        }
        return $entries;
    }
    
    public function save(Default_Model_AdminUser $value)
    {
        $data = array(
			'roleId'			=> $value->getRoleId(),
			'username'			=> $value->getUsername(),
			'password'			=> $value->getPassword(),
			'salutation'		=> $value->getSalutation(),
			'firstname'			=> $value->getFirstname(),
			'lastname'			=> $value->getLastname(),
            'cnp'				=> $value->getCnp(),
            'seria'				=> $value->getSeria(),
            'address'			=> $value->getAddress(),
			'county'			=> $value->getCounty(),
            'city'				=> $value->getCity(),
            'zip'				=> $value->getZip(),
            'phone'				=> $value->getPhone(),
            'phone2'			=> $value->getPhone2(),
            'fax'				=> $value->getFax(),
			'email'				=> $value->getEmail(),
            'addressS'			=> $value->getAddressS(),
			'countyS'			=> $value->getCountyS(),
            'cityS'				=> $value->getCityS(),
            'zipS'				=> $value->getZipS(),
            'phoneS'			=> $value->getPhoneS(),
			'institution'		=> $value->getInstitution(),
			'fiscalcode'		=> $value->getFiscalcode(),
			'traderegister'		=> $value->getTraderegister(),
			'bank'				=> $value->getBank(),
			'ibancode'			=> $value->getIbancode(),
			'function'			=> $value->getFunction(),
			'department'		=> $value->getDepartment(),
            'activationcode'	=> $value->getActivationcode(),
            'campaign'			=> $value->getCampaign(),
            'person'			=> $value->getPerson(),
			'status'			=> $value->getStatus(),
        );
        
        if (null === ($id = $value->getId())) {
        	$data['created']	 = new Zend_Db_Expr('NOW()');
            $id = $this->getDbTable()->insert($data);
            
        } else {
        	$data['modified']	 = new Zend_Db_Expr('NOW()');
            $this->getDbTable()->update($data, array('id = ?' => $id));
            
        }
        return $id;
    }

	public function saveLastlogin(Default_Model_AdminUser $adminUser)
    {
        $data = array();

        if(null === ($id = $adminUser->getId())) {
        	throw new Exception("Invalid admin account selected!");
        } else {
			$data['lastlogin']	 = new Zend_Db_Expr('NOW()');
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