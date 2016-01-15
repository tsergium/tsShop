<?php
class Default_Model_Product
{
    protected $_id;
    protected $_promotionId;
    protected $_urlOrigin;
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

    public function setUrlOrigin($urlOrigin)
    {
        $this->_urlOrigin = (string) $urlOrigin;
        return $this;
    }

    public function getUrlOrigin()
    {
        return $this->_urlOrigin;
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