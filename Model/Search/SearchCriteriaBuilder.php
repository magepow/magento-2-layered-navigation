<?php
/**
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 3:00 PM
 *  * @@Modify Date: 4/16/19 3:00 PM
 *
 *
 */

namespace Magepow\Layerednav\Model\Search;

use Magento\Framework\Api\ObjectFactory;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder as SourceSearchCriteriaBuilder;

class SearchCriteriaBuilder extends SourceSearchCriteriaBuilder
{

    public function __construct(
        ObjectFactory $objectFactory,
        FilterGroupBuilder $filterGroupBuilder,
        SortOrderBuilder $sortOrderBuilder
    )
    {
        parent::__construct($objectFactory, $filterGroupBuilder, $sortOrderBuilder);
    }

    public function removeFilter($attributeCode)
    {
        $this->filterGroupBuilder->removeFilter($attributeCode);

        return $this;
    }

    public function setFilterGroupBuilder($filterGroupBuilder)
    {
        $this->filterGroupBuilder = $filterGroupBuilder;
    }

    public function cloneObject()
    {
        $cloneObject = clone $this;
        $cloneObject->setFilterGroupBuilder($this->filterGroupBuilder->cloneObject());

        return $cloneObject;
    }
}
