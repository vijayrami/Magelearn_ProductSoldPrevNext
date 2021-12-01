<?php

namespace Magelearn\ProductSoldPrevNext\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Reports\Model\ResourceModel\Product\Sold\CollectionFactory;

/**
 * Class Data
 * @package Magelearn\ProductSoldPrevNext\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var CollectionFactory
     */
    protected $reportCollectionFactory;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param CollectionFactory $reportCollectionFactory
     */
    public function __construct(
        Context $context,
        CollectionFactory  $reportCollectionFactory
    ) {
        $this->reportCollectionFactory = $reportCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Get the count of Current Products Sold
     *
     * @param null $productId
     * @return int
     */
    public function getSoldQtyByProductId($productId = null)
    {
        $SoldProducts = $this->reportCollectionFactory->create();
        $SoldProductsCount = $SoldProducts->addOrderedQty()->addAttributeToFilter('product_id', $productId);
        if(!$SoldProductsCount->count()) {
            return 0;
        }
        $product = $SoldProductsCount->getFirstItem();
        return (int)$product->getData('ordered_qty');
    }
}
