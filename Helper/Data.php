<?php
/**
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 2:42 PM
 *  * @@Modify Date: 4/16/19 2:40 PM
 *
 *
 */

namespace Magepow\Layerednav\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    protected $storeManager;

    protected $objectManager;

    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager
    )
    {
        $this->objectManager   = $objectManager;
        $this->storeManager    = $storeManager;
        parent::__construct($context);
    }

    public function isEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'layered_ajax/general/enable',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isEnabledPriceRangeSliders($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'layered_ajax/general/price_slider',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }


    public function isEnabledShowAllFilters($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'layered_ajax/general/show_filters',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
