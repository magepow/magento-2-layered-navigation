<?php
/**
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 2:43 PM
 *  * @@Modify Date: 4/16/19 2:43 PM
 *
 *
 */

namespace Magepow\Layerednav\Model\Layer\Filter;

use Magento\CatalogSearch\Model\Layer\Filter\Price as AbstractFilter;

class Price extends AbstractFilter
{

    private $dataProvider;

    private $priceCurrency;

    protected $_moduleHelper;

    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory,
        \Magepow\Layerednav\Helper\Data $moduleHelper,
        array $data = []
    )
    {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $resource,
            $customerSession,
            $priceAlgorithm,
            $priceCurrency,
            $algorithmFactory,
            $dataProviderFactory,
            $data
        );

        $this->priceCurrency = $priceCurrency;
        $this->dataProvider  = $dataProviderFactory->create(['layer' => $this->getLayer()]);
        $this->_moduleHelper = $moduleHelper;
    }

    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->_moduleHelper->isEnabled() || !$this->_moduleHelper->isEnabledPriceRangeSliders()) {
            return parent::apply($request);
        }

        $filter = $request->getParam($this->getRequestVar());
        if (!$filter || is_array($filter)) {
            $this->filterValue = false;

            return $this;
        }

        $filterParams = explode(',', $filter);
        $filter       = $this->dataProvider->validateFilter($filterParams[0]);
        if (!$filter) {
            $this->filterValue = false;

            return $this;
        }

        $this->dataProvider->setInterval($filter);
        $priorFilters = $this->dataProvider->getPriorFilters($filterParams);
        if ($priorFilters) {
            $this->dataProvider->setPriorIntervals($priorFilters);
        }

        list($from, $to) = $filter;

        $fromBase = $from/$this->getCurrencyRate();
        $toBase = $to/$this->getCurrencyRate();

        $this->getLayer()->getProductCollection()->addFieldToFilter(
            'price',
            ['from' => $fromBase, 'to' => $toBase]
        );

        $this->getLayer()->getState()->addFilter(
            $this->_createItem($this->_renderRangeLabel(empty($from) ? 0 : $from, $to), $filter)
        );

        return $this;
    }

    protected function _renderRangeLabel($fromPrice, $toPrice, $isLast = false)
    {
        if (!$this->_moduleHelper->isEnabled() || !$this->_moduleHelper->isEnabledPriceRangeSliders()) {
            return parent::_renderRangeLabel($fromPrice, $toPrice, $isLast);
        }
        $formattedFromPrice = $this->priceCurrency->format($fromPrice);
        if ($toPrice === '') {
            return __('%1 and above', $formattedFromPrice);
        } elseif ($fromPrice == $toPrice && $this->dataProvider->getOnePriceIntervalValue()) {
            return $formattedFromPrice;
        } else {
            return __('%1 - %2', $formattedFromPrice, $this->priceCurrency->format($toPrice));
        }
    }

    protected function _getItemsData()
    {
        if (!$this->_moduleHelper->isEnabled() || !$this->_moduleHelper->isEnabledPriceRangeSliders()) {
            return parent::_getItemsData();
        }

        $data = [[
            'label' => '0-100',
            'value' => '0-100',
            'count' => 1,
            'from'  => '0',
            'to'    => '100',
        ]];

        return $data;
    }
}
