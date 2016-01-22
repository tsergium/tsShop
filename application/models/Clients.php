<?php

class Default_Model_Clients
{
    protected $_id;
    protected $_username;
    protected $_password;
    protected $_email;
    protected $_firstname;
    protected $_lastname;
    protected $_firstnameS;
    protected $_lastnameS;
    protected $_birthday;
    protected $_gender;
    protected $_address;
    protected $_addressS;
    protected $_stateS;
    protected $_zipcodeS;
    protected $_county;
    protected $_city;
    protected $_zip;
    protected $_phone;
    protected $_fax;
    protected $_clienttype;
    protected $_companyname;
    protected $_fiscalcode;
    protected $_traderegister;
    protected $_activationcode;
    protected $_status;
    protected $_bank;
    protected $_bankaccount;
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

    public function setRoleId($roleId)
    {
        $role = new Default_Model_Role();
        $role->find($roleId);
        if (null !== $role->getId()) {
            $this->setRole($role);
            $this->_roleId = $role->getId();
        }
        return $this;
    }

    public function setUsername($username)
    {
        $this->_username = (!empty($username)) ? (string)$username : null;
        return $this;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function setPassword($password)
    {
        $this->_password = (string)$password;
        return $this;
    }

    public function getPassword()
    {
        return $this->_password;
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

    public function setFirstname($firstname)
    {
        $this->_firstname = (!empty($firstname)) ? (string)$firstname : null;
        return $this;
    }

    public function getFirstname()
    {
        return $this->_firstname;
    }

    public function setLastname($lastname)
    {
        $this->_lastname = (!empty($lastname)) ? (string)$lastname : null;
        return $this;
    }

    public function getLastname()
    {
        return $this->_lastname;
    }

    public function setFirstnameS($firstnameS)
    {
        $this->_firstnameS = (!empty($firstnameS)) ? (string)$firstnameS : null;
        return $this;
    }

    public function getFirstnameS()
    {
        return $this->_firstnameS;
    }

    public function setLastnameS($lastnameS)
    {
        $this->_lastnameS = (!empty($lastnameS)) ? (string)$lastnameS : null;
        return $this;
    }

    public function getLastnameS()
    {
        return $this->_lastnameS;
    }

    public function setAddressS($addressS)
    {
        $this->_addressS = (!empty($addressS)) ? (string)$addressS : null;
        return $this;
    }

    public function getAddressS()
    {
        return $this->_addressS;
    }

    public function setStateS($stateS)
    {
        $this->_stateS = (!empty($stateS)) ? (string)$stateS : null;
        return $this;
    }

    public function getStateS()
    {
        return $this->_stateS;
    }

    public function setZipcodeS($zipcodeS)
    {
        $this->_zipcodeS = (!empty($zipcodeS)) ? (string)$zipcodeS : null;
        return $this;
    }

    public function getZipcodeS()
    {
        return $this->_zipcodeS;
    }

    public function setBirthday($birthday)
    {
        $this->_birthday = (!empty($birthday) && strtotime($birthday)) ? strtotime($birthday) : null;
        return $this;
    }

    public function getBirthday()
    {
        return $this->_birthday;
    }

    public function setGender($gender)
    {
        $this->_gender = (!empty($gender)) ? (string)$gender : '0';
        return $this;
    }

    public function getGender()
    {
        return $this->_gender;
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

    public function setCounty($county)
    {
        $this->_county = (!empty($county)) ? (string)$county : null;
        return $this;
    }

    public function getCounty()
    {
        return $this->_county;
    }

    public function setCity($city)
    {
        $this->_city = (!empty($city)) ? (string)$city : null;
        return $this;
    }

    public function getCity()
    {
        return $this->_city;
    }

    public function setZip($zip)
    {
        $this->_zip = (!empty($zip)) ? (string)$zip : null;
        return $this;
    }

    public function getZip()
    {
        return $this->_zip;
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

    public function setFax($fax)
    {
        $this->_fax = (!empty($fax)) ? (string)$fax : null;
        return $this;
    }

    public function getFax()
    {
        return $this->_fax;
    }

    public function setFiscalcode($fiscalcode)
    {
        $this->_fiscalcode = (!empty($fiscalcode)) ? (string)$fiscalcode : null;
        return $this;
    }

    public function getFiscalcode()
    {
        return $this->_fiscalcode;
    }

    public function setTraderegister($traderegister)
    {
        $this->_traderegister = (!empty($traderegister)) ? (string)$traderegister : null;
        return $this;
    }

    public function getTraderegister()
    {
        return $this->_traderegister;
    }

    public function setBank($bank)
    {
        $this->_bank = (!empty($bank)) ? (string)$bank : null;
        return $this;
    }

    public function getBank()
    {
        return $this->_bank;
    }

    public function setBankaccount($bankaccount)
    {
        $this->_bankaccount = (!empty($bankaccount)) ? (string)$bankaccount : null;
        return $this;
    }

    public function getBankaccount()
    {
        return $this->_bankaccount;
    }

    public function setActivationcode($activationcode)
    {
        $this->_activationcode = (!empty($activationcode)) ? (string)$activationcode : null;
        return $this;
    }

    public function getActivationcode()
    {
        return $this->_activationcode;
    }

    public function setClienttype($clienttype)
    {
        $this->_clienttype = (string)$clienttype;
        return $this;
    }

    public function getClienttype()
    {
        return $this->_clienttype;
    }

    public function setCompanyname($companyname)
    {
        $this->_companyname = (!empty($companyname)) ? (string)$companyname : null;
        return $this;
    }

    public function getCompanyname()
    {
        return $this->_companyname;
    }

    public function setStatus($status)
    {
        $this->_status = (!empty($status)) ? (string)$status : "0";
        return $this;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setCreated($date)
    {
        $this->_created = (!empty($date) && strtotime($date) > 0) ? strtotime($date) : null;
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
            $this->setMapper(new Default_Model_ClientsMapper());
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

    public function fetchRow($select = null)
    {
        return $this->getMapper()->fetchRow($select, $this);
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