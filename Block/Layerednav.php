<?php 
namespace Magepow\Layerednav\Block;

class Layerednav extends \Magento\Framework\View\Element\Template
{
	protected $_registry;
	protected $_productCollectionFactory;
    protected $_catalogProductVisibility;
    protected $_ProductCollection;
    protected $_productFactory;

	public function __construct(
		\Magento\Framework\Registry $registry,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		\Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $ProductCollection,
		\Magento\Catalog\Model\ProductFactory $productFactory
	) {
		$this->_registry = $registry;
		$this->_productCollectionFactory = $productCollectionFactory;
		$this->_catalogProductVisibility = $catalogProductVisibility;
		$this->_ProductCollection = $ProductCollection;
		$this->_productFactory = $productFactory;
	}

	public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }
	
	public function getCurrentCategoryMaxPrice() {
        $current_category = $this->getCurrentCategory();
        if($current_category) {

            /** @var $product \Magento\Catalog\Model\Product */
            $productModel = $this->_productFactory->create();

            $collection = $productModel->getResourceCollection()
                ->addAttributeToSelect('*')
                ->addCategoryFilter($current_category)
                ->addAttributeToSort('price', 'desc');
            $RangePrice = array();

            $product = $collection->getFirstItem();
            $max_price = $product->getFinalPrice();
            if($product->getTypeId() == 'bundle' || $product->getTypeId() == 'grouped')
            {
                $max_price = $product->getMinPrice();
            }
            $RangePrice['max'] = ceil($max_price);
            
            $product = $collection->getLastItem();
            $min_price = $product->getFinalPrice();
            if($product->getTypeId() == 'bundle' || $product->getTypeId() == 'grouped')
            {
                $min_price = $product->getMinPrice();
            }
            $RangePrice['min'] = ceil($min_price);
            return $RangePrice;
        } else {
            return false;
        }
    }
}