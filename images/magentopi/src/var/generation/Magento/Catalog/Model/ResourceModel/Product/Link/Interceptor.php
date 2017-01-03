<?php
namespace Magento\Catalog\Model\ResourceModel\Product\Link;

/**
 * Interceptor class for @see \Magento\Catalog\Model\ResourceModel\Product\Link
 */
class Interceptor extends \Magento\Catalog\Model\ResourceModel\Product\Link implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, \Magento\Catalog\Model\ResourceModel\Product\Relation $catalogProductRelation, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $catalogProductRelation, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteProductLink($linkId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'deleteProductLink');
        if (!$pluginInfo) {
            return parent::deleteProductLink($linkId);
        } else {
            return $this->___callPlugins('deleteProductLink', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveProductLinks($parentId, $data, $typeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveProductLinks');
        if (!$pluginInfo) {
            return parent::saveProductLinks($parentId, $data, $typeId);
        } else {
            return $this->___callPlugins('saveProductLinks', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\Framework\Model\AbstractModel $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($object);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Magento\Framework\Model\AbstractModel $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete($object);
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }
}
