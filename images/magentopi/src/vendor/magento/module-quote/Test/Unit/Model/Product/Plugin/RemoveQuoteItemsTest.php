<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Quote\Test\Unit\Model\Product\Plugin;

class RemoveQuoteItemsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Quote\Model\Product\Plugin\RemoveQuoteItems
     */
    private $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Quote\Model\Product\QuoteItemsCleanerInterface
     */
    private $quoteItemsCleanerMock;

    protected function setUp()
    {
        $this->quoteItemsCleanerMock = $this->getMock(\Magento\Quote\Model\Product\QuoteItemsCleanerInterface::class);
        $this->model = new \Magento\Quote\Model\Product\Plugin\RemoveQuoteItems($this->quoteItemsCleanerMock);
    }

    public function testAroundDelete()
    {
        $productResourceMock = $this->getMock(\Magento\Catalog\Model\ResourceModel\Product::class, [], [], '', false);
        $productMock = $this->getMock(\Magento\Catalog\Api\Data\ProductInterface::class);
        $closure = function () use ($productResourceMock) {
            return $productResourceMock;
        };

        $this->quoteItemsCleanerMock->expects($this->once())->method('execute')->with($productMock);
        $result = $this->model->aroundDelete($productResourceMock, $closure, $productMock);
        $this->assertEquals($result, $productResourceMock);
    }
}
