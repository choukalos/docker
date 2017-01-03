<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Eav\Model\ResourceModel;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Eav\Api\AttributeRepositoryInterface as AttributeRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Model\Entity\ScopeResolver;
use Magento\Framework\EntityManager\Operation\AttributeInterface;

/**
 * Class UpdateHandler
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpdateHandler implements AttributeInterface
{
    /**
     * @var AttributeRepository
     */
    private $attributeRepository;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var AttributePersistor
     */
    private $attributePersistor;

    /**
     * @var ReadSnapshot
     */
    private $readSnapshot;

    /**
     * @var ScopeResolver
     */
    private $scopeResolver;

    /**
     * @var ReadHandler
     */
    private $readHandler;

    /**
     * @var AttributeLoader
     */
    private $attributeLoader;

    /**
     * UpdateHandler constructor.
     * @param AttributeRepository $attributeRepository
     * @param MetadataPool $metadataPool
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AttributePersistor $attributePersistor
     * @param ReadSnapshot $readSnapshot
     * @param ScopeResolver $scopeResolver
     * @param AttributeLoader $attributeLoader
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        MetadataPool $metadataPool,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AttributePersistor $attributePersistor,
        ReadSnapshot $readSnapshot,
        ScopeResolver $scopeResolver,
        AttributeLoader $attributeLoader = null
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->metadataPool = $metadataPool;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->attributePersistor = $attributePersistor;
        $this->readSnapshot = $readSnapshot;
        $this->scopeResolver = $scopeResolver;
        $this->attributeLoader = $attributeLoader ?: ObjectManager::getInstance()->get(AttributeLoader::class);
    }

    /**
     * @param string $entityType
     * @param int $attributeSetId
     * @return \Magento\Eav\Api\Data\AttributeInterface[]
     */
    protected function getAttributes($entityType, $attributeSetId = null)
    {
        return $this->attributeLoader->getAttributes($entityType, $attributeSetId);
    }

    /**
     * @param string $entityType
     * @param array $entityData
     * @param array $arguments
     * @return array
     * @throws \Exception
     * @throws \Magento\Framework\Exception\ConfigurationMismatchException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entityType, $entityData, $arguments = [])
    {
        /** @var \Magento\Eav\Model\Entity\Attribute\AbstractAttribute $attribute */
        $metadata = $this->metadataPool->getMetadata($entityType);
        if ($metadata->getEavEntityType()) {
            $context = $this->scopeResolver->getEntityContext($entityType, $entityData);
            $entityDataForSnapshot = [$metadata->getLinkField() => $entityData[$metadata->getLinkField()]];
            foreach ($context as $scope) {
                if (isset($entityData[$scope->getIdentifier()])) {
                    $entityDataForSnapshot[$scope->getIdentifier()] = $entityData[$scope->getIdentifier()];
                }
            }
            $attributeSetId = isset($entityData[AttributeLoader::ATTRIBUTE_SET_ID])
                ? $entityData[AttributeLoader::ATTRIBUTE_SET_ID]
                : null; // @todo verify is it normal to not have attributer_set_id
            $snapshot = $this->readSnapshot->execute($entityType, $entityDataForSnapshot);
            foreach ($this->getAttributes($entityType, $attributeSetId) as $attribute) {
                if ($attribute->isStatic()) {
                    continue;
                }
                /**
                 * Only scalar values can be stored in generic tables
                 */
                if (isset($entityData[$attribute->getAttributeCode()])
                    && !is_scalar($entityData[$attribute->getAttributeCode()])) {
                    continue;
                }
                if (isset($snapshot[$attribute->getAttributeCode()])
                    && $snapshot[$attribute->getAttributeCode()] !== false
                    && (array_key_exists($attribute->getAttributeCode(), $entityData)
                        && $attribute->isValueEmpty($entityData[$attribute->getAttributeCode()]))
                ) {
                    $this->attributePersistor->registerDelete(
                        $entityType,
                        $entityData[$metadata->getLinkField()],
                        $attribute->getAttributeCode()
                    );
                }
                if ((!array_key_exists($attribute->getAttributeCode(), $snapshot)
                        || $snapshot[$attribute->getAttributeCode()] === false)
                    && array_key_exists($attribute->getAttributeCode(), $entityData)
                    && !$attribute->isValueEmpty($entityData[$attribute->getAttributeCode()])
                ) {
                    $this->attributePersistor->registerInsert(
                        $entityType,
                        $entityData[$metadata->getLinkField()],
                        $attribute->getAttributeCode(),
                        $entityData[$attribute->getAttributeCode()]
                    );
                }
                if (array_key_exists($attribute->getAttributeCode(), $snapshot)
                    && $snapshot[$attribute->getAttributeCode()] !== false
                    && array_key_exists($attribute->getAttributeCode(), $entityData)
                    && $snapshot[$attribute->getAttributeCode()] != $entityData[$attribute->getAttributeCode()]
                    && !$attribute->isValueEmpty($entityData[$attribute->getAttributeCode()])
                ) {
                    $this->attributePersistor->registerUpdate(
                        $entityType,
                        $entityData[$metadata->getLinkField()],
                        $attribute->getAttributeCode(),
                        $entityData[$attribute->getAttributeCode()]
                    );
                }
            }
            $this->attributePersistor->flush($entityType, $context);
        }
        return $this->getReadHandler()->execute($entityType, $entityData, $arguments);
    }

    /**
     * Get read handler
     *
     * @deprecated
     *
     * @return ReadHandler
     */
    protected function getReadHandler()
    {
        if (!$this->readHandler) {
            $this->readHandler = ObjectManager::getInstance()->get(ReadHandler::class);
        }
        return $this->readHandler;
    }
}
