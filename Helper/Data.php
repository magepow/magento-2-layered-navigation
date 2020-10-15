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

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var array
     */
    protected $configModule;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
        parent::__construct($context);
        $this->configModule = $this->getConfig(strtolower($this->_getModuleName()));
    }

    public function getConfig($cfg='')
    {
        if($cfg) return $this->scopeConfig->getValue( $cfg, \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        return $this->scopeConfig;
    }

    public function getConfigModule($cfg='', $value=null)
    {
        $values = $this->configModule;
        if( !$cfg ) return $values;
        $config  = explode('/', $cfg);
        $end     = count($config) - 1;
        foreach ($config as $key => $vl) {
            if( isset($values[$vl]) ){
                if( $key == $end ) {
                    $value = $values[$vl];
                }else {
                    $values = $values[$vl];
                }
            } 

        }
        return $value;
    }

    public function isEnabled()
    {
        return $this->getConfigModule('general/enabled');
    }

    public function isEnabledPriceRangeSliders()
    {
        return $this->getConfigModule('general/price_slider');
    }

    public function isEnabledShowAllFilters()
    {
        return $this->getConfigModule('general/show_filters');
    }
    
    public function isEnabledStickySidebar()
    {
        return $this->getConfigModule('general/sticky_sidebar');
    }
}
