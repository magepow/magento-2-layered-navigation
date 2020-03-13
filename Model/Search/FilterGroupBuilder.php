<?php
/**
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 2:59 PM
 *  * @@Modify Date: 4/16/19 2:59 PM
 *
 *
 */

namespace Magepow\Layerednav\Model\Search;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\ObjectFactory;
use Magento\Framework\Api\Search\FilterGroupBuilder as SourceFilterGroupBuilder;

class FilterGroupBuilder extends SourceFilterGroupBuilder
{

    public function __construct(
        ObjectFactory $objectFactory,
        FilterBuilder $filterBuilder
    )
    {
        parent::__construct($objectFactory, $filterBuilder);
    }

    public function setFilterBuilder($filterBuilder)
    {
        $this->_filterBuilder = $filterBuilder;
    }

    public function cloneObject()
    {
        $cloneObject = clone $this;
        $cloneObject->setFilterBuilder(clone $this->_filterBuilder);

        return $cloneObject;
    }

    public function removeFilter($attributeCode)
    {
        if (isset($this->data[FilterGroup::FILTERS])) {
            foreach ($this->data[FilterGroup::FILTERS] as $key => $filter) {
                if ($filter->getField() == $attributeCode) {
                    unset($this->data[FilterGroup::FILTERS][$key]);
                }
            }
        }

        return $this;
    }
}
