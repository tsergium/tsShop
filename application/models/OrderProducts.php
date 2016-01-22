<?php

class Default_Model_DbTable_OrderProducts extends Zend_Db_Table_Abstract
{
    protected $_name = 'ts_order_products';
    protected $_primary = 'id';
}

class Default_Model_OrderProducts
{
    protected $_id;
    protected $_orderId;
    protected $_order;
    protected $_customerId;
    protected $_customer;
    protected $_productId;
    protected $_product;
    protected $_sizeId;
    protected $_sizeName;
    protected $_colorId;
    protected $_colorName;
    protected $_quantity;
    protected $_price;
    protected $_date;
    protected $_datetime;
    protected $_modified;

    protected $_bestSellers;

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
            throw new Exception('Invalid ' . $name . ' product property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid ' . $name . ' product property');
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

    public function setOrderId($orderId)
    {
        $order = new Default_Model_Orders();
        $order->find($orderId);
        if (null !== $order->getId()) {
            $this->setOrder($order);
            $this->_orderId = $order->getId();
        }
        return $this;
    }

    public function getOrderId()
    {
        return $this->_orderId;
    }

    public function setOrder(Default_Model_Orders $order)
    {
        $this->_order = $order;
        return $this;
    }

    public function getOrder()
    {
        return $this->_order;
    }

    public function setCustomerId($customerId)
    {
        $customer = new Default_Model_AdminUser();
        $customer->find($customerId);
        if (null !== $customer->getId()) {
            $this->setCustomer($customer);
            $this->_customerId = $customer->getId();
        }
        return $this;
    }

    public function getCustomerId()
    {
        return $this->_customerId;
    }

    public function setCustomer(Default_Model_AdminUser $customer)
    {
        $this->_customer = $customer;
        return $this;
    }

    public function getCustomer()
    {
        return $this->_customer;
    }

    public function setProductId($productId)
    {
        $product = new Default_Model_Product();
        $product->find($productId);
        if (null !== $product->getId()) {
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
        $this->_sizeId = $value;
        $attribute = new Default_Model_ProductAttributeValue();
        $attribute->find($value);
        if (null !== $attribute->getId()) {
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
        $this->_sizeName = $value;
        return $this;
    }

    public function getSizeName()
    {
        return $this->_sizeName;
    }

    public function setColorId($value)
    {
        $this->_colorId = $value;
        $attribute = new Default_Model_ProductAttributeValue();
        $attribute->find($value);
        if (null !== $attribute->getId()) {
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
        $this->_colorName = $value;
        return $this;
    }

    public function getColorName()
    {
        return $this->_colorName;
    }

    public function setQuantity($quantity)
    {
        $this->_quantity = (int)$quantity;
        return $this;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

    public function setPrice($price)
    {
        $this->_price = (float)$price;
        return $this;
    }

    public function getPrice()
    {
        return $this->_price;
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

    public function setModified($date)
    {
        $this->_modified = (!empty($date) && strtotime($date) > 0) ? strtotime($date) : null;
        return $this;
    }

    public function getModified()
    {
        return $this->_modified;
    }

    public function setBestSellers($bestSellers)
    {
        $this->_bestSellers = (int)$bestSellers;
        return $this;
    }

    public function getBestSellers()
    {
        return $this->_bestSellers;
    }

    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Default_Model_OrderProductsMapper());
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
        if (null === ($id = $this->getId())) {
            throw new Exception('Invalid record selected!');
        }
        return $this->getMapper()->delete($id);
    }
}

class Default_Model_OrderProductsMapper
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
            $this->setDbTable('Default_Model_DbTable_OrderProducts');
        }
        return $this->_dbTable;
    }

    public function find($id, Default_Model_OrderProducts $order)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $order->setOptions($row->toArray());
        return $order;
    }

    public function fetchAll($select)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);

        $entries = array();
        foreach ($resultSet as $row) {
            $order = new Default_Model_OrderProducts();
            $order->setOptions($row->toArray())
                ->setMapper($this);
            $entries[] = $order;
        }
        return $entries;
    }

    public function save(Default_Model_OrderProducts $order)
    {
        $data = array(
            'orderId' => $order->getOrderId(),
            'customerId' => $order->getCustomerId(),
            'productId' => $order->getProductId(),
            'sizeId' => $order->getSizeId(),
            'colorId' => $order->getColorId(),
            'quantity' => $order->getQuantity(),
            'price' => $order->getPrice(),
        );
        if (null === ($id = $order->getId())) {
            $data['date'] = new Zend_Db_Expr('CURDATE()');
            $data['datetime'] = new Zend_Db_Expr('NOW()');
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