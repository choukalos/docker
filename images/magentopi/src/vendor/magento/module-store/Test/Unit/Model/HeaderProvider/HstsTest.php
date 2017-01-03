<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Store\Test\Unit\Model\HeaderProvider;

use \Magento\Store\Model\HeaderProvider\Hsts;
use \Magento\Store\Model\Store;
use \Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class HstsTest extends \PHPUnit_Framework_TestCase
{
    /** Strict-Transport-Security (HSTS) Header name */
    const HEADER_NAME = 'Strict-Transport-Security';

    /**
     * Strict-Transport-Security (HSTS) header value
     */
    const HEADER_VALUE = 'max-age=31536000';

    /**
     * @var Hsts
     */
    protected $object;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfigMock;

    protected function setUp()
    {
        $this->scopeConfigMock = $this->getMockBuilder('\Magento\Framework\App\Config')
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager = new ObjectManagerHelper($this);
        $this->object = $objectManager->getObject(
            '\Magento\Store\Model\HeaderProvider\Hsts',
            ['scopeConfig' => $this->scopeConfigMock]
        );
    }

    public function testGetName()
    {
        $this->assertEquals($this::HEADER_NAME, $this->object->getName(), 'Wrong header name');
    }

    public function testGetValue()
    {
        $this->assertEquals($this::HEADER_VALUE, $this->object->getValue(), 'Wrong header value');
    }

    /**
     * @param [] $configValuesMap
     * @param bool $expected
     * @dataProvider testCanApplyDataProvider
     */
    public function testCanApply($configValuesMap, $expected)
    {
        $this->scopeConfigMock->expects($this->any())->method('isSetFlag')->will(
            $this->returnValueMap($configValuesMap)
        );
        $this->assertEquals($expected, $this->object->canApply(), 'Incorrect canApply result');
    }

    /**
     * Data provider for testCanApply test
     *
     * @return array
     */
    public function testCanApplyDataProvider()
    {
        return [
            [
                [
                    [Store::XML_PATH_SECURE_IN_FRONTEND, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true],
                    [Store::XML_PATH_SECURE_IN_ADMINHTML, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true],
                    [Store::XML_PATH_ENABLE_HSTS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true]
                ],
                true
            ],
            [
                [
                    [Store::XML_PATH_SECURE_IN_FRONTEND, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false],
                    [Store::XML_PATH_SECURE_IN_ADMINHTML, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true],
                    [Store::XML_PATH_ENABLE_HSTS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true]
                ],
                false
            ],
            [
                [
                    [Store::XML_PATH_SECURE_IN_FRONTEND, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true],
                    [Store::XML_PATH_SECURE_IN_ADMINHTML, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false],
                    [Store::XML_PATH_ENABLE_HSTS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true]
                ],
                false
            ],
            [
                [
                    [Store::XML_PATH_SECURE_IN_FRONTEND, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true],
                    [Store::XML_PATH_SECURE_IN_ADMINHTML, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true],
                    [Store::XML_PATH_ENABLE_HSTS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false]
                ],
                false
            ],
            [
                [
                    [Store::XML_PATH_SECURE_IN_FRONTEND, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false],
                    [Store::XML_PATH_SECURE_IN_ADMINHTML, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true],
                    [Store::XML_PATH_ENABLE_HSTS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false]
                ],
                false
            ],
            [
                [
                    [Store::XML_PATH_SECURE_IN_FRONTEND, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true],
                    [Store::XML_PATH_SECURE_IN_ADMINHTML, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false],
                    [Store::XML_PATH_ENABLE_HSTS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false]
                ],
                false
            ],
            [
                [
                    [Store::XML_PATH_SECURE_IN_FRONTEND, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false],
                    [Store::XML_PATH_SECURE_IN_ADMINHTML, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false],
                    [Store::XML_PATH_ENABLE_HSTS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false]
                ],
                false
            ],
            [
                [
                    [Store::XML_PATH_SECURE_IN_FRONTEND, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false],
                    [Store::XML_PATH_SECURE_IN_ADMINHTML, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, false],
                    [Store::XML_PATH_ENABLE_HSTS, ScopeConfigInterface::SCOPE_TYPE_DEFAULT , null, true]
                ],
                false
            ],
        ];
    }
}
