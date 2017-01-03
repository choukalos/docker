<?php
namespace Magento\Search\Controller\Adminhtml\Synonyms\Edit;

/**
 * Interceptor class for @see \Magento\Search\Controller\Adminhtml\Synonyms\Edit
 */
class Interceptor extends \Magento\Search\Controller\Adminhtml\Synonyms\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $registry, \Magento\Search\Controller\Adminhtml\Synonyms\ResultPageBuilder $pageBuilder, \Magento\Search\Api\SynonymGroupRepositoryInterface $synGroupRepository)
    {
        $this->___init();
        parent::__construct($context, $registry, $pageBuilder, $synGroupRepository);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
