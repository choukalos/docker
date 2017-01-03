<?php
namespace Magento\Cron\Model\Config\Backend\Product\Alert;

/**
 * Interceptor class for @see \Magento\Cron\Model\Config\Backend\Product\Alert
 */
class Interceptor extends \Magento\Cron\Model\Config\Backend\Product\Alert implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\App\Config\ScopeConfigInterface $config, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, \Magento\Framework\App\Config\ValueFactory $configValueFactory, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, $runModelPath = '', array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $config, $cacheTypeList, $configValueFactory, $resource, $resourceCollection, $runModelPath, $data);
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
