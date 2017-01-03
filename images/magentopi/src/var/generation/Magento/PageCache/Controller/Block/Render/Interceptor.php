<?php
namespace Magento\PageCache\Controller\Block\Render;

/**
 * Interceptor class for @see \Magento\PageCache\Controller\Block\Render
 */
class Interceptor extends \Magento\PageCache\Controller\Block\Render implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Translate\InlineInterface $translateInline)
    {
        $this->___init();
        parent::__construct($context, $translateInline);
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
