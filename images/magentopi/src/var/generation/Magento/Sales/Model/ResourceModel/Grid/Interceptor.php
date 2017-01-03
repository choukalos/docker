<?php
namespace Magento\Sales\Model\ResourceModel\Grid;

/**
 * Interceptor class for @see \Magento\Sales\Model\ResourceModel\Grid
 */
class Interceptor extends \Magento\Sales\Model\ResourceModel\Grid implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, $mainTableName, $gridTableName, $orderIdField, array $joins = array(), array $columns = array(), $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $mainTableName, $gridTableName, $orderIdField, $joins, $columns, $connectionName);
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
