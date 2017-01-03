<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Search\Adapter\Aggregation;

use Magento\Framework\Search\RequestInterface;

interface AggregationResolverInterface
{
    /**
     * Filter aggregation from request
     *
     * @param RequestInterface $request
     * @param array $documentIds
     * @return array
     */
    public function resolve(RequestInterface $request, array $documentIds);
}
