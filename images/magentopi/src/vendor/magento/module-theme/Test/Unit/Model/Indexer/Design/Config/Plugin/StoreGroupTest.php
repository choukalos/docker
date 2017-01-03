<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Theme\Test\Unit\Model\Indexer\Design\Config\Plugin;

use Magento\Framework\Indexer\IndexerInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Theme\Model\Data\Design\Config;
use Magento\Theme\Model\Indexer\Design\Config\Plugin\StoreGroup;

class StoreGroupTest extends \PHPUnit_Framework_TestCase
{
    /** @var StoreGroup */
    protected $model;

    /** @var IndexerRegistry|\PHPUnit_Framework_MockObject_MockObject */
    protected $indexerRegistryMock;

    protected function setUp()
    {
        $this->indexerRegistryMock = $this->getMockBuilder('Magento\Framework\Indexer\IndexerRegistry')
            ->disableOriginalConstructor()
            ->getMock();

        $this->model = new StoreGroup($this->indexerRegistryMock);
    }

    public function testAfterDelete()
    {
        /** @var \Magento\Store\Model\Group|\PHPUnit_Framework_MockObject_MockObject $subjectMock */
        $subjectMock = $this->getMockBuilder('Magento\Store\Model\Group')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var IndexerInterface|\PHPUnit_Framework_MockObject_MockObject $indexerMock */
        $indexerMock = $this->getMockBuilder('Magento\Framework\Indexer\IndexerInterface')
            ->getMockForAbstractClass();
        $indexerMock->expects($this->once())
            ->method('invalidate');

        $this->indexerRegistryMock->expects($this->once())
            ->method('get')
            ->with(Config::DESIGN_CONFIG_GRID_INDEXER_ID)
            ->willReturn($indexerMock);

        $this->assertEquals($subjectMock, $this->model->afterDelete($subjectMock, $subjectMock));
    }
}
