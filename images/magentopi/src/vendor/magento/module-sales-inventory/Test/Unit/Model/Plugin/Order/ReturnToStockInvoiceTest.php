<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SalesInventory\Test\Unit\Model\Plugin\Order;

/**
 * Class ReturnToStockInvoiceTest
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ReturnToStockInvoiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\SalesInventory\Model\Plugin\Order\ReturnToStockInvoice */
    private $returnToStock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\SalesInventory\Model\Order\ReturnProcessor
     */
    private $returnProcessorMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Api\CreditmemoRepositoryInterface
     */
    private $creditmemoRepositoryMock;

    /**
     * @var  \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Api\InvoiceRepositoryInterface
     */
    private $invoiceRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Api\RefundInvoiceInterface
     */
    private $refundInvoiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Api\Data\CreditmemoCreationArgumentsInterface
     */
    private $creditmemoCreationArgumentsMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Api\Data\OrderInterface
     */
    private $orderMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Api\Data\CreditmemoInterface
     */
    private $creditmemoMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Api\Data\InvoiceInterface
     */
    private $invoiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Sales\Api\Data\CreditmemoCreationArgumentsInterface
     */
    private $extensionAttributesMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\CatalogInventory\Api\StockConfigurationInterface
     */
    private $stockConfigurationMock;

    /**
     * @var \Closure
     */
    private $proceed;

    protected function setUp()
    {
        $this->returnProcessorMock = $this->getMockBuilder(\Magento\SalesInventory\Model\Order\ReturnProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->creditmemoRepositoryMock = $this->getMockBuilder(\Magento\Sales\Api\CreditmemoRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->orderRepositoryMock = $this->getMockBuilder(\Magento\Sales\Api\OrderRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->invoiceRepositoryMock = $this->getMockBuilder(\Magento\Sales\Api\InvoiceRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->refundInvoiceMock = $this->getMockBuilder(\Magento\Sales\Api\RefundInvoiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->creditmemoCreationArgumentsMock = $this->getMockBuilder(
            \Magento\Sales\Api\Data\CreditmemoCreationArgumentsInterface::class
        )->disableOriginalConstructor()
            ->getMock();
        $this->extensionAttributesMock = $this->getMockBuilder(
            \Magento\Sales\Api\Data\CreditmemoCreationArgumentsExtensionInterface::class
        )->disableOriginalConstructor()
            ->setMethods(['getReturnToStockItems'])
            ->getMock();
        $this->orderMock = $this->getMockBuilder(\Magento\Sales\Api\Data\OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->creditmemoMock = $this->getMockBuilder(\Magento\Sales\Api\Data\CreditmemoInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->invoiceMock = $this->getMockBuilder(\Magento\Sales\Api\Data\InvoiceInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->stockConfigurationMock = $this->getMockBuilder(
            \Magento\CatalogInventory\Api\StockConfigurationInterface::class
        )->disableOriginalConstructor()
            ->getMock();

        $this->returnToStock = new \Magento\SalesInventory\Model\Plugin\Order\ReturnToStockInvoice(
            $this->returnProcessorMock,
            $this->creditmemoRepositoryMock,
            $this->orderRepositoryMock,
            $this->invoiceRepositoryMock,
            $this->stockConfigurationMock
        );
    }

    public function testAroundExecute()
    {
        $orderId = 1;
        $creditmemoId = 99;
        $items = [];
        $returnToStockItems = [1];
        $invoiceId = 98;

        $this->proceed = function () use ($creditmemoId) {
            return $creditmemoId;
        };
        $this->creditmemoCreationArgumentsMock->expects($this->exactly(3))
            ->method('getExtensionAttributes')
            ->willReturn($this->extensionAttributesMock);

        $this->invoiceRepositoryMock->expects($this->once())
            ->method('get')
            ->with($invoiceId)
            ->willReturn($this->invoiceMock);

        $this->extensionAttributesMock->expects($this->exactly(2))
            ->method('getReturnToStockItems')
            ->willReturn($returnToStockItems);

        $this->orderRepositoryMock->expects($this->once())
            ->method('get')
            ->with($orderId)
            ->willReturn($this->orderMock);

        $this->creditmemoRepositoryMock->expects($this->once())
            ->method('get')
            ->with($creditmemoId)
            ->willReturn($this->creditmemoMock);

        $this->returnProcessorMock->expects($this->once())
            ->method('execute')
            ->with($this->creditmemoMock, $this->orderMock, $returnToStockItems);

        $this->invoiceMock->expects($this->once())
            ->method('getOrderId')
            ->willReturn($orderId);

        $this->stockConfigurationMock->expects($this->once())
            ->method('isAutoReturnEnabled')
            ->willReturn(false);

        $this->assertEquals(
            $this->returnToStock->aroundExecute(
                $this->refundInvoiceMock,
                $this->proceed,
                $invoiceId,
                $items,
                false,
                false,
                false,
                null,
                $this->creditmemoCreationArgumentsMock
            ),
            $creditmemoId
        );
    }
}
