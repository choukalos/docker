<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Store\Test\Unit\Model\Config;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\Store\Model\Config\Converter */
    protected $_model;

    protected function setUp()
    {
        $this->_model = new \Magento\Store\Model\Config\Converter();
    }

    public function testConvert()
    {
        $initial = ['path' => ['to' => ['save' => 'saved value', 'overwrite' => 'old value']]];
        $source = ['path/to/overwrite' => 'overwritten', 'path/to/added' => 'added value'];
        $mergeResult = [
            'path' => [
                'to' => ['save' => 'saved value', 'overwrite' => 'overwritten', 'added' => 'added value'],
            ],
        ];
        $this->assertEquals($mergeResult, $this->_model->convert($source, $initial));
    }
}
