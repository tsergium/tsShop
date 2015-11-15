<?php
class Default_Model_DbTable_ProductSpecifications extends Zend_Db_Table_Abstract
{
	protected $_name    = 'fp_product_specifications';
	protected $_primary = 'id';
}

class Default_Model_ProductSpecifications
{
    protected $_id;
	protected $_productId;
	protected $_product;
	protected $_modelId;
	protected $_model;
	protected $_groupId;
	protected $_group;
	protected $_listId;
	protected $_list;
	protected $_value;
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

	public function setProductId($productId)
    {
    	$product = new Default_Model_Products();
    	$product->find($productId);
    	if(null !== $product->getId()) {
    		$this->setProduct($product);
	        $this->_productId = $product->getId();
    	}
        return $this;
    }

    public function getProductId()
    {
        return $this->_productId;
    }

    public function setProduct(Default_Model_Products $product)
    {
    	$this->_product = $product;
    	return $this;
    }

    public function getProduct()
    {
    	return $this->_product;
    }

	public function setModelId($value)
    {
		$this->_modelId = $value;
    }

    public function getModelId()
    {
        return $this->_modelId;
    }

    public function setModel($value)
    {
    	$this->_model = $value;
    	return $this;
    }

    public function getModel()
    {
    	return $this->_model;
    }

	public function setGroupId($groupId)
    {
	    $this->_groupId = $groupId;
        return $this;
    }

    public function getGroupId()
    {
        return $this->_groupId;
    }

    public function setGroup($group)
    {
    	$this->_group = $group;
    	return $this;
    }

    public function getGroup()
    {
    	return $this->_group;
    }

	public function setListId($listId)
    {
	    $this->_listId = $listId;
        return $this;
    }

    public function getListId()
    {
        return $this->_listId;
    }

    public function setList($list)
    {
    	$this->_list = $list;
    	return $this;
    }

    public function getList()
    {
    	return $this->_list;
    }

	public function setValue($value)
    {
        $this->_value = (string) $value;
        return $this;
    }

    public function getValue()
    {
        return $this->_value;
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
            $this->setMapper(new Default_Model_ProductSpecificationsMapper());
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

class Default_Model_ProductSpecificationsMapper
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
            $this->setDbTable('Default_Model_DbTable_ProductSpecifications');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_ProductSpecifications $value)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $value->setOptions($row->toArray());
		return $value;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach($resultSet as $row) {
            $value = new Default_Model_ProductSpecifications();
            $value->setOptions($row->toArray())
					->setMapper($this);
			if(isset($row->groupId)) {
				$model = new Default_Model_ProductsSpecifications();
				$model->find($row->modelId);
				$value->setModel($model);
				$group = new Default_Model_ProductsSpecificationsGroup();
				$group->find($row->groupId);
				$value->setGroup($group);
				$list = new Default_Model_ProductsSpecificationsModel();
				$list->find($row->listId);
				$value->setList($list);
            }
			$entries[] = $value;
        }
        return $entries;
    }

    public function save(Default_Model_ProductSpecifications $value)
    {
        $data = array(
			'productId'				=> $value->getProductId(),
			'modelId'				=> $value->getModelId(),
			'groupId'				=> $value->getGroupId(),
			'listId'				=> $value->getListId(),
			'value'					=> $value->getValue(),
        );
        if(null === ($id = $value->getId())) {
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