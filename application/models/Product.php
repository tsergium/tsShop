<?php
class Default_Model_DbTable_Product extends Zend_Db_Table_Abstract
{
	protected $_name    = 'ts_products';
	protected $_primary = 'id';
}
class Default_Model_Product
{
    protected $_id;
    protected $_promotionId;
	protected $_name;
	protected $_oldprice;
	protected $_price;
	protected $_image;
	protected $_imageShopmania;
	protected $_description;
	protected $_composition;
	protected $_stockNelimitat;
	protected $_stock;
	protected $_status;
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
            throw new Exception('Invalid '.$name.' product property '.$method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if(('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid '.$name.' product property '.$method);
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
    
    public function setPromotionId($promotionId)
    {
        $this->_promotionId = (int) $promotionId;
        return $this;
    }

    public function getPromotionId()
    {
        return $this->_promotionId;
    }

	public function setName($name)
    {
        $this->_name = (string) $name;
        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

	public function setOldprice($oldprice)
    {
        $this->_oldprice = (float) $oldprice;
        return $this;
    }

    public function getOldprice()
    {
        return $this->_oldprice;
    }

	public function setPrice($price)
    {
        $this->_price = (float) $price;
        return $this;
    }

    public function getPrice()
    {
        return $this->_price;
    }

	public function setImage($image)
    {
        $this->_image = (string) $image;
        return $this;
    }

    public function getImage()
    {
        return $this->_image;
    }

	public function setImageShopmania($image)
    {
        $this->_imageShopmania = (string) $image;
        return $this;
    }

    public function getImageShopmania()
    {
        return $this->_imageShopmania;
    }

    public function setDescription($description)
    {
        $this->_description = (string) $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }
    
    public function setComposition($composition)
    {
        $this->_composition = (string) $composition;
        return $this;
    }

    public function getComposition()
    {
        return $this->_composition;
    }

	public function setStockNelimitat($value)
    {
        $this->_stockNelimitat = (string) $value;
        return $this;
    }

    public function getStockNelimitat()
    {
        return $this->_stockNelimitat;
    }
    
	public function setStock($value)
    {
        $this->_stock = (int) $value;
        return $this;
    }

    public function getStock()
    {
        return $this->_stock;
    }

	public function setStatus($status)
    {
        $this->_status = (int) $status;
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

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if(null === $this->_mapper) {
            $this->setMapper(new Default_Model_ProductMapper());
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

class Default_Model_ProductMapper
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
            $this->setDbTable('Default_Model_DbTable_Product');
        }
        return $this->_dbTable;
    }
	
    public function find($id, Default_Model_Product $model)
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
            $model = new Default_Model_Product();
            $model->setOptions($row->toArray())
                 	->setMapper($this);
			$entries[] = $model;
        }
        return $entries;
    }

    public function save(Default_Model_Product $model)
    {
        $data = array(
			'promotionId'		=> $model->getPromotionId(),
			'name'				=> $model->getName(),
			'oldprice'			=> $model->getOldprice(),
			'price'				=> $model->getPrice(),
			'image'				=> $model->getImage(),
			'imageShopmania'	=> $model->getImageShopmania(),
			'description'		=> $model->getDescription(),
			'composition'		=> $model->getComposition(),
			'stockNelimitat'	=> $model->getStockNelimitat(),
			'stock'				=> $model->getStock(),
			'status'			=> $model->getStatus(),
        );
        if(null === ($id = $model->getId())) {
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