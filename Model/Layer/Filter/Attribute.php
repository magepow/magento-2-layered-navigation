<?php
/**
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 2:42 PM
 *  * @@Modify Date: 4/16/19 2:42 PM
 *
 *
 */

namespace Magepow\Layerednav\Model\Layer\Filter;

use Magento\CatalogSearch\Model\Layer\Filter\Attribute as AbstractFilter;

class Attribute extends AbstractFilter
{
    private $tagFilter;

    protected $filterValue = true;

    protected $_moduleHelper;

    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Filter\StripTags $tagFilter,
        \Magepow\Layerednav\Helper\Data $moduleHelper,
        array $data = []
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $tagFilter,
            $data
        );
        $this->tagFilter = $tagFilter;
        $this->_moduleHelper = $moduleHelper;
    }

    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if(!$this->_moduleHelper->isEnabled()){
            return parent::apply($request);
        }
        $attributeValue = $request->getParam($this->_requestVar);

        if (empty($attributeValue)) {
            $this->filterValue = false;
            return $this;
        }
        $attributeValue = explode(',', $attributeValue);
        $attribute = $this->getAttributeModel();

        $productCollection = $this->getLayer()
            ->getProductCollection();
        if(sizeof($attributeValue) > 1){
            $valueFilter = array();
            foreach($attributeValue as $value){
                $valueFilter[] = array('eq' => $value);
            }
            $productCollection->addFieldToFilter($attribute->getAttributeCode(), array('ln_filter' => $valueFilter));
        } else {
            $productCollection->addFieldToFilter($attribute->getAttributeCode(), $attributeValue[0]);
        }

        $state = $this->getLayer()->getState();
        foreach($attributeValue as $value){
            $label = $this->getOptionText($value);
            $state->addFilter($this->_createItem($label, $value));
        }

        return $this;
    }

    protected function _getItemsData()
    {
        if(!$this->_moduleHelper->isEnabled()){
            return parent::_getItemsData();
        }

        $attribute = $this->getAttributeModel();

        $productCollection = $this->getLayer()
            ->getProductCollection();

        $collection = $productCollection;
        
        $optionsFacetedData = $collection->getFacetedData($attribute->getAttributeCode());

        if (count($optionsFacetedData) === 0
            && $this->getAttributeIsFilterable($attribute) !== static::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS
        ) {
            return $this->itemDataBuilder->build();
        }

        $productSize = $collection->getSize();

        $options = $attribute->getFrontend()
            ->getSelectOptions();
        foreach ($options as $option) {
            if (empty($option['value'])) {
                continue;
            }

            $value = $option['value'];

            $count = isset($optionsFacetedData[$value]['count'])
                ? (int)$optionsFacetedData[$value]['count']
                : 0;
            if (
                $this->getAttributeIsFilterable($attribute) === static::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS
                && (!$this->isOptionReducesResults($count, $productSize) || $count === 0)
            ) {
                continue;
            }
            $this->itemDataBuilder->addItemData(
                $this->tagFilter->filter($option['label']),
                $value,
                $count
            );
        }

        return $this->itemDataBuilder->build();
    }
}
