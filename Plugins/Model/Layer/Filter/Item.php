<?php
/**
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 3:05 PM
 *  * @@Modify Date: 4/16/19 3:05 PM
 *
 *
 */

namespace Magepow\Layerednav\Plugins\Model\Layer\Filter;

class Item
{
    protected $_url;
    protected $_htmlPagerBlock;
    protected $_request;
    protected $_moduleHelper;

    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \Magento\Framework\App\RequestInterface $request,
        \Magepow\Layerednav\Helper\Data $moduleHelper
    ) {
        $this->_url = $url;
        $this->_htmlPagerBlock = $htmlPagerBlock;
        $this->_request = $request;
        $this->_moduleHelper = $moduleHelper;
    }

    public function aroundGetUrl(\Magento\Catalog\Model\Layer\Filter\Item $item, $proceed)
    {
        if(!$this->_moduleHelper->isEnabled()){
            return $proceed();
        }

        $value = array();
        $requestVar = $item->getFilter()->getRequestVar();
        if($requestValue = $this->_request->getParam($requestVar)){
            $value = explode(',', $requestValue);
        }
        $value[] = $item->getValue();

        if($requestVar == 'price' && $this->_moduleHelper->isEnabledPriceRangeSliders()){
            $value = ["{price_start}-{price_end}"];
        }

        $query = [
            $item->getFilter()->getRequestVar() => implode(',', $value),
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];

        
        if(isset($query['cat'])){
            $cat = explode( ',', $query['cat'] );
            $number = count($cat) - 1;
            $query['cat'] = $cat[$number];
        }
        
        return $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }

    public function aroundGetRemoveUrl(\Magento\Catalog\Model\Layer\Filter\Item $item, $proceed)
    {
        if(!$this->_moduleHelper->isEnabled()){
            return $proceed();
        }

        $value = array();
        $requestVar = $item->getFilter()->getRequestVar();
        if($requestValue = $this->_request->getParam($requestVar)){
            $value = explode(',', $requestValue);
        }

        if(in_array($item->getValue(), $value)){
            $value = array_diff($value, array($item->getValue()));
        }

        if($requestVar == 'price' && $this->_moduleHelper->isEnabledPriceRangeSliders()){
            $value = [];
        }

        $query = [$requestVar => count($value) ? implode(',', $value) : $item->getFilter()->getResetValue()];
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $query;
        $params['_escape'] = true;
        return $this->_url->getUrl('*/*/*', $params);
    }
}
