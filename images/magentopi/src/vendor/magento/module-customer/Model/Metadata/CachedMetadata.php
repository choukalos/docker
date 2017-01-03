<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Customer\Model\Metadata;

use Magento\Customer\Api\MetadataInterface;
use Magento\Eav\Model\Entity\AttributeCache;
use Magento\Framework\App\ObjectManager;

/**
 * Cached attribute metadata service
 */
class CachedMetadata implements MetadataInterface
{
    const CACHE_SEPARATOR = ';';

    /**
     * @var string
     */
    protected $entityType = 'none';

    /**
     * @var AttributeCache
     */
    private $cache;

    /**
     * @var MetadataInterface
     */
    protected $metadata;

    /**
     * Initialize dependencies.
     *
     * @param MetadataInterface $metadata
     */
    public function __construct(MetadataInterface $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($formCode)
    {
        $attributes = $this->getCache()->getAttributes($this->entityType, $formCode);
        if ($attributes !== false) {
            return $attributes;
        }
        $attributes = $this->metadata->getAttributes($formCode);
        $this->getCache()->saveAttributes($this->entityType, $attributes, $formCode);
        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeMetadata($attributeCode)
    {
        $metadata = $this->getCache()->getAttributes($this->entityType, $attributeCode);
        if ($metadata) {
            return $metadata;
        }
        $metadata = $this->metadata->getAttributeMetadata($attributeCode);
        $this->getCache()->saveAttributes($this->entityType, $metadata, $attributeCode);
        return $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllAttributesMetadata()
    {
        $attributes = $this->getCache()->getAttributes($this->entityType, 'all');
        if ($attributes !== false) {
            return $attributes;
        }
        $attributes = $this->metadata->getAllAttributesMetadata();
        $this->getCache()->saveAttributes($this->entityType, $attributes, 'all');
        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomAttributesMetadata($dataObjectClassName = null)
    {
        $attributes = $this->getCache()->getAttributes($this->entityType, 'custom');
        if ($attributes !== false) {
            return $attributes;
        }
        $attributes = $this->metadata->getCustomAttributesMetadata();
        $this->getCache()->saveAttributes($this->entityType, $attributes, 'custom');
        return $attributes;
    }

    /**
     * @return AttributeCache
     * @deprecated
     */
    private function getCache()
    {
        if (!$this->cache) {
            $this->cache = ObjectManager::getInstance()->get(AttributeCache::class);
        }
        
        return $this->cache;
    }
}
