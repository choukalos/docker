<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product attribute source model for enable/disable option
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\Catalog\Model\Product\Attribute\Source;

class Boolean extends \Magento\Eav\Model\Entity\Attribute\Source\Boolean
{
    /**
     * Value of 'Use Config' option
     */
    const VALUE_USE_CONFIG = 2;

    /**
     * Retrieve all attribute options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('Yes'), 'value' => static::VALUE_YES],
                ['label' => __('No'), 'value' => static::VALUE_NO],
                ['label' => __('Use config'), 'value' => static::VALUE_USE_CONFIG],
            ];
        }
        return $this->_options;
    }
}
