<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Block\Product\ProductList\Item\AddTo;

/**
 * Add product to compare
 */
class Compare extends \Magento\Catalog\Block\Product\ProductList\Item\Block
{
    /**
     * @return \Magento\Catalog\Helper\Product\Compare
     */
    public function getCompareHelper()
    {
        return $this->_compareProduct;
    }
}
