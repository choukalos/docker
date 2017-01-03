<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\EntityManager\Test\Unit;

class MapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\EntityManager\Mapper
     */
    private $mapper;

    public function setUp()
    {
        $config = [
            'Magento\Customer\Api\Data\CustomerInterface' => ['entity_id' => 'id'],
            'Magento\Customer\Api\Data\AddressInterface' => ['parent_id' => 'customer_id', 'invalid' => '']
        ];
        $this->mapper = new \Magento\Framework\EntityManager\Mapper($config);
    }

    public function testEntityToDatabase()
    {
        $inputData = [
            'group_id' => 1,
            'extension_attributes' => ['extension_attribute' => ['value' => 'some value']],
            'id' => 123
        ];
        $expectedOutput = $inputData;
        $expectedOutput['entity_id'] = 123;
        unset($expectedOutput['id']);

        $actualOutput = $this->mapper->entityToDatabase('Magento\Customer\Api\Data\CustomerInterface', $inputData);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Incorrect configuration for Magento\Customer\Api\Data\AddressInterface
     */
    public function testEntityToDatabaseException()
    {
        $inputData = [
            'group_id' => 1,
            'extension_attributes' => ['extension_attribute' => ['value' => 'some value']],
        ];
        $this->mapper->entityToDatabase('Magento\Customer\Api\Data\AddressInterface', $inputData);
    }

    public function testDatabaseToEntity()
    {
        $inputData = [
            'group_id' => 1,
            'extension_attributes' => ['extension_attribute' => ['value' => 'some value']],
            'entity_id' => 123
        ];
        $expectedOutput = $inputData;
        $expectedOutput['id'] = 123;
        unset($expectedOutput['entity_id']);

        $actualOutput = $this->mapper->databaseToEntity('Magento\Customer\Api\Data\CustomerInterface', $inputData);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Incorrect configuration for Magento\Customer\Api\Data\AddressInterface
     */
    public function testDatabaseToEntityException()
    {
        $inputData = [
            'group_id' => 1,
            'extension_attributes' => ['extension_attribute' => ['value' => 'some value']],
            'invalid' => 123
        ];
        $this->mapper->databaseToEntity('Magento\Customer\Api\Data\AddressInterface', $inputData);
    }
}
