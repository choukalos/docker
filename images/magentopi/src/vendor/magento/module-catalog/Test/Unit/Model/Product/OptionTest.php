<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Test\Unit\Model\Product;

use \Magento\Catalog\Model\Product\Option;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class OptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Catalog\Model\Product\Option
     */
    protected $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    protected function setUp()
    {
        $this->productMock = $this->getMock('\Magento\Catalog\Model\Product', [], [], '', false);
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject('Magento\Catalog\Model\Product\Option');
        $this->model->setProduct($this->productMock);
    }

    public function testGetProductSku()
    {
        $productSku = 'product-sku';
        $this->productMock->expects($this->once())->method('getSku')->willReturn($productSku);
        $this->assertEquals($productSku, $this->model->getProductSku());
    }

    public function testGetRegularPrice()
    {
        $priceInfoMock = $this->getMockForAbstractClass(
            'Magento\Framework\Pricing\PriceInfoInterface',
            [],
            '',
            false,
            false,
            true,
            ['getAmount', 'getPrice']
        );
        $priceInfoMock->expects($this->once())->method('getPrice')->willReturnSelf();
        $amountMock = $this->getMockForAbstractClass('Magento\Framework\Pricing\Amount\AmountInterface');
        $priceInfoMock->expects($this->once())->method('getAmount')->willReturn($amountMock);

        $this->productMock->expects($this->once())->method('getPriceInfo')->willReturn($priceInfoMock);

        $amountMock->expects($this->once())->method('getValue')->willReturn(50);
        $this->model->setPrice(50);
        $this->model->setPriceType(\Magento\Catalog\Model\Product\Option\Value::TYPE_PERCENT);
        $this->assertEquals(25, $this->model->getRegularPrice());
        $this->model->setPriceType(null);
        $this->assertEquals(50, $this->model->getRegularPrice());
    }
}
