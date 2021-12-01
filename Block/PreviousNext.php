<?php

namespace Magelearn\ProductSoldPrevNext\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\ObjectManagerInterface;

class PreviousNext extends Template
{
    protected $_product = null;
    protected $_registry;
    protected $_objectManager;
    protected $_productModel;

	public function __construct(
        Context $context,
        Registry $registry,
        ObjectManagerInterface $objectManager,
        array $data = []
	)
	{
        $this->_registry = $registry;
        $this->_objectManager = $objectManager;
        $this->_productModel = 'Magento\Catalog\Model\Product';
        $this->_categoryModel = 'Magento\Catalog\Model\Category';
        parent::__construct($context,$data);
    }
    
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_registry->registry('product');
        }
        return $this->_product;
    }

    public function getProductModel() {
        return $this->_objectManager->create($this->_productModel);
    }

    public function getCategoryModel() {
        return $this->_objectManager->create($this->_categoryModel);
    }

    public function getCurrentCategory($category_ids)
    {
        $category = $this->getCategoryModel()->load($category_ids[0]);
        return $category;
    }

    public function getCategoryProductIds($category) {
        $category_products = $category->getProductCollection();
        $category_products->addAttributeToSelect('*');
        $category_products->addAttributeToFilter('status',1);
        $category_products->addAttributeToFilter('visibility',4);
        $category_products->addAttributeToSort('position', 'asc');
            
        $cat_prod_ids = $category_products->getAllIds();
        
        return $cat_prod_ids;
    }

    public function getPrevProduct($productId,$category_ids) {
        $category = $this->getCurrentCategory($category_ids);
        $cat_prod_ids = $this->getCategoryProductIds($category);
        $product_id = array_search($productId, $cat_prod_ids);
        $prev_product_id = $product_id - 1;
        $keys = array_keys($cat_prod_ids);

        if(in_array($prev_product_id, $keys)){
            $_prev_prod = $this->getProductModel()->load($cat_prod_ids[$prev_product_id]);
            return $_prev_prod;
        }
        return false;
    }
    
    public function getNextProduct($productId,$category_ids) {
        $category = $this->getCurrentCategory($category_ids);
        $cat_prod_ids = $this->getCategoryProductIds($category);
        $product_id = array_search($productId, $cat_prod_ids);
        $next_product_id = $product_id + 1;
        $keys = array_keys($cat_prod_ids);

        if(in_array($next_product_id, $keys)){
            $_next_prod = $this->getProductModel()->load($cat_prod_ids[$next_product_id]);
            return $_next_prod;
        }
        return false;
    }
}