<?php
namespace Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\NewFolder;

/**
 * Interceptor class for @see \Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\NewFolder
 */
class Interceptor extends \Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\NewFolder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $resultJsonFactory);
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
