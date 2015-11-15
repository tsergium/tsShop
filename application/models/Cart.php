<?php
class Default_Model_DbTable_Cart extends Zend_Db_Table_Abstract
{
	protected $_name    = 'ts_cart';
	protected $_primary = 'id';
}

class Default_Model_Cart
{
    protected $_id;
	protected $_productId;
	protected $_product;
	protected $_sizeId;
	protected $_sizeName;
	protected $_colorId;
	protected $_colorName;
	protected $_quantity;
	protected $_cookie;
	protected $_date;

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
            throw new Exception('Invalid cart property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if(('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid cart property');
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
    	$product = new Default_Model_Product();
    	$product -> find($productId);
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

    public function setProduct(Default_Model_Product $product)
    {
    	$this->_product = $product;
    	return $this;
    }

    public function getProduct()
    {
    	return $this->_product;
    }
    
    public function setSizeId($value)
    {
    	$this->_sizeId = (int) $value;
    	$attribute = new Default_Model_ProductAttributeValue();
    	$attribute -> find($value);
    	if(null !== $attribute->getId()) {
    		$this->setSizeName($attribute->getName());	       
    	}
        return $this;
    }

    public function getSizeId()
    {
    	return $this->_sizeId;
    }
    
    public function setSizeName($value)
    {
    	$this->_sizeName = (string) $value;
    	return $this;
    }

    public function getSizeName()
    {
    	return $this->_sizeName;
    }
    
    public function setColorId($value)
    {
    	$this->_colorId = (int) $value;
    	$attribute = new Default_Model_ProductAttributeValue();
    	$attribute -> find($value);
    	if(null !== $attribute->getId()) {
    		$this->setColorName($attribute->getName());	       
    	}
    	return $this;
    }

    public function getColorId()
    {
    	return $this->_colorId;
    }
    
    public function setColorName($value)
    {
    	$this->_colorName = (string) $value;
    	return $this;
    }

    public function getColorName()
    {
    	return $this->_colorName;
    }

	public function setQuantity($quantity)
    {
        $this->_quantity = (string) $quantity;
        return $this;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

	public function setCookie($cookie)
    {
        $this->_cookie = (string) $cookie;
        return $this;
    }

    public function getCookie()
    {
        return $this->_cookie;
    }

    public function setDate($date)
    {
        $this->_date = (!empty($date) && strtotime($date)>0)?strtotime($date):null;
        return $this;
    }

    public function getDate()
    {
        return $this->_date;
    }

	public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if(null === $this->_mapper) {
            $this->setMapper(new Default_Model_CartMapper());
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

class Default_Model_CartMapper
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
            $this->setDbTable('Default_Model_DbTable_Cart');
        }
        return $this->_dbTable;
    }

	public function find($id, Default_Model_Cart $cart)
    {
        $result = $this->getDbTable()->find($id);
        if(0 == count($result)) {
            return;
        }
        $row = $result->current();
        $cart->setOptions($row->toArray());
		return $cart;
    }

	public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach($resultSet as $row) {
            $cart = new Default_Model_Cart();
            $cart->setOptions($row->toArray())
                  	->setMapper($this);
//			if (isset($row->productId)) {
//				$brand = new Default_Model_Products();
//				$brand->find($row->productId);
//				$cart->setProduct($brand);
//            }
            $entries[] = $cart;
        }
        return $entries;
    }

	public function save(Default_Model_Cart $cart)
    {
        $data = array(
			'productId'				=> $cart->getProductId(),
			'sizeId'				=> $cart->getSizeId(),
			'colorId'				=> $cart->getColorId(),
			'quantity'				=> $cart->getQuantity(),
			'cookie'				=> $cart->getCookie(),
        );
        if(null === ($id = $cart->getId())) {
        	$data['date']		 = new Zend_Db_Expr('CURDATE()');
            $id = $this->getDbTable()->insert($data);
        } else {
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