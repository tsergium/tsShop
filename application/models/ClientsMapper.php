<?php
class Default_Model_ClientsMapper
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
            $this->setDbTable('Default_Model_DbTable_Clients');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_Clients $clients)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $clients -> setOptions($row->toArray());
        return $clients;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach($resultSet as $row) {
            $clients = new Default_Model_Clients();
            $clients->setOptions($row->toArray())
                ->setMapper($this);
            $entries[] = $clients;
        }
        return $entries;
    }


    public function fetchRow($select, Default_Model_Clients $model)
    {
        $result=$this->getDbTable()->fetchRow($select);
        if (0 == count($result)) {
            return;
        }
        $model->setOptions($result->toArray());
        return $model;
    }

    public function save(Default_Model_Clients $value)
    {
        $data = array(
            'username'			=> $value->getUsername(),
            'password'			=> $value->getPassword(),
            'email'			=> $value->getEmail(),
            'firstname'			=> $value->getFirstname(),
            'lastname'			=> $value->getLastname(),
            'firstnameS'		=> $value->getFirstnameS(),
            'lastnameS'			=> $value->getLastnameS(),
            'birthday'			=> ($value->getBirthday()!= null)?date('Y-m-d',$value->getBirthday()):null,
            'gender'			=> $value->getGender(),
            'address'			=> $value->getAddress(),
            'addressS'			=> $value->getAddressS(),
            'stateS'			=> $value->getStateS(),
            'zipcodeS'			=> $value->getZipcodeS(),
            'county'			=> $value->getCounty(),
            'city'			=> $value->getCity(),
            'zip'			=> $value->getZip(),
            'phone'			=> $value->getPhone(),
            'fax'			=> $value->getFax(),
            'clienttype'		=> $value->getClienttype(),
            'companyname'		=> $value->getCompanyname(),
            'fiscalcode'		=> $value->getFiscalcode(),
            'traderegister'		=> $value->getTraderegister(),
            'bank'			=> $value->getBank(),
            'activationcode'            => $value->getActivationcode(),
            'status'			=> $value->getStatus(),
            'bankaccount'			=> $value->getBankaccount(),
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

    public function delete($id)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id);
        return $this->getDbTable()->delete($where);
    }
}