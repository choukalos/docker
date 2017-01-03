<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\View\Element\UiComponent;

use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\BlockInterface;

/**
 * Interface BlockWrapperInterface
 */
interface BlockWrapperInterface extends UiComponentInterface
{
    /**
     * Get wrapped block
     *
     * @return BlockInterface
     */
    public function getBlock();
}
