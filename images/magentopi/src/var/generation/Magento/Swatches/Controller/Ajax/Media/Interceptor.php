<?php
namespace Magento\Swatches\Controller\Ajax\Media;

/**
 * Interceptor class for @see \Magento\Swatches\Controller\Ajax\Media
 */
class Interceptor extends \Magento\Swatches\Controller\Ajax\Media implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Swatches\Helper\Data $swatchHelper, \Magento\Catalog\Model\ProductFactory $productModelFactory)
    {
        $this->___init();
        parent::__construct($context, $swatchHelper, $productModelFactory);
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
