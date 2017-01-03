<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Test\Unit;

use Magento\Framework\Currency;

/**
 * Test for Magento\Framework\Currency
 */
class CurrencyTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $frontendCache = $this->getMock('Magento\Framework\Cache\FrontendInterface', [], [], '', false, false);
        $lowLevelFrontend = $this->getMock('Zend_Cache_Core', [], [], '', false, false);
        /** @var \Magento\Framework\App\CacheInterface|\PHPUnit_Framework_MockObject_MockObject $appCache */
        $appCache = $this->getMock('Magento\Framework\App\CacheInterface', [], [], '', false, false);
        $frontendCache->expects($this->once())->method('getLowLevelFrontend')->willReturn($lowLevelFrontend);
        $appCache->expects($this->once())
            ->method('getFrontend')
            ->willReturn($frontendCache);

        // Create new currency object
        $currency = new Currency($appCache, null, 'en_US');
        $this->assertEquals($lowLevelFrontend, $currency->getCache());
        $this->assertEquals('USD', $currency->getShortName());
    }
}
