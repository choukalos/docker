<?php
namespace Magento\CatalogInventory\Model\Config\Backend\Backorders;

/**
 * Interceptor class for @see \Magento\CatalogInventory\Model\Config\Backend\Backorders
 */
class Interceptor extends \Magento\CatalogInventory\Model\Config\Backend\Backorders implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\App\Config\ScopeConfigInterface $config, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, \Magento\CatalogInventory\Api\StockIndexInterface $stockIndex, \Magento\CatalogInventory\Model\Indexer\Stock\Processor $stockIndexerProcessor, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $config, $cacheTypeList, $stockIndex, $stockIndexerProcessor, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterSave');
        if (!$pluginInfo) {
            return parent::afterSave();
        } else {
            return $this->___callPlugins('afterSave', func_get_args(), $pluginInfo);
        }
    }
}
