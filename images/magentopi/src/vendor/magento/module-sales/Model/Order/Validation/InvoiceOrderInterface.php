<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Model\Order\Validation;

use Magento\Sales\Api\Data\InvoiceCommentCreationInterface;
use Magento\Sales\Api\Data\InvoiceCreationArgumentsInterface;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\ValidatorResultInterface;

/**
 * Interface InvoiceOrderInterface
 */
interface InvoiceOrderInterface
{
    /**
     * @param OrderInterface $order
     * @param InvoiceInterface $invoice
     * @param bool $capture
     * @param array $items
     * @param bool $notify
     * @param bool $appendComment
     * @param InvoiceCommentCreationInterface|null $comment
     * @param InvoiceCreationArgumentsInterface|null $arguments
     * @return ValidatorResultInterface
     */
    public function validate(
        OrderInterface $order,
        InvoiceInterface $invoice,
        $capture = false,
        array $items = [],
        $notify = false,
        $appendComment = false,
        InvoiceCommentCreationInterface $comment = null,
        InvoiceCreationArgumentsInterface $arguments = null
    );
}
