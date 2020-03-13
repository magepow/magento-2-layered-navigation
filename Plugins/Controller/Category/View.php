<?php
/**
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 3:03 PM
 *  * @@Modify Date: 4/16/19 3:03 PM
 *
 *
 */

namespace Magepow\Layerednav\Plugins\Controller\Category;

class View
{
    protected $_jsonHelper;
    protected $_moduleHelper;

    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magepow\Layerednav\Helper\Data $moduleHelper
    ){
        $this->_jsonHelper = $jsonHelper;
        $this->_moduleHelper = $moduleHelper;
    }
    public function afterExecute(\Magento\Catalog\Controller\Category\View $action, $page)
    {
        if($this->_moduleHelper->isEnabled() && $action->getRequest()->getParam('isAjax')){
            $navigation = $page->getLayout()->getBlock('catalog.leftnav');
            $products = $page->getLayout()->getBlock('category.products');
            $result = ['products' => $products->toHtml(), 'navigation' => $navigation->toHtml()];
            $action->getResponse()->representJson($this->_jsonHelper->jsonEncode($result));
        } else {
            return $page;
        }
    }
}
