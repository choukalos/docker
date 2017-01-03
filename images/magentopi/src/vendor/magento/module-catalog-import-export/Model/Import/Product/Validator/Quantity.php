<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogImportExport\Model\Import\Product\Validator;

use Magento\CatalogImportExport\Model\Import\Product\RowValidatorInterface;

/**
 * Class Quantity
 */
class Quantity extends AbstractImportValidator implements RowValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function init($context)
    {
        return parent::init($context);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->_clearMessages();
        if (!empty($value['qty']) && !is_numeric($value['qty'])) {
            $this->_addMessages(
                [
                    sprintf(
                        $this->context->retrieveMessageTemplate(self::ERROR_INVALID_ATTRIBUTE_TYPE),
                        'qty',
                        'decimal'
                    ),
                ]
            );
            return false;
        }
        return true;
    }
}
